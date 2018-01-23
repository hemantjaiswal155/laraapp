@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}" id="frm_user_registration" data-parsley-validate=true>
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name*</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus pattern="{{ config('app.validation_patterns.name') }}" maxlength="{{ config('app.length.name') }}" data-parsley-required-message="{{ $validationMessages['name.required'] }}" data-parsley-pattern-message="{{ $validationMessages['name.regex'] }}">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address*</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required data-parsley-type="email" data-parsley-type-message="{{ $validationMessages['email.email'] }}" data-parsley-required-message="{{ $validationMessages['email.required'] }}"  data-parsley-remote-message = "{{ $validationMessages['email.unique'] }}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password*</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required  data-parsley-minlength="{{ config('app.length.password_min') }}" data-parsley-maxlength="{{ config('app.length.password_max') }}" maxlength="{{ config('app.length.password_max') }}" data-parsley-required-message="{{ $validationMessages['password.required'] }}" data-parsley-minlength-message="{{ $validationMessages['password.min'] }}" data-parsley-maxlength-message="{{ $validationMessages['password.max'] }}">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password*</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required data-parsley-equalto="#password" data-parsley-equalto-message="{{ $validationMessages['password.confirmed'] }}" data-parsley-required-message="{{ 'Please enter confirm password' }}">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                            <label for="country_id" class="col-md-4 control-label">Country*</label>
                            <div class="col-md-6">
                                <select name="country_id" id="country_id" required data-parsley-required-message="{{ $validationMessages['country_id.required'] }}">
                                    @if(count($countries)>0)
                                        @foreach($countries as $country_id => $country_name)
                                            <option value="{{ $country_id }}">{{ $country_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                                <select name="state_id" id="state_id" required data-parsley-required-message="{{ $validationMessages['state_id.required'] }}">
                                    @if(count($states) > 0)
                                        @foreach($states as $state_id => $state_name)
                                            <option value="{{ $state_id }}">{{ $state_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('state_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </div>
                        <div class="register-now-link m-t30">Existing user? <a href="{{ route('login') }}" >Login here</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
