@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>Customer Details</h3>
                        <p><strong>Name :</strong> {{ $customer->first_name.' '.$customer->last_name }}</p>
                        <p><strong>Gender :</strong> {{ $customer->gender ? $customer->gender : 'N/A' }}</p>
                        <p><strong>Email :</strong> {{ $customer->email ? $customer->email : 'N/A' }}</p>
                        <p><strong>Mobile :</strong> {{ $customer->mobile ? $customer->mobile : 'N/A' }}</p>
                        <p><strong>Country :</strong> {{ $customer->country_name ? $customer->country_name : 'N/A' }}</p>
                        <p><strong>State :</strong> {{ $customer->state_name ? $customer->state_name : 'N/A' }}</p>
                        <a class="btn btn-primary" href="{{ url('customer') }}">Go to customer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
