@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Contact US Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard'))->class('') !!}</li>
            <li class="breadcrumb-item">{{ __('Contact US Settings') }}</li>
        </ul>
</div>@endsection
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
                    <div class="card">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.contactus.store'))->id('froentend-form')->attribute('data-validate', true)->attribute('novalidate', true)->attribute('enctype', 'multipart/form-data')->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Contact Us Setting') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('contactus_setting_enable',Utility::getsettings('contactus_setting_enable') == 'on',null)->class('custom-control custom-switch form-check-input input-primary')->id('startViewSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h5>{{ __('Contact Us Details') }}</h5>
                                            <div class="form-group">
                                                {!! html()->label(__('Contact Email'), 'contact_email')->class('col-form-label') !!}
                                                <div class="custom-input-group">
                                                    {!! html()->text('contact_email')->class('form-control')->placeholder(__('Enter contact email'))->value(Utility::getsettings('contact_email')) !!}
                                                </div>
                                                <p>{{ _('This email is for receive email when user submit contact us form.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! html()->label(__('Latitude'), 'latitude')->class('col-form-label') !!}
                                                    <div class="custom-input-group">
                                                        {!! html()->text('latitude')->class('form-control')->placeholder(__('Enter latitude'))->value(Utility::getsettings('latitude')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! html()->label(__('Longitude'), 'longitude')->class('col-form-label') !!}
                                                    <div class="custom-input-group">
                                                        {!! html()->text('longitude')->class('form-control')->placeholder(__('Enter longitude'))->value(Utility::getsettings('longitude')) !!}
                                                    </div>
                                                </div>
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
@endsection
