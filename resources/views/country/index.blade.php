@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="{{ URL::to('/') }}">Home</a>
                        <table border="0" width="100%" id="country_table" class="table">
                            <thead>
                            <tr>
                                <th>Country Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @if(count($countries) > 0)
                                @foreach($countries as $country)
                                    <tr>
                                        <td>{{ $country->country_name ? $country->country_name : 'N/A' }}</td>
                                        <td>
                                            <?php $frmId = 'country_delete_frm_'.$country->id; ?>
                                            {!! Form::open(['url' => route('country.destroy', $country->id), 'method' => 'DELETE', 'id' => $frmId]) !!}
                                                <button class="btn btn-primary delete-record" data-msg="If you delete this country, all the associated states will also be deleted." type="button">Delete</button>
                                            {!! Form::close() !!}
                                            <a class="btn btn-primary" href="{{ route('country.edit', $country->id) }}">edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#country_table').DataTable({
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [1] }
                ]
            });
        });
    </script>
@endsection