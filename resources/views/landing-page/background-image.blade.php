@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Background Setting') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"> {!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Background Setting') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('landing-page.landingpage-sidebar')
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card"></div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="card">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.page.background.store'))->id('froentend-form')->attribute('enctype', 'multipart/form-data')->attribute('data-validate', true)->attribute('novalidate', true)->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h5 class="mb-0">{{ __('Background Setting') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {!! html()->label(__('Background Image'), 'background_image')->class('form-label') !!} *
                                                {!! html()->file('background_image')->class('form-control')->id('background_image')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                                    </div>
                                </div>
                                {!! html()->form()->close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
