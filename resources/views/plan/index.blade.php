@extends('layouts.app')

<style>
    .columns {
    float: left;
    width: 33.3%;
    padding: 8px;
    }

    .price {
    list-style-type: none;
    border: 1px solid #eee;
    margin: 0;
    padding: 0;
    -webkit-transition: 0.3s;
    transition: 0.3s;
    }

    .price:hover {
    box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
    }

    .price .header {
    background-color: #111;
    color: white;
    font-size: 25px;
    }

    .price li {
    border-bottom: 1px solid #eee;
    padding: 20px;
    text-align: center;
    }

    .price .grey {
    background-color: #eee;
    font-size: 20px;
    }

    .button {
    background-color: #04AA6D;
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    font-size: 18px;
    }

    @media only screen and (max-width: 600px) {
    .columns {
        width: 100%;
    }
}
</style>

@section('content')
    <div class="container m-4">
        <div class="card p-4">
            <div class="card-body">
                <div class="error-area m-2 p-2">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('alert-success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('alert-success') }}
                        </div>
                    @endif
                </div>
                <h2 style="text-align:center">Responsive Pricing Tables</h2>
                <p style="text-align:center">Resize the browser window to see the effect.</p>
                @foreach ($plans as $item)
                    <div class="columns">
                        <ul class="price">
                            <li class="header">{{ $item->name }}</li>
                            <li class="grey">{{ $item->currency == "usd" ? '$' : $item->currency }} {{ $item->price }} / {{ $item->billing_method }}</li>
                            <li>Demo 10GB Storage</li>
                            <li>Demo 10 Emails</li>
                            <li>Demo 10 Domains</li>
                            <li>Demo 1GB Bandwidth</li>
                            <li class="grey"><a href="{{ route('plan.checkout', $item->plan_id) }}" class="button">Checkout</a></li>
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


