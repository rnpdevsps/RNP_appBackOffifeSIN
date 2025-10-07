{!! Form::model($empleado, [
    'route' => ['empleados.update', $empleado->id],
    'method' => 'Put',
    'data-validate',
]) !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
    var mainurl = "{{ url('/') }}";
    MunicipiosxDeptos();
    RCMxMunicipios();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function MunicipiosxDeptos() {
        //var id = $(this).val();
        var id = $('#idDeptoModal').val();

        if(id) {
            $.ajax({
                url: mainurl+'/obtenerMunicipiosPorDeptos/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#idMunicipioModal').empty();
                    $('#idMunicipioModal').append('<option value="">Seleccione un Municipio</option>');
                    $.each(data, function(index, opcion) {
                        $('#idMunicipioModal').append('<option value="'+ opcion.id +'">'+ opcion.nombremunicipio +'</option>');
                    });
                    let idMunicipio = "{{ $empleado->idmunicipio }}";
                    $('#idMunicipioModal').val(idMunicipio);
                }
            });
        } else {
            $('#idMunicipioModal').empty();
            $('#idMunicipioModal').append('<option value="">Seleccione un Municipio</option>');
        }
    }

    // AJAX para cargar Municipios x Deptos
    $('#idDeptoModal').change(function() {
        MunicipiosxDeptos();
    });

    // AJAX para cargar RCM x Municipios
    function RCMxMunicipios() {
        //var id = $(this).val();
        var idDepto = $('#idDeptoModal').val()
        var id = $('#idMunicipioModal').val();


        if(id) {
            $.ajax({
                url: mainurl+'/obtenerRCMS/' +idDepto+"/"+ id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#rcm_idModal').empty();
                    $('#rcm_idModal').append('<option value="">Seleccione un RCM</option>');
                    $.each(data, function(index, opcion) {
                        $('#rcm_idModal').append('<option value="'+ opcion.id +'">'+ opcion.codigo + (opcion.name ? " - " + opcion.name : "") +'</option>');
                    });
                    let idRCM = "{{ $empleado->rcm_id }}";
                    $('#rcm_idModal').val(idRCM);
                }
            });
        } else {
            $('#rcm_idModal').empty();
            $('#rcm_idModal').append('<option value="">Seleccione un RCM</option>');
        }
    }

    $('#idMunicipioModal').change(function() {
        RCMxMunicipios();
    });

});
</script>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('idDepto', __('Departamento'), ['class' => 'col-form-label']) }}
                {!! Form::select('idDepto', $deptos, $empleado->iddepto,['id' => 'idDeptoModal', 'class' => 'form-control wizard-required'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}
                <div class="wizard-form-error"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('idMunicipio', __('Municipio'), ['class' => 'col-form-label']) }}
                {!! Form::select('idMunicipio', $municipios, $empleado->idmunicipio,['id' => 'idMunicipioModal', 'class' => 'form-control wizard-required'. ($errors->has('idMunicipio') ? ' is-invalid' : null) ]) !!}
                <div class="wizard-form-error"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('rcm_id', __('RCM'), ['class' => 'col-form-label']) }}
                {!! Form::select('rcm_id', $rcms, $empleado->rcm_id,['id' => 'rcm_idModal', 'class' => 'form-control wizard-required'. ($errors->has('rcm_id') ? ' is-invalid' : null) ]) !!}
                <div class="wizard-form-error"></div>
            </div>
        </div>
    </div>
    
    <div class="row">
    	<div class="col-lg-4">
            <div class="form-group  ">
                {{ Form::label('dni', __('DNI (Max. 13 caracteres)'), ['class' => 'col-form-label']) }}
                {!! Form::text('dni', null, ['class' => 'form-control', 'maxlength' => '13',  'placeholder' => __('050119953745')]) !!}
                <p id="errdni" class="mb-0 text-danger em"></p>
            </div>
    	</div>

        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('name', __('Nombre'), ['class' => 'col-form-label']) }}
                {!! Form::text('name', null, [
                    'class' => 'form-control',
                    'placeholder' => __('Nombre'),
                    'style' => 'text-transform: uppercase;',
                    'oninput' => 'this.value = this.value.toUpperCase()'
                ]) !!}
                <p id="errdni" class="mb-0 text-danger em"></p>
            </div>
        </div>


        <div class="col-lg-2">
            <div class="form-group  ">
                {{ Form::label('codigo', __('Codigo'), ['class' => 'col-form-label']) }}
                {!! Form::text('codigo', null, ['class' => 'form-control', 'placeholder' => __('1234')]) !!}
                <p id="errdni" class="mb-0 text-danger em"></p>
            </div>
    	</div>

        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('cargo', __('Cargo'), ['class' => 'col-form-label']) }}
                {!! Form::text('cargo', null, [
                    'class' => 'form-control',
                    'placeholder' => __('Aseadora (0501)'),
                    'style' => 'text-transform: uppercase;',
                    'oninput' => 'this.value = this.value.toUpperCase()'
                ]) !!}
                <p id="errcargo" class="mb-0 text-danger em"></p>
            </div>
        </div>

        @canany(['manage-marcarsinhuella'])
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::label('marcajeh', __('Marcar con Huella'), ['class' => 'col-form-label']) }}
                    <div class="col-sm-4 form-check form-switch custom-switch-v1">
                        <label class="mt-2 custom-switch float-end">
                            {!! Form::checkbox(
                                'marcajeh',
                                null, $empleado->marcajeh == 1 ? true : false,
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
        @endcanany


    </div>

</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}
