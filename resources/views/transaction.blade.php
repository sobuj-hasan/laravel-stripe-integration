@extends('layouts.app')

@section('content')
    <div class="container m-4">
        <div class="card p-4">
            <div class="card-body">
                <h1>Your Transaction</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentHistory as $invoice)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($invoice->created)->toDateString() }}</td>
                                <td>{{ $invoice->amount_due / 100 }} {{ $invoice->currency }}</td>
                                <td>{{ $invoice->status }}</td>
                            </tr>
                        @empty
                            <span>Transaction not found !</span>
                        @endforelse
                    </tbody>
                </table>
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

