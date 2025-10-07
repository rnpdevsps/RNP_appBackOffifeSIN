@extends('layouts.main')
@section('title', __('Empleados'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Empleados') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active">{{ __('Empleados') }}</li>
        </ul>
    </div>
@endsection
@section('content')
@include('layouts.includes.customjs')

    <div class="row mt-4">
        <div class="main-content">
            <section class="section">
                 <div class="section-body filter">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    

                                          
                                             <form method="GET" action="{{ route('empleados.index') }}" >
                                                <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-group ">
                                                        {{ Form::label('idDepto', __('Departamento: '), ['class' => 'form-label']) }}
                                                        {!! Form::select('idDepto', $deptos, request()->query('idDepto') ,['id' => 'idDepto', 'class' => 'form-control wizard-required'. ($errors->has('idDepto') ? ' is-invalid' : null) ]) !!}
                                                    </div>
                                                    </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group ">
                                                        {{ Form::label('idMunicipio', __('Municipio: '), ['class' => 'form-label']) }}
                                                        {!! Form::select('idMunicipio', $municipios, request()->query('idMunicipio'),['id' => 'idMunicipio', 'class' => 'form-control wizard-required'. ($errors->has('idMunicipio') ? ' is-invalid' : null) ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-3"> </br>
                                                    <button type="submit" class="btn btn-primary btn-lg">Filtrar</button>
                                                </div>
                                                </div>
                                            </form>

                                        
                                        
                                        <form method="GET" action="{{ route('empleados.index') }}" >
                                            <div class="row">
                                        <div class="col-lg-4" style="float: right;">
                                            {!! Form::hidden('idDepto', null, ['placeholder' => 'Nombre de la plantilla', 'class' => 'form-control']) !!}
                                            {!! Form::hidden('idMunicipio', null, ['placeholder' => 'Nombre de la plantilla', 'class' => 'form-control']) !!}
                                            <button type="submit" class="btn btn-secondary btn-lg ">Limpiar Filtro</button>
                                        </div>
                                        </div>
                                        </form>
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <div class="table-responsive py-4">
                                                {{ $dataTable->table(['width' => '100%']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('style')
    @include('layouts.includes.datatable-css')
@endpush
@push('script')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
    <script>
        $(function() {
            $('body').on('click', '.add-empleado', function() {
                var modal = $('#common_modal_g');
                $.ajax({
                    type: "GET",
                    url: '{{ route('empleados.create') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Crear Empleado') }}');
                        modal.find('.body').html(response.html);
                        modal.modal('show');
                    },
                    error: function(error) {}
                });
            });
            $('body').on('click', '#inactivar-empleado', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Inactivar Empleado') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
            $('body').on('click', '#edit-empleado', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Editar Empleado') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
            $('body').on('click', '#bitacora-empleado', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Bitacora Empleado') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
            
            $('body').on('click', '.report-empleado', function() {
                var modal = $('#common_modal');
                $.ajax({
                    type: "GET",
                    url: '{{ route('rcmreport') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Generar Reporte') }}');
                        modal.find('.body').html(response.html);
                        modal.modal('show');
                    },
                    error: function(error) {}
                });
            });

        });
    </script>
@endpush
