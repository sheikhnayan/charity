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
        }

        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();

        if ($setting->payment_method == 'stripe') {
            return view('stripe',compact('data','type'));
        }else{
            return view('authorize-net',compact('data','type'));
        }


    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentPost(Request $request)
    {

        // dd($request->all());

        $cardNumber = $request->input('card_number');
        $date = \Carbon\Carbon::parse($request->input('date'))->format('m/y');
        // dd($date);
        $expirationDate = $date;
        $cvv = $request->input('cvv');

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_TRANSACTION_KEY'));

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expirationDate);
        $creditCard->setCardCode($cvv);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        if ($request->type == 'auction') {
            # code...
            $transactionRequestType->setTransactionType("authOnlyTransaction");
        } else {
            # code...
            $transactionRequestType->setTransactionType("authCaptureTransaction");
        }
        $amount = number_format((float)$request->amount, 2, '.', '');
        $transactionRequestType->setAmount($amount);
        // $transactionRequestType->setAmount("10.00");
        $transactionRequestType->setPayment($payment);

        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId("ref" . time());
        $requests->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            if ($tresponse != null & $tresponse->getResponseCode() == "1") {
                // dd($request->all());
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

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

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
