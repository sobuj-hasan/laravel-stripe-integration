<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\SetupIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // private $secretKey; 
    // public function __construct()
    // {
    //     $this->secretKey = config('services.stripe.secret'); 
    //     Stripe::setApiKey($this->secretKey); // Set the API key
    // }

    // public function index()
    // {
    //     $user = Auth::user();

    //     // Now you can access $this->secretKey in the index method
    //     // $this->secretKey contains the Stripe secret key

    //     $intent = SetupIntent::create([
    //         'customer' => $user->id, // Replace with your customer ID
    //     ]);

    //     return view('dashboard', [
    //         'intent' => $intent,
    //     ]);
    // }


    public function subscription_pay(){
        $user = Auth::user();
        return view('subscription-pay', [
            'intent' => $user->createSetupIntent(),
        ]);
    }

    public function singleCharge(Request $request){
        $pay = $request->amount;
        $amount = $pay * 100;
        $payment_method = $request->payment_method;

        $user = Auth::user();
        $user->createOrGetStripeCustomer();

        $payment_method = $user->addPaymentMethod($payment_method);
        $user->charge($amount, $payment_method->id);
        return redirect(route('subscription.pay'));
    }
}
