<a class="btn btn-primary" href="{{ route('user.show', $user->id) }}">view</a>

@if(Entrust::hasRole([config('app.roles.super_admin.name'), config('app.roles.admin.name')]))
    <a class="btn btn-success" href="{{ route('user.edit', $user->id) }}">edit</a>
@endif

@if(Entrust::hasRole(config('app.roles.super_admin.name')))
    @if(Auth::user()->id != $user->id)
        <?php $frmId = 'user_delete_frm_'.$user->id; ?>
        {!! Form::open(['url' => route('user.destroy', $user->id), 'method' => 'DELETE', 'id' => $frmId]) !!}
        <button class="btn btn-danger delete-record" data-msg="Are you sure, you want to delete this user?" type="button">Delete</button>
        {!! Form::close() !!}
    @endif
@endif