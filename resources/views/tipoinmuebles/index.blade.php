@extends('layouts.main')
@section('title', __('Tipos de Inmuebles'))
@section('breadcrumb')
@php
    $show = request()->query->get('view');
    if ($show == 'todos') {
        $view = 'eliminados';
    } else {
        $view = 'todos';
    }

@endphp
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Tipos de Inmuebles') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
            <li class="breadcrumb-item active">{{ __('Tipos de Inmuebles') }}</li>
        </ul>
         <div class="float-end">
            <div class="d-flex align-items-center">
                <a href="{{ route('tipoinmuebles.index', ['view' => $view]) }}" data-bs-toggle="tooltip" title="{{ __('Filtro') }}"
                    class="btn btn-sm  @if ($view == 'eliminados')
                        btn-danger
                    @else
                        btn-primary
                    @endif" data-bs-placement="bottom">
                    <i class="ti ti-layout-grid"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
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
            $('.add-tipoinmueble').on('click', function() {
                var modal = $('#common_modal');
                $.ajax({
                    type: "GET",
                    url: '{{ route('tipoinmuebles.create') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Nuevo Tipo de Inmueble') }}');
                        modal.find('.body').html(response.html);
                        modal.modal('show');
                    },
                    error: function(error) {
                    }
                });
            });
            $('body').on('click', '.edit-tipoinmueble', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Editar Tipo de Inmueble') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                })
            });
        });
    </script>
@endpush
