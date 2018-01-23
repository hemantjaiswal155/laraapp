@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}" id="frm_user_login" data-parsley-validate=true>
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus data-parsley-type="email" data-parsley-type-message="{{ 'Email id is invalid' }}" data-parsley-required-message="{{ 'Please enter email id' }}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required data-parsley-required-message="{{ 'Please enter password' }}">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <a class="btn btn-primary" href="{{ url('auth/facebook') }}" id="btn-fblogin">Login with Facebook</a>
                                <a class="btn btn-primary" href="{{ url('auth/linkedin') }}" id="btn-fblogin">Login with Linkedin</a>
                                <a class="btn btn-primary" href="{{ url('auth/twitter') }}" id="btn-fblogin">Login with Twitter</a>
                                <a class="btn btn-primary" href="{{ url('auth/google') }}" id="btn-fblogin">Login with Google</a>

                                <div class="register-now-link">New user ? <a href="{{ route('register') }}" >Register now</a></div>
                                <a class="btn-link" href="{{ route('password.request') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

