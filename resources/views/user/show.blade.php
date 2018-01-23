@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>User Details</h3>
                        <p><strong>Name :</strong> {{ $user->name }}</p>
                        <p><strong>Email :</strong> {{ $user->email ? $user->email : 'N/A' }}</p>
                        <p><strong>Country :</strong> {{ $user->country_name ? $user->country_name : 'N/A' }}</p>
                        <p><strong>State :</strong> {{ $user->state_name ? $user->state_name : 'N/A' }}</p>
                        <p><strong>Is verified :</strong> {{ ($user->is_verify == 1) ? 'Yes' : 'No' }}</p>
                        <a class="btn btn-primary" href="{{ url('user') }}">Go to user</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
