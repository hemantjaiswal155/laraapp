@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">User {{ isset($user) && count($user) > 0 ? 'Edit' : 'Add' }}</div>
                    <div class="panel-body">
                        @if(isset($user) && count($user) > 0)
                            {!! Form::model($user, ['url' => URL::route('user.update', $user->id), 'method' => 'PUT', 'id' => 'user_form', 'data-parsley-validate' => true]) !!}
                        @else
                            {!! Form::open(['url' => URL::route('user.store'), 'method' => 'POST', 'id' => 'user_form', 'data-parsley-validate' => true]) !!}
                        @endif

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name*</label>
                            <div class="col-md-6">
                                {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'required', 'pattern' => config('app.validation_patterns.name'), 'maxlength' => config('app.length.name'), 'data-parsley-required-message' => $validationMessages['name.required'], 'data-parsley-pattern-message' => $validationMessages['name.regex']]) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email*</label>
                            <div class="col-md-6">
                                @php
                                    $id = 0;
                                     if(isset($user) && count($user) > 0) {
                                       $id = $user->id;
                                     }
                                @endphp
                                @if(count($user)>0)
                                    <input type="text" value="{{$user->email}}" disabled>
                                @else
                                    {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'required', 'data-parsley-type' => "email", 'data-parsley-type-message' => $validationMessages['email.email'], 'data-parsley-required-message' => $validationMessages['email.required'], "data-parsley-trigger" => "change", "data-parsley-remote" =>  url('check-email', $id).'?user=yes', "data-parsley-remote-message" => $validationMessages['email.unique']]) !!}
                                @endif
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if(count($user)==0 || $user->id == Auth::user()->id)
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password{{ (count($user) == 0) ? '*' : '' }}</label>
                                <div class="col-md-6">
                                    @if(count($user) == 0)
                                        {!! Form::password('password', ['id' => 'password', 'required', 'data-parsley-minlength' => config('app.length.password_min'), 'data-parsley-maxlength' => config( config('app.length.password_max') ), 'maxlength' => config('app.length.password_max'),'data-parsley-required-message'=>$validationMessages['password.required'],'data-parsley-minlength-message'=>$validationMessages['password.min'],'data-parsley-maxlength-message'=>$validationMessages['password.max']]) !!}
                                    @else
                                        {!! Form::password('password', ['id' => 'password', 'minlength'=>config('app.length.password_min'), 'data-parsley-maxlength' => config( config('app.length.password_max') ), 'maxlength' => config('app.length.password_max'), 'data-parsley-minlength-message'=>$validationMessages['password.min'],'data-parsley-maxlength-message'=>$validationMessages['password.max']]) !!}
                                    @endif

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password{{ (count($user) == 0) ? '*' : '' }}</label>
                                <div class="col-md-6">
                                    @if(count($user) == 0)
                                        {!! Form::password('password_confirmation', ['id' => 'confirm_password', 'required', 'data-parsley-equalto' => '#password',  'maxlength' => config('app.length.password_max'),'data-parsley-equalto-message'=>$validationMessages['password.confirmed'],'data-parsley-required-message'=>'Please enter confirm password']) !!}
                                    @else
                                        {!! Form::password('password_confirmation', ['id' => 'confirm_password', 'data-parsley-equalto' => '#password',  'maxlength' => config('app.length.password_max'),'data-parsley-equalto-message'=>$validationMessages['password.confirmed'],'data-parsley-required-message'=>'Please enter confirm password']) !!}
                                    @endif
                                </div>
                            </div>
                        @endif

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

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a class="btn btn-primary" href="{{ url('user') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
