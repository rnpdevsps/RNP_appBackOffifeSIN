@php
    use App\Facades\UtilityFacades;
    @include('contratos.templates');
    $user = \Auth::user();
    $username = $user->name;
@endphp

@extends('layouts.main')
@section('title', __('Editar Contrato'))
@section('breadcrumb') 
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('Editar Contrato') }}</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
                        <li class="breadcrumb-item">{!! Html::link(route('rcms.index'), __('RCM'), []) !!}</li>
                        <li class="breadcrumb-item active">{{ __('Editar Contrato') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
@include('layouts.includes.customjs')
<br>

<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('observaciones');
</script>

    <div class="layout-px-spacing row" id="divRefreshC">
        <div id="basic" class="mx-auto col-lg-12 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                @if (!$contrato->is_propio)
                                
                                     @if (empty($contrato->DetContrato->fecha_final))
                                        -----
                                    @else
                                        <label for="fecha" class="form-label">Fecha Vencimiento: {{UtilityFacades::date_format($contrato->DetContrato->fecha_final)}}</label>
                                    @endif
                                
                                    
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="usuario" class="form-label">Usuario: {{ $contrato->createdBy->name }}</label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="usuario" class="form-label">
                                    @if ($contrato->Rcm->clasificacion === 0)
                                        Ventanilla: {{ $contrato->Rcm->codigo }}
                                    @endif
                                    @if ($contrato->Rcm->clasificacion == 1)
                                        RCM: {{ $contrato->Rcm->codigo }}
                                    @endif
                                    @if ($contrato->Rcm->clasificacion == 2)
                                        Kioscos: {{ $contrato->Rcm->codigo }}
                                    @endif
                                    @if ($contrato->Rcm->clasificacion == 3)
                                        Bodega: {{ $contrato->Rcm->codigo }}
                                    @endif
                                </label>

                            </div>
                        </div>

                        <div class="col-lg-3" id="status_contrato">
                            <div class="form-group">
                                <label for="status_contrato" class="form-label">Estado del Contrato:
                                    @if (!$contrato->is_propio)
                                     @if (empty($contrato->DetContrato->fecha_final))
                                        <span class="p-2 px-3 badge rounded-pill bg-danger">En Proceso</span>
                                    @else
                                         @if ($contrato->DetContrato->fecha_final < now())
                                            <span class="p-2 px-3 badge rounded-pill bg-danger">Vencido</span>
                                        @else
                                            <span class="p-2 px-3 badge rounded-pill bg-success">Vigente</span>
                                        @endif
                                    @endif
                                    
                                       
                                    @else
                                        <span class="p-2 px-3 badge rounded-pill bg-success">Vigente</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::model($contrato, [
                    'route' => ['contratos.update', $contrato->id],
                    'method' => 'Put',
                    'data-validate',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]) !!}


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


@if (!$contrato->is_propio)
   <script>
        $('#divRefreshCNO').addClass('d-none');
   </script>
@else
    <script>
        $('#divRefreshCNO').removeClass('d-none');
    </script>
@endif

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
                        $('#agregarDetalle').hide();
                        $('#divRefreshCNO').addClass('d-none');
                    } else {
                        // Comprobar si el botón ya existe
                        if ($('#agregarDetalle').length === 0) {
                            // Crear el botón
                            var botonDetalle = $('<button>', {
                                type: 'button',
                                id: 'agregarDetalle',
                                class: 'btn btn-primary',
                                'data-bs-toggle': 'modal',
                                'data-bs-target': '#exampleModal',
                                text: 'Agregar Detalle'
                            });

                            // Agregar el botón al div con id "btnaddDetalle"
                            $('#btnaddDetalle').append(botonDetalle);
                        } else {
                            $('#agregarDetalle').show();
                            $('#divRefreshCNO').removeClass('d-none');
                        }

                       // $('#agregarDetalle').show();
                    }
                }
            });
        } else {

        }
    });
});
</script>

                <div class="card-body">

                    {!! Form::hidden('rcm_id', $contrato->rcm_id, ['class' => 'form-control']) !!}

                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group ">
                                {{ Form::label('idDepto', __('Departamento'), ['class' => 'form-label']) }}
                                {!! Form::select('idDepto', $deptos, $contrato->Rcm->iddepto,['id' => 'idDepto', 'required', 'disabled', 'class' => 'form-control wizard-required'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}

                                <div class="wizard-form-error"></div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                {{ Form::label('codigo_depto', __('Codigo'), ['class' => 'form-label']) }}
                                {!! Form::text('codigo_depto', (strlen($contrato->Rcm->codigodepto) === 1 ? '0'.$contrato->Rcm->codigodepto :  $contrato->Rcm->codigodepto), [
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
                                {!! Form::select('idMunicipio', $municipios, $contrato->Rcm->idmunicipio, ['id' => 'idMunicipio', 'disabled', 'class' => 'form-control wizard-required' . ($errors->has('idMunicipio') ? ' is-invalid' : null)]) !!}
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>


                        <div class="col-lg-1">
                            <div class="form-group">
                                {{ Form::label('codigo_muni', __('Codigo'), ['class' => 'form-label']) }}
                                {!! Form::text('codigo_muni', (strlen($contrato->Rcm->codigomunicipio) === 1 ? '0'.$contrato->Rcm->codigomunicipio :  $contrato->Rcm->codigomunicipio), [
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
                                {!! Form::select('inmueble_id', $inmuebles, $contrato->inmueble_id, ['id' => 'inmueble_id', 'class' => 'form-control wizard-required' . ($errors->has('inmueble_id') ? ' is-invalid' : null)]) !!}
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
                                {!! Form::number('metros2', null, [
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
                                    'required',
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
                                    'id' => 'password',
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
                                    'placeholder' => __('xxxxxxxx'),
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


                    @if (!$contrato->is_propio)
                    
                    @if (empty($contrato->DetContrato->fecha_final))
                                        <button type="button" id="agregarDetalle" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Agregar Detalle
                                            </button>
                                    @else
                                         @if ($contrato->DetContrato->fecha_final < now())
                                            <button type="button" id="agregarDetalle" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Agregar Detalle
                                            </button>
                                        @endif
                                    @endif
                                    
                                    
                        
                    @endif

                    <div id="btnaddDetalle"></div>


                    <style>
                        #divRefreshCNO {
                            overflow-x: auto;
                            white-space: nowrap;
                        }

                        .table th, .table td {
                            white-space: nowrap;
                        }
                    </style>


                    <div id="divRefreshCNO" >
                        <table id="detalleTable" class="table">
                            <thead>
                            <tr>
                                <th>Año</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Final</th>
                                <th>Meses</th>
                                <th>Valor Metros2</th>
                                <th>Valor Mensual</th>
                                <th>Valor Anual</th>
                                <th>Status</th>
                                <th>Moneda</th>
                                <th>Creado por</th>
                                <th>Adjunto</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                <!-- body -->
                                @foreach ($detalleContrato as $key => $data)
                                <tr>
                                    <td>{{$data->anio}}</td>
                                    <td>{{UtilityFacades::date_format($data->fecha_inicio)}}</td>
                                    <td>{{UtilityFacades::date_format($data->fecha_final)}}</td>
                                    <td>{{$data->no_meses}}</td>
                                    <td>{{$data->valor_metros2}}</td>
                                    <td>{{$data->valor_mensual}}</td>
                                    <td>{{$data->valor_total}}</td>
                                    <td>
                                        @if ($data->fecha_final < now())
                                            <span class="p-2 px-3 badge rounded-pill bg-danger">Vencido</span>
                                        @else
                                            <span class="p-2 px-3 badge rounded-pill bg-success">Vigente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->moneda == 1)
                                            Lempiras
                                        @else
                                            Dolar
                                        @endif
                                    </td>
                                    <td>{{$data->createdBy->name}}</td>
                                    <td>
                                        @if (isset($data->adjunto) && $data->adjunto !== '')
                                            <a href="{{ Storage::url('Contratos/'.$data->adjunto) }}" class="btn btn-success btn-sm" data-toggle="tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Editar') }}" target="_blank">
                                                <i class="ti ti-download"></i>Ver
                                            </a>
                                        @else
                                            <span>-------------</span>
                                        @endif
                                    </td>
                                    <td>

                                        @if ($data->fecha_final < now())
                                            -------------
                                        @else
                                            <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-detalleContrato"
                                                data-url="{{ route('contratos.editDetalle', $data->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i>
                                            </a>
                                        @endif


                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

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
                        {{ Form::button(__('Save'),['type' => 'submit','class' => 'btn btn-primary']) }}

                        <a class="btn btn-warning" href="javascript:void(0);" id="template"
                        data-url="{{ route('plantilla', 1) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-original-title="{{ __('Contrato') }}">Contrato</a>

                    </div>
                </div>
                <script>
                    $(function() {           
                        $('body').on('click', '#template', function() {
                            var plantillaId = 1;        
                            var url = '{{ route("plantilla","") }}';
                                    
                            if (plantillaId > 0) {
                                var action = url+"/"+plantillaId;
                                var modal = $('#common_modal_xl');
                                $.get(action, function(response) {
                                    modal.find('.modal-title').html('Contrato');
                                    modal.find('.body').html(response.html);
                                    modal.modal('show');
                                })
                            }
                        });
                    });
                </script>

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
                                            'maxlength' => '4',
                                            'id' => 'anio_detalle',
                                            'placeholder' => __('2024'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('fecha_inicio', __('Fecha Inicio'), ['class' => 'form-label']) }}
                                        {!! Form::date('fecha_inicio', null, [
                                            'class' => 'form-control',
                                            'id' => 'fecha_inicio',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('fecha_final', __('Fecha Final'), ['class' => 'form-label']) }}
                                        {!! Form::date('fecha_final', null, [
                                            'class' => 'form-control',
                                            'id' => 'fecha_final',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('valor_metros_detalle', __('Valor Metros2'), ['class' => 'form-label']) }}
                                        {!! Form::text('valor_metros_detalle', null, [
                                            'class' => 'form-control',
                                            'id' => 'valor_metros_detalle',
                                            'onkeyup' => "calcula_valores(0);",
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
                                            'onkeyup' => "calcula_valores(1);",
                                            'placeholder' => __('10000'),
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
                                            <select name="moneda" id="moneda" class="form-select">
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
                                    {!! Form::file('adjunto', ['class' => 'form-control', 'id' => 'adjunto',]) !!}
                                </div>
                            </div>

                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="Agregar" onclick="addRowFromModal('C')">Agregar</button>
                            <button type="button" class="btn btn-primary d-none" id="Actualizar" onclick="addRowFromModal('U')">Actualizar</button>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Add delete modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Compareciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        Esta seguro que desea eliminar detalle agregado?
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
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



<script type="text/javascript">

    $('body').on('click', '#edit-detalleContrato', function() {
        var action = $(this).attr('data-url');
        var modal = $('#common_modal_g');
        $.get(action, function(response) {
            modal.find('.modal-title').html('{{ __('Editar Detalle Contrato') }}');
            modal.find('.body').html(response.html);
            modal.modal('show');
        })
    });

    function ValidFields(fieldId, msg) {
        const fieldValue = document.getElementById(fieldId).value.trim();
        const fieldElement = document.getElementById(fieldId);

        if (fieldValue === "") {
            fieldElement.style.border = "1px solid red";
            return msg;
        } else {
            fieldElement.style.border = "1px solid green";
            return "";
        }
    }

    function validarCampos() {
        let errorMessages = "";
        let errorCount = 0;

        const campo1 = ValidFields("anio_detalle", "debe registrar el año");
        if (campo1 !== "") {
            errorMessages += ++errorCount + ". " + campo1 + "<br>";
        }

        const campo2 = ValidFields("valor_metros_detalle", "debe registrar el valor de metros2");
        if (campo2 !== "") {
            errorMessages += ++errorCount + ". " + campo2 + "<br>";
        }

        const campo3 = ValidFields("fecha_inicio", "debe registrar la fecha de inicio");
        if (campo3 !== "") {
            errorMessages += ++errorCount + ". " + campo3 + "<br>";
        }

        const campo4 = ValidFields("fecha_final", "debe registrar la fecha final");
        if (campo4 !== "") {
            errorMessages += ++errorCount + ". " + campo4 + "<br>";
        }

        const campo5 = ValidFields("valor_mensual", "debe registrar el valor mensual");
        if (campo5 !== "") {
            errorMessages += ++errorCount + ". " + campo5 + "<br>";
        }

        if (errorMessages.trim() !== "") {
            $("#exampleModal").modal("hide");
            new swal({
                content: "text/html",
                title: "Atención",
                html: '<div style="text-align: left;">' + errorMessages + '</div>',
                icon: 'error',
            }).then(function() {
                $("#exampleModal").modal("show");
            });
            return;
        }
    }




function addRowFromModal(accion) {

    validarCampos();

    $("#Agregar").addClass('d-none');
    $("#Actualizar").removeClass('d-none');

    document.getElementById("agregarDetalle").style.display = "none";

    const table = document.getElementById("detalleTable");
    if (accion == 'U') { table.deleteRow(-1); }
    const idInput = document.getElementById("anio_detalle");
    const anio_detalle = idInput.value;
    const row = table.insertRow(-1);

    const anio_detalleCell = row.insertCell(0);
    anio_detalleCell.innerHTML = anio_detalle;

    const fecha_inicioCell = row.insertCell(1);
    const fecha_inicio = document.getElementById("fecha_inicio").value;
    fecha_inicioCell.innerHTML = fecha_inicio;

    const fecha_finalCell = row.insertCell(2);
    const fecha_final = document.getElementById("fecha_final").value;
    fecha_finalCell.innerHTML = fecha_final;

    const no_mesesCell = row.insertCell(3);
    const no_meses = document.getElementById("no_meses").value;
    no_mesesCell.innerHTML = no_meses;

    const valor_metros_detalleCell = row.insertCell(4);
    const valor_metros_detalle = document.getElementById("valor_metros_detalle").value;
    valor_metros_detalleCell.innerHTML = parseFloat(valor_metros_detalle);

    const valor_mensualCell = row.insertCell(5);
    const valor_mensual = document.getElementById("valor_mensual").value;
    valor_mensualCell.innerHTML = parseFloat(valor_mensual);

    const valor_totalCell = row.insertCell(6);
    const valor_total = document.getElementById("valor_total").value;
    valor_totalCell.innerHTML = parseFloat(valor_total);

    const statusCell = row.insertCell(7);
    const fechaActual = new Date();
    const fechaFinal = new Date(fecha_final);

    // Comparar las fechas
    if (fechaFinal < fechaActual) {
        statusCell.innerHTML = '<span class="p-2 px-3 badge rounded-pill bg-danger">Vencido</span>';
    } else {
        statusCell.innerHTML = '<span class="p-2 px-3 badge rounded-pill bg-success">Vigente</span>';
    }


    const monedaCell = row.insertCell(8);
    const moneda = document.getElementById("moneda").value;
    if (moneda == 1) {
        var valmoneda ="Lempiras";
    } else {
        var valmoneda ="Dolar";
    }
    monedaCell.innerHTML = valmoneda;

    const creadoporCell = row.insertCell(9);
    creadoporCell.innerHTML = '{{ $username }}';

    const adjuntoCell = row.insertCell(10);
    adjuntoCell.innerHTML = '--------------';

    const actionCell = row.insertCell(11); // Crea la celda donde se colocarán los enlaces

    const actionsContainer = document.createElement("div"); // Crea un contenedor para los enlaces
    actionsContainer.classList.add("btn-group"); // Opcional: Añade clases de Bootstrap para diseño

    const editLink = document.createElement("a"); // Crea el enlace de edición
    editLink.classList.add("btn", "btn-primary", "btn-sm", "bg-primary");
    editLink.textContent = "📝";
    editLink.addEventListener("click", () => {
        const editModal = new bootstrap.Modal(document.getElementById("exampleModal"));
        editModal.show();
    });

    const deleteLink = document.createElement("a"); // Crea el enlace de eliminación
    deleteLink.classList.add("btn", "btn-danger", "btn-sm", "bg-danger");
    deleteLink.textContent = "🗑️";
    deleteLink.addEventListener("click", () => {
        const firstCellValue = anio_detalleCell.innerHTML;
        document.getElementById("deleteModalLabel").textContent = `Contrato  - ${anio_detalle}`;
        const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
        deleteModal.show();
    });

    actionsContainer.appendChild(editLink);
    actionsContainer.appendChild(deleteLink);
    actionCell.appendChild(actionsContainer);


    const modal = bootstrap.Modal.getInstance(document.getElementById("exampleModal"));
    modal.hide();
}

document.getElementById("confirmDeleteButton").addEventListener("click", () => {
    const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
    deleteModal.hide();
    const table = document.getElementById("detalleTable");
    table.deleteRow(-1);

    document.getElementById("anio_detalle").value = "";
    document.getElementById("valor_metros_detalle").value = "";
    document.getElementById("fecha_inicio").value = "";
    document.getElementById("fecha_final").value = "";
    document.getElementById("moneda").value = 1;
    document.getElementById("adjunto").value = "";

    $("#Agregar").removeClass('d-none');
    $("#Actualizar").addClass('d-none');

    document.getElementById("agregarDetalle").style.display = "block";
});


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#common_modal_g").on("click", "#submitDataC", function(event) {
    event.preventDefault();

    let ajaxForm = $("#common_modal_g").find('#form_dataC')[0];
    let fd = new FormData(ajaxForm);
    let url = $(ajaxForm).attr('action');
    let method = $(ajaxForm).attr('method');

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
            $("#common_modal_g").modal("hide");
            new swal({
                text: data.success,
                icon: "success"
            }).then(() => {
              $("#divRefreshC").load(location.href + " #divRefreshC", function() {
                });
            });
        },
        error:function(){
          new swal({
                text: "Error.! Intentelo de nuevo.",
                icon: 'error',
            });
        }
    });
});

</script>
@endpush


