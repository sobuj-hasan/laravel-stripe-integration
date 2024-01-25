<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Stripe\Plan;
use Stripe\Stripe;
use App\Models\PlanItem;
use Illuminate\Http\Request;
use App\Models\Plan as ModelsPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class PlanController extends Controller
{
    private $secretKey;
    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->secretKey); // Set the API key
    }

    public function index(){
        $plans = ModelsPlan::all();
        return view('plan.index', get_defined_vars());
    }

    public function create(){
        return view('plan.create');
    }

    public function store(Request $request){
        // dd($request->all());
        $amountForStripe = $request->amount * 100;
        try {
            $plan = Plan::create([
                'amount' => $amountForStripe,
                'currency' => $request->currency,
                'nickname' => $request->nickname,
                'interval' => $request->billing_period,
                'interval_count' => $request->interval_count,
                'product' => [
                    'name' => $request->name,
                ],
            ]);

            ModelsPlan::create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'nickname' => $request->nickname,
                'billing_method' => $plan->interval,
                'price' => $request->amount,
                'currency' => $plan->currency,
                'interval_count' => $plan->interval_count,
            ]);

            PlanItem::create([
                'plan_id' => $plan->id,
                'character_limit' => $request->character_limit,
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->route('plan.index');
    }

    public function checkout($plan_id){
        $plan = ModelsPlan::where('plan_id', $plan_id)->first();
        if (!$plan) {
            return back()->withErrors([
                'plan_error' => "This plan is something went wrong!",
            ]);
        }

        return view('subscription-pay', [
            'plan' => $plan,
            'intent' => Auth::user()->createSetupIntent(),
        ]);
    }

    public function planProcess(Request $request){

        dd($request->all());

        $user = Auth()->user();
        $user->createOrGetStripeCustomer();
        $paymentMethodInfo = null;
        $paymentMethod = $request->payment_method;
        if ($paymentMethod != null) {
            $paymentMethodInfo = $user->addPaymentMethod($paymentMethod);
        }

        $plan = $request->plan_id;
        $plan_name = ModelsPlan::where('plan_id', $plan)->first()->name;
        try {
            $subscriptionInfo = $user->newSubscription($plan_name, $plan)->create($paymentMethodInfo != null ? $paymentMethodInfo->id : '');

            // Subscription created now store data in payment table
            $plan_info = ModelsPlan::where('plan_id', $subscriptionInfo->stripe_price)->first();
            Payment::create([
                'user_id' => $subscriptionInfo->user_id,
                'plan_id' => $subscriptionInfo->stripe_price,
                'subscription_id' => $subscriptionInfo->id,
                'stripe_id' => $subscriptionInfo->stripe_id,
                'stripe_status' => $subscriptionInfo->stripe_status,
                'quantity' => $subscriptionInfo->quantity,
                'validity' => $plan_info->billing_method,
                'interval_count' => $plan_info->interval_count,
                'amount' => $plan_info->price,
                'status' => 'paid',
            ]);
        } catch (\Throwable $th) {
            $errorMessage = $th->getMessage();
            return back()->withErrors([
                'plan_error' => "Payment error: " . $errorMessage,
            ]);
        }
        
        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        return redirect()->route('plan.index');
    }

    public function subscriptionAll(){
        $subscriptions = Auth::user()->subscriptions;
        // dd($subscriptions);
        return view('plan.list', get_defined_vars());
    }

    public function cancelSubscription(Request $request){
        return $subscriptionName = $request->subscriptionName;
        
        if($subscriptionName){
            $user = Auth::user();
            $user->subscription($subscriptionName)->cancel();
            return 'Your subscription canceled';
        }
    }

    public function resumeSubscription(Request $request)
    {
        $subscriptionName = $request->subscriptionName;
        if ($subscriptionName) {
            $user = Auth::user();
            $user->subscription($subscriptionName)->resume();
            return 'Your subscription started auto Renewed';
        }
    }

    public function transaction()
    {
        $user = Auth::user();
        if ($user->stripe_id) {
            $subscriptionName = $user->subscriptions;
            $stripeCustomer = $user->asStripeCustomer();
            $paymentHistory = \Stripe\Invoice::all(['customer' => $stripeCustomer->id]);
            return view('transaction', get_defined_vars());
        }
        else{
            $paymentHistory = [];
            return view('transaction', get_defined_vars());
        }
    }

}
