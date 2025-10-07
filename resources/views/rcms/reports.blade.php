{!! Form::open([
    'route' => 'report',
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
});
</script>

<div class="modal-body">
    <div class="row">

        <div class="col-lg-4">
            <div class="form-group">
                {{ Form::label('from_date', __('Fecha Inicio *'), ['class' => 'form-label']) }}
                {!! Form::date('from_date', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                {{ Form::label('to_date', __('Fecha Final *'), ['class' => 'form-label']) }}
                {!! Form::date('to_date', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group  ">
                {{ Form::label('clasificacion', __('Clasificación '), ['class' => 'form-label']) }}
                <div class="input-group">
                    <select name="clasificacion" class="form-select">
                        <option value=" ">Todo</option>
                        <option value="1">RCM</option>
                        <option value="0">Ventanilla</option>
                        <option value="2">Kioscos</option>
                        <option value="3">Bodega</option>

                    </select>
                </div>
            </div>
    	</div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group ">
                {{ Form::label('idDepto', __('Departamento'), ['class' => 'col-form-label']) }}
                {!! Form::select('idDepto', $deptos, null,['id' => 'idDeptoModal', 'class' => 'form-control'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group ">
                {{ Form::label('idMunicipio', __('Municipio'), ['class' => 'col-form-label']) }}
                {!! Form::select('idMunicipio', $municipios, null,['id' => 'idMunicipioModal', 'class' => 'form-control'. ($errors->has('idMunicipio') ? ' is-invalid' : null) ]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">

            <div class="form-group">
                {{ Form::label('fileType', __('Formato a Exportar:'), ['class' => 'form-label']) }}
                <br>

                {!! Form::radio('fileType', 'csv', false, [
                    'class' => 'btn-check',
                    'id' => 'csv',
                    'onclick' => "mostrarPDF(0);",
                ]) !!}
                {{ Form::label('csv', __('CSV'), ['class' => 'btn btn-outline-primary']) }}


                {!! Form::radio('fileType', 'excel', true, [
                    'class' => 'btn-check',
                    'id' => 'excel',
                    'onclick' => "mostrarPDF(0);",
                ]) !!}
                {{ Form::label('excel', __('EXCEL'), ['class' => 'btn btn-outline-primary']) }}


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
