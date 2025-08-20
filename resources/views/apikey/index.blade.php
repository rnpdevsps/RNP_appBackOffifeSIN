@extends('layouts.main')
@section('title', __('ApiKeys'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('ApiKeys') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item active">{{ __('ApiKeys') }}</li>
        </ul>
        
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
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        $(function() {
            $('body').on('click', '.add-apikey', function() {
                var modal = $('#common_modal_g');
                $.ajax({
                    type: "GET",
                    url: '{{ route('apikey.create') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html('{{ __('Crear ApiKey') }}');
                        modal.find('.body').html(response.html);                        
                        modal.modal('show');
                    },
                    error: function(error) {}
                });
            });
            $('body').on('click', '#edit-apikey', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal_g');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Editar ApiKey') }}');
                    modal.find('.body').html(response.html);                    
                    modal.modal('show');
                })
            });
        });
    </script>
@endpush
