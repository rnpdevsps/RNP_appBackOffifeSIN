@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Form Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"> {!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Form Settings') }}</li>
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
                    <div class="card">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.form.store'))->id('froentend-form')->attributes(['data-validate', 'novalidate'])->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Form Setting') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('form_setting_enable', Utility::getsettings('form_setting_enable') == 'on',null)->class('custom-control custom-switch form-check-input input-primary')->id('startViewSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Form Name'))->for('form_name')->class('form-label') !!}
                                                {!! html()->text('form_name', Utility::getsettings('form_name'))->class('form-control')->placeholder(__('Enter form name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Form Detail'))->for('form_detail')->class('form-label') !!}
                                                {!! html()->textarea('form_detail', Utility::getsettings('form_detail'))->class('form-control')->rows(3)->placeholder(__('Enter form detail')) !!}
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
