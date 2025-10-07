{!! Form::model($PersonalVotaciones, [
    'route' => ['listadoelectoral.update', $PersonalVotaciones->id],
    'method' => 'Put',
    'data-validate',
    'enctype' => 'multipart/form-data',
]) !!}

<script>
$(document).ready(function(){
    //////////////////////////////////////////////
    $('#dni').on('input', function() {
        var numeroDNI = $(this).val();
        let query = numeroDNI.replace(/-/g, '');

        if (query.length > 12) {
            $.ajax({
                url: "{{ route('buscarDNIApi') }}",
                method: 'GET',
                data: {
                    query: query
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var Nombres = data.Qry_InscripcionNacimientoResult.Nombres;
                    var PrimerApellido = data.Qry_InscripcionNacimientoResult.PrimerApellido;
                    var SegundoApellido = data.Qry_InscripcionNacimientoResult.SegundoApellido;

                    var Resultado = response[0];

                    if (Resultado) {
                        $('#nombre').val(Nombres+" "+PrimerApellido+" "+SegundoApellido);
                        $('#puesto').focus();

                    } else {
                        $('#nombre').val("");
                    }
                }
            });
        }
    });

    $('#dni').on('input', function() {
        var value = $(this).val();
        value = value.replace(/\D/g, ''); // Elimina cualquier carácter que no sea un dígito
        if (value.length > 0) {
            var formattedValue = '';
            // Formatea los primeros cuatro dígitos
            formattedValue += value.substr(0, 4);
            if (value.length > 4) {
                formattedValue += '-';
                // Formatea los siguientes cuatro dígitos
                formattedValue += value.substr(4, 4);
                if (value.length > 8) {
                    formattedValue += '-';
                    // Formatea los últimos cinco dígitos
                    formattedValue += value.substr(8, 5);
                }
            }
            $(this).val(formattedValue);
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
                    'placeholder' => 'Seleccione un periodo'
                ]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group  ">
                {{ Form::label('dni', __('DNI'), ['class' => 'col-form-label']) }}
                {!! Form::text('dni', null, ['id' => 'dni', 'class' => 'form-control', 'required', 'placeholder' => __('DNI'), 'id' => 'dni']) !!}
            </div>
    	</div>

        <div class="col-lg-6">
            <div class="form-group  ">
                {{ Form::label('nombre', __('Nombre'), ['class' => 'col-form-label']) }}
                {!! Form::text('nombre', null, ['style' => 'text-transform: uppercase;',
                    'oninput' => 'this.value = this.value.toUpperCase()', 'id' => 'nombre', 'class' => 'form-control', 'required', 'placeholder' => __('Nombre')]) !!}
            </div>
    	</div>

        <div class="col-lg-6">
            <div class="form-group  ">
                {{ Form::label('puesto', __('Puesto'), ['class' => 'col-form-label']) }}
                {!! Form::text('puesto', null, ['style' => 'text-transform: uppercase;',
                    'oninput' => 'this.value = this.value.toUpperCase()', 'id' => 'puesto', 'class' => 'form-control', 'required', 'placeholder' => __('Puesto')]) !!}
            </div>
    	</div>

        <div class="col-lg-6">
            <div class="form-group  ">
                {{ Form::label('ubicacion', __('Ubicacion'), ['class' => 'col-form-label']) }}
                {!! Form::text('ubicacion', null, ['style' => 'text-transform: uppercase;',
                    'oninput' => 'this.value = this.value.toUpperCase()', 'class' => 'form-control', 'required', 'placeholder' => __('Ubicacion')]) !!}
            </div>
    	</div>

        <div class="col-lg-6">
            <div class="form-group  ">
                {{ Form::label('municipio', __('Municipio'), ['class' => 'col-form-label']) }}
                {!! Form::text('municipio', null, ['style' => 'text-transform: uppercase;',
                    'oninput' => 'this.value = this.value.toUpperCase()', 'class' => 'form-control', 'required', 'placeholder' => __('Municipio')]) !!}
            </div>
    	</div>
    	<div class="col-lg-9"></div>
    	<div class="col-lg-3">
                <div class="form-group d-flex align-items-center">
                    {{-- Label --}}
                    {{ Form::label('flag', __('Flag'), ['class' => 'col-form-label me-3 mb-0']) }}
                
                    {{-- Switch --}}
                    <div class="form-check form-switch custom-switch-v1">
                        <label class="custom-switch">
                            {!! Form::checkbox(
                                'flag',
                                null,
                                $PersonalVotaciones->flag == 1 ? true : false,
                                [
                                    'data-onstyle' => 'primary',
                                    'class' => 'custom-control custom-switch form-check-input input-primary',
                                    'data-toggle' => 'switchbutton',
                                ],
                            ) !!}
                        </label>
                    </div>
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
