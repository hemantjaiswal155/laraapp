@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Country {{ isset($country) && count($country) > 0 ? 'Edit' : '' }}</div>
                    <div class="panel-body">
                        @if(isset($country) && count($country) > 0)
                            {!! Form::model($country, ['url' => URL::route('country.update', $country->id), 'method' => 'PUT', 'id' => 'country_form', 'data-parsley-validate' => true]) !!}
                        @endif

                        <div class="form-group{{ $errors->has('country_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Country Name*</label>
                            <div class="col-md-6">
                                {!! Form::text('country_name', null, ['id' => 'country_name', 'class' => 'form-control', 'required', 'data-parsley-required-message' => $validationMessages['country_name.required'], 'pattern'=> config('app.validation_patterns.other_name'), 'data-parsley-pattern-message' => $validationMessages['country_name.regex'], "data-parsley-trigger" => "change", "data-parsley-remote" =>  url('check-duplicate-name', $country->id), "data-parsley-remote-message" => $validationMessages['country_name.unique']]) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('country_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a class="btn btn-primary" href="{{ url('country') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
