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
                        <li class="breadcrumb-item">{!! Html::link(route('contratos.index'), __('Contratos'), []) !!}</li>
                        <li class="breadcrumb-item active">{{ __('Nuevo Contrato') }}</li>
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

    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-12 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="fecha" class="col-form-label">Fecha: {{ $fecha }}</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="usuario" class="col-form-label">Usuario: {{ $contrato->createdBy->name }}</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="status_contrato" class="col-form-label">Estado del Contrato:
                                    @if ($contrato->status_contrato == 1)
                                        <span class="p-2 px-3 badge rounded-pill bg-warning">Local propio</span>
                                    @endif
                                    @if ($contrato->status_contrato == 2)
                                        <span class="p-2 px-3 badge rounded-pill bg-secondary">Posible cambio</span>
                                    @endif
                                    @if ($contrato->status_contrato == 3)
                                        <span class="p-2 px-3 badge rounded-pill bg-danger">Aumento</span>
                                    @endif
                                    @if ($contrato->status_contrato == 4)
                                        <span class="p-2 px-3 badge rounded-pill bg-success">Firmado</span>
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
                ]) !!}

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('codigo_muni', __('Codigo Muni'), ['class' => 'form-label']) }}
                                {!! Form::text('codigo_muni', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0501'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('municipio', __('Municipio'), ['class' => 'form-label']) }}
                                {!! Form::text('municipio', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('San Pedro Sula'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
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

                    </div>


                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('dni_o_rtn', __('DNI o RTN'), ['class' => 'form-label']) }}
                                {!! Form::text('dni_o_rtn', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0501000000000'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('celular', __('Tel. Celular'), ['class' => 'form-label']) }}
                                {!! Form::text('celular', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('99999999'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group ">
                                {{ Form::label('local_propio', __('Local Propio'), ['class' => 'col-form-label']) }}
                                <div class="input-group">
                                    <select name="local_propio" id="local_propio" class="form-select">
                                        <option value="0" @if ($contrato->local_propio == 0) selected @endif>NO</option>
                                        <option value="1" @if ($contrato->local_propio == 1) selected @endif>SI</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>




                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('valor_inmueble', __('Valor del Inmueble'), ['class' => 'form-label']) }}
                                {!! Form::text('valor_inmueble', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('impacto_anual', __('Impacto Anual Contratos'), ['class' => 'form-label']) }}
                                {!! Form::text('impacto_anual', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('valor_incremento', __('Valor Incremento'), ['class' => 'form-label']) }}
                                {!! Form::text('valor_incremento', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                    </div>




                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('impacto_anual_aumento', __('Impacto Anual Aumentos'), ['class' => 'form-label']) }}
                                {!! Form::text('impacto_anual_aumento', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('total_bien_inmueble_mensual', __('Total Bien Inmueble Mensual'), ['class' => 'form-label']) }}
                                {!! Form::text('total_bien_inmueble_mensual', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('traslado_valor', __('Valor Traslado'), ['class' => 'form-label']) }}
                                {!! Form::text('traslado_valor', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                    </div>



                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('total', __('Total'), ['class' => 'form-label']) }}
                                {!! Form::text('total', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('0.00'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('clave_enee', __('Clave ENEE'), ['class' => 'form-label']) }}
                                {!! Form::text('clave_enee', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('123456'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('contador', __('Contador'), ['class' => 'form-label']) }}
                                {!! Form::text('contador', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('12345678'),
                                ]) !!}
                            </div>
                        </div>

                    </div>



                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('clave_agua', __('Clave Agua'), ['class' => 'form-label']) }}
                                {!! Form::text('clave_agua', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('123456'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group ">
                                {{ Form::label('doc_comp', __('Documentos Comp.'), ['class' => 'col-form-label']) }}
                                <div class="input-group">
                                    <select name="doc_comp" id="doc_comp" class="form-select">
                                        <option value="0" @if ($contrato->doc_comp == 0) selected @endif>NO</option>
                                        <option value="1" @if ($contrato->doc_comp == 0) selected @endif>SI</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group ">
                                {{ Form::label('status_contrato', __('Estado Contrato'), ['class' => 'col-form-label']) }}
                                <div class="input-group">
                                    <select name="status_contrato" id="status_contrato" class="form-select">
                                        <option value="1" @if ($contrato->status_contrato == 1) selected @endif>Local propio</option>
                                        <option value="2" @if ($contrato->status_contrato == 2) selected @endif>Posible cambio</option>
                                        <option value="3" @if ($contrato->status_contrato == 3) selected @endif>Aumento</option>
                                        <option value="4" @if ($contrato->status_contrato == 4) selected @endif>Firmado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                {{ Form::label('inconsistencias_doc', __('Inconsistencias Doc.'), ['class' => 'form-label']) }}
                                {!! Form::text('inconsistencias_doc', null, [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required',
                                    'placeholder' => __('Inconsistencias'),
                                ]) !!}
                            </div>
                        </div>
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
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
