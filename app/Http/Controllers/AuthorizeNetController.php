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
                        $tran->fee = 0;
                        $tran->fee_paid = 1;

                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

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
                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

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

                    return view('thank-you', compact('type'));

                }else {
                        # code...
                        return redirect('/')->with('success', 'Payment successful!');
                }


            } else {
                return back()->with('error', "Payment failed: ". $response->getMessages()->getMessage()[0]->getText());
            }
        } else {
            return back()->with('error', "Payment failed: " . $response->getMessages()->getMessage()[0]->getText());
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
        $paymentConfig = $this->paymentGatewayService->getPaymentConfig($website);
        
        if (!$paymentConfig || !isset($paymentConfig['stripe']['secret_key'])) {
            return back()->with('error', 'Stripe is not configured for this website');
        }

        Stripe\Stripe::setApiKey($paymentConfig['stripe']['secret_key']);

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
                        $tran->country = $request->country;
                        $tran->fee = 0;
                        $tran->fee_paid = 1;

                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        return view('thank-you', compact('type'));
                        return view('stripe',compact('data','type'));
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
                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

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

                    return view('thank-you', compact('type'));

                }else {
                        # code...
                        return redirect('/')->with('success', 'Payment successful!');
                }

        } catch (CardException $e) {
            // Card declined or invalid
            // dd($e);
            return back()->with('error', "Payment failed: ". $e->getError()->message);
            // return back()->withErrors(['Payment failed: ' => $e->getError()->message]);
        } catch (\Exception $e) {
            // Anything else
            // dd($e);
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
}
