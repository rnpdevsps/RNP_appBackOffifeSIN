{!! Form::model($estado, [
    'route' => ['tipoinmuebles.update', $estado->id],
    'method' => 'Put',
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
                    <option value="0" @if ($estado->local_propio == 0) selected @endif>NO</option>
                    <option value="1" @if ($estado->local_propio == 1) selected @endif>SI</option>
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
