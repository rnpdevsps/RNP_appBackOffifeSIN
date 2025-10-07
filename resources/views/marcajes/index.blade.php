@extends('layouts.main')
@section('title', __('Marcajes'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Marcajes') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active">{{ __('Marcajes') }}</li>
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

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                            {{ Form::label('empleado', __('Nombre/DNI/RCM: '), ['class' => 'form-label']) }}
                                                {{ Form::text('empleado', null, ['class' => 'form-control mr-1 ', 'placeholder' => __('Buscar por Nombre/DNI/RCM'), 'style' => 'text-transform: uppercase;',
                                                    'oninput' => 'this.value = this.value.toUpperCase()', 'data-kt-ecommerce-category-filter' => 'search']) }}
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3">
                                            <br>
                                            {{ Form::button(__('Buscar'), ['class' => 'btn btn-primary btn-lg  add_filter button-left']) }}
                                            {{ Form::button(__('Limpiar Filtro'), ['class' => 'btn btn-secondary btn-lg clear_filter']) }}
                                        </div>
                                    </div>

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
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

    <script>
        $(function() {
            $('body').on('click', '.report-marcajes', function() {
                var modal = $('#common_modal_g');
                $.ajax({
                    type: "GET",
                    url: '{{ route('marcajesreport') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Generar Reporte Marcaje') }}');
                        modal.find('.body').html(response.html);                        
                        
                       
                       /* var multipleCancelButton3 = new Choices('#empleado_idModal', {
                            removeItemButton: true,
                        });*/
                        
                        modal.modal('show');
                    },
                    error: function(error) {}
                });
            });

        });
    </script>
@endpush
