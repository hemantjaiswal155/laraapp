
    <a class="btn btn-primary" href="{{ route('customer.show', $user->id) }}">view</a>
    @if(Entrust::hasRole([config('app.roles.super_admin.name'), config('app.roles.admin.name')]))
        <a class="btn btn-success" href="{{ route('customer.edit', $user->id) }}">edit</a>
    @endif
    @if(Entrust::hasRole(config('app.roles.super_admin.name')))
        <?php $frmId = 'customer_delete_frm_'.$user->id; ?>
        {!! Form::open(['url' => route('customer.destroy', $user->id), 'method' => 'DELETE', 'id' => $frmId]) !!}
        <button class="btn btn-danger delete-record" data-msg="Are you sure, you want to delete this customer?" type="button" id="delete-customer">Delete</button>
        {!! Form::close() !!}
    @endif
