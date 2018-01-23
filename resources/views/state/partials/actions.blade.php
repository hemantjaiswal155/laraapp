<?php $frmId = 'state_delete_frm_'.$state->id; ?>
{!! Form::open(['url' => route('state.destroy', $state->id), 'method' => 'DELETE', 'id' => $frmId]) !!}
<button class="btn btn-primary delete-record" data-msg="Are you sure, you want to delete this state?" type="button">Delete</button>
{!! Form::close() !!}
<a class="btn btn-primary" href="{{ route('state.edit', $state->id) }}">edit</a>
