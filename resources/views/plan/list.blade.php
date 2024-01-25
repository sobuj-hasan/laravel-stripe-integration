@extends('layouts.app')

@section('content')
    <div class="container m-4">
        <div class="card p-4">
            <div class="card-body">
                <h1>Your Subscriptions</h1>
                @if (count($subscriptions) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Plan Name</th>
                                <th scope="col">Subscription Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Trial Start At</th>
                                <th scope="col">Auto Renew</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $item)
                                {{ $item }}
                                <tr>
                                    <th scope="row">1</th>
                                    <td>{{ $item->plan->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>$ {{ $item->plan->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            @if ($item->ends_at == null)
                                                <input class="form-check-input" type="checkbox" checked value="{{$item->name}}" id="switcher">
                                                @else
                                                <input class="form-check-input" type="checkbox" value="{{$item->name}}" id="switcher">
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="mt-3">
                        <span class="text-danger">You are not subscribed to any Plan !</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#switcher').click(function() {
                var subscriptionName = $('#switcher').val();
                if($(this).is(':checked')){
                    $.ajax({
                        url: '{{  route("subscription.resume")  }}',
                        data: { subscriptionName },
                        type: "GET",
                        success:function(response)
                        {
                            console.log(response);
                        },
                        error: function(response)
                        {

                        }
                    })
                }else{
                    $.ajax({
                        url: '{{  route("subscription.cancel")  }}',
                        data: { subscriptionName },
                        type: "GET",
                        success:function(response)
                        {
                            console.log(response);
                        },
                        error: function(response)
                        {

                        }
                    });
                }
            });
        });
    </script>
@endsection

