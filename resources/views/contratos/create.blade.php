@extends('layouts.main')
@section('title', __('Nuevo Contrato'))
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('Nuevo Contrato') }}</h4>
                    </div> 
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
                        <li class="breadcrumb-item">{!! Html::link(route('rcms.index'), __('RCM'), []) !!}</li>
                        <li class="breadcrumb-item active">{{ __('Nuevo Contrato') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
<br>

<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('observaciones');
</script>

    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-12 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="fecha" class="form-label">Fecha: {{ $fecha }}</label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="usuario" class="form-label">Usuario: {{ $usuario}}</label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="usuario" class="form-label">
                                    @if ($rcm->clasificacion === 0)
                                        Ventanilla: {{ $rcm->codigo }}
                                    @endif
                                    @if ($rcm->clasificacion == 1)
                                        RCM: {{ $rcm->codigo }}
                                    @endif
                                    @if ($rcm->clasificacion == 2)
                                        Kioscos: {{ $rcm->codigo }}
                                    @endif
                                    @if ($rcm->clasificacion == 3)
                                        Bodega: {{ $rcm->codigo }}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="status_contrato" class="form-label">Estado del Contrato: {{ $status_contrato }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::open([
                    'route' => ['contratos.store'],
                    'method' => 'POST',
                    'data-validate',
                    'id' => 'form_dataT',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]) !!}

<script>
    // Function declaration
    function calcula_valores2(val) {
        let metros2 = parseFloat($('#metros2').val());
        if (isNaN(metros2)) {
            metros2 = 0;
        }

        if (val == 1) {
            let valorm = parseFloat($('#valor_mensual').val());
            if (isNaN(valorm)) {
                valorm = 0;
            }

            $('#valor_metros_detalle').val(valorm/metros2);

        } else {
            let valor_metros_detalle = parseFloat($('#valor_metros_detalle').val());
            if (isNaN(valor_metros_detalle)) {
                valor_metros_detalle = 0;
            }

            $('#valor_mensual').val(metros2*valor_metros_detalle);
        }

        let valor_mensual = parseFloat($('#valor_mensual').val());

        const fecha_inicio = document.getElementById("fecha_inicio").value;
        const fecha_final = document.getElementById("fecha_final").value;

        const fechaInicio = new Date(fecha_inicio);
        const fechaFinal = new Date(fecha_final);

        let yearDiff = fechaFinal.getFullYear() - fechaInicio.getFullYear();
        let monthDiff = fechaFinal.getMonth() - fechaInicio.getMonth();
        let dayDiff = fechaFinal.getDate() - fechaInicio.getDate();

        // Calcular el total de meses sin considerar los días aún
        let totalMonths = yearDiff * 12 + monthDiff;

        // Si hay una diferencia en días, incrementar el total de meses en 1
        if (dayDiff > 0) {
            totalMonths += 1;
        }

        // Asegurarse de que totalMonths no sea negativo si la fecha final es antes de la fecha de inicio
        totalMonths = totalMonths < 0 ? 0 : totalMonths;


        $('#no_meses').val(totalMonths);
        if (isNaN(valor_mensual)) {
            valor_mensual = 0;
        }

        let valor_total = valor_mensual*totalMonths;
        $('#valor_total').val(valor_total);

        console.log("Total de meses:", totalMonths);

    }


    // Function call
    calcula_valores2(val);
</script>


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
$('#inmueble_id').change(function() {
        var id = $(this).val();

        if(id) {
            $.ajax({
                url: mainurl+'/obtenerStatusporLocal/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#is_propio').val(data.local_propio);
                    if (data.local_propio == 1) {
                        $('#addDetalle').hide();

                        $('#anio_detalle').removeAttr('required');
                        $('#fecha_inicio').removeAttr('required');
                        $('#fecha_final').removeAttr('required');
                        $('#valor_metros_detalle').removeAttr('required');
                        $('#valor_mensual').removeAttr('required');
                        $('#no_meses').removeAttr('required');
                        $('#valor_total').removeAttr('required');

                    } else {
                        $('#addDetalle').show();

                        $('#anio_detalle').attr('required', 'required');
                        $('#fecha_inicio').attr('required', 'required');
                        $('#fecha_final').attr('required', 'required');
                        $('#valor_metros_detalle').attr('required', 'required');
                        $('#valor_mensual').attr('required', 'required');
                        $('#no_meses').attr('required', 'required');
                        $('#valor_total').attr('required', 'required');
                    }
                }
            });
        } else {

        }
    });
});


