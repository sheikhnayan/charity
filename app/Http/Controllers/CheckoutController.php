<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\PaymentFunnelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected PaymentFunnelService $paymentFunnelService;

    public function __construct(CartService $cartService, PaymentFunnelService $paymentFunnelService)
    {
        $this->cartService = $cartService;
        $this->paymentFunnelService = $paymentFunnelService;
    }

    /**
     * Show checkout page
     */
    public function show()
    {
        // Get current cart
        $cart = $this->cartService->getCart();

        // Redirect to shop if cart is empty
        if (empty($cart['items'])) {
            return redirect('/')->with('info', 'Please add items to your cart first');
        }

        // Validate cart items
        $validation = $this->cartService->validateForCheckout();
        if (!$validation['valid']) {
            return redirect('/')->with('error', $validation['message']);
        }

        // Get user if logged in
        $user = Auth::user();

        // Build checkout data
        $checkoutData = [
            'cart' => $cart,
            'user' => $user,
            'itemCount' => $cart['item_count'],
            'total' => $cart['total'],
            'items' => $this->formatCheckoutItems($cart['items']),
            'requiresEmail' => !$user,
            'csrfToken' => csrf_token()
        ];

        return view('checkout', $checkoutData);
    }

    /**
     * Process checkout and prepare for payment
     */
    public function process(Request $request)
    {
        // Validate required payment information
        $validated = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'payment_method' => 'required|in:stripe,authorize_net',
            'payment_token' => 'required|string',
            // Optional: address fields
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'nullable|string'
        ]);

        // Get cart
        $cart = $this->cartService->getCart();
        
        if (empty($cart['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Validate cart again
        $validation = $this->cartService->validateForCheckout();
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 400);
        }

        try {
            // Transform cart to checkout data
            $checkoutData = $this->cartService->getCheckoutData();

            // Add user/payer information
            $checkoutData['email'] = $validated['email'];
            $checkoutData['first_name'] = $validated['first_name'];
            $checkoutData['last_name'] = $validated['last_name'];
            $checkoutData['address'] = $validated['address'] ?? null;
            $checkoutData['city'] = $validated['city'] ?? null;
            $checkoutData['state'] = $validated['state'] ?? null;
            $checkoutData['zip'] = $validated['zip'] ?? null;
            $checkoutData['country'] = $validated['country'] ?? null;

            // Log checkout attempt
            \Log::info('Cart Checkout Initiated', [
                'email' => $validated['email'],
                'total' => $cart['total'],
                'items' => count($cart['items']),
                'payment_method' => $validated['payment_method'],
                'session_id' => session()->getId()
            ]);

            // Track in payment funnel
            $this->paymentFunnelService->trackEvent(
                'checkout_initiated',
                'cart',
                ['item_count' => count($cart['items']), 'total' => $cart['total']]
            );

            // Route to appropriate payment processor based on payment method
            if ($validated['payment_method'] === 'stripe') {
                return $this->processStripePayment($checkoutData, $validated['payment_token']);
            } else {
                return $this->processAuthorizeNetPayment($checkoutData, $validated['payment_token']);
            }

        } catch (\Exception $e) {
            \Log::error('Cart Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during checkout. Please try again.'
            ], 500);
        }
    }

    /**
     * Process cart payment via Stripe
     */
    protected function processStripePayment($checkoutData, $paymentToken)
    {
        try {
            // Call AuthorizeNetController payment logic for Stripe
            // Or directly process with Stripe API
            
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

            // Create charge
            $charge = $stripe->charges->create([
                'amount' => (int)($checkoutData['total'] * 100), // Convert to cents
                'currency' => 'usd',
                'source' => $paymentToken,
                'description' => $this->buildChargeDescription($checkoutData),
                'metadata' => [
                    'type' => 'cart',
                    'item_count' => count($checkoutData['items']),
                    'email' => $checkoutData['email']
                ]
            ]);

            // Payment succeeded
            if ($charge->status === 'succeeded') {
                return $this->handlePaymentSuccess($checkoutData, $charge, 'stripe');
            } else {
                return $this->handlePaymentFailure('Payment declined. Please try again.', $checkoutData);
            }

        } catch (\Stripe\Exception\CardException $e) {
            return $this->handlePaymentFailure('Card declined: ' . $e->getError()->message, $checkoutData);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->handlePaymentFailure('Payment error: ' . $e->getMessage(), $checkoutData);
        } catch (\Exception $e) {
            return $this->handlePaymentFailure('An unexpected error occurred', $checkoutData);
        }
    }

    /**
     * Process cart payment via Authorize.net
     */
    protected function processAuthorizeNetPayment($checkoutData, $paymentToken)
    {
        try {
            // Use Authorize.net API
            // For now, delegate to AuthorizeNetController with cart type
            $request = new Request([
                'type' => 'cart',
                'payment_token' => $paymentToken,
                'cart_data' => $checkoutData,
                'amount' => $checkoutData['total'],
                'email' => $checkoutData['email'],
                'first_name' => $checkoutData['first_name'],
                'last_name' => $checkoutData['last_name']
            ]);

            $authorizeNetController = new AuthorizeNetController();
            $response = $authorizeNetController->processCartPayment($request);
            
            if ($response['success'] ?? false) {
                return $this->handlePaymentSuccess($checkoutData, $response, 'authorize_net');
            } else {
                return $this->handlePaymentFailure($response['message'] ?? 'Payment failed', $checkoutData);
            }

        } catch (\Exception $e) {
            return $this->handlePaymentFailure('Payment processing error: ' . $e->getMessage(), $checkoutData);
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess($checkoutData, $paymentResponse, $paymentMethod)
    {
        try {
            // Track each item in payment funnel
            foreach ($checkoutData['items'] as $item) {
                $this->paymentFunnelService->trackEvent(
                    'payment_complete',
                    $item['type'],
                    [
                        'item_id' => $item['id'],
                        'item_name' => $item['name'],
                        'amount' => $item['amount'],
                        'payment_method' => $paymentMethod,
                        'transaction_id' => $paymentResponse->id ?? $paymentResponse['transaction_id'] ?? null
                    ]
                );
            }

            // Clear cart after successful payment
            $this->cartService->clearCart();

            // Store transaction info for order/receipt
            session(['last_transaction' => [
                'payment_method' => $paymentMethod,
                'total' => $checkoutData['total'],
                'items' => $checkoutData['items'],
                'email' => $checkoutData['email'],
                'timestamp' => now()
            ]]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'redirect' => route('checkout.success')
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment Success Handler Error', [
                'error' => $e->getMessage(),
                'checkout_data' => $checkoutData
            ]);

            // Even if logging fails, payment succeeded
            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'redirect' => route('checkout.success')
            ]);
        }
    }

    /**
     * Handle payment failure
     */
    protected function handlePaymentFailure($message, $checkoutData = null)
    {
        // Track failed payment attempt
        if ($checkoutData) {
            $this->paymentFunnelService->trackEvent(
                'payment_failed',
                'cart',
                [
                    'message' => $message,
                    'item_count' => count($checkoutData['items'] ?? [])
                ]
            );
        }

        return response()->json([
            'success' => false,
            'message' => $message
        ], 400);
    }

    /**
     * Show checkout success page
     */
    public function success()
    {
        $transaction = session('last_transaction');

        if (!$transaction) {
            return redirect('/')->with('info', 'No recent transaction');
        }

        return view('checkout-success', ['transaction' => $transaction]);
    }

    /**
     * Format cart items for display/processing
     */
    protected function formatCheckoutItems($items)
    {
        $formatted = [];

        foreach ($items as $key => $item) {
            $formatted[] = [
                'key' => $key,
                'type' => $item['type'],
                'id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'] ?? 1,
                'amount' => $item['amount'] ?? $item['price'] ?? 0,
                'total' => $this->calculateItemTotal($item),
                'description' => $this->getItemDescription($item)
            ];
        }

        return $formatted;
    }

    /**
     * Calculate item total
     */
    protected function calculateItemTotal($item)
    {
        $price = 0;

        if ($item['type'] === 'student') {
            $price = $item['amount'] ?? 0;
        } else if ($item['type'] === 'ticket' || $item['type'] === 'product') {
            $price = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        } else if ($item['type'] === 'auction') {
            $price = ($item['current_bid'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return $price;
    }

    /**
     * Get human-readable item description
     */
    protected function getItemDescription($item)
    {
        switch ($item['type']) {
            case 'student':
                return "Donation to {$item['name']}";
            case 'ticket':
                return "Ticket: {$item['name']}";
            case 'auction':
                return "Auction Item: {$item['name']}";
            case 'product':
                return "Product: {$item['name']}";
            default:
                return $item['name'];
        }
    }

    /**
     * Build charge description for payment processor
     */
    protected function buildChargeDescription($checkoutData)
    {
        $itemTypes = [];
        foreach ($checkoutData['items'] as $item) {
            $itemTypes[] = $item['type'];
        }

        $types = array_unique($itemTypes);
        $description = 'Charity Purchase: ' . implode(', ', $types);

        if (strlen($description) > 1000) {
            $description = 'Charity Purchase - ' . count($checkoutData['items']) . ' items';
        }

        return $description;
    }
}
