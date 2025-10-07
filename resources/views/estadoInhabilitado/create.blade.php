{!! Form::open([
    'route' => 'estadoinhabilitado.store',
    'method' => 'Post',
    'data-validate',
]) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('descripcion', __('Descripcion'), ['class' => 'col-form-label']) }}
            {!! Form::text('descripcion', null, ['placeholder' => __('Nombre del estado'), 'required', 'class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}
