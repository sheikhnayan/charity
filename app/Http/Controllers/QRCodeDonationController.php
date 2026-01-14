<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Website;
use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Services\PaymentFunnelService;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class QRCodeDonationController extends Controller
{
    private function getCurrentWebsite()
    {
        try {
            $url = url()->current();
            $domain = parse_url($url, PHP_URL_HOST);
            $currentWebsite = \App\Models\Website::where('domain', $domain)->first();
            
            // Log attempt
            \Log::info('getCurrentWebsite: Domain lookup', [
                'domain' => $domain,
                'found' => $currentWebsite ? $currentWebsite->id : false
            ]);
            
            if (!$currentWebsite && auth()->check()) {
                // Try user's assigned website_id
                if (auth()->user()->website_id) {
                    $currentWebsite = auth()->user()->website;
                    \Log::info('getCurrentWebsite: Using user->website_id', ['website_id' => auth()->user()->website_id]);
                } else {
                    // Fallback: get first website where user has a role
                    $userWebsite = auth()->user()->roles()
                        ->wherePivot('website_id', '!=', null)
                        ->first()
                        ?->pivot
                        ?->website_id;
                    if ($userWebsite) {
                        $currentWebsite = \App\Models\Website::find($userWebsite);
                        \Log::info('getCurrentWebsite: Using user role website', ['website_id' => $userWebsite]);
                    }
                }
            }
            
            // Final fallback: first website
            if (!$currentWebsite) {
                $currentWebsite = \App\Models\Website::first();
                \Log::warning('getCurrentWebsite: Using first website fallback', ['website_id' => $currentWebsite?->id ?? 'none']);
            }
            
            return $currentWebsite;
        } catch (\Exception $e) {
            \Log::error('getCurrentWebsite error: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Get the current domain for QR code generation
     */
    private function getCurrentDomain()
    {
        // Check if HTTPS
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
                    ? 'https://' : 'http://';
        
        // Get current host
        $domain = $_SERVER['HTTP_HOST'] ?? request()->getHost();
        
        $fullDomain = $protocol . $domain;
        
        // Log for debugging
        \Log::info('QR Code Domain Detection', [
            'protocol' => $protocol,
            'domain' => $domain,
            'full_domain' => $fullDomain,
            'http_host' => $_SERVER['HTTP_HOST'] ?? 'not set',
            'request_host' => request()->getHost(),
            'https' => $_SERVER['HTTPS'] ?? 'not set',
            'x_forwarded_proto' => $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'not set'
        ]);
        
        return $fullDomain;
    }
    
    /**
     * Generate QR code for a donation page
     */
    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:donation,auction,ticket',
            'reference_id' => 'nullable|integer',
            'amount' => 'nullable|numeric|min:1',
            'size' => 'nullable|integer|min:100|max:1000',
            'website_id' => 'nullable|integer|exists:websites,id',
        ]);
        
        // Resolve website context
        $website = null;
        if (auth()->check() && auth()->user()->hasRoleForWebsite('admin')) {
            if ($request->filled('website_id')) {
                $website = Website::find($request->website_id);
            }
        }
        if (!$website) {
            // Fallback to current inferred website (website admin scope)
            $website = $this->getCurrentWebsite();
        }
        if (!$website) {
            return response()->json(['success' => false, 'message' => 'Website context not found'], 422);
        }

        // Generate unique QR code identifier
        $qrIdentifier = Str::random(10);
        
        // Build donation URL with QR parameters
        $params = [
            'qr' => $qrIdentifier,
            'website_id' => $website->id,
            'type' => $request->type,
        ];

        if ($request->amount) {
            $params['amount'] = $request->amount;
        }

        // Map reference id according to type
        if ($request->type === 'auction' && $request->reference_id) {
            $params['auction_id'] = (int) $request->reference_id;
        } elseif ($request->type === 'ticket' && $request->reference_id) {
            $params['ticket_id'] = (int) $request->reference_id;
        } elseif ($request->type === 'donation' && $request->reference_id) {
            $params['student_id'] = (int) $request->reference_id;
        }
        
        // Use selected website domain (or current inferred) for QR code URL
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
                ? 'https://' : 'http://';
        $domainBase = $website->domain ? ($protocol . $website->domain) : $this->getCurrentDomain();
        $donationUrl = $domainBase . '/qr-donate?' . http_build_query($params);

        $size = $request->input('size', 500);

        // Generate QR code as base64 PNG for consistent preview rendering
        $qrCode = base64_encode(
            QrCode::format('png')
                ->size($size)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($donationUrl)
        );

        return response()->json([
            'success' => true,
            'qr_code_base64' => 'data:image/png;base64,' . $qrCode,
            'qr_identifier' => $qrIdentifier,
            'donation_url' => $donationUrl,
            'website' => $website->name
        ]);
    }

    /**
     * Display QR donation page (mobile-optimized)
     */
    public function donate(Request $request)
    {
        $websiteId = $request->query('website_id');
        
        if (!$websiteId) {
            abort(404, 'Invalid QR code');
        }
        
        $website = Website::findOrFail($websiteId);
        
        // Get QR parameters
        $qrIdentifier = $request->query('qr') ?? 'legacy_' . Str::random(8);
        $campaignName = $request->query('campaign');
        $donationType = $request->query('type', 'general');
        
        // Normalize variables for view
        $type = $request->query('type', 'donation');
        $selectedId = null;
        if ($type === 'auction') {
            $selectedId = $request->query('auction_id');
        } elseif ($type === 'ticket') {
            $selectedId = $request->query('ticket_id');
            // Map to 'sales' for view compatibility
            $type = 'sales';
        } elseif ($type === 'donation') {
            $selectedId = $request->query('student_id');
        }

        // Get payment fee
        $paymentSetting = \App\Models\PaymentSetting::find(1);
        $paymentFee = $paymentSetting ? $paymentSetting->fee : 2.9;
        
        return view('qr-donate', compact(
            'website',
            'qrIdentifier',
            'campaignName',
            'type',
            'selectedId',
            'paymentFee'
        ));
    }

    /**
     * Process QR code donation with payment
     */
    public function process(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|exists:websites,id',
                'amount' => 'required|numeric|min:1',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'qr_identifier' => 'required|string',
                'type' => 'required|string|in:donation,auction,sales',
                'payment_method' => 'required|string|in:authorize_net,coinbase',
                // Card details for Authorize.Net
                'card_number' => 'required_if:payment_method,authorize_net',
                'expiry_date' => 'required_if:payment_method,authorize_net',
                'cvv' => 'required_if:payment_method,authorize_net',
                'billing_address' => 'required_if:payment_method,authorize_net',
                'billing_city' => 'required_if:payment_method,authorize_net',
                'billing_state' => 'required_if:payment_method,authorize_net',
                'billing_zip' => 'required_if:payment_method,authorize_net',
                'billing_country' => 'required_if:payment_method,authorize_net',
            ]);

            // Get website
            $website = Website::findOrFail($request->website_id);
            
            // Map frontend type to donation type
            $typeMapping = [
                'donation' => 'student',
                'auction' => 'auction',
                'sales' => 'ticket'
            ];
            $donationType = $typeMapping[$request->type] ?? 'general';

            // Calculate total amount including processing fee and tip
            $baseAmount = $request->amount;
            $tipAmount = $request->input('tip_amount', 0);
            
            // Get processing fee
            $paymentSetting = \App\Models\PaymentSetting::find(1);
            $feePercent = $paymentSetting ? $paymentSetting->fee : 2.9;
            $processingFee = ($baseAmount / 100) * $feePercent;
            
            $totalAmount = $baseAmount + $processingFee + $tipAmount;

            // Create donation record
            $donation = new Donation;
            $donation->first_name = $request->first_name;
            $donation->last_name = $request->last_name;
            $donation->email = $request->email;
            $donation->amount = $baseAmount;
            $donation->website_id = $request->website_id;
            $donation->type = $donationType;
            $donation->status = 0; // Pending
            $donation->hide = $request->anonymous_donation ? 1 : 0;
            $donation->comment = $request->comment;
            
            // Process tip if enabled
            if ($request->input('tip_enabled') && $tipAmount > 0) {
                $donation->tip_amount = $tipAmount;
                $donation->tip_percentage = $request->input('tip_percentage');
                $donation->tip_enabled = true;
            }
            
            // Add QR tracking metadata
            $donation->utm_source = 'qr_code';
            $donation->utm_medium = 'qr';
            $donation->utm_campaign = $request->campaign_name ?? 'qr_donation';
            $donation->referrer_url = 'qr://' . $request->qr_identifier;
            
            // Add related IDs
            if ($request->student_id) $donation->student_id = $request->student_id;
            if ($request->auction_id) $donation->auction_id = $request->auction_id;
            if ($request->ticket_id) $donation->ticket_id = $request->ticket_id;
            
            $donation->save();

            // Track payment initiation funnel event
            try {
                $funnelService = new PaymentFunnelService();
                $funnelService->trackPaymentInitiated(
                    $donation->type ?? 'general',
                    $totalAmount,
                    $request->payment_method,
                    [
                        'first_name' => $donation->first_name,
                        'last_name' => $donation->last_name,
                        'email' => $donation->email,
                        'comment' => $donation->comment,
                        'anonymous' => $donation->hide ? true : false,
                        'source' => 'qr_code',
                        'qr_identifier' => $request->qr_identifier,
                        'campaign' => $request->campaign_name,
                        'tip_amount' => $tipAmount
                    ],
                    null,
                    $website->id
                );
            } catch (\Exception $e) {
                \Log::error('Payment funnel tracking error in QR donation: ' . $e->getMessage());
            }

            // Process payment based on method
            if ($request->payment_method === 'authorize_net') {
                return $this->processAuthorizeNetPayment($request, $donation, $totalAmount, $website);
            } elseif ($request->payment_method === 'coinbase') {
                return $this->processCoinbasePayment($request, $donation, $totalAmount, $website);
            }

            return back()->with('error', 'Invalid payment method selected.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('QR Donation Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check all required fields: ' . implode(', ', array_keys($e->errors())));
        } catch (\Exception $e) {
            \Log::error('QR Donation Processing Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Process Authorize.Net payment
     */
    private function processAuthorizeNetPayment($request, $donation, $totalAmount, $website)
    {
        try {
            // Parse expiry date
            $expiryParts = explode('/', $request->expiry_date);
            $expiryMonth = $expiryParts[0] ?? '';
            $expiryYear = $expiryParts[1] ?? '';

            // Initialize Authorize.Net
            $merchantAuth = new AnetAPI\MerchantAuthenticationType();
            $merchantAuth->setName(config('services.authorizenet.login_id'));
            $merchantAuth->setTransactionKey(config('services.authorizenet.transaction_key'));

            // Create credit card object
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber(str_replace(' ', '', $request->card_number));
            $creditCard->setExpirationDate($expiryYear . '-' . $expiryMonth);
            $creditCard->setCardCode($request->cvv);

            // Payment data
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            // Order information
            $order = new AnetAPI\OrderType();
            $order->setInvoiceNumber('QR-' . $donation->id);
            $order->setDescription('QR Code Donation - ' . ucfirst($request->type));

            // Billing address
            $billTo = new AnetAPI\CustomerAddressType();
            $billTo->setFirstName($request->first_name);
            $billTo->setLastName($request->last_name);
            $billTo->setAddress($request->billing_address);
            $billTo->setCity($request->billing_city);
            $billTo->setState($request->billing_state);
            $billTo->setZip($request->billing_zip);
            $billTo->setCountry($request->billing_country);
            $billTo->setEmail($request->email);

            // Create transaction request
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($totalAmount);
            $transactionRequestType->setOrder($order);
            $transactionRequestType->setPayment($paymentOne);
            $transactionRequestType->setBillTo($billTo);

            // Assemble the complete transaction request
            $authRequest = new AnetAPI\CreateTransactionRequest();
            $authRequest->setMerchantAuthentication($merchantAuth);
            $authRequest->setRefId('ref' . time());
            $authRequest->setTransactionRequest($transactionRequestType);

            // Create the controller and get the response
            $controller = new AnetController\CreateTransactionController($authRequest);
            $response = $controller->executeWithApiResponse(
                config('services.authorizenet.environment') === 'production' 
                    ? \net\authorize\api\constants\ANetEnvironment::PRODUCTION 
                    : \net\authorize\api\constants\ANetEnvironment::SANDBOX
            );

            if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    // Payment successful
                    $donation->status = 1;
                    $donation->transaction_id = $tresponse->getTransId();
                    $donation->payment_method = 'authorize_net';
                    $donation->save();
                    
                    // Create transaction record
                    $tran = new \App\Models\Transaction();
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->user_id = null; // QR donations don't have logged-in users
                    $tran->website_id = $website->id;
                    $tran->amount = $donation->amount;
                    $tran->fee = ($donation->amount / 100) * $feePercent;
                    $tran->fee_paid = 1;
                    
                    if ($donation->tip_enabled) {
                        $tran->tip_amount = $donation->tip_amount;
                        $tran->tip_percentage = $donation->tip_percentage;
                    }
                    
                    $tran->status = 1;
                    $tran->reference_id = $donation->id;
                    $tran->save();

                    // Track payment success
                    try {
                        $funnelService = new PaymentFunnelService();
                        $funnelService->trackPaymentCompleted(
                            $donation->type,
                            $totalAmount,
                            'authorize_net',
                            $tresponse->getTransId(),
                            ['qr_donation' => true],
                            null,
                            $website->id
                        );
                    } catch (\Exception $e) {
                        \Log::error('Payment funnel tracking error: ' . $e->getMessage());
                    }

                    // Use existing thank-you page with type
                    $type = $request->type; // donation, auction, sales
                    return view('thank-you', compact('type'));
                } else {
                    // Payment failed
                    $errorMessages = $tresponse->getErrors();
                    $errorText = $errorMessages[0]->getErrorText();
                    
                    \Log::error('Authorize.Net Transaction Error', [
                        'donation_id' => $donation->id,
                        'error' => $errorText
                    ]);

                    return back()->withInput()->with('error', 'Payment failed: ' . $errorText);
                }
            } else {
                // API error
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $errorText = $tresponse->getErrors()[0]->getErrorText();
                } else {
                    $errorText = $response->getMessages()->getMessage()[0]->getText();
                }

                \Log::error('Authorize.Net API Error', [
                    'donation_id' => $donation->id,
                    'error' => $errorText
                ]);

                return back()->withInput()->with('error', 'Payment processing error: ' . $errorText);
            }
        } catch (\Exception $e) {
            \Log::error('QR Donation Payment Processing Error: ' . $e->getMessage(), [
                'donation_id' => $donation->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    /**
     * Process Coinbase payment
     */
    private function processCoinbasePayment($request, $donation, $totalAmount, $website)
    {
        // Coinbase payment flow - create charge and redirect
        // This will be similar to existing coinbase flow
        return back()->with('error', 'Coinbase payment is not yet implemented for QR donations.');
    }

    /**
     * Admin: Display QR code generator page
     */
    public function adminIndex()
    {
        $user = auth()->user();
        $isSuper = $user && $user->hasRoleForWebsite('admin');
        

        // Resolve website context
        $currentWebsite = $this->getCurrentWebsite();
        if (!$currentWebsite) {
            $currentWebsite = Website::first();
        }

        $auctions = \App\Models\Auction::where('website_id', optional($currentWebsite)->id)->orderByDesc('id')->get(['id','title','value']);
        $tickets = \App\Models\Ticket::where('website_id', optional($currentWebsite)->id)->orderByDesc('id')->get(['id','name','price','category_id']);
        $students = \App\Models\User::where('website_id', optional($currentWebsite)->id)
            ->whereNotNull('parent_id')
            ->orderBy('name')
            ->get(['id','name','last_name','email']);

        $data = [
            'website' => $currentWebsite,
            'auctions' => $auctions,
            'tickets' => $tickets,
            'students' => $students,
        ];

        if ($isSuper) {
            $data['websites'] = Website::orderBy('name')->get(['id','name','domain']);
        }

        return view('admin.qr-codes.index', $data);
    }

    /**
     * Admin: Download QR code as PNG
     */
    public function download(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $qrCode = QrCode::format('png')
            ->size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($request->url);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-donation-' . time() . '.png"');
    }

    /**
     * Admin: Generate QR code for specific campaign
     */
    public function generateCampaign(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|exists:websites,id',
                'campaign_name' => 'required|string|max:255',
                'preset_amount' => 'nullable|numeric|min:1',
                'size' => 'nullable|integer|min:100|max:1000'
            ]);

            $website = Website::findOrFail($request->website_id);
            $size = $request->size ?? 300;
            
            // Generate unique QR code identifier
            $qrIdentifier = Str::random(10);
            
            // Build campaign URL using website's domain
            $params = [
                'qr' => $qrIdentifier,
                'website_id' => $website->id,
                'campaign' => $request->campaign_name
            ];
            
            if ($request->preset_amount) {
                $params['amount'] = $request->preset_amount;
            }
            
            // Use website domain for QR code URL
            $donationUrl = $this->getCurrentDomain() . '/qr-donate?' . http_build_query($params);
            
            // Generate QR code as base64
            $qrCode = base64_encode(
                QrCode::format('png')
                    ->size($size)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($donationUrl)
            );
            
            return response()->json([
                'success' => true,
                'qr_code_base64' => 'data:image/png;base64,' . $qrCode,
                'donation_url' => $donationUrl,
                'campaign_name' => $request->campaign_name,
                'website' => $website->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR code statistics
     */
    public function statistics(Request $request)
    {
        $websiteId = $request->query('website_id');
        $startDate = $request->query('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));

        $query = Donation::where('utm_source', 'qr_code')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }

        $stats = [
            'total_scans' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'completed_donations' => $query->where('status', 1)->count(),
            'pending_donations' => $query->where('status', 0)->count(),
            'average_donation' => $query->avg('amount'),
            'by_campaign' => $query->select('utm_campaign', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(amount) as total'))
                ->groupBy('utm_campaign')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }
}
