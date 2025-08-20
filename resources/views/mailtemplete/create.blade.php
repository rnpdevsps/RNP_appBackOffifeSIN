@extends('layouts.main')
@section('title', __('Create Mail Template'))
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('Create Mail Template') }}</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"> {!! html()->a(route('home'))->text(__('Dashboard'))->class('breadcrumb-link') !!}</li>
                        <li class="breadcrumb-item">{!! html()->a(route('mailtemplate.index'))->text(__('Mail Templates'))->class('breadcrumb-link') !!}</li>
                        <li class="breadcrumb-item active">{{ __('Create Mail Template') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-6 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <h5>{{ __('Create Mail Template') }}</h5>
                </div>
                {!! html()->form('POST', route('mailtemplate.store'))->attributes(['data-validate'])->open() !!}
                <div class="card-body">
                    <div class="row">
                        <div class="mx-auto col-lg-12 col-12">
                            <div class="form-group">
                                {!! html()->label(__('Mailable'), 'mailable', ['class' => 'form-label']) !!}
                                {!! html()->text('mailable')->placeholder('App\Mail\TestMail')->class('form-control')->required() !!}
                            </div>
                            <div class="form-group">
                                {!! html()->label(__('Subject'), 'subject', ['class' => 'form-label']) !!}
                                {!! html()->text('subject')->placeholder('readonly')->class('form-control')->required() !!}
                            </div>
                            <div class="form-group">
                                {!! html()->label(__('Html Template'), 'html_template', ['class' => 'form-label']) !!}
                                {!! html()->textarea('html_template')->placeholder('Enter html template')->class('form-control')->required() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        {!! html()->a(route('mailtemplate.index'), __('Cancel'))->class('btn btn-secondary') !!}
                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                    </div>
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        CKEDITOR.replace('html_template', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
