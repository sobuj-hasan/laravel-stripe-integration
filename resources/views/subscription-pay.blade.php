@extends('layouts.app')

@section('style')
    <style>
        .StripeElement {
            background-color: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid {
            border-color: #fa755a;
        }
        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
@endsection
@section('content')
    <div class="container my-4">
        <div class="card p-4">
            <div class="plan-info mb-3">
                <span>Your Plan <strong>{{ $plan->name }}</strong></span><br>
                <span>Plan Price <strong>{{ $plan->currency == "usd" ? '$' : $plan->currency }} {{ $plan->price }}</strong> / <strong>{{ $plan->billing_method }}</strong></span>
            </div>
            <form action="{{ route('plan.process') }}" method="POST" id="subscribe-form">
                <div class="form-group">
                    <div class="row">
                        {{-- @foreach($plans as $plan)
                        <div class="col-md-4">
                            <div class="subscription-option">
                                <input type="radio" id="plan-silver" name="plan" value='{{$plan->id}}'>
                                <label for="plan-silver">
                                    <span class="plan-price">{{$plan->currency}}{{$plan->amount/100}}<small> /{{$plan->interval}}</small></span>
                                    <span class="plan-name">{{$plan->product->name}}</span>
                                </label>
                            </div>
                        </div>
                        @endforeach --}}
                    </div>
                </div>
                <input type="hidden" name="plan_id" value="{{ $plan->plan_id }}" class="form-control">
                <label class="my-2" for="card-holder-name">Card Holder Name</label><br>
                <input class="form-control" id="card-holder-name" name="card_holder_name" type="text">
                @csrf
                <div class="form-row">
                    <label class="my-2" for="card-element">Credit or debit card</label>
                    <div id="card-element" class="form-control">
                    </div>
                    <!-- Used to display form errors. -->
                    <div id="card-errors" role="alert"></div>
                </div>
                <div class="stripe-errors"></div>
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                    @endforeach
                </div>
                @endif
                <div class="form-group text-center mt-4">
                    <button  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-lg btn-success btn-block">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {hidePostalCode: true,
            style: style});
        card.mount('#card-element');
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            console.log("attempting");
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: { name: cardHolderName.value }
                    }
                }
                );
            if (error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                paymentMethodHandler(setupIntent.payment_method);
            }
        });
        function paymentMethodHandler(payment_method) {
            var form = document.getElementById('subscribe-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', payment_method);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
@endsection

