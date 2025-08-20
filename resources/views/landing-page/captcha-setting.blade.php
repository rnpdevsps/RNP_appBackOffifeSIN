@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Recaptcha Setting') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Recaptcha Setting') }}</li>
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
                                {!! html()->form('POST', route('landing.captcha.store'))->id('froentend-form')->attribute('data-validate')->attribute('novalidate', true)->attribute('enctype', 'multipart/form-data')->open() !!}
                                <div class="card-header">
                                    <h5> {{ __('Captcha Setting') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label
                                                    for="contact_us_recaptcha_status" class="col-form-label">{{ __('Contact Us Recaptcha Status') }}</label>
                                                <label class="mt-2 form-switch float-end custom-switch-v1">
                                                    <input type="checkbox" name="contact_us_recaptcha_status"
                                                        id="contact_us_recaptcha_status"
                                                        class="form-check-input input-primary"
                                                        {{ Utility::getsettings('contact_us_recaptcha_status') ? 'checked' : '' }}>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    for="login_recaptcha_status" class="col-form-label">{{ __('LogIn Recaptcha Status') }}</label>
                                                <label class="mt-2 form-switch float-end custom-switch-v1">
                                                    <input type="checkbox" name="login_recaptcha_status"
                                                        id="login_recaptcha_status" class="form-check-input input-primary"
                                                        {{ Utility::getsettings('login_recaptcha_status') ? 'checked' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Recaptcha Key'))->for('recaptcha_key')->class('col-form-label') !!}
                                                {!! html()->text('recaptcha_key', Utility::getsettings('recaptcha_key'))->class('form-control')->placeholder(__('Enter recaptcha key')) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Recaptcha Secret'))->for('recaptcha_secret')->class('col-form-label') !!}
                                                {!! html()->text('recaptcha_secret', Utility::getsettings('recaptcha_secret'))->class('form-control')->placeholder(__('Enter recaptcha secret')) !!}
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
