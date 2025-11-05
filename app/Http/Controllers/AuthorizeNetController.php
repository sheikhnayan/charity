<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Models\PaymentSetting;
use App\Models\Donation;
use App\Models\TicektSell;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\Auction;
use App\Models\Website;
use App\Models\Page;
use App\Models\Setting;
use App\Services\PaymentGatewayService;
use App\Services\PaymentFunnelService;
use App\Mail\TransactionInvoice;
use Illuminate\Support\Facades\Mail;
use Stripe;

class AuthorizeNetController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type, $id): View
    {
        if ($type == 'donation') {
            # code...
            $data = Donation::find($id);
        }elseif($type == 'ticket'){
            $data = TicektSell::find($id);
        }elseif($type == 'auction'){
            // dd($request->amount);
            $data = Auction::find($id);
            $data->amount = $request->amount;
        }elseif($type == 'investment'){
            $data = \App\Models\Investment::find($id);
            $data->amount = $data->investment_amount; // Set amount for payment processing
        }

        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            abort(404, 'Website not found');
        }
        
        $paymentGatewayService = new PaymentGatewayService();
        $paymentConfig = $paymentGatewayService->getPaymentConfigForWebsite($website);
        $paymentMethod = $paymentConfig['payment_method'];

        if ($paymentMethod == 'stripe') {
            return view('stripe', compact('data', 'type', 'website', 'paymentConfig'));
        } else {
            return view('authorize-net', compact('data', 'type', 'website', 'paymentConfig'));
        }
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentPost(Request $request)
    {
        // Get website from current domain
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            return back()->with('error', 'Website not found');
        }
        
        $paymentGatewayService = new PaymentGatewayService();
        $paymentConfig = $paymentGatewayService->getPaymentConfigForWebsite($website);
        
        // Validate payment configuration
        $validationErrors = $paymentGatewayService->validatePaymentConfig($website);
        if (!empty($validationErrors)) {
            return back()->with('error', 'Payment configuration error: ' . implode(', ', $validationErrors));
        }

        $cardNumber = $request->input('card_number');
        $date = \Carbon\Carbon::parse($request->input('date'))->format('m/y');
        $expirationDate = $date;
        $cvv = $request->input('cvv');

        // Use website-specific credentials instead of environment variables
        $merchantAuthentication = $paymentGatewayService->createAuthorizeNetAuth($website);
        if (!$merchantAuthentication) {
            return back()->with('error', 'Failed to initialize payment gateway');
        }

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expirationDate);
        $creditCard->setCardCode($cvv);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        if ($request->type == 'auction') {
            $transactionRequestType->setTransactionType("authOnlyTransaction");
        } else {
            $transactionRequestType->setTransactionType("authCaptureTransaction");
        }
        $amount = number_format((float)$request->amount, 2, '.', '');
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($payment);

        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId("ref" . time());
        $requests->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($requests);
        // Use website-specific environment (sandbox/production)
        $environment = $paymentGatewayService->getAuthorizeNetEnvironment($website);
        $response = $controller->executeWithApiResponse($environment);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            if ($tresponse != null & $tresponse->getResponseCode() == "1") {
                $type = $request->type;
                if ($request->type == 'donation') {
                    # code...
                    $donation = Donation::find($request->donation_id);
                    $donation->status = 1;
                    $donation->transaction_id = $tresponse->getTransId();
                    
                    // Process tip if enabled
                    if ($request->input('tip_enabled') && $request->input('tip_amount') > 0) {
                        $donation->tip_amount = $request->input('tip_amount');
                        $donation->tip_percentage = $request->input('tip_percentage');
                        $donation->tip_enabled = true;
                    }
                    
                    $donation->update();

                    if ($donation->type == 'student') {
                        # code...
                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $tresponse->getTransId();
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->country = $request->country;
                        $tran->ip_address = $request->ip();
                        $tran->fee = 0;
                        $tran->fee_paid = 1;
                        
                        // Add tip information
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }

                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        // Track successful payment
                        $this->trackPaymentFunnel('completed', $donation->type, $donation->amount, $tresponse->getTransId(), null, $request->input('student_id'));

                        return view('thank-you', compact('type'));
                    }elseif ($donation->type == 'general') {
                        # code...
                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $tresponse->getTransId();
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->country = $request->country;
                        $tran->fee = 0;
                        $tran->fee_paid = 1;
                        
                        // Add tip information
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }
                        
                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        return view('thank-you', compact('type'));
                    } else {
                        # code...
                        return redirect('/auction')->with('success', 'Payment successful!');
                    }
                }elseif($request->type == 'ticket'){
                    $donation = TicektSell::find($request->donation_id);
                    $donation->status = 1;
                    $donation->first_name = $request->first_name;
                    $donation->last_name = $request->last_name;
                    $donation->email = $request->email;
                    $donation->update();


                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'ticket';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = 0;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Authorize.Net payment for ticket purchase
                    $this->trackPaymentFunnel('completed', 'ticket', $request->amount, $tresponse->getTransId(), null, null);

                    foreach ($donation->details as $key => $value) {
                        # code...
                        $ticket = Ticket::find($value->ticket_id);
                        $ticket->quantity -= $value->quantity;
                        $ticket->update();
                    }

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'auction'){
                    $donation = Auction::find($request->donation_id);
                    $donation->last_bid = $request->amount;
                    $donation->transaction_id = $tresponse->getTransId();
                    // $donation->email = $request->email;
                    $donation->update();

                    $del = Transaction::where('type','auction')->where('reference_id',$donation->id)->delete();


                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'auction';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = 0;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Authorize.Net payment for auction
                    $this->trackPaymentFunnel('completed', 'auction', $request->amount, $tresponse->getTransId(), null, null);

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'investment'){
                    $investment = \App\Models\Investment::find($request->donation_id);
                    $investment->status = 'completed';
                    $investment->transaction_id = $tresponse->getTransId();
                    $investment->update();

                    $tran = new Transaction;
                    $tran->amount = $investment->investment_amount;
                    $tran->type = 'investment';
                    $tran->website_id = $investment->website_id;
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = 0;
                    $tran->fee_paid = 1;
                    $tran->status = 1; // Completed status
                    $tran->reference_id = $investment->id;
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Authorize.Net payment for investment
                    $this->trackPaymentFunnel('completed', 'investment', $investment->investment_amount, $tresponse->getTransId(), null, null);

                    return view('thank-you', compact('type'));

                }else {
                        # code...
                        return redirect('/')->with('success', 'Payment successful!');
                }


            } else {
                // Track payment failure
                $amount = $request->input('amount', 0);
                $type = $request->input('type', 'general');
                $this->trackPaymentFunnel('failed', $type, $amount, null, 'Payment failed - Response error');
                
                dd($response);
                return back()->with('error', "Payment failed");
            }
        } else {
            // Track payment failure
            $amount = $request->input('amount', 0);
            $type = $request->input('type', 'general');
            $this->trackPaymentFunnel('failed', $type, $amount, null, 'Payment failed - Transaction not approved');
            
            // dd($response);
                dd($response);
            return back()->with('error', "Payment failed ");
        }

    }

    public function paymentStripe(Request $request)
    {
        // Get current website based on domain
        $currentDomain = request()->getHost();
        $website = Website::where('domain', $currentDomain)->first();
        
        if (!$website) {
            return back()->with('error', 'Website not found');
        }

        // Get Stripe credentials for this website
        $paymentGatewayService = new PaymentGatewayService();
        $paymentData = $paymentGatewayService->getPaymentConfigForWebsite($website);
        
        if (!$paymentData || !isset($paymentData['config']['secret_key'])) {
            return back()->with('error', 'Stripe is not configured for this website');
        }

        Stripe\Stripe::setApiKey($paymentData['config']['secret_key']);

        try {
            // 3️⃣ Create a one‑time token from the raw card data
            $charge = Stripe\Charge::create ([
                    "amount" => $request->amount * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "Payment fit"
            ]);

            // dd();
            $type = $request->type;

              if ($request->type == 'donation') {
                    # code...
                    $donation = Donation::find($request->donation_id);
                    $donation->status = 1;
                    $donation->transaction_id = $charge->id;
                    
                    // Process tip if enabled
                    if ($request->input('tip_enabled') && $request->input('tip_amount') > 0) {
                        $donation->tip_amount = $request->input('tip_amount');
                        $donation->tip_percentage = $request->input('tip_percentage');
                        $donation->tip_enabled = true;
                    }
                    
                    $donation->update();

                    if ($donation->type == 'student') {
                        # code...
                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $charge->id;
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->ip_address = $request->ip();
                        $tran->fee = 0;
                        $tran->fee_paid = 1;
                        
                        // Add tip information (Stripe)
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }

                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        // Track successful Stripe payment
                        $this->trackPaymentFunnel('completed', $donation->type, $donation->amount, $charge->id, null, $request->input('student_id'));

                        return view('thank-you', compact('type'));
                    }elseif ($donation->type == 'general') {
                        # code...
                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $charge->id;
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->country = $request->country;
                        $tran->fee = 0;
                        $tran->fee_paid = 1;
                        
                        // Add tip information (Stripe)
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }
                        
                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        return view('thank-you', compact('type'));
                    } else {
                        # code...
                        return redirect('/auction')->with('success', 'Payment successful!');
                    }
                }elseif($request->type == 'ticket'){
                    $donation = TicektSell::find($request->donation_id);
                    $donation->status = 1;
                    $donation->first_name = $request->first_name;
                    $donation->last_name = $request->last_name;
                    $donation->email = $request->email;
                    $donation->update();


                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'ticket';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $charge->id;
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = 0;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Stripe payment for ticket purchase
                    $this->trackPaymentFunnel('completed', 'ticket', $request->amount, $charge->id, null, null);

                    foreach ($donation->details as $key => $value) {
                        # code...
                        $ticket = Ticket::find($value->ticket_id);
                        $ticket->quantity -= $value->quantity;
                        $ticket->update();
                    }

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'auction'){
                    $donation = Auction::find($request->donation_id);
                    $donation->last_bid = $request->amount;
                    $donation->transaction_id = $charge->id;
                    // $donation->email = $request->email;
                    $donation->update();

                    $del = Transaction::where('type','auction')->where('reference_id',$donation->id)->delete();


                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'auction';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $charge->id;
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = 0;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Stripe payment for auction
                    $this->trackPaymentFunnel('completed', 'auction', $request->amount, $charge->id, null, null);

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'investment'){
                    $investment = \App\Models\Investment::find($request->donation_id);
                    $investment->status = 'completed';
                    $investment->transaction_id = $charge->id;
                    $investment->update();

                    $tran = new Transaction;
                    $tran->amount = $investment->investment_amount;
                    $tran->type = 'investment';
                    $tran->website_id = $investment->website_id;
                    $tran->transaction_id = $charge->id;
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = 0;
                    $tran->fee_paid = 1;
                    $tran->status = 1; // Completed status
                    $tran->reference_id = $investment->id;
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Stripe payment for investment
                    $this->trackPaymentFunnel('completed', 'investment', $investment->investment_amount, $charge->id, null, null);

                    return view('thank-you', compact('type'));

                }else {
                        # code...
                        return redirect('/')->with('success', 'Payment successful!');
                }

        } catch (CardException $e) {
            // Card declined or invalid
            // Track payment failure
            $amount = $request->input('amount', 0);
            $type = $request->input('type', 'general');
            $this->trackPaymentFunnel('failed', $type, $amount, null, 'Card declined: ' . $e->getError()->message);
            
            return back()->with('error', "Payment failed: ". $e->getError()->message);
        } catch (\Exception $e) {
            // Anything else
            // Track payment failure
            $amount = $request->input('amount', 0);
            $type = $request->input('type', 'general');
            $this->trackPaymentFunnel('failed', $type, $amount, null, 'Payment processing error: ' . $e->getMessage());
            
            report($e);
            return back()->with('error', "Payment failed: ". 'Payment could not be processed.');
            // return back()->withErrors(['Payment failed: ' => 'Payment could not be processed.']);
        }
    }

    public function setting()
    {
        $data = PaymentSetting::first();

        return view('admin.setting.payment', compact('data'));
    }

    public function update(Request $request){
        // dd($request->all());
        $update = PaymentSetting::first();
        $update->app_id = $request->app_id;
        $update->transaction_id = $request->transaction_id;
        $update->fee = $request->fee;
        $update->update();

        return back();
    }

    /**
     * Send invoice email after transaction
     */
    private function sendInvoiceEmail($transaction, $website)
    {
        try {
            Mail::to($transaction->email)->send(new TransactionInvoice($transaction, $website));
        } catch (\Exception $e) {
            \Log::error('Failed to send transaction invoice', [
                'transaction_id' => $transaction->transaction_id,
                'email' => $transaction->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle post-transaction operations (email, logging, etc.)
     */
    private function afterTransactionSaved($transaction, $website)
    {
        // Send invoice email
        $this->sendInvoiceEmail($transaction, $website);
        
        // Log successful transaction
        \Log::info('Transaction completed and email sent', [
            'transaction_id' => $transaction->transaction_id,
            'email' => $transaction->email,
            'amount' => $transaction->amount,
            'type' => $transaction->type ?? 'unknown'
        ]);
    }

    /**
     * Set common transaction fields including IP address
     */
    private function setTransactionFields($transaction, $request, $transactionId, $donationOrAmount, $websiteId, $type)
    {
        $transaction->amount = is_object($donationOrAmount) ? $donationOrAmount->amount : $donationOrAmount;
        $transaction->type = $type;
        $transaction->website_id = $websiteId;
        $transaction->transaction_id = $transactionId;
        $transaction->name = $request->first_name;
        $transaction->last_name = $request->last_name;
        $transaction->email = $request->email;
        $transaction->address = $request->address ?? null;
        $transaction->apartment = $request->apartment ?? null;
        $transaction->city = $request->city ?? null;
        $transaction->state = $request->state ?? null;
        $transaction->zip = $request->zipcode ?? null;
        $transaction->phone = $request->phone ?? null;
        $transaction->name_on_card = $request->name_on_card ?? null;
        $transaction->country = $request->country ?? null;
        $transaction->ip_address = $request->ip();
        $transaction->fee = 0;
        $transaction->fee_paid = 1;
        
        return $transaction;
    }

    /**
     * Track payment funnel events
     */
    protected function trackPaymentFunnel($event, $type, $amount, $transactionId = null, $errorMessage = null, $userId = null, $paymentMethod = null)
    {
        try {
            \Log::info('Payment funnel tracking initiated', [
                'event' => $event,
                'type' => $type,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'payment_method' => $paymentMethod
            ]);
            
            $funnelService = new PaymentFunnelService();
            
            // Determine form type based on type parameter
            $formType = $this->mapTypeToFormType($type);
            
            // Auto-detect payment method if not provided
            if (!$paymentMethod) {
                $paymentMethod = $this->detectPaymentMethod();
            }
            
            \Log::info('Payment funnel tracking details', [
                'form_type' => $formType,
                'payment_method' => $paymentMethod,
                'event' => $event
            ]);
            
            if ($event === 'completed') {
                $result = $funnelService->trackPaymentCompleted(
                    $formType,
                    $amount,
                    $paymentMethod,
                    $transactionId,
                    $userId
                );
                \Log::info('Payment completion tracked successfully', ['result' => $result ? $result->id : 'false']);
            } elseif ($event === 'failed') {
                $result = $funnelService->trackPaymentFailed(
                    $formType,
                    $amount,
                    $paymentMethod,
                    $errorMessage,
                    $userId
                );
                \Log::info('Payment failure tracked successfully', ['result' => $result ? $result->id : 'false']);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the payment process
            \Log::error('Payment funnel tracking error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'event' => $event,
                'type' => $type,
                'amount' => $amount
            ]);
        }
    }

    /**
     * Auto-detect payment method from request context
     */
    protected function detectPaymentMethod()
    {
        $request = request();
        
        // Check if it's a Stripe payment (has stripeToken)
        if ($request->has('stripeToken')) {
            return 'stripe';
        }
        
        // Check if it's crypto payment (future implementation)
        if ($request->has('cryptoWallet') || $request->has('blockchainTx')) {
            return 'crypto';
        }
        
        // Default to Authorize.Net
        return 'authorize_net';
    }

    /**
     * Map payment type to form type for funnel tracking
     */
    protected function mapTypeToFormType($type)
    {
        switch ($type) {
            case 'student':
                return 'student';
            case 'donation':
            case 'general':
                return 'general';
            case 'ticket':
                return 'ticket';
            case 'auction':
                return 'auction';
            case 'investment':
                return 'investment';
            default:
                return 'general';
        }
    }
}
