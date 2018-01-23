@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if(Entrust::hasRole([config('app.roles.super_admin.name'), config('app.roles.admin.name')]))
                            <a class="btn btn-primary" href="{{ route('customer.create') }}">Add customer</a>
                        @endif
                        <a id="test" href="javascript:void(0)">Home</a>
                        <table border="0" width="100%" id="customer_table" class="table">
                            <thead>
                                <tr>
                                    
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                   
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

<script>
    var table;
    $(document).ready(function(){
        if( $('#customer_table').length > 0){
            table  =  $('#customer_table').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: '{{ url("customer") }}',
                columns: [
                   
                    { data: 'gender', name: 'gender' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile', name: 'mobile' },
               
                    { data: 'action', name: 'action' },
                ]
            });
        }
    });

    $("body").on('click', '#test', function(){
        table.ajax.reload();
    })
</script>

@endsection
