@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if(Entrust::hasRole([config('app.roles.super_admin.name'), config('app.roles.admin.name')]))
                            <a class="btn btn-primary" href="{{ route('user.create') }}">Add user</a>
                        @endif
                        <a href="{{ URL::to('/') }}">Home</a>
                        <table border="0" width="100%" id="user_table" class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Is verified</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        /*$(document).ready(function(){
            $('#user_table').DataTable({
                "order": [[ 3, "desc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [4] }
                ]
            });
        });*/

        var table;
        $(document).ready(function(){
            if( $('#user_table').length > 0){
                table  =  $('#user_table').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [[ 3, "desc" ]],
                    ajax: '{{ url("user") }}',
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'is_verify', name: 'is_verify' },
                        { data: 'created_date', name: 'created_date' },
                        { data: 'action', name: 'action', 'orderable': false},
                    ]
                });
            }
        });
    </script>
@endsection