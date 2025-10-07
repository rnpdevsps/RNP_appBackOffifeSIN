{!! Form::open([
    'route' => 'empleados.store',
    'method' => 'Post',
    'data-validate',
]) !!}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
    var mainurl = "{{ url('/') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // AJAX para cargar Municipios x Deptos
    $('#idDeptoModal').change(function() {
        var id = $(this).val();

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
                }
            });
        } else {
            $('#idMunicipioModal').empty();
            $('#idMunicipioModal').append('<option value="">Seleccione un Municipio</option>');
        }
    });

    $('#idMunicipioModal').change(function() {
        var id = $(this).val();
        var idDepto = $('#idDeptoModal').val()


        if(id) {
            $.ajax({
                url: mainurl+'/obtenerRCMS/' +idDepto+"/"+ id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#rcm_idModal').empty();
                    $('#ircm_idModal').append('<option value="">Seleccione un RCM</option>');
                    $.each(data, function(index, opcion) {
                        $('#rcm_idModal').append('<option value="'+ opcion.id +'">'+ opcion.codigo +'</option>');
                    });
                }
            });
        } else {
            $('#rcm_idModal').empty();
            $('#rcm_idModal').append('<option value="">Seleccione un RCM</option>');
        }
    });

});
</script>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('idDepto', __('Departamento'), ['class' => 'col-form-label']) }}
                {!! Form::select('idDepto', $deptos, null,['id' => 'idDeptoModal', 'class' => 'form-control wizard-required'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}
                <div class="wizard-form-error"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('idMunicipio', __('Municipio'), ['class' => 'col-form-label']) }}
                {!! Form::select('idMunicipio', $municipios, null,['id' => 'idMunicipioModal', 'class' => 'form-control wizard-required'. ($errors->has('idMunicipio') ? ' is-invalid' : null) ]) !!}
                <div class="wizard-form-error"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('rcm_id', __('RCM'), ['class' => 'col-form-label']) }}
                {!! Form::select('rcm_id', $rcms, null,['id' => 'rcm_idModal', 'class' => 'form-control wizard-required'. ($errors->has('rcm_id') ? ' is-invalid' : null) ]) !!}
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
            <div class="form-group  ">
                {{ Form::label('name', __('Nombre'), ['class' => 'col-form-label']) }}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Nombre')]) !!}
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
            <div class="form-group  ">
                {{ Form::label('cargo', __('Cargo'), ['class' => 'col-form-label']) }}
                {!! Form::text('cargo', null, ['class' => 'form-control', 'placeholder' => __('Aseadora (0501)')]) !!}
                <p id="errdni" class="mb-0 text-danger em"></p>
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
