@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">State {{ isset($state) && count($state) > 0 ? 'Edit' : '' }}</div>
                    <div class="panel-body">
                        @if(isset($state) && count($state) > 0)
                            {!! Form::model($state, ['url' => URL::route('state.update', $state->id), 'method' => 'PUT', 'id' => 'state_form', 'data-parsley-validate' => true]) !!}
                        @endif

                        <div class="form-group{{ $errors->has('state_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">State Name*</label>
                            <div class="col-md-6">
                                {!! Form::text('state_name', null, ['id' => 'state_name', 'class' => 'form-control', 'required', 'data-parsley-required-message' => $validationMessages['state_name.required'], 'pattern'=> config('app.validation_patterns.other_name'), 'data-parsley-pattern-message' => $validationMessages['state_name.regex'], "data-parsley-trigger" => "change", "data-parsley-remote" =>  url('check-duplicate-name', $state->id), "data-parsley-remote-message" => $validationMessages['state_name.unique']]) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a class="btn btn-primary" href="{{ url('state') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
