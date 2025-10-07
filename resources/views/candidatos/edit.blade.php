{!! Form::model($candidato, [
    'route' => ['candidatos.update', $candidato->id],
    'method' => 'Put',
    'data-validate',
    'enctype' => 'multipart/form-data',
]) !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

<script>
$(document).ready(function () {
    var mainurl = "{{ url('/') }}";

    // Inicializar Choices una vez
    var choicesInstance = new Choices('#personal_id', {
        removeItemButton: true,
        shouldSort: false
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#idPeriodo').change(function () {
        var id = $(this).val();

        if (id) {
            $.ajax({
                url: mainurl + '/obtenerListaElectoral/' + id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Limpiar opciones actuales
                    choicesInstance.clearStore();
                    choicesInstance.clearChoices();

                    // Agregar opción predeterminada
                    choicesInstance.setChoices(
                        [{ value: '', label: 'Seleccione un Personal', selected: true }],
                        'value',
                        'label',
                        false
                    );

                    // Agregar nuevas opciones
                    var opciones = data.map(function (opcion) {
                        return {
                            value: opcion.id,
                            label: opcion.nombre
                        };
                    });

                    choicesInstance.setChoices(opciones, 'value', 'label', false);
                }
            });
        } else {
            choicesInstance.clearStore();
            choicesInstance.clearChoices();
            choicesInstance.setChoices([{ value: '', label: 'Seleccione un Personal', selected: true }], 'value', 'label', false);
        }
    });
});

</script>

<div class="modal-body">
    <div class="row">
    	<div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('periodo', __('Periodo'), ['class' => 'col-form-label']) }}
                {!! Form::select('periodo', array_combine(range(2025, 2035), range(2025, 2035)), null, [
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => 'Seleccione un periodo',
                    'id' => 'idPeriodo',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row align-items-center">
                <div class="col-md-8">
                    {{ Form::label('foto', __('Foto'), ['class' => 'col-form-label']) }} *
                    {!! Form::file('foto', ['class' => 'form-control']) !!}
                    <small class="form-text text-muted">
                        {{ __('Extensión: .jpeg,.jpg,.png (Max Size: 2 MB)') }}
                    </small>
                </div>
            
                @if (isset($candidato->foto))
                    <div class="col-md-4">
                        <img src="{{ Storage::url($candidato->foto) }}" width="60" height="60" class="img-fluid">
                    </div>
                @else
                    <div class="col-md-4">
                        <img src="{{ Storage::url('candidatos/sinfoto.png') }}" width="60" height="60" class="img-fluid">
                    </div>
                @endif
            </div>
        </div>

    </div>
    
    <div class="row">
    	<div class="col-lg-12">
            <div class="form-group">
            {{ Form::label('personal_id', __('Personal'), ['class' => 'col-form-label']) }}
            {!! Form::select('personal_id', $PersonalVotaciones, $candidato->personal_id, [
                'class' => 'form-select',
                'required',
                'id' => 'personal_id',
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
