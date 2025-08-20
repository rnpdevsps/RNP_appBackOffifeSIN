@extends('layouts.main')
@section('title', __('Edit Faq'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <div class="previous-next-btn">
                <div class="page-header-title">
                    <h4 class="m-b-10">{{ __('Edit Faq') }}</h4>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard'))->attributes(['']) !!}</li>
                    <li class="breadcrumb-item">{!! html()->a(route('faqs.index'), __('Faqs'))->attributes(['']) !!}</li>
                    <li class="breadcrumb-item">{{ __('Edit Faq') }}</li>
                </ul>
            </div>
            <div class="float-end">
                <div class="d-flex align-items-center">
                    <a href="@if (!empty($previous)) {{ route('faqs.edit', [$previous->id]) }}@else javascript:void(0) @endif"
                        type="button" class="btn btn-outline-primary"><i class="me-2"
                            data-feather="chevrons-left"></i>Previous</a>
                    <a href="@if (!empty($next)) {{ route('faqs.edit', [$next->id]) }}@else javascript:void(0) @endif"
                        class="btn btn-outline-primary ms-1"><i class="me-2" data-feather="chevrons-right"></i>Next</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-6">
                <div class="card ">
                    <div class="card-header">
                        <h5> {{ __('Edit Faq') }}</h5>
                    </div>
                    {!! html()
                        ->modelForm($faq, 'PUT', route('faqs.update', $faq->id))
                        ->attribute('data-validate')
                        ->open() !!}
                    <div class="card-body">
                        <div class="form-group">
                                {!! html()->label(__('Questions'))->for('questions')->class('form-label') !!}
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
                    </div>
                    {!! html()->closeModelForm() !!}
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
