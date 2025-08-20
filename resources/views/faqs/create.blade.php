@extends('layouts.main')
@section('title', __('Create Faq'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Create Faq') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('faqs.index'), __('Faqs')) !!}</li>
            <li class="breadcrumb-item">{{ __('Create Faq') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-6">
                <div class="card ">
                    <div class="card-header">
                        <h5> {{ __('Create Faq') }}</h5>
                    </div>
                    {!! html()->form('POST', route('faqs.store'), ['data-validate'])->open() !!}
                    <div class="card-body">
                        <div class="form-group ">
                            {!! html()->label(__('questions'), 'questions')->class('form-label') !!}
                            {!! html()->text('questions')->class('form-control')->required()->placeholder(__('Enter questions')) !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Answer'))->for('answer')->class('form-label') !!}
                            {!! html()->textarea('answer')->class('form-control')->required()->placeholder(__('Enter answer'))->attribute('data-trigger') !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Order'))->for('order')->class('form-label') !!}
                            {!! html()->number('order')->class('form-control')->required()->placeholder(__('Enter order')) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-end">
                            {!! html()->a(route('faqs.index'), __('Cancel'))->class('btn btn-secondary') !!}
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                        </div>
                    </div>
                    {!! html()->form()->close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('answer', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
