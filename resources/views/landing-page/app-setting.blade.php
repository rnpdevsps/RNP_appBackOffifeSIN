@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('App Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('App Settings') }}</li>
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
                                {!! html()->form('POST', route('landing.app.store'))->id('froentend-form')->attribute('enctype', 'multipart/form-data')->attribute('data-validate', true)->attribute('novalidate', true)->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('App Setting') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                <input type="checkbox"
       name="apps_setting_enable"
       id="appsSettingEnableBtn"
       class="custom-control custom-switch form-check-input input-primary"
       data-onstyle="primary"
       data-toggle="switchbutton"
       {{ Utility::getsettings('apps_setting_enable') == 'on' ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('App Image'), 'apps_image')->class('form-label') !!} *
                                                {!! html()->file('apps_image')->class('form-control')->id('apps_image')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('App Multiple Image'), 'apps_multiple_image')->class('form-label') !!} *
                                                {!! html()->file('apps_multiple_image[]')->class('form-control')->id('apps_multiple_image')->attribute('multiple', 'multiple')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('App Name'), 'apps_name')->class('form-label') !!}
                                                {!! html()->text('apps_name', Utility::getsettings('apps_name'))->class('form-control')->placeholder(__('Enter app name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('App Bold Name'), 'apps_bold_name')->class('form-label') !!}
                                                {!! html()->text('apps_bold_name', Utility::getsettings('apps_bold_name'))->class('form-control')->placeholder(__('Enter app bold name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {!! html()->label(__('App Detail'), 'app_detail')->class('form-label') !!}
                                                {!! html()->textarea('app_detail', Utility::getsettings('app_detail'))->class('form-control')->rows(3)->placeholder(__('Enter app detail')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!} </div>
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
