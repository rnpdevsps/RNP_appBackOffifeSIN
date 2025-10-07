{!! Form::open([
    'route' => 'tipoinmuebles.store',
    'method' => 'Post',
    'data-validate',
]) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('descri', __('Nombre'), ['class' => 'col-form-label']) }}
            {!! Form::text('descri', null, ['placeholder' => __('Nombre'), 'required', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group  ">
            {{ Form::label('local_propio', __('Local Propio '), ['class' => 'col-form-label']) }}
            <div class="input-group">
                <select name="local_propio" class="form-select">
                    <option value="0">NO</option>
                    <option value="1">SI</option>
                </select>
            </div>
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
