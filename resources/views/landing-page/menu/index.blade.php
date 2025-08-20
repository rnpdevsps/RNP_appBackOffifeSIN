@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Menu Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}<< /li>
            <li class="breadcrumb-item">{{ __('Menu Settings') }}</li>
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
                                {!! html()->form('POST', route('landing.menusection1.store'))->attribute('id', 'froentend-form')->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->attribute('novalidate')->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Menu Setting Section 1') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('menu_setting_section1_enable', Utility::getsettings('menu_setting_section1_enable') == 'on',null)->class('custom-control custom-switch form-check-input input-primary')->id('appsSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Image'), 'menu_image_section1')->class('form-label') !!}
                                                {!! html()->file('menu_image_section1')->class('form-control')->id('menu_image_section1')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Name'), 'menu_name_section1')->class('form-label') !!}
                                                {!! html()->text('menu_name_section1', Utility::getsettings('menu_name_section1'))->class('form-control')->placeholder(__('Enter menu name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Bold Name'), 'menu_bold_name_section1')->class('form-label') !!}
                                                {!! html()->text('menu_bold_name_section1', Utility::getsettings('menu_bold_name_section1'))->class('form-control')->placeholder(__('Enter menu bold name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Detail'), 'menu_detail_section1')->class('form-label') !!}
                                                {!! html()->textarea('menu_detail_section1', Utility::getsettings('menu_detail_section1'))->class('form-control')->rows(3)->placeholder(__('Enter menu detail')) !!}
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
                    <div class="card">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.menusection2.store'))->attribute('id', 'froentend-form')->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->attribute('novalidate')->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Menu Setting Section 2') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('menu_setting_section2_enable', Utility::getsettings('menu_setting_section2_enable') == 'on', null)->class('custom-control custom-switch form-check-input input-primary')->id('appsSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Image'), 'menu_image_section2')->class('form-label') !!}
                                                {!! html()->file('menu_image_section2')->class('form-control')->id('menu_image_section2')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Name'), 'menu_name_section2')->class('form-label') !!}
                                                {!! html()->text('menu_name_section2', Utility::getsettings('menu_name_section2'))->class('form-control')->placeholder(__('Enter menu name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Bold Name'), 'menu_bold_name_section2')->class('form-label') !!}
                                                {!! html()->text('menu_bold_name_section2', Utility::getsettings('menu_bold_name_section2'))->class('form-control')->placeholder(__('Enter menu bold name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Detail'), 'menu_detail_section2')->class('form-label') !!}
                                                {!! html()->textarea('menu_detail_section2', Utility::getsettings('menu_detail_section2'))->class('form-control')->rows(3)->placeholder(__('Enter menu detail')) !!}
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
                    <div class="card">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.menusection3.store'))->attribute('id', 'froentend-form')->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->attribute('novalidate')->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Menu Setting Section3') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('menu_setting_section3_enable', Utility::getsettings('menu_setting_section3_enable') == 'on', null)->class('custom-control custom-switch form-check-input input-primary')->id('appsSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Image'), 'menu_image_section3')->class('form-label') !!}
                                                {!! html()->file('menu_image_section3')->class('form-control')->id('menu_image_section3')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Name'), 'menu_name_section3')->class('form-label') !!}
                                                {!! html()->text('menu_name_section3', Utility::getsettings('menu_name_section3'))->class('form-control')->placeholder(__('Enter menu name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Bold Name'), 'menu_bold_name_section3')->class('form-label') !!}
                                                {!! html()->text('menu_bold_name_section3', Utility::getsettings('menu_bold_name_section3'))->class('form-control')->placeholder(__('Enter menu bold name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Menu Detail'), 'menu_detail_section3')->class('form-label') !!}
                                                {!! html()->textarea('menu_detail_section3', Utility::getsettings('menu_detail_section3'))->class('form-control')->rows(3)->placeholder(__('Enter menu detail')) !!}
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
