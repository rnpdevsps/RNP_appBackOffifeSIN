@extends('layouts.main')
@section('title', __('Nuevo Empleado'))
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('Nuevo Empleado') }}</h4>
                    </div> 
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
                        <li class="breadcrumb-item">{!! Html::link(route('rcms.index'), __('RCM'), []) !!}</li>
                        <li class="breadcrumb-item active">{{ __('Nuevo Empleado') }}</li>
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
                            
                        </div>
                    </div>
                </div>

                {!! Form::open([
                    'route' => ['empleados.store'],
                    'method' => 'POST',
                    'data-validate',
                    'id' => 'form_dataT',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]) !!}



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
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
                                {!! Form::select('idDepto', $deptos, $rcm->idDepto,['id' => 'idDepto', 'required', 'disabled', 'class' => 'form-control wizard-required'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}

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
                                {!! Form::select('idMunicipio', $municipios, $rcm->idMunicipio, ['id' => 'idMunicipio', 'disabled', 'class' => 'form-control wizard-required' . ($errors->has('idMunicipio') ? ' is-invalid' : null)]) !!}

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
                                    'placeholder' => __('02'),
                                ]) !!}
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('name', __('Nombre'), ['class' => 'form-label']) }}
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'id' => 'name',
                                    'required',
                                    'placeholder' => __('Nombre del propietario'),
                                ]) !!}
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="form-group">
                                {{ Form::label('dni', __('DNI'), ['class' => 'form-label']) }}
                                {!! Form::text('dni', null, [
                                    'class' => 'form-control',
                                    'id' => 'dni',
                                    'required',
                                    'maxlength' => '13',
                                    'placeholder' => __('0501000000000'),
                                ]) !!}
                            </div>
                        </div>

                       


                    </div>



                </div>

                <div class="card-footer">
                    <div class="float-end">
                        {!! Html::link(route('rcms.index'), __('Cancel'), ['class'=>'btn btn-secondary']) !!}
                        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submitButton']) }}

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection