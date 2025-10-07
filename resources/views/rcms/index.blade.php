@extends('layouts.main')
@section('title', __('RCM'))
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>


    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('RCM') }}</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">
                            {!! html()->a(route('home'), __('Dashboard'))->class('')->target('') !!}
                            </li>
                        <li class="breadcrumb-item active">{{ __('RCM') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
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
            $('body').on('click', '.add-rcm', function() {
                var modal = $('#common_modal_g');
                $.ajax({
                    type: "GET",
                    url: '{{ route('rcms.create') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Crear RCM') }}');
                        modal.find('.body').html(response.html);
                        modal.modal('show');
                    },
                    error: function(error) {}
                });
            });
            $('body').on('click', '#inactivar-rcm', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Inactivar RCM') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
            $('body').on('click', '#edit-rcm', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Editar RCM') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
            $('body').on('click', '#bitacora-rcm', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Bitacora RCM') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
            $('body').on('click', '#empleados-rcm', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_xl');
                $.get(action, function(response) {
                    modal.find('.modal-title').html(response.titulo);
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });

            $('body').on('click', '.report-rcm', function() {
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