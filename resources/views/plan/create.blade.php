@extends('layouts.app')

@section('content')
    <div class="container m-4">
        <div class="card p-4">
            <div class="card-body">
                <form action="{{ route('plan.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="exampleInputEmail1">Plan Name</label>
                        <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name">
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputEmail1">Character Limit</label>
                        <input type="text" class="form-control" name="character_limit" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Character Limit">
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputEmail1">Description</label>
                        <input type="text" class="form-control" name="nickname" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Description">
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputPassword1">Amount </label>
                        <input type="number" class="form-control" name="amount" id="exampleInputPassword1" placeholder="Amount">
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputPassword1">Currency </label>
                        <input type="text" class="form-control" name="currency" id="exampleInputPassword1" placeholder="Currency">
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputPassword1">Interval Count</label>
                        <input type="text" class="form-control" name="interval_count" id="exampleInputPassword1" placeholder="Interval Count">
                    </div>
                    <div class="form-group mb-4">
                        <label for="">Billing Period</label>
                        <select class="form-select" name="billing_period" aria-label="Default select example">
                            <option selected>Select billing plan</option>
                            <option value="day">Days</option>
                            <option value="week">Weekly</option>
                            <option value="month">Monthly</option>
                            <option value="year">Yearly</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
