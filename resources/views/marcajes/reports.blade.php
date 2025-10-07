{!! Form::open([
    'route' => 'reportMarcajes',
    'method' => 'Post',
    'data-validate',
    'target' => '_blank'
]) !!}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

<script>
    $(document).ready(function(){

        var multipleCancelButton3 = new Choices('#empleado_idModal', { removeItemButton: true,searchResultLimit: 5 });

        //var multipleCancelButton1 = new Choices('#empleado_idModal', { removeItemButton: true,searchResultLimit: 15 });

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
                        $('#rcm_idModal').empty();
                        $('#rcm_idModal').append('<option value="">Seleccione un RCM</option>');
                        $('#empleado_idModal').empty();
                        $('#empleado_idModal').append('<option value="">Seleccione un Empleado</option>');

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
                        $('#rcm_idModal').append('<option value="">Seleccione un RCM</option>');
                        $('#empleado_idModal').empty();
                        $('#empleado_idModal').append('<option value="">Seleccione un Empleado</option>');
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

        $('#rcm_idModal').change(function() {
            var rcm_id = $(this).val();
            var idDepto = $('#idDeptoModal').val()
            var idMunicipio = $('#idMunicipioModal').val()

            if(rcm_id) {
                $.ajax({
                    url: mainurl+'/obtenerEmpleados/' +idDepto+"/"+ idMunicipio+"/"+ rcm_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#empleado_idModal').empty();
                        $('#empleado_idModal').append('<option value="">Seleccione un Empleado</option>');
                        $.each(data, function(index, opcion) {
                            $('#empleado_idModal').append('<option value="'+ opcion.id +'">'+ opcion.name +'</option>');
                        });
                        //var multipleCancelButton1 = new Choices('#empleado_idModal', { removeItemButton: true,searchResultLimit: 15 });

                    }
                });
            } else {
                $('#empleado_idModal').empty();
                $('#empleado_idModal').append('<option value="">Seleccione un Empleado</option>');
            }
        });

    });
</script>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('from_date', __('Fecha Inicio *'), ['class' => 'form-label']) }}
                {!! Form::date('from_date', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {{ Form::label('to_date', __('Fecha Final *'), ['class' => 'form-label']) }}
                {!! Form::date('to_date', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('idDepto', __('Departamento'), ['class' => 'col-form-label']) }}
                {!! Form::select('idDepto', $deptos, null,['id' => 'idDeptoModal', 'class' => 'form-control'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group ">
                {{ Form::label('idMunicipio', __('Municipio'), ['class' => 'col-form-label']) }}
                {!! Form::select('idMunicipio', $municipios, null,['id' => 'idMunicipioModal', 'class' => 'form-control'. ($errors->has('idMunicipio') ? ' is-invalid' : null) ]) !!}
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
        <div class="col-lg-5">
            <div class="form-group ">
                {{ Form::label('empleado_id', __('Empleado'), ['class' => 'col-form-label']) }}
                {!! Form::select('empleado_id', $empleados, null,['id' => 'empleado_idModal', 'class' => 'form-control wizard-required'. ($errors->has('empleado_id') ? ' is-invalid' : null) ]) !!}
                <div class="wizard-form-error"></div>
            </div>
        </div>
        
        <div class="col-lg-7">

            <div class="form-group">
                {{ Form::label('fileType', __('Formato a Exportar:'), ['class' => 'col-form-label']) }}
                <br>

                {!! Form::radio('fileType', 'csv', false, [
                    'class' => 'btn-check',
                    'id' => 'csv',
                    'onclick' => "mostrarPDF(0);",
                ]) !!}
                {{ Form::label('csv', __('CSV'), ['class' => 'btn btn-outline-primary d-none']) }}

                {!! Form::radio('fileType', 'excel', true, [
                    'class' => 'btn-check',
                    'id' => 'excel',
                    'onclick' => "mostrarPDF(0);",
                ]) !!}
                {{ Form::label('excel', __('EXCEL'), ['class' => 'btn btn-outline-primary']) }}

                {{-- NUEVO: EXCEL DETALLADO --}}
                {!! Form::radio('fileType', 'excel_detallado', false, [
                    'class' => 'btn-check',
                    'id' => 'excel_detallado',
                    'onclick' => "mostrarPDF(0);",
                ]) !!}
                {{ Form::label('excel_detallado', __('EXCEL DETALLADO'), ['class' => 'btn btn-outline-primary']) }}

                {!! Form::radio('fileType', 'pdf', false, [
                    'class' => 'btn-check',
                    'id' => 'pdf',
                    'onclick' => "mostrarPDF(1);",
                ]) !!}
                {{ Form::label('pdf', __('PDF'), ['class' => 'btn btn-outline-primary']) }}

            </div>
        </div>
        <div class="col-lg-5 d-none" id="archivoPDF">

            <div class="form-group">
                {{ Form::label('fileType', __('Visualizar PDF:'), ['class' => 'form-label']) }}
                <br>

                {!! Form::radio('visualizarPDF', 'si', false, [
                    'class' => 'btn-check',
                    'id' => 'si',
                ]) !!}
                {{ Form::label('si', __('SI'), ['class' => 'btn btn-outline-warning']) }}

                {!! Form::radio('visualizarPDF', 'no', true, [
                    'class' => 'btn-check',
                    'id' => 'no',
                ]) !!}
                {{ Form::label('no', __('NO'), ['class' => 'btn btn-outline-warning']) }}

            </div>
        </div>

    </div>


</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Generar Reporte'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div>
{!! Form::close() !!}

<script>
    // Function declaration
    function mostrarPDF(val) {
        if (val == 1) {
            $("#archivoPDF").removeClass('d-none');
        } else {
            $("#archivoPDF").addClass('d-none');
        }
    }
</script>