$('#submitButton').on('click', function(e) {
    // Prevent form submission by default
    e.preventDefault();

    // Array to store empty fields
    var emptyFields = [];

    // Check each required field and validate if it's empty
    $('#propietario_inmueble, #anio_detalle, #fecha_inicio, #fecha_final, #valor_metros_detalle, #valor_mensual, #no_meses, #valor_total').each(function() {
        // Check if the field has the 'required' attribute and if it's empty
        if ($(this).attr('required') && !$(this).val()) {
            // Get the label or field name to show in the alert
            var fieldName = $(this).attr('data-name') || $(this).attr('name'); // Optionally use 'data-name' or 'name' attribute
            emptyFields.push(fieldName);
        }
    });

    // If there are empty fields, show an alert
    if (emptyFields.length > 0) {
        alert('Los siguientes campos están vacíos: ' + emptyFields.join(', '));
    } else {
        // If no empty fields, submit the form
        $('#form_dataT').submit(); // Replace 'yourFormId' with your actual form ID
    }
});





</script>


                <div class="card-body">

                    {!! Form::hidden('rcm_id', $rcm->id, ['class' => 'form-control']) !!}

                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group ">
                                {{ Form::label('idDepto', __('Departamento'), ['class' => 'form-label']) }}
                                {!! Form::select('idDepto', $deptos, $rcm->iddepto,['id' => 'idDepto', 'required', 'disabled', 'class' => 'form-control wizard-required'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}

                                <div class="wizard-form-error"></div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                {{ Form::label('codigo_depto', __('Codigo'), ['class' => 'form-label']) }}
                                {!! Form::text('codigo_depto', (strlen($rcm->codigodepto) === 1 ? '0'.$rcm->codigodepto :  $rcm->codigodepto), [
                                    'class' => 'form-control',
                                    'id' => 'codigo_depto',
                                    'required',
                                    'readonly',
                                    'placeholder' => __('05'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('idMunicipio', __('Municipio'), ['class' => 'form-label']) }}
                                {!! Form::select('idMunicipio', $municipios, $rcm->idmunicipio, ['id' => 'idMunicipio', 'disabled', 'class' => 'form-control wizard-required' . ($errors->has('idMunicipio') ? ' is-invalid' : null)]) !!}

                                <div class="wizard-form-error"></div>
                            </div>
                        </div>


                        <div class="col-lg-1">
                            <div class="form-group">
                                {{ Form::label('codigo_muni', __('Codigo'), ['class' => 'form-label']) }}
                                {!! Form::text('codigo_muni', (strlen($rcm->codigomunicipio) === 1 ? '0'.$rcm->codigomunicipio :  $rcm->codigomunicipio), [
                                    'class' => 'form-control',
                                    'id' => 'codigo_muni',
                                    'readonly',
                                    'placeholder' => __('01'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                {{ Form::label('inmueble_id', __('Tipo Inmueble'), ['class' => 'form-label']) }}
                                {!! Form::select('inmueble_id', $inmuebles, null, ['required', 'id' => 'inmueble_id', 'class' => 'form-control wizard-required' . ($errors->has('inmueble_id') ? ' is-invalid' : null)]) !!}
                                <div class="wizard-form-error"></div>
                                {!! Form::hidden('is_propio', null, [
                                    'class' => 'form-control',
                                    'id' => 'is_propio',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                {{ Form::label('metros2', __('Metros2'), ['class' => 'form-label']) }}
                                {!! Form::text('metros2', null, [
                                    'class' => 'form-control',
                                    'id' => 'metros2',
                                    'placeholder' => __('20'),
                                ]) !!}
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('nombre_local', __('Nombre del Local'), ['class' => 'form-label']) }}
                                {!! Form::text('nombre_local', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Plaza Uno'),
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('direccion', __('Dirección'), ['class' => 'form-label']) }}
                                {!! Form::text('direccion', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('San Pedro Sula'),
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('propietario_inmueble', __('Propietario del Inmueble'), ['class' => 'form-label']) }}
                                {!! Form::text('propietario_inmueble', null, [
                                    'class' => 'form-control',
                                    'id' => 'propietario_inmueble',
                                    'required',
                                    'placeholder' => __('Nombre del propietario'),
                                ]) !!}
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('dni_o_rtn', __('DNI o RTN'), ['class' => 'form-label']) }}
                                {!! Form::text('dni_o_rtn', null, [
                                    'class' => 'form-control',
                                    'id' => 'dni_o_rtn',
                                    'required',
                                    'maxlength' => '14',
                                    'placeholder' => __('0501000000000'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('contacto_directo', __('Contacto'), ['class' => 'form-label']) }}
                                {!! Form::text('contacto_directo', null, [
                                    'class' => 'form-control',
                                    'id' => 'contacto_directo',
                                    'required',
                                    'placeholder' => __('Nombre del contacto'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('celular', __('Tel. Celular'), ['class' => 'form-label']) }}
                                {!! Form::text('celular', null, [
                                    'class' => 'form-control'. ($errors->has('celular') ? ' is-invalid' : null),
                                    'id' => 'celular',
                                    'required',
                                    'placeholder' => __('xxxx-xxxx'),
                                ]) !!}
                                <div id="error-msg1" style="color: red;"></div>
                                @error('celular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                    </div>



                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('clave_enee', __('Clave ENEE'), ['class' => 'form-label']) }}
                                {!! Form::text('clave_enee', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'placeholder' => __('123456'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('contador', __('Contador'), ['class' => 'form-label']) }}
                                {!! Form::text('contador', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'placeholder' => __('12345678'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('clave_agua', __('Clave Agua'), ['class' => 'form-label']) }}
                                {!! Form::text('clave_agua', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'placeholder' => __('123456'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('clave_catastral', __('Clave Catastral'), ['class' => 'form-label']) }}
                                {!! Form::text('clave_catastral', null, [
                                    'class' => 'form-control',
                                    'id' => 'clave_catastral',
                                    'placeholder' => __('123456'),
                                ]) !!}
                            </div>
                        </div>
                    </div>


                    <button type="button" id="addDetalle" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Agregar Detalle
                    </button>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('observaciones', __('Observaciones'), ['class' => 'form-label']) }}
                                {!! Form::textarea('observaciones', null, ['id' => 'observaciones', 'placeholder' => 'Escribe un comentario...', 'class' => 'form-control', 'rows' => 6]) !!}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="float-end">
                        {!! Html::link(route('contratos.index'), __('Cancel'), ['class'=>'btn btn-secondary']) !!}
                        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submitButton']) }}

                    </div>
                </div>


                <!-- Modal Nuevo Detalle -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalle del Contrato</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div id="addForm">

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('anio_detalle', __('Año'), ['class' => 'form-label']) }}
                                        {!! Form::text('anio_detalle', null, [
                                            'class' => 'form-control',
                                            'placeholder' => __('2024'),
                                            'maxlength' => '4',
                                            'id' => 'anio_detalle',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('fecha_inicio', __('Fecha Inicio'), ['class' => 'form-label']) }}
                                        {!! Form::date('fecha_inicio', null, [
                                            'class' => 'form-control',
                                            'id' => 'fecha_inicio',
                                            'onkeyup' => "calcula_valores(0);",
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('fecha_final', __('Fecha Final'), ['class' => 'form-label']) }}
                                        {!! Form::date('fecha_final', null, [
                                            'class' => 'form-control',
                                            'id' => 'fecha_final',
                                            'onkeyup' => "calcula_valores(0);",
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('valor_metros_detalle', __('Valor Metros2'), ['class' => 'form-label']) }}
                                        {!! Form::text('valor_metros_detalle', null, [
                                            'class' => 'form-control',
                                            'id' => 'valor_metros_detalle',
                                            'onkeyup' => "calcula_valores2(0);",
                                            'placeholder' => __('0.00'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('valor_mensual', __('Valor Mensual'), ['class' => 'form-label']) }}
                                        {!! Form::text('valor_mensual', null, [
                                            'class' => 'form-control',
                                            'id' => 'valor_mensual',
                                            'onkeyup' => "calcula_valores2(1);",
                                            'placeholder' => __('2024'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        {{ Form::label('no_meses', __('No. Meses'), ['class' => 'form-label']) }}
                                        {!! Form::text('no_meses', null, [
                                            'class' => 'form-control',
                                            'readonly',
                                            'id' => 'no_meses',
                                            'placeholder' => __('12'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        {{ Form::label('valor_total', __('Total'), ['class' => 'form-label']) }}
                                        {!! Form::text('valor_total', null, [
                                            'class' => 'form-control',
                                            'readonly',
                                            'id' => 'valor_total',
                                            'placeholder' => __('25000'),
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group ">
                                        {{ Form::label('moneda', __('Moneda'), ['class' => 'form-label']) }}
                                        <div class="input-group">
                                            <select name="moneda" class="form-select">
                                                <option value="1">Lempiras</option>
                                                <option value="2">Dolar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        {{ Form::label('costo_dicional', __('Costo Adicional'), ['class' => 'form-label']) }}
                                        {!! Form::text('costo_dicional', null, [
                                            'class' => 'form-control',
                                            'id' => 'costo_dicional',
                                            'placeholder' => __('500'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('observaciones_det', __('Observaciones'), ['class' => 'form-label']) }}
                                        {!! Form::textarea('observaciones_det', null, ['id' => 'observaciones_det', 'placeholder' => 'Escribe un comentario...', 'class' => 'form-control', 'rows' => 4]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('adjunto', __('Adjuntar Documento'), ['class' => 'form-label']) }} *
                                    <small class="text-end d-flex mt-2">{{ __('Adjunte un archivo PDF') }}</small>
                                    {!! Form::file('adjunto', ['class' => 'form-control']) !!}
                                </div>
                            </div>

                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Agregar</button>
                        </div>
                    </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>






@endsection

@push('script')
<script>

$('#metros2').on('input', function() {
        var value = $(this).val();

        // Eliminar cualquier coma o carácter que no sea número o punto decimal
        value = value.replace(/[^0-9.]/g, '');

        // Asegurarse de que solo haya un punto decimal
        var parts = value.split('.');
        if (parts.length > 2) {
            parts = [parts[0], parts[1]]; // Mantener solo el primer punto decimal
        }

        // Volver a unir las partes antes y después del punto decimal
        $(this).val(parts.join('.'));
    });
    

    function calcula_valores(val) {
        let metros2 = parseFloat($('#metros2').val());
        if (isNaN(metros2)) {
            metros2 = 0;
        }

        if (val == 1) {
            let valorm = parseFloat($('#valor_mensual').val());
            if (isNaN(valorm)) {
                valorm = 0;
            }

            $('#valor_metros_detalle').val(valorm/metros2);

        } else {
            let valor_metros_detalle = parseFloat($('#valor_metros_detalle').val());
            if (isNaN(valor_metros_detalle)) {
                valor_metros_detalle = 0;
            }

            $('#valor_mensual').val(metros2*valor_metros_detalle);
        }



        let valor_mensual = parseFloat($('#valor_mensual').val());

        const fecha_inicio = document.getElementById("fecha_inicio").value;
        const fecha_final = document.getElementById("fecha_final").value;

        const fechaInicio = new Date(fecha_inicio);
        const fechaFinal = new Date(fecha_final);

        let yearDiff = fechaFinal.getFullYear() - fechaInicio.getFullYear();
        let monthDiff = fechaFinal.getMonth() - fechaInicio.getMonth();
        let dayDiff = fechaFinal.getDate() - fechaInicio.getDate();

        // Calcular el total de meses sin considerar los días aún
        let totalMonths = yearDiff * 12 + monthDiff;

        // Si hay una diferencia en días, incrementar el total de meses en 1
        if (dayDiff > 0) {
            totalMonths += 1;
        }

        // Asegurarse de que totalMonths no sea negativo si la fecha final es antes de la fecha de inicio
        totalMonths = totalMonths < 0 ? 0 : totalMonths;


        $('#no_meses').val(totalMonths);
        if (isNaN(valor_mensual)) {
            valor_mensual = 0;
        }

        let valor_total = valor_mensual*totalMonths;
        $('#valor_total').val(valor_total);

        console.log("Total de meses:", totalMonths);

    }

</script>

@endpush
