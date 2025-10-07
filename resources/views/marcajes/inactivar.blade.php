{!! Form::open(['route' => 'ChangeStatusEmpleado', 'method' => 'Post', 'data-validate','enctype' => 'multipart/form-data']) !!}


<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>


<div class="modal-body">


<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::hidden('empleado_id', $empleado->id, ['class' => 'form-control']) !!}

            {{ Form::label('estadoinhabilitado_id', __('Motivo de Inactivación'), ['class' => 'col-form-label']) }}
            {!! Form::select('estadoinhabilitado_id', $estados, null, ['required' => 'required', 'class' => 'form-select', 'id' => 'estadoinhabilitado_id']) !!}
        </div>
    </div>

</div>

    <div class="row">

    	<div class="col-md-12">
            <div class="form-group">
                {{ Form::label('observaciones', __('Observaciones'), ['class' => 'form-label']) }} *
                {!! Form::textarea('observaciones', null, [
                    'class' => 'form-control ',
                    'placeholder' => __('Ingrese el motivo de Inactivación'),
                    'rows' => '4',
                    'required' => 'required',
                ]) !!}
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
