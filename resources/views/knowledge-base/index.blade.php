@extends('layouts.main')
@section('title', __('Knowledge Base'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Knowledge Base') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Knowledge Base') }}</li>
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
            $('.add-knowledge-base').on('click', function() {
                var modal = $('#common_modal');
                $.ajax({
                    type: "GET",
                    url: '{{ route('knowledges.create') }}',
                    data: {},
                    success: function(response) {
                        modal.find('.modal-title').html(
                            '{{ __('Create Knowledge Category') }}');
                        modal.find('.body').html(response.html);
                        modal.modal('show');
                        ['#category', '#document'].forEach(selector => new Choices(selector));
                    },
                    error: function(error) {}
                });
            });
            $('body').on('click', '.edit-knowledge-base', function() {
                var action = $(this).attr('data-url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Edit Knowledge Category') }}');
                    modal.find('.body').html(response.html);
                    modal.modal('show');
                    ['#category', '#document'].forEach(selector => new Choices(selector));
                })
            });
        });
    </script>
@endpush
