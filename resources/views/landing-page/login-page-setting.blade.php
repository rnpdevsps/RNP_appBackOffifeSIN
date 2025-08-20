@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Login Setting') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard'))->class('') !!}</li>
            <li class="breadcrumb-item">{{ __('Login Setting') }}</li>
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
                    <div class="tab-content" id="pills-tabContent">
                        <div class="card">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.login.store'))->id('froentend-form')->attributes([
                                        'enctype' => 'multipart/form-data',
                                        'data-validate',
                                        'novalidate',
                                    ])->open() !!}
                                <div class="card-header">
                                    <h5> {{ __('LogIn Page Setting') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group">
                                            {!! html()->label(__('Image'))->for('login_image')->class('form-label') !!} *
                                            {!! html()->file('login_image')->class('form-control')->id('images')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                            <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Login Title'))->for('login_title')->class('form-label') !!}
                                                {!! html()->text('login_title', Utility::getsettings('login_title'))->class('form-control')->placeholder(__('Enter login title')) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Login Subtitle'))->for('login_subtitle')->class('form-label') !!}
                                                {!! html()->text('login_subtitle', Utility::getsettings('login_subtitle'))->class('form-control')->placeholder(__('Enter login subtitle')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary float-end mb-3') !!}
                                </div>
                                {!! html()->form()->close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
