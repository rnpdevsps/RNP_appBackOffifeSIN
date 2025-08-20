@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Start View Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard'))->class('') !!}</li>
            <li class="breadcrumb-item">{{ __('Start View Settings') }}</li>
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
                                {!! html()->form('POST', route('landing.start.view.store'))->id('froentend-form')->attributes([
                                        'enctype' => 'multipart/form-data',
                                        'data-validate',
                                        'novalidate',
                                    ])->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Start Using View Setting') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('start_view_setting_enable', Utility::getsettings('start_view_setting_enable') == 'on', null)->class('custom-control custom-switch form-check-input input-primary')->id('startViewSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Start View Name'), 'start_view_name')->class('form-label') !!}
                                                {!! html()->text('start_view_name')->value(Utility::getsettings('start_view_name'))->attribute('class', 'form-control')->attribute('placeholder', __('Enter start view name')) !!} </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Start View Detail'))->for('start_view_detail')->class('form-label') !!}
                                                {!! html()->text('start_view_detail', Utility::getsettings('start_view_detail'))->class('form-control')->placeholder(__('Enter start view detail')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Start View Link Name'))->for('start_view_link_name')->class('form-label') !!}
                                                {!! html()->text('start_view_link_name', Utility::getsettings('start_view_link_name'))->class('form-control')->placeholder(__('Enter start view link name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Start View Link'))->for('start_view_link')->class('form-label') !!}
                                                {!! html()->text('start_view_link', Utility::getsettings('start_view_link'))->class('form-control')->placeholder(__('Enter start view link')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {!! html()->label(__('Image'))->for('start_view_image')->class('form-label') !!} *
                                                {!! html()->file('start_view_image')->class('form-control')->id('start_view_image')->attribute('accept', '.jpeg,.jpg,.png') !!}
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
@endsection
