@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Customer {{ isset($customer) && count($customer) > 0 ? 'Edit' : 'Add' }}</div>
                    <div class="panel-body">
                        @if(isset($customer) && count($customer) > 0)
                            {!! Form::model($customer, ['url' => URL::route('customer.update', $customer->id), 'method' => 'PUT', 'id' => 'customer_form', 'data-parsley-validate' => true]) !!}
                        @else
                             {!! Form::open(['url' => URL::route('customer.store'), 'method' => 'POST', 'id' => 'customer_form', 'data-parsley-validate' => true]) !!}
                        @endif

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">First Name*</label>
                            <div class="col-md-6">
                                {!! Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control', 'required', 'pattern' => config('app.validation_patterns.name'), 'maxlength' => config('app.length.name'), 'data-parsley-required-message' => $validationMessages['first_name.required'], 'data-parsley-pattern-message' => $validationMessages['first_name.regex']]) !!}
                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">Last Name*</label>
                            <div class="col-md-6">
                                {!! Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control', 'required', 'pattern' => config('app.validation_patterns.name'), 'maxlength' => config('app.length.name'), 'data-parsley-required-message' => $validationMessages['last_name.required'], 'data-parsley-pattern-message' => $validationMessages['last_name.regex']]) !!}
                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-md-4 control-label">Gender*</label>
                            <div class="col-md-12">
                                Male {!! Form::radio('gender', 'Male', (isset($customer->gender) && count($customer->gender) > 0) ? $customer->gender : true ) !!}
                                Female {!! Form::radio('gender', 'Female', (isset($customer->gender) && count($customer->gender) > 0) ? $customer->gender : false ) !!}
                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email*</label>
                            <div class="col-md-6">
                                @php
                                   $id = 0;
                                    if(isset($customer) && count($customer) > 0) {
                                      $id = $customer->id;
                                    }
                                @endphp
                                {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'required', 'data-parsley-type' => "email", 'data-parsley-type-message' => $validationMessages['email.email'], 'data-parsley-required-message' => $validationMessages['email.required'], "data-parsley-trigger" => "change", "data-parsley-remote" =>  url('check-email', $id), "data-parsley-remote-message" => $validationMessages['email.unique']]) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                            <label for="country_id" class="col-md-4 control-label">Country*</label>
                            <div class="col-md-6">
                                {!! Form::select('country_id', $countries, null, ['id' => 'country_id', 'required', 'data-parsley-required-message' => $validationMessages['country_id.required']]) !!}
                                @if ($errors->has('country_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}">
                            <label for="state_id" class="col-md-4 control-label">State*</label>
                            <div class="col-md-6">
                                {!! Form::select('state_id', $states, null, ['id' => 'state_id', 'required', 'data-parsley-required-message' => $validationMessages['state_id.required']]) !!}
                                @if ($errors->has('state_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <label for="mobile" class="col-md-4 control-label">Mobile</label>
                            <div class="col-md-6">
                                {!! Form::text('mobile', null, ['id' => 'mobile', 'class' => 'form-control only-integer', 'data-parsley-minlength' => config('app.length.mobile_min'), 'data-parsley-maxlength' => config('app.length.mobile_max'), 'maxlength' => config('app.length.mobile_max'), 'data-parsley-minlength-message' => $validationMessages['mobile.min'], 'data-parsley-maxlength-message' => $validationMessages['mobile.max'], 'pattern'=> config('app.validation_patterns.mobile'), 'placeholder' => "xxxxxxxxxxx", 'data-parsley-pattern-message' => $validationMessages['mobile.regex']]) !!}
                                @if ($errors->has('mobile'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a class="btn btn-primary" href="{{ url('customer') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
