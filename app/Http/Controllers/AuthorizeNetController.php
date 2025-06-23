<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Models\PaymentSetting;
use App\Models\Donation;

class AuthorizeNetController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id): View
    {
        $data = Donation::find($id);

        return view('authorize-net',compact('data'));
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentPost(Request $request): RedirectResponse
    {
        $cardNumber = $request->input('card_number');
        $expirationDate = $request->input('expiration_date');
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
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount("10.00");
        $transactionRequestType->setPayment($payment);

        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId("ref" . time());
        $requests->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            // dd($tresponse);

            if ($tresponse != null & $tresponse->getResponseCode() == "1") {
                // dd($request->all());
                $donation = Donation::find($request->donation_id);
                $donation->status = 1;
                $donation->transaction_id = $tresponse->getTransId();
                $donation->update();
                return redirect('/student/'.$donation->user->id.'-'.$donation->user->name.'-'.$donation->user->last_name)->with('success', 'Payment successful!');
            } else {
                return back()->with('error', "Payment failed: ". $response->getMessages()->getMessage()[0]->getText());
            }
        } else {
            return back()->with('error', "Payment failed: " . $response->getMessages()->getMessage()[0]->getText());
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
