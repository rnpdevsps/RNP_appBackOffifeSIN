@php
    use App\Facades\UtilityFacades;
    $lang = \App\Facades\UtilityFacades::getValByName('default_language');
    $primaryColor = \App\Facades\UtilityFacades::getsettings('color');
    $primaryColor = Auth::user()->theme_color;
    if (isset($primaryColor)) {
        $color = $primaryColor;
    } else {
        $color = 'theme-2';
    }
    $flag = Auth::user()->color_flag;
    $roles = App\Models\Role::whereNotIn('name', ['Super Admin', 'Admin'])
        ->pluck('name', 'name')
        ->all();
@endphp

@extends('layouts.main')
@section('title', __('Settings'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Settings') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="mt-3 card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#app-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('App Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#general-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('General Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#storage-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Storage Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#pusher-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Pusher Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#social-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Social Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#email-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Email Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#captcha-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Captcha Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#seo-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('SEO Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#cache-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Cache Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#cookie-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Cookie Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#payment-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Payment Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#sms-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Sms Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#google-calender-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Google Calender Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#google-map-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Google Map Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#notification-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Notification Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#pwa-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('PWA Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#analytics-dashboard-setting"
                                class="border-0 list-group-item list-group-item-action">{{ __('Analytics Dashboard Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">

                    <div id="app-setting" class="pt-0 card">
                        {!! html()->form('POST', route('settings.app-name.update'))->attribute('enctype', 'multipart/form-data')->open() !!}
                        <div class="card-header">
                            <h5> {{ __('App Setting') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="pt-0 row">
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('App Dark Logo') }}</h5>
                                        </div>
                                        <div class="pt-0 card-body">
                                            <div class="inner-content">
                                                <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                    <a href="{{ Utility::getpath('app_dark_logo') ? Storage::url('app-logo/app-dark-logo.png') : '' }}"
                                                        target="_blank">
                                                        <img src="{{ Utility::getpath('app_dark_logo') ? Storage::url('app-logo/app-dark-logo.png') : '' }}"
                                                            id="app_dark">
                                                    </a>
                                                </div>
                                                <div class="mt-3 text-center choose-files">
                                                    <label for="app_dark_logo">
                                                        <div class="bg-primary company_logo_update"> <i
                                                                class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        {!! html()->file('app_dark_logo')->class('form-control file')->id('app_dark_logo')->attribute('onchange', "document.getElementById('app_dark').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'app_dark_logo')->accept('.jpeg,.jpg,.png,.webp,.svg') !!}
                                                    </label>
                                                </div>
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png,.webp,.svg (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('App Light Logo') }}</h5>
                                        </div>
                                        <div class="pt-0 card-body bg-primary">
                                            <div class="inner-content">
                                                <div class="py-2 mt-4 text-center logo-content light-logo-content">
                                                    <a href="{{ Utility::getpath('app_logo') ? Storage::url('app-logo/app-logo.png') : Storage::url('app-logo/78x78.png') }}"
                                                        target="_blank">
                                                        <img src="{{ Utility::getpath('app_logo') ? Storage::url('app-logo/app-logo.png') : Storage::url('app-logo/78x78.png') }}"
                                                            id="app_light">
                                                    </a>
                                                </div>
                                                <div class="mt-3 text-center choose-files">
                                                    <label for="app_logo">
                                                        <div class="company_logo_update w-logo"> <i
                                                                class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        {!! html()->file('app_logo')->class('form-control file')->id('app_logo')->attribute('onchange', "document.getElementById('app_light').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'app_logo')->accept('.jpeg,.jpg,.png,.webp,.svg') !!}
                                                    </label>
                                                </div>
                                                <small
                                                    class="text-white">{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png,.webp,.svg (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('App Favicon Logo') }}</h5>
                                        </div>
                                        <div class="pt-0 card-body">
                                            <div class="inner-content">
                                                <div class="py-2 mt-4 text-center logo-content">
                                                    <a href="{{ Utility::getpath('favicon_logo') ? Storage::url('app-logo/app-favicon-logo.png') : '' }}"
                                                        target="_blank">
                                                        <img height="35px"
                                                            src="{{ Utility::getpath('favicon_logo') ? Storage::url('app-logo/app-favicon-logo.png') : '' }}"
                                                            id="app_favicon">
                                                    </a>
                                                </div>
                                                <div class="mt-3 text-center choose-files">
                                                    <label for="favicon_logo">
                                                        <div class="bg-primary company_logo_update"> <i
                                                                class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        {!! html()->file('favicon_logo')->class('form-control file')->id('favicon_logo')->attribute('onchange', "document.getElementById('app_favicon').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'favicon_logo')->accept('.jpeg,.jpg,.png,.webp,.svg') !!}

                                                    </label>
                                                </div>
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png,.webp,.svg (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ html()->label(__('Application Name'))->class('form-label')->for('app_name') }}
                                    {!! html()->text('app_name', Utility::getsettings('app_name'))->class('form-control')->placeholder(__('Enter application name')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                            </div>
                        </div>
                        {{ html()->form()->close() }}
                    </div>

                    <div id="general-setting" class="">
                        {!! html()->form('POST', route('settings.auth-settings.update'))->novalidate()->attribute('data-validate')->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <h5>{{ __('General Settings') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <strong class="d-block">{{ __('Two Factor Authentication') }}</strong>
                                                {{ !Utility::getsettings('2fa') ? __('Activate') : __('Deactivate') }}
                                                {{ __('Two Factor Authentication For Application') }}
                                            </div>
                                            <div class="col-sm-4 form-check form-switch custom-switch-v1">
                                                <label class="mt-2 custom-switch float-end">
                                                    {!! html()->checkbox('two_factor_auth')->checked(Utility::getsettings('2fa') ? true : false)->value('on')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <strong class="d-block">{{ __('Email Verification') }}</strong>
                                                {{ Utility::getsettings('email_verification') == '1' ? __('Activate') : __('Deactivate') }}
                                                {{ __('Email Verification For Application') }}
                                            </div>
                                            <div class="col-sm-4 form-check form-switch custom-switch-v1">
                                                <label class="mt-2 custom-switch float-end">
                                                    {!! html()->checkbox('email_verification')->checked(Utility::getsettings('email_verification') == '1')->value('on')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->class('custom-control custom-switch form-check-input input-primary') !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <strong class="d-block">{{ __('Sms Verification') }}</strong>
                                                {{ Utility::getsettings('sms_verification') == 0 ? __('Activate') : __('Deactivate') }}
                                                {{ __('Sms Verification For Application') }}
                                            </div>
                                            <div class="col-sm-4 form-check form-switch custom-switch-v1">
                                                <label class="mt-2 custom-switch float-end">
                                                    {!! html()->checkbox('sms_verification')->checked(Utility::getsettings('sms_verification') == '1')->value('on')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->class('form-check-input input-primary custom-control custom-switch') !!}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <strong class="d-block">{{ __('RTL Setting') }}</strong>
                                                {{ Utility::getsettings('rtl') == '0' ? __('Deactivate') : __('Activate') }}
                                                {{ __('RTL Setting For Application') }}
                                            </div>
                                            <div class="col-sm-4 form-check form-switch custom-switch-v1">
                                                <label class="mt-2 custom-switch float-end">
                                                    {!! html()->checkbox('rtl_setting')->checked(Utility::getsettings('rtl') == '1')->value('on')->attribute('id', 'site_rtl')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->class('custom-control custom-switch form-check-input input-primary') !!}

                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <strong class="d-block">{{ __('Register') }}</strong>
                                                {{ Utility::getsettings('register') == '1' ? __('Activate') : __('Deactivate') }}
                                                {{ __('Register For Application') }}
                                            </div>
                                            <div class="col-sm-4 form-check form-switch custom-switch-v1">
                                                <label class="mt-2 custom-switch float-end">
                                                    {!! html()->checkbox('register')->checked(Utility::getsettings('register') == '1')->value('on')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->class('custom-control custom-switch form-check-input input-primary') !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <strong class="d-block">{{ __('Landing Page') }}</strong>
                                                {{ Utility::getsettings('landing_page') == '1' ? __('Activate') : __('Deactivate') }}
                                                {{ __('LandingPage For Application') }}
                                            </div>
                                            <div class="col-sm-4 form-check form-switch custom-switch-v1">
                                                <label class="mt-2 custom-switch float-end">
                                                    {!! html()->checkbox('landing_page')->checked(Utility::getsettings('landing_page') == '1')->value('on')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->class('custom-control custom-switch form-check-input input-primary') !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 col-sm-12">
                                        <div class="form-group d-flex align-items-center row">
                                            <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                            <div class="setting-card setting-logo-box">
                                                <div class="row">
                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2">
                                                            <i data-feather="credit-card"
                                                                class="me-2"></i>{{ __('Primary color settings') }}
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="color-wrp">
                                                            <div class="theme-color themes-color">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                                    data-value="theme-1"
                                                                    onclick="check_theme('theme-1')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-1">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                                    data-value="theme-2"
                                                                    onclick="check_theme('theme-2')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-2">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                                    data-value="theme-3"
                                                                    onclick="check_theme('theme-3')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-3">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                                    data-value="theme-4"
                                                                    onclick="check_theme('theme-4')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-4">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                                    data-value="theme-5"
                                                                    onclick="check_theme('theme-5')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-5">
                                                                <br>
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                                    data-value="theme-6"
                                                                    onclick="check_theme('theme-6')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-6">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                                    data-value="theme-7"
                                                                    onclick="check_theme('theme-7')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-7">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                                    data-value="theme-8"
                                                                    onclick="check_theme('theme-8')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-8">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                                    data-value="theme-9"
                                                                    onclick="check_theme('theme-9')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-9">
                                                                <a href="#!"
                                                                    class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                                    data-value="theme-10"
                                                                    onclick="check_theme('theme-10')"></a>
                                                                <input type="radio" class="theme_color d-none"
                                                                    name="color" value="theme-10">
                                                            </div>
                                                            <div class="color-picker-wrp">
                                                                <input type="color" value="{{ $color ? $color : '' }}"
                                                                    class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}"
                                                                    name="custom_color" id="color-picker">
                                                                <input type='hidden' name="color_flag"
                                                                    value={{ isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2">
                                                            <i data-feather="layout"
                                                                class="me-2"></i>{{ __('Sidebar settings') }}
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="form-check form-switch">
                                                            {!! html()->checkbox('transparent_layout')->value('on')->checked(Utility::getsettings('transparent_layout') == 'on')->attribute('data-onstyle', 'primary')->id('cust-theme-bg')->class('form-check-input') !!}

                                                            {!! html()->label(__('Transparent layout'), 'cust-theme-bg')->class('form-check-label f-w-600 pl-1') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                                        <h6 class="mt-2">
                                                            <i data-feather="sun"
                                                                class="me-2"></i>{{ __('Layout settings') }}
                                                        </h6>
                                                        <hr class="my-2" />
                                                        <div class="mt-2 form-check form-switch">
                                                            {!! html()->checkbox('dark_mode')->value('on')->checked(Utility::getsettings('dark_mode') == 'on')->id('cust-darklayout')->class('form-check-input') !!}
                                                            {!! html()->label(__('Dark Layout'), 'cust-darklayout')->class('form-check-label f-w-600 pl-1') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @if (!extension_loaded('imagick'))
                                        <small>
                                            {{ __('Note: for 2FA your server must have Imagick.') }}
                                            {!! html()->a('https://www.php.net/manual/en/book.imagick.php', __('Imagick Document'))->target('_blank') !!}
                                        </small>
                                    @endif
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    {!! html()->label(__('Default Language'), 'default_language')->class('form-label') !!}

                                                    {!! html()->select('default_language', $languages, $lang)->attribute('data-trigger', true)->id('choices-single-default')->placeholder(__('This is a search placeholder'))->class('form-control form-control-inline-block') !!}
                                                </div>
                                                <div class="form-group">
                                                    {!! html()->label(__('Date Format'), 'date_format')->class('form-label') !!}
                                                    <select name="date_format" class="form-select" data-trigger>
                                                        <option value="M j, Y"
                                                            {{ Utility::getsettings('date_format') == 'M j, Y' ? 'selected' : '' }}>
                                                            {{ __('Jan 1, 2020') }}</option>
                                                        <option value="d-M-y"
                                                            {{ Utility::getsettings('date_format') == 'd-M-y' ? 'selected' : '' }}>
                                                            {{ __('01-Jan-20') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    {!! html()->label(__('Time Format'), 'time_format')->class('form-label') !!}
                                                    <select name="time_format" class="form-select" data-trigger>
                                                        <option value="g:i A"
                                                            {{ Utility::getsettings('time_format') == 'g:i A' ? 'selected' : '' }}>
                                                            {{ __('hh:mm AM/PM') }}</option>
                                                        <option value="H:i:s"
                                                            {{ Utility::getsettings('time_format') == 'H:i:s' ? 'selected' : '' }}>
                                                            {{ __('HH:mm:ss') }}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Social Login Role') }}</label>
                                                    {!! html()->select('roles', $roles, Utility::getsettings('roles'))->class('form-control')->attribute('data-trigger', true) !!}
                                                    <div class="invalid-feedback">
                                                        {{ __('Role is required') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    {!! html()->label(__('Gtag Tracking ID'), 'gtag')->class('form-label') !!}
                                                    {!! html()->a('https://support.google.com/analytics/answer/1008080?hl=en#zippy=%2Cin-this-article', __('Document'))->class('m-2')->target('_blank') !!}
                                                    </label>
                                                    {!! html()->text('gtag', Utility::getsettings('gtag'))->class('form-control')->placeholder(__('Enter gtag tracking id')) !!}
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
                        </div>
                        {!! html()->form()->close() !!}
                    </div>

                    <div id="storage-setting" class="">
                        {!! html()->form('POST', route('settings.wasabi-setting.update'))->attribute('data-validate', true)->attribute('novalidate', true)->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <h5> {{ __('Storage Settings') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="pb-3">
                                    <p class="text-danger">
                                        {{ __('Note :- If you Add S3 & wasabi Storage settings then you have to store all images First.') }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    {!! html()->radio('storage_type', Utility::getsettings('storage_type') == 'local', 'local')->class('btn-check')->id('localsetting') !!}
                                    {!! html()->label(__('Local'), 'localsetting')->class('btn btn-outline-primary') !!}

                                    {!! html()->radio('storage_type', Utility::getsettings('storage_type') == 's3', 's3')->class('btn-check')->id('s3setting') !!}
                                    {!! html()->label(__('S3 setting'), 's3setting')->class('btn btn-outline-primary') !!}

                                    {!! html()->radio('storage_type', Utility::getsettings('storage_type') == 'wasabi', 'wasabi')->class('btn-check')->id('wasabisetting') !!}
                                    {!! html()->label(__('Wasabi'), 'wasabisetting')->class('btn btn-outline-primary') !!}
                                </div>
                                <div id="s3"
                                    class="desc {{ Utility::getsettings('storage_type') == 's3' ? 'block' : 'd-none' }}">
                                    <div class="">
                                        <div class="row">
                                            <div class="form-group">
                                                {!! html()->label(__('S3 Key'))->for('s3_key')->class('form-label') !!}
                                                {!! html()->text('s3_key', Utility::getsettings('s3_key'))->placeholder(__('Enter s3 key'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('S3 Secret'))->for('s3_secret')->class('form-label') !!}
                                                {!! html()->text('s3_secret', Utility::getsettings('s3_secret'))->placeholder(__('Enter s3 secret'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('S3 Region'))->for('s3_region')->class('form-label') !!}
                                                {!! html()->text('s3_region', Utility::getsettings('s3_region'))->placeholder(__('Enter s3 region'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('S3 Bucket'))->for('s3_bucket')->class('form-label') !!}
                                                {!! html()->text('s3_bucket', Utility::getsettings('s3_bucket'))->placeholder(__('Enter s3 bucket'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('S3 URL'))->for('s3_url')->class('form-label') !!}
                                                {!! html()->text('s3_url', Utility::getsettings('s3_url'))->placeholder(__('Enter s3 URL'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('S3 Endpoint'))->for('s3_endpoint')->class('form-label') !!}
                                                {!! html()->text('s3_endpoint', Utility::getsettings('s3_endpoint'))->placeholder(__('Enter s3 endpoint'))->class('form-control') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="wasabi"
                                    class="desc {{ Utility::getsettings('storage_type') == 'wasabi' ? 'block' : 'd-none' }}">
                                    <div class="">
                                        <div class="row">
                                            <div class="form-group">
                                                {!! html()->label(__('Wasabi Key'))->for('wasabi_key')->class('form-label') !!}
                                                {!! html()->text('wasabi_key', Utility::getsettings('wasabi_key'))->placeholder(__('Enter Wasabi key'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Wasabi Secret'))->for('wasabi_secret')->class('form-label') !!}
                                                {!! html()->text('wasabi_secret', Utility::getsettings('wasabi_secret'))->placeholder(__('Enter Wasabi Secret'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Wasabi Region'))->for('wasabi_region')->class('form-label') !!}
                                                {!! html()->text('wasabi_region', Utility::getsettings('wasabi_region'))->placeholder(__('Enter Wasabi region'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Wasabi Bucket'))->for('wasabi_bucket')->class('form-label') !!}
                                                {!! html()->text('wasabi_bucket', Utility::getsettings('wasabi_bucket'))->placeholder(__('Enter Wasabi bucket'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Wasabi URL'))->for('wasabi_url')->class('form-label') !!}
                                                {!! html()->text('wasabi_url', Utility::getsettings('wasabi_url'))->placeholder(__('Enter Wasabi URL'))->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Wasabi Endpoint'))->for('wasabi_root')->class('form-label') !!}
                                                {!! html()->text('wasabi_root', Utility::getsettings('wasabi_root'))->placeholder(__('Enter Wasabi endpoint'))->class('form-control') !!}
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
                        </div>
                        {!! html()->form()->close() !!}
                    </div>
                    <div id="pusher-setting" class="">
                        {!! html()->form('POST', route('settings.pusher-setting.update'))->id('pusher-setting-form')->attributes([
                                'data-validate' => true,
                                'novalidate' => true,
                            ])->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <h5> {{ __('Pusher Setting') }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted"> {{ __('Pusher Setting') }}
                                    {!! html()->a('https://pusher.com/', __('Document'))->class('m-2')->target('_blank') !!}
                                </p>
                                <div class="">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Pusher App ID'), 'pusher_id')->class('form-label') !!}
                                                {!! html()->text('pusher_id', Utility::getsettings('pusher_id'))->placeholder(__('Enter pusher app id'))->required()->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Pusher Key'), 'pusher_key')->class('form-label') !!}
                                                {!! html()->text('pusher_key', Utility::getsettings('pusher_key'))->placeholder(__('Enter pusher key'))->required()->class('form-control') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Pusher Secret'), 'pusher_secret')->class('form-label') !!}
                                                {!! html()->text('pusher_secret', Utility::getsettings('pusher_secret'))->placeholder(__('Enter pusher secret'))->required()->class('form-control') !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Pusher Cluster'), 'pusher_cluster')->class('form-label') !!}
                                                {!! html()->text('pusher_cluster', Utility::getsettings('pusher_cluster'))->placeholder(__('Enter pusher cluster'))->required()->class('form-control') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    {!! html()->label(__('Status'), 'pusher_status')->class('form-label') !!}
                                                </div>
                                                <div class="col-md-4 form-check form-switch">
                                                    {!! html()->checkbox('pusher_status', Utility::getsettings('pusher_status') ? true : false, null)->class('form-check-input float-end')->id('pusher_status') !!}
                                                    <span class="custom-switch-indicator"></span>
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
                        </div>
                        {!! html()->form()->close() !!}
                    </div>

                    <div id="social-setting" class="faq">
                        {!! html()->form('POST', route('settings/social-setting/update'))->method('POST')->attributes(['data-validate' => true])->attributes(['novalidate' => true])->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <h5> {{ __('Social Settings') }}</h5>
                            </div>
                            <div class="p-4 card-body">
                                <div class="mt-3 row">
                                    <div class="col-md-12">
                                        <div class="accordion accordion-flush" id="accordionExamples">
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="google">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseone"
                                                        aria-expanded="true" aria-controls="collapseone">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-brand-google text-primary"></i>
                                                            {{ __('Google') }}
                                                        </span>
                                                        @if (Utility::getsettings('googlesetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapseone" class="accordion-collapse collapse"
                                                    aria-labelledby="google" data-bs-parent="#accordionExamples">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">
                                                            <small
                                                                class="">{{ __('How To Enable Login With Google') }}
                                                                {!! html()->a(Storage::url('pdf/login with google.pdf'), __('Document'))->class('m-2')->target('_blank') !!}
                                                            </small>
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('socialsetting[]', 'google', Utility::getsettings('googlesetting') == 'on')->value('google')->checked(Utility::getsettings('googlesetting') == 'on')->class('form-check-input')->id('googlesetting') !!}
                                                                {!! html()->label(__('Enable'), 'googlesetting')->class('form-check-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="form-group">
                                                                {!! html()->label(__('Google Client Id'), 'google_client_id')->class('form-label') !!}
                                                                {!! html()->text('google_client_id', Utility::getsettings('google_client_id') ?: '')->placeholder(__('Enter google client id'))->class('form-control') !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! html()->label(__('Google Client Secret'), 'google_client_secret')->class('form-label') !!}
                                                                {!! html()->text('google_client_secret', Utility::getsettings('google_client_secret') ?: '')->placeholder(__('Enter google client secret'))->class('form-control') !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! html()->label(__('Google Redirect Url'), 'google_redirect')->class('form-label') !!}
                                                                {!! html()->text('google_redirect', Utility::getsettings('google_redirect') ?: '')->placeholder(__('https://demo.test.com/callback/google'))->class('form-control') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="facebook">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsetwo"
                                                        aria-expanded="true" aria-controls="collapsetwo">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-brand-facebook text-primary"></i>
                                                            {{ __('Facebook') }}
                                                        </span>
                                                        @if (Utility::getsettings('facebooksetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsetwo" class="accordion-collapse collapse"
                                                    aria-labelledby="facebook" data-bs-parent="#accordionExamples">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">
                                                            <small
                                                                class="">{{ __('How To Enable Login With Facebook') }}
                                                                {!! html()->a(Storage::url('pdf/login with facebook.pdf'), __('Document'))->class('m-2')->target('_blank') !!}
                                                            </small>
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="checkbox" name="socialsetting[]"
                                                                    value="facebook"
                                                                    {{ Utility::getsettings('facebooksetting') == 'on' ? 'checked' : '' }}
                                                                    class="form-check-input" id="facebooksetting">
                                                                {!! html()->label(__('Enable'), 'cust-theme-bg')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="form-group">
                                                                {!! html()->label(__('Facebook Client Id'), 'facebook_client_id')->class('form-label') !!}
                                                                {!! html()->text('facebook_client_id', Utility::getsettings('facebook_client_id') ?: '')->placeholder(__('Enter facebook client id'))->class('form-control') !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! html()->label(__('Facebook Client Secret'), 'facebook_client_secret')->class('form-label') !!}
                                                                {!! html()->text('facebook_client_secret', Utility::getsettings('facebook_client_secret') ?: '')->placeholder(__('Enter facebook client secret'))->class('form-control') !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! html()->label(__('Facebook Redirect Url'), 'facebook_redirect')->class('form-label') !!}
                                                                {!! html()->text('facebook_redirect', Utility::getsettings('FACEBOOK_REDIRECT') ?: '')->placeholder(__('https://demo.test.com/callback/facebook'))->class('form-control') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="github">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsethree"
                                                        aria-expanded="true" aria-controls="collapsethree">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-brand-github text-primary"></i>
                                                            {{ __('Github') }}
                                                        </span>
                                                        @if (Utility::getsettings('githubsetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsethree" class="accordion-collapse collapse"
                                                    aria-labelledby="github" data-bs-parent="#accordionExamples">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-between">
                                                            <small
                                                                class="">{{ __('How To Enable Login With Github') }}
                                                                {!! html()->a(Storage::url('pdf/login with github.pdf'), __('Document'))->class('m-2')->target('_blank') !!}
                                                            </small>
                                                            <div class="form-check form-switch d-inline-block">

                                                                {!! html()->checkbox('socialsetting[]', 'github', Utility::getsettings('githubsetting') == 'on')->value('github')->checked(Utility::getsettings('githubsetting') == 'on')->class('form-check-input')->id('githubsetting') !!}
                                                                {!! html()->label(__('Enable'), 'githubsetting')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="form-group">
                                                                {!! html()->label(__('Github Client Id'), 'github_client_id')->class('form-label') !!}
                                                                {!! html()->text('github_client_id', Utility::getsettings('github_client_id') ?: '')->placeholder(__('Enter github client id'))->class('form-control') !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! html()->label(__('Github Client Secret'), 'github_client_secret')->class('form-label') !!}
                                                                {!! html()->text('github_client_secret', Utility::getsettings('github_client_secret') ?: '')->placeholder(__('Enter github client secret'))->class('form-control') !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! html()->label(__('Github Redirect Url'), 'github_redirect')->class('form-label') !!}
                                                                {!! html()->text('github_redirect', Utility::getsettings('github_redirect') ?: '')->placeholder(__('https://demo.test.com/callback/github'))->class('form-control') !!}
                                                            </div>
                                                        </div>
                                                    </div>
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
                        </div>
                        {!! html()->form()->close() !!}

                    </div>

                    <div id="email-setting" class="">
                        {!! html()->form('POST', route('settings.email-setting.update'))->attribute('data-validate')->attribute('novalidate')->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <h5> {{ __('Email Settings') }}</h5>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">
                                            {!! html()->checkbox('email_setting_enable', UtilityFacades::getsettings('email_setting_enable') == 'on' ? true : false)->class('custom-control custom-switch form-check-input input-primary')->id('emailSettingEnableBtn')->attributes([
                                                    'data-onstyle' => 'primary',
                                                    'data-toggle' => 'switchbutton',
                                                ]) !!}
                                            <small
                                                class="mt-2 text-end d-flex">{{ __('Please turn on this Email enable button.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body emailSettingEnableBtn">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {!! html()->label(__('Mail Mailer'), 'mail_mailer')->class('form-label') !!}
                                            {!! html()->text('mail_mailer', UtilityFacades::getsettings('mail_mailer') ?: '')->placeholder(__('Enter mail mailer'))->class('form-control') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Mail Host'), 'mail_host')->class('form-label') !!}
                                            {!! html()->text('mail_host', UtilityFacades::getsettings('mail_host') ?: '')->placeholder(__('Enter mail host'))->class('form-control') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Mail Username'), 'mail_username')->class('form-label') !!}
                                            {!! html()->text('mail_username', UtilityFacades::getsettings('mail_username') ?: '')->placeholder(__('Enter mail username'))->class('form-control') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Mail Encryption'), 'mail_encryption')->class('form-label') !!}
                                            {!! html()->text('mail_encryption', UtilityFacades::getsettings('mail_encryption') ?: '')->placeholder(__('Enter mail encryption'))->class('form-control') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {!! html()->label(__('Mail From Name'), 'mail_from_name')->class('form-label') !!}
                                            {!! html()->text('mail_from_name', UtilityFacades::getsettings('mail_from_name') ?: '')->placeholder(__('Enter mail from name'))->class('form-control') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Mail Port'), 'mail_port')->class('form-label') !!}
                                            {!! html()->text('mail_port', UtilityFacades::getsettings('mail_port') ?: '')->placeholder(__('Enter mail port'))->class('form-control') !!}

                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Mail Password'), 'mail_password')->class('form-label') !!}
                                            <input class="form-control"
                                                value="{{ UtilityFacades::getsettings('mail_password') }}"
                                                placeholder="{{ __('Enter mail password') }}" name="mail_password"
                                                type="password" id="mail_password">
                                        </div>

                                        <div class="form-group">
                                            {!! html()->label(__('Mail From Address'), 'mail_from_address')->class('form-label') !!}
                                            {!! html()->text('mail_from_address', UtilityFacades::getsettings('mail_from_address') ?: '')->placeholder(__('Enter mail from address'))->class('form-control') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="">
                                    {!! html()->button(__('Send Test Mail'))->type('button')->class('btn btn-info send_mail d-inline float-start')->attributes(['data-action' => route('test.mail'), 'id' => 'test-mail']) !!}
                                    {!! html()->button(__('Save'))->type('submit')->class('mt-2 btn btn-primary float-end') !!}
                                </div>
                            </div>
                        </div>
                        {!! html()->form()->close() !!}
                    </div>


                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Email Setting App') }}</h5>
                        </div>
                        {!! html()->form('POST', route('settings.emailapp-setting.update'))->attribute('data-validate')->attribute('novalidate')->open() !!}

                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="form-group">
                                {!! html()->label(__('Recipient Email'), 'email[]')->class('form-label') !!}
                                {!! html()->text('email[]', UtilityFacades::getsettings('email') ?: '')->placeholder(__('Enter recipient email'))->class('form-control') !!}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                {!! html()->label(__('Cc Emails (Optional)'), 'ccemail[]')->class('form-label') !!}
                                {!! html()->text('ccemail[]', UtilityFacades::getsettings('ccemail') ?: '')->placeholder(__('Enter recipient cc email'))->class('form-control inputtags') !!}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! html()->label(__('Bcc Emails (Optional)'), 'bccemail[]')->class('form-label') !!}
                                    {!! html()->text('bccemail[]', UtilityFacades::getsettings('bccemail') ?: '')->placeholder(__('Enter recipient bcc email'))->class('form-control inputtags') !!}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {!! html()->button(__('Save'), 'submit')->class('btn btn-primary') !!}
                            </div>
                        </div>
                        {!! html()->form()->close() !!}
                    </div>


                    <div id="captcha-setting" class="">

                        {!! html()->form('POST', route('settings.captcha-setting.update'))->attribute('data-validate')->attribute('novalidate')->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <div class="row d-flex align-items-center">
                                    <div class="col-6 d-flex justify-content-start">
                                        <h5>{{ __('Capcha Settings') }}</h5>
                                    </div>

                                    <div class="col-6 text-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">
                                            {!! html()->checkbox('captcha_enable', UtilityFacades::getsettings('captcha_enable') == 'on', 'on')->class('custom-control custom-switch form-check-input input-primary')->id('captchaEnableButton')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            <small
                                                class="mt-2 text-end d-flex">{{ __('Please turn on this Captcha Enable button.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="card-body captchaSetting {{ UtilityFacades::getsettings('captcha_enable') == 'on' ? '' : 'd-none' }}">
                                <div class="row" id="captchaSetting">
                                    <div class="form-group">
                                        {!! html()->radio('captcha', Utility::getsettings('captcha') == 'recaptcha', 'recaptcha')->class('btn-check')->id('recaptchaSetting') !!}
                                        {!! html()->label(__('Recaptcha setting'), 'recaptchaSetting')->class('btn btn-outline-primary') !!}

                                        {!! html()->radio('captcha', Utility::getsettings('captcha') == 'hcaptcha', 'hcaptcha')->class('btn-check')->id('hcaptchaSetting') !!}
                                        {!! html()->label(__('Hcaptcha setting'), 'hcaptchaSetting')->class('btn btn-outline-primary') !!}
                                    </div>
                                    <div id="recaptcha" class="desc {{ Utility::getsettings('captcha') == 'recaptcha' ? '' : 'd-none' }}">
                                        <p class="text-muted"> {{ __('Recaptcha Setting') }}
                                            {!! html()->a('https://www.google.com/recaptcha/admin', __('Document'))->class('m-2')->target('_blank') !!}
                                        </p>
                                        <div class="row">
                                            <div class="form-group">
                                                {!! html()->label(__('Recaptcha Key'), 'recaptcha_key')->class('form-label') !!}
                                                {!! html()->text('recaptcha_key', Utility::getsettings('captcha_sitekey'))->class('form-control')->placeholder(__('Enter recaptcha key')) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Recaptcha Secret'), 'recaptcha_secret')->class('form-label') !!}
                                                {!! html()->text('recaptcha_secret', Utility::getsettings('captcha_secret'))->class('form-control')->placeholder(__('Enter recaptcha secret')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div id="hcaptcha" class="desc {{ Utility::getsettings('captcha') == 'hcaptcha' ? '' : 'd-none' }}">
                                        <p class="text-muted"> {{ __('Hcaptcha Setting') }}
                                            {!! html()->a('https://docs.hcaptcha.com/switch', __('Document'))->class('m-2')->target('_blank') !!}
                                        </p>
                                        <div class="row">
                                            <div class="form-group">
                                                {!! html()->label(__('Hcaptcha Key'), 'hcaptcha_key')->class('form-label') !!}
                                                {!! html()->text('hcaptcha_key', Utility::getsettings('hcaptcha_sitekey'))->class('form-control')->placeholder(__('Enter hcaptcha key')) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! html()->label(__('Hcaptcha Secret'), 'hcaptcha_secret')->class('form-label') !!}
                                                {!! html()->text('hcaptcha_secret', Utility::getsettings('hcaptcha_secret'))->class('form-control')->placeholder(__('Enter hcaptcha secret')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {!! html()->button(__('Save'), 'submit')->class('btn btn-primary') !!}
                                </div>
                            </div>
                        </div>
                        {!! html()->form()->close() !!}
                    </div>

                    <div id="seo-setting" class="pt-0 card">
                        {!! html()->form('POST', route('settings.seo-setting.update'))->attribute('enctype', 'multipart/form-data')->open() !!}
                        <div class="justify-content-between card-header d-flex align-items-center">
                            <h5> {{ __('SEO Setting') }}</h5>
                            <div class="d-flex align-items-center text-end">
                                <div class="custom-control custom-switch" onclick="enableseo()">
                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                        name="seo_setting" class="form-check-input input-primary " id="seo_setting"
                                        {{ Utility::getsettings('seo_setting') == 'on' ? ' checked ' : '' }}>
                                    <label class="mb-1 custom-control-label" for="seo_setting"></label>
                                    <small
                                        class="mt-2 text-end d-flex">{{ __('Please turn on this SEO Enable button.') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row seoDiv">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Meta Title'), 'meta_title')->class('col-form-label') !!}
                                        {!! html()->text('meta_title', Utility::getsettings('meta_title') ?: '')->placeholder(__('Enter meta title'))->class('form-control') !!}
                                    </div>
                                    <div class="form-group">
                                        {!! html()->label(__('Meta Keywords'), 'meta_keywords')->class('col-form-label') !!}
                                        {!! html()->text('meta_keywords', Utility::getsettings('meta_keywords') ?: '')->id('choices-text-remove-button')->class('form-control')->attribute('data-placeholder', __('Enter meta keywords')) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! html()->label(__('Meta Description'), 'meta_description')->class('form-label') !!}
                                        {!! html()->textarea('meta_description', Utility::getsettings('meta_description') ?: '')->class('form-control')->rows(5)->placeholder(__('Enter meta description')) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Meta Image'), 'meta_image')->class('col-form-label ms-4') !!}
                                        <div class="pt-0 card-body">
                                            <div class="setting-card">
                                                <div class="seo-image-content">
                                                    <a href="{{ Utility::getsettings('meta_image') ? Storage::url(Utility::getsettings('meta_image')) : Storage::url('seo-image/meta-image.jpg') }}"
                                                        target="_blank">
                                                        <img id="meta"
                                                            src="{{ Utility::getsettings('meta_image') ? Storage::url(Utility::getsettings('meta_image')) : Storage::url('seo-image/meta-image.jpg') }}"
                                                            width="250px">
                                                    </a>
                                                </div>
                                                <div class="mt-4 choose-files">
                                                    <label for="meta_image">
                                                        <div class="bg-primary logo"> <i
                                                                class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input style="margin-top: -40px;" type="file"
                                                            class="form-control file" name="meta_image" id="meta_image"
                                                            data-filename="meta_image"
                                                            onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])"
                                                            accept=".jpeg,.jpg,.png">
                                                    </label>
                                                </div>
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {!! html()->button(__('Save'), 'submit')->class('btn btn-primary') !!}
                            </div>
                        </div>
                        {!! html()->form()->close() !!}
                    </div>

                    <div id="cache-setting" class="">
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <h5>{{ __('Cache Setting') }}</h5>
                                <small>
                                    {{ __('This is a page meant for more advanced users, simply ignore it if you don\'tunderstand what cache is.') }}
                                </small>
                            </div>
                            {!! html()->form('POST', route('config.cache'))->attribute('data-validate')->open() !!}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Current cache size'), 'cache_size')->class('form-label') !!}
                                            <div class="input-group">
                                                {!! html()->text('cache_size', Utility::GetCacheSize())->class('form-control')->attribute('readonly')->placeholder(__('Enter cache size'))->id('cache_size') !!}
                                                <span class="input-group-text">{{ __('MB') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {!! html()->button(__('Save'), 'submit')->class('btn btn-primary') !!}
                                </div>
                            </div>
                            {!! html()->form()->close() !!}
                        </div>
                    </div>

                    <div id="cookie-setting" class="">
                        {!! html()->form('POST', route('settings.cookie-setting.update'))->attribute('data-validate')->attribute('novalidate')->open() !!}
                        <div class="card">
                            <div class="justify-content-between card-header d-flex align-items-center">
                                <h5> {{ __('Cookie Setting') }}</h5>
                                <div class="d-flex align-items-center text-end">
                                    <div class="custom-control custom-switch" onclick="enablecookie()">
                                        <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                            name="enable_cookie" class="form-check-input input-primary "
                                            id="enable_cookie"
                                            {{ Utility::getsettings('enable_cookie') == 'on' ? ' checked ' : '' }}>
                                        <label class="mb-1 custom-control-label" for="enable_cookie"></label>
                                        <small
                                            class="mt-2 text-end d-flex">{{ __('Please turn on this Cookie Enable button.') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row cookieDiv">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                            <input type="checkbox" name="cookie_logging"
                                                class="form-check-input input-primary" id="cookie_logging"
                                                onclick="enableButton()"
                                                {{ Utility::getsettings('cookie_logging') == 'on' ? ' checked ' : '' }}>
                                            <label class="form-check-label"
                                                for="cookie_logging">{{ __('Enable logging') }}</label>
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Cookie Title'), 'cookie_title')->class('col-form-label') !!}
                                            {!! html()->text('cookie_title', Utility::getsettings('cookie_title'))->class('form-control')->placeholder(__('Enter cookie title')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Cookie Description'), 'cookie_description')->class('form-label') !!}
                                            {!! html()->textarea('cookie_description', Utility::getsettings('cookie_description'))->class('form-control')->placeholder(__('Enter cookie description'))->rows(3) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch custom-switch-v1 ">
                                            <input type="checkbox" name="necessary_cookies"
                                                class="form-check-input input-primary" id="necessary_cookies" checked
                                                onclick="return false">
                                            <label class="form-check-label"
                                                for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Strictly Cookie Title'), 'strictly_cookie_title')->class('col-form-label') !!}
                                            {!! html()->text('strictly_cookie_title', Utility::getsettings('strictly_cookie_title'))->class('form-control')->placeholder(__('Enter strictly cookie description')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Strictly Cookie Description'), 'strictly_cookie_description')->class('form-label') !!}
                                            {!! html()->textarea('strictly_cookie_description', Utility::getsettings('strictly_cookie_description'))->class('form-control')->placeholder(__('Enter strictly cookie description'))->rows(3) !!}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h5>{{ __('More Information') }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            {!! html()->label(__('Contact Us Description'), 'more_information_description')->class('col-form-label') !!}
                                            {!! html()->text('more_information_description', Utility::getsettings('more_information_description'))->class('form-control')->placeholder(__('Enter more information description')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! html()->label(__('Contact Us URL'), 'contactus_url')->class('col-form-label') !!}
                                            {!! html()->text('contactus_url', Utility::getsettings('contactus_url'))->class('form-control')->placeholder(__('Enter contact URL')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Download cookie accepted data') }}</label>
                                            <a href="{{ Storage::url('seo-image/cookie-data.csv') }}"
                                                class="mr-2 btn btn-primary">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {!! html()->button(__('Save'), 'submit')->class('btn btn-primary') !!}
                                </div>
                            </div>
                        </div>
                        {!! html()->form()->close() !!}
                    </div>
                    <div id="payment-setting" class="faq">
                        {!! html()->form('POST', route('settings/stripe-setting/update'))->attribute('data-validate')->attribute('novalidate')->open() !!}
                        <div class="card" id="settings-card">
                            <div class="card-header">
                                <h5> {{ __('Payment Settings') }}</h5>
                            </div>
                            <div class="p-4 card-body">
                                <div class="mt-3 row">
                                    <div class="col-md-12">
                                        <div class="accordion accordion-flush" id="accordionExample">
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="stripe">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse1"
                                                        aria-expanded="true" aria-controls="collapse1">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Stripe') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('stripesetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse1" class="accordion-collapse collapse"
                                                    aria-labelledby="stripe" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('stripesetting') == 'on', 'stripe')->class('form-check-input')->id('is_stripe_enable') !!}
                                                                {!! html()->label(__('Enable'), 'is_stripe_enable')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">

                                                                    {!! html()->label(__('Stripe Key'), 'stripe_key')->class('form-label') !!}
                                                                    {!! html()->text('stripe_key', UtilityFacades::getsettings('stripe_key'))->class('form-control')->placeholder(__('Enter stripe key')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Stripe Secret'), 'stripe_secret')->class('form-label') !!}
                                                                    {!! html()->text('stripe_secret', UtilityFacades::getsettings('stripe_secret'))->class('form-control')->placeholder(__('Enter stripe secret')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="razorpay">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse2"
                                                        aria-expanded="true" aria-controls="collapse2">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Razorpay') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('razorpaysetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse2" class="accordion-collapse collapse"
                                                    aria-labelledby="razorpay" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('razorpaysetting') == 'on', 'razorpay')->class('form-check-input')->id('is_razorpay_enable') !!}
                                                                {!! html()->label(__('Enable'), 'is_razorpay_enable')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Razorpay Key'), 'razorpay_key')->class('form-label') !!}
                                                                    {!! html()->text('razorpay_key', UtilityFacades::getsettings('razorpay_key'))->class('form-control')->placeholder(__('Enter razorpay key')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Razorpay Secret'), 'razorpay_secret')->class('form-label') !!}
                                                                    {!! html()->text('razorpay_secret', UtilityFacades::getsettings('razorpay_secret'))->class('form-control')->placeholder(__('Enter razorpay secret')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="paypal">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsepaypal"
                                                        aria-expanded="true" aria-controls="collapsepaypal">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Paypal') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('paypalsetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsepaypal" class="accordion-collapse collapse"
                                                    aria-labelledby="paypal" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('paypalsetting') == 'on', 'paypal')->class('form-check-input')->id('is_paypal_enable') !!}
                                                                {!! html()->label(__('Enable'), 'is_paypal_enable')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            {!! html()->label(__('Paytm Environment'), 'paypal_mode')->class('paypal-label col-form-label') !!}
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2">
                                                                    <div class="p-3 border card">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                {!! html()->radio('paypal_mode', UtilityFacades::getsettings('paypal_mode') == 'sandbox', 'sandbox')->class('form-check-input') !!}
                                                                                {!! html()->label(__('Sandbox'), 'paypal_mode')->class('form-check-label text-dark') !!}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="p-3 border card">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                {!! html()->radio('paypal_mode', UtilityFacades::getsettings('paypal_mode') == 'live', 'live')->class('form-check-input') !!}
                                                                                {!! html()->label(__('Live'), 'paypal_mode')->class('form-check-label text-dark') !!}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Paypal Key'), 'client_id')->class('form-label') !!}
                                                                    {!! html()->text('client_id', UtilityFacades::getsettings('paypal_sandbox_client_id'))->class('form-control')->placeholder(__('Enter paypal key')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Paypal Secret'), 'client_secret')->class('form-label') !!}
                                                                    {!! html()->text('client_secret', UtilityFacades::getsettings('paypal_sandbox_client_secret'))->class('form-control')->placeholder(__('Enter paypal secret')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="paytm">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsepaytm"
                                                        aria-expanded="true" aria-controls="collapsepaytm">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Paytm') }}
                                                        </span>
                                                        @if (Utility::getsettings('paytmsetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsepaytm" class="accordion-collapse collapse"
                                                    aria-labelledby="paytm" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('paytmsetting') == 'on', 'paytm')->class('form-check-input')->id('is_paytm_enable') !!}
                                                                {!! html()->label(__('Enable'), 'is_paytm_enable')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class=" col-md-12">
                                                                {!! html()->label(__('Paytm Environment'), 'paytm_environment')->class('paypal-label col-form-label') !!}
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2">
                                                                        <div class="p-3 border card">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! html()->radio('paytm_environment', UtilityFacades::getsettings('paytm_environment') == 'local', 'local')->class('form-check-input') !!}
                                                                                    {!! html()->label(__('Local'), 'paytm_environment')->class('form-check-label text-dark') !!}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="p-3 border card">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! html()->radio('paytm_environment', UtilityFacades::getsettings('paytm_environment') == 'production', 'production')->class('form-check-input') !!}
                                                                                    {!! html()->label(__('Production'), 'paytm_environment')->class('form-check-label text-dark') !!}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Paytm Merchant Id'), 'merchant_id')->class('form-label') !!}
                                                                    {!! html()->text('merchant_id', UtilityFacades::getsettings('paytm_merchant_id'))->class('form-control')->placeholder(__('Enter paytm merchant id')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Paytm Merchant Key'), 'merchant_key')->class('form-label') !!}
                                                                    {!! html()->text('merchant_key', UtilityFacades::getsettings('paytm_merchant_key'))->class('form-control')->placeholder(__('Enter paytm merchant key')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="flutterwave">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseflutterwave"
                                                        aria-expanded="true" aria-controls="collapseflutterwave">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Flutterwave') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('flutterwavesetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapseflutterwave" class="accordion-collapse collapse"
                                                    aria-labelledby="flutterwave" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('flutterwavesetting') == 'on', 'flutterwave')->class('form-check-input')->id('is_flutterwave_enable') !!}
                                                                {!! html()->label(__('Enable'), 'is_flutterwave_enable')->class('custom-control-label form-control-label') !!}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Flutterwave Public Key'), 'flw_public_key')->class('form-label') !!}
                                                                    {!! html()->text('flw_public_key', UtilityFacades::getsettings('flw_public_key'))->class('form-control')->placeholder(__('Enter flutterwave public key')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {!! html()->label(__('Flutterwave Secret Key'), 'flw_secret_key')->class('form-label') !!}
                                                                    {!! html()->text('flw_secret_key', UtilityFacades::getsettings('flw_secret_key'))->class('form-control')->placeholder(__('Enter flutterwave secret key')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="paystack">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsepaystack"
                                                        aria-expanded="true" aria-controls="collapsepaystack">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Paystack') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('paystacksetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsepaystack" class="accordion-collapse collapse"
                                                    aria-labelledby="paystack" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('paystacksetting') == 'on', 'paystack')->class('form-check-input')->id('is_paystack_enable') !!}
                                                                {{ html()->label(__('Enable'), 'is_paystack_enable')->class('custom-control-label form-control-label') }}

                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('Paystack Public Key'), 'paystack_public_key')->class('form-label') }}
                                                                    {!! html()->text('paystack_public_key', UtilityFacades::getsettings('paystack_public_key'))->class('form-control')->placeholder(__('Enter paystack public key')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('Paystack Secret Key'), 'paystack_secret_key')->class('form-label') }}
                                                                    {!! html()->text('paystack_secret_key', UtilityFacades::getsettings('paystack_secret_key'))->class('form-control')->placeholder(__('Enter paystack secret key')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-11">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse10"
                                                        aria-expanded="true" aria-controls="collapse10">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('CoinGate') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('coingatesetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse10" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-11" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('coingatesetting') == 'on', 'coingate')->class('form-check-input')->id('is_coingate_enable') !!}
                                                                {{ html()->label(__('Enable'), 'is_coingate_enable')->class('custom-control-label form-control-label') }}
                                                            </div>
                                                        </div>
                                                        <div class=" col-md-12">
                                                            {{ html()->label(__('CoinGate Mode'), 'coingate_mode')->class('col-form-label') }}
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2">
                                                                    <div class="p-3 border card">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                {!! html()->radio('coingate_mode', 'sandbox', UtilityFacades::getsettings('coingate_environment') == 'sandbox')->class('form-check-input')->id('coingate_mode_sandbox') !!}
                                                                                {{ html()->label(__('Sandbox'))->class('form-check-label text-dark')->for('coingate_mode_sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="p-3 border card">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                {!! html()->radio('coingate_mode', UtilityFacades::getsettings('coingate_environment') == 'live', 'live')->class('form-check-input')->id('coingate_mode_live') !!}
                                                                                {{ html()->label(__('Live'))->class('form-check-label text-dark')->for('coingate_mode_live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            {{ html()->label(__('CoinGate Auth Token'), 'coingate_auth_token')->class('form-label') }}
                                                            {!! html()->text('coingate_auth_token', UtilityFacades::getsettings('coingate_auth_token'))->class('form-control')->placeholder(__('Enter coingate auth token'))->id('coingate_auth_token') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- PayUMoney -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-payumoney">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse-payumoney"
                                                        aria-expanded="true" aria-controls="collapse-payumoney">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('PayUMoney') }}
                                                        </span>
                                                        @if (UtilityFacades::getsettings('payumoneysetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse-payumoney" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-payumoney"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="py-1 col-12 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! html()->checkbox('paymentsetting[]', UtilityFacades::getsettings('payumoneysetting') == 'on', 'payumoney')->class('form-check-input mx-2')->id('payment_payumoney') !!}
                                                                    {{ html()->label(__('Enable'), 'payment_payumoney')->class('form-check-label') }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                {{ html()->label(__('PayUMoney Mode'), 'payumoney_mode')->class('paypal-label form-label') }}
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2">
                                                                        <div class="p-3 border card">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! html()->radio('payumoney_mode', Utility::getsettings('payumoney_mode') == 'sandbox', 'sandbox')->class('form-check-input')->id('payumoney_sandbox') !!}
                                                                                    {{ html()->label(__('Sandbox'))->class('form-check-label text-dark')->for('payumoney_sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="p-3 border card">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! html()->radio('payumoney_mode', Utility::getsettings('payumoney_mode') == 'production', 'production')->class('form-check-input')->id('payumoney_production') !!}
                                                                                    {{ html()->label(__('Production'))->class('form-check-label text-dark')->for('payumoney_production') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('PayUMoney Merchant Key'), 'payumoney_merchant_key')->class('form-label') }}
                                                                    {!! html()->text('payumoney_merchant_key', UtilityFacades::getsettings('payumoney_merchant_key'))->class('form-control')->placeholder(__('Enter payumoney merchant key')) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('PayUMoney Salt Key'), 'payumoney_salt_key')->class('form-label') }}
                                                                    {!! html()->text('payumoney_salt_key', UtilityFacades::getsettings('payumoney_salt_key'))->class('form-control')->placeholder(__('Enter payumoney salt key')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Mollie -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading18">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse18"
                                                        aria-expanded="true" aria-controls="collapse18">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Mollie') }}
                                                        </span>
                                                        @if (Utility::getsettings('molliesetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse18" class="accordion-collapse collapse"
                                                    aria-labelledby="heading18" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="py-2 col-12 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! html()->checkbox('paymentsetting[]', Utility::getsettings('molliesetting') == 'on', 'mollie')->class('form-check-input mx-2')->id('payment_mollie') !!}
                                                                    {{ html()->label(__('Enable'), 'payment_mollie')->class('form-check-label') }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('Mollie Api Key'), 'mollie_api_key')->class('form-label') }}
                                                                    {!! html()->text('mollie_api_key', Utility::getsettings('mollie_api_key'))->class('form-control')->placeholder(__('Enter Mollie Api Key'))->id('mollie_api_key') !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('Mollie Profile Id'), 'mollie_profile_id')->class('form-label') }}
                                                                    {!! html()->text('mollie_profile_id', Utility::getsettings('mollie_profile_id'))->class('form-control')->placeholder(__('Enter Mollie Profile Id'))->id('mollie_profile_id') !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ html()->label(__('Mollie Partner Id'), 'mollie_partner_id')->class('form-label') }}
                                                                    {!! html()->text('mollie_partner_id', Utility::getsettings('mollie_partner_id'))->class('form-control')->placeholder(__('Enter Mollie Partner Id'))->id('mollie_partner_id') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- Mercado pago --->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="mercado">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsemercado"
                                                        aria-expanded="true" aria-controls="collapsemercado">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Mercado') }}
                                                        </span>
                                                        @if (Utility::getsettings('mercadosetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsemercado" class="accordion-collapse collapse"
                                                    aria-labelledby="mercado" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', Utility::getsettings('mercadosetting') == 'on', 'mercado')->class('form-check-input')->id('is_mercado_enable') !!}
                                                                {{ html()->label(__('Enable'), 'is_mercado_enable')->class('custom-control-label form-control-label') }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            {{ html()->label(__('Mercado Environment'), 'mercado_mode')->class('mercado-label col-form-label') }}
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2">
                                                                    <div class="p-3 border card">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                {!! html()->radio('mercado_mode', UtilityFacades::getsettings('mercado_mode') == 'sandbox', 'sandbox')->class('form-check-input') !!}
                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="p-3 border card">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                {!! html()->radio('mercado_mode', UtilityFacades::getsettings('mercado_mode') == 'live', 'live')->class('form-check-input') !!}
                                                                                {{ __('Live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 row">
                                                            <div class="form-group">
                                                                {{ html()->label(__('Mercado Access Token'), 'mercado_access_token')->class('form-label') }}
                                                                {!! html()->text('mercado_access_token', UtilityFacades::getsettings('mercado_access_token'))->class('form-control')->placeholder(__('Enter mercado access token'))->id('mercado_access_token') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- offline payment --}}
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="mercado">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsofflinepayment"
                                                        aria-expanded="true" aria-controls="collapsofflinepayment">
                                                        <span class="flex-1 d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Offline Payment') }}
                                                        </span>
                                                        @if (Utility::getsettings('offlinepaymentsetting') == 'on')
                                                            <a
                                                                class="text-white btn btn-sm btn-primary float-end me-3">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapsofflinepayment" class="accordion-collapse collapse"
                                                    aria-labelledby="offline_payment" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('paymentsetting[]', Utility::getsettings('offlinepaymentsetting') == 'on', 'offlinepayment')->class('form-check-input')->id('is_offline_payment') !!}
                                                                {{ html()->label(__('Enable'), 'is_offline_payment')->class('custom-control-label form-control-label') }}
                                                            </div>
                                                        </div>

                                                        <div class="mt-2 row">
                                                            <div class="form-group">
                                                                {{ html()->label(__('Payment Details'), 'offline_payment_details')->class('form-label') }}
                                                                {!! html()->textarea('offline_payment_details', UtilityFacades::getsettings('offline_payment_details'))->class('form-control')->placeholder(__('Enter Payment Details'))->id('offline_payment_details')->rows(3) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary')->name('save_button') !!} </div>
                            </div>
                        </div>
                        {!! html()->form()->close() !!}


                        <div id="sms-setting" class="card">
                            {!! html()->form('POST', route('settings.sms-setting.update'))->class('data-validate')->novalidate()->open() !!}
                            <div class="card-header">
                                <div class="row d-flex align-items-center">
                                    <div class="col-6 d-flex justify-content-start">
                                        <h5> {{ __('Sms Setting') }}</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">
                                            {!! html()->checkbox('multisms_setting', UtilityFacades::getsettings('multisms_setting') == 'on', 'on')->class('custom-control custom-switch form-check-input input-primary')->id('multi_sms')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            <small
                                                class="mt-2 text-end d-flex">{{ __('Please turn on this SMS enable button.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div
                                        class="col-sm-12 multi_sms {{ UtilityFacades::getsettings('multisms_setting') == 'on' ? '' : 'd-none' }}">
                                        <div class="form-group">
                                            {!! html()->radio('smssetting', Utility::getsettings('smssetting') == 'twilio', 'twilio')->class('btn-check')->id('smssetting_twilio') !!}
                                            {{ html()->label(__('Twilio'), 'smssetting_twilio')->class('btn btn-outline-primary') }}

                                            {!! html()->radio('smssetting', Utility::getsettings('smssetting') == 'nexmo', 'nexmo')->class('btn-check')->id('smssetting_nexmo') !!}
                                            {{ html()->label(__('Nexmo'), 'smssetting_nexmo')->class('btn btn-outline-primary') }}
                                        </div>
                                        <div id="twilio"
                                            class="desc {{ Utility::getsettings('smssetting') == 'twilio' ? 'block' : 'd-none' }}">
                                            <div class="">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="name">{{ __('Twilio SID') }}</label>
                                                        <input type="text" name="twilio_sid" class="form-control"
                                                            value="{{ Utility::getsettings('twilio_sid') }}"
                                                            placeholder="{{ __('Enter twilio sid') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="name">{{ __('Twilio Auth Token') }}</label>
                                                        <input type="text" name="twilio_auth_token"
                                                            class="form-control"
                                                            value="{{ Utility::getsettings('twilio_auth_token') }}"
                                                            placeholder="{{ __('Enter twilio auth token') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="name">{{ __('Twilio Verify SID') }}</label>
                                                        <input type="text" name="twilio_verify_sid"
                                                            class="form-control"
                                                            value="{{ Utility::getsettings('twilio_verify_sid') }}"
                                                            placeholder="{{ __('Enter verify sid') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="name">{{ __('Twilio Number') }}</label>
                                                        <input type="text" name="twilio_number" class="form-control"
                                                            value="{{ Utility::getsettings('twilio_number') }}"
                                                            placeholder="{{ __('Enter number') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nexmo"
                                            class="desc {{ Utility::getsettings('smssetting') == 'nexmo' ? 'block' : 'd-none' }}">
                                            <div class="">
                                                <div class="row">
                                                    <div class="form-group">
                                                        {{ html()->label(__('Nexmo Key'), 'nexmo_key')->class('form-label') }}
                                                        {!! html()->text('nexmo_key', Utility::getsettings('nexmo_key'))->class('form-control')->placeholder(__('Enter nexmo key')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                        {{ html()->label(__('Nexmo Secret'), 'nexmo_secret')->class('form-label') }}
                                                        {!! html()->text('nexmo_secret', Utility::getsettings('nexmo_secret'))->class('form-control')->placeholder(__('Enter nexmo secret')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary float-end mb-3') !!} </div>
                            {!! html()->form()->close() !!}
                        </div>

                        <div id="google-calender-setting" class="card">
                            <div class="col-md-12">
                                {{ html()->form('POST', route('settings.google-calender.update'))->attribute('data-validate')->attribute('novalidate')->attribute('enctype', 'multipart/form-data')->open() }}i
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <h5>{{ __('Google Calendar Settings') }}</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="">
                                                <div class="form-switch custom-switch-v1 d-inline-block">
                                                    {!! html()->checkbox('google_calendar_enable', UtilityFacades::getsettings('google_calendar_enable') == 'on' ? '1' : '0')->value('1')->checked(UtilityFacades::getsettings('google_calendar_enable') == 'on')->class('custom-control custom-switch form-check-input input-primary')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->id('google_calender') !!} <small
                                                        class="mt-2 text-end d-flex">{{ __('Please turn on this Google calendar enable button.') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body google_calender">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                            {{ html()->label(__('Google Calendar Id'), 'google_calendar_id')->class('col-form-label') }}
                                            {{ html()->text('google_calendar_id')->class('form-control')->placeholder('Google Calendar Id')->required()->value(UtilityFacades::getsettings('google_calendar_id')) }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                            {{ html()->label(__('Google Calendar json File'), 'Google_calendar_json_file')->class('col-form-label') }}
                                            {{ html()->file('google_calendar_json_file')->class('form-control')->id('file')->accept('.json') }}
                                            <small>{{ __('NOTE: Allowed file extension : .json (Max Size: 2 MB)') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>

                        <div id="google-map-setting" class="card">
                            <div class="col-md-12">
                                {{ html()->form('POST', route('settings.googlemap.update'))->attribute('data-validate')->attribute('novalidate')->open() }}
                                <div class="card-header">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-6">
                                            <h5 class="mb-2">{{ __('Google Map Setting') }}</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {{ html()->checkbox('google_map_enable', UtilityFacades::getsettings('google_map_enable') == 'on')->class('custom-switch custom-control form-check-input input-primary')->attribute('data-toggle', 'switchbutton')->id('google_map') }}
                                                <small
                                                    class="mt-2 text-end d-flex">{{ __('Please turn on this Google Map enable button.') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body google_map">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                            {{ html()->label(__('Google Map Api Key'), 'google_map_api')->class('col-form-label') }}
                                            {{ html()->text('google_map_api')->class('form-control')->placeholder('Enter Map API key')->required()->value(UtilityFacades::getsettings('google_map_api')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                        <div id="notification-setting" class="card">
                            <div class="card-header">
                                <h5>{{ __('Notifications') }}</h5>
                            </div>
                            <div class="pt-0 card-body">
                                <div class="mt-0 table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th class="w-auto text-end">{{ __('Email') }}</th>
                                                <th class="w-auto text-end">{{ __('Notification') }}</th>
                                            </tr>
                                        </thead>
                                        @foreach ($notificationsSettings as $notificationsSetting)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <span name="title" class="form-control"
                                                                placeholder="Enter title"
                                                                value="{{ $notificationsSetting->id }}">
                                                                {{ $notificationsSetting->title }}</span>
                                                        </div>
                                                    </td>
                                                    @if ($notificationsSetting->email_notification != 2)
                                                        <td class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('email_notification')->class('form-check-input chnageEmailNotifyStatus')->checked($notificationsSetting->email_notification == 1)->attribute('data-url', route('notification.status.change', $notificationsSetting->id)) !!} </div>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif

                                                    @if (
                                                        $notificationsSetting->status == 2 &&
                                                            $notificationsSetting->title != 'testing purpose' &&
                                                            $notificationsSetting->title != 'new Enquire details' &&
                                                            $notificationsSetting->title != 'new survey details')
                                                    @else
                                                        <td class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! html()->checkbox('notify', $notificationsSetting->notify == 1, null)->class('form-check-input chnageNotifyStatus')->data('url', route('notification.status.change', $notificationsSetting->id)) !!}
                                                        </td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="pwa-setting" class="card">
                            <div class="col-md-12">
                                {!! html()->form('POST', route('settings.pwa-setting.update'))->attribute('enctype', 'multipart/form-data')->open() !!}
                                <div class="card-header">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-6">
                                            <h5 class="mb-2">{{ __('PWA Setting') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body google_map">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-6 col-md-6 form-group">
                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h5 class="me-1">{{ __('PWA Icon') }}</h5>
                                                    <span>(128x128)</span>
                                                </div>
                                                <div class="pt-0 card-body">
                                                    <div class="inner-content">
                                                        <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                            <a href="{{ Utility::getpath('pwa_icon_128') ? Storage::url(Utility::getsettings('pwa_icon_128')) : '' }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('pwa_icon_128') ? Storage::url(Utility::getsettings('pwa_icon_128')) : '' }}"
                                                                    id="pwa_128">
                                                            </a>
                                                        </div>
                                                        <div class="mt-3 text-center choose-files">
                                                            <label for="pwa_icon_128">
                                                                <div class="bg-primary company_logo_update"> <i
                                                                        class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                {!! html()->file('pwa_icon_128')->class('form-control file')->id('pwa_icon_128')->attribute('onchange', "document.getElementById('pwa_128').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'pwa_icon_128')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h5 class="me-1">{{ __('PWA Icon') }}</h5>
                                                    <span>(144x144)</span>
                                                </div>
                                                <div class="pt-0 card-body">
                                                    <div class="inner-content">
                                                        <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                            <a href="{{ Utility::getpath('pwa_icon_144') ? Storage::url(Utility::getsettings('pwa_icon_144')) : '' }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('pwa_icon_144') ? Storage::url(Utility::getsettings('pwa_icon_144')) : '' }}"
                                                                    id="pwa_144">
                                                            </a>
                                                        </div>
                                                        <div class="mt-3 text-center choose-files">
                                                            <label for="pwa_icon_144">
                                                                <div class="bg-primary company_logo_update"> <i
                                                                        class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                {!! html()->file('pwa_icon_144')->class('form-control file')->id('pwa_icon_144')->attribute('onchange', "document.getElementById('pwa_144').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'pwa_icon_144')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h5 class="me-1">{{ __('PWA Icon') }}</h5>
                                                    <span>(152x152)</span>
                                                </div>
                                                <div class="pt-0 card-body">
                                                    <div class="inner-content">
                                                        <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                            <a href="{{ Utility::getpath('pwa_icon_152') ? Storage::url(Utility::getsettings('pwa_icon_152')) : '' }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('pwa_icon_152') ? Storage::url(Utility::getsettings('pwa_icon_152')) : '' }}"
                                                                    id="pwa_152">
                                                            </a>
                                                        </div>
                                                        <div class="mt-3 text-center choose-files">
                                                            <label for="pwa_icon_152">
                                                                <div class="bg-primary company_logo_update"> <i
                                                                        class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                {!! html()->file('pwa_icon_152')->class('form-control file')->id('pwa_icon_152')->attribute('onchange', "document.getElementById('pwa_152').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'pwa_icon_152')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h5 class="me-1">{{ __('PWA Icon') }}</h5>
                                                    <span>(192x192)</span>
                                                </div>
                                                <div class="pt-0 card-body">
                                                    <div class="inner-content">
                                                        <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                            <a href="{{ Utility::getpath('pwa_icon_192') ? Storage::url(Utility::getsettings('pwa_icon_192')) : '' }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('pwa_icon_192') ? Storage::url(Utility::getsettings('pwa_icon_192')) : '' }}"
                                                                    id="pwa_192">
                                                            </a>
                                                        </div>
                                                        <div class="mt-3 text-center choose-files">
                                                            <label for="pwa_icon_192">
                                                                <div class="bg-primary company_logo_update"> <i
                                                                        class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                {!! html()->file('pwa_icon_192')->class('form-control file')->id('pwa_icon_192')->attribute('onchange', "document.getElementById('pwa_192').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'pwa_icon_192')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h5 class="me-1">{{ __('PWA Icon') }}</h5>
                                                    <span>(256x256)</span>
                                                </div>
                                                <div class="pt-0 card-body">
                                                    <div class="inner-content">
                                                        <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                            <a href="{{ Utility::getpath('pwa_icon_256') ? Storage::url(Utility::getsettings('pwa_icon_256')) : '' }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('pwa_icon_256') ? Storage::url(Utility::getsettings('pwa_icon_256')) : '' }}"
                                                                    id="pwa_256">
                                                            </a>
                                                        </div>
                                                        <div class="mt-3 text-center choose-files">
                                                            <label for="pwa_icon_256">
                                                                <div class="bg-primary company_logo_update"> <i
                                                                        class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                {!! html()->file('pwa_icon_256')->class('form-control file')->id('pwa_icon_256')->attribute('onchange', "document.getElementById('pwa_256').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'pwa_icon_256')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h5 class="me-1">{{ __('PWA Icon') }}</h5>
                                                    <span>(512x512)</span>
                                                </div>
                                                <div class="pt-0 card-body">
                                                    <div class="inner-content">
                                                        <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                            <a href="{{ Utility::getpath('pwa_icon_512') ? Storage::url(Utility::getsettings('pwa_icon_512')) : '' }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('pwa_icon_512') ? Storage::url(Utility::getsettings('pwa_icon_512')) : '' }}"
                                                                    id="pwa_512">
                                                            </a>
                                                        </div>
                                                        <div class="mt-3 text-center choose-files">
                                                            <label for="pwa_icon_512">
                                                                <div class="bg-primary company_logo_update"> <i
                                                                        class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                {!! html()->file('pwa_icon_512')->class('form-control file')->id('pwa_icon_512')->attribute('onchange', "document.getElementById('pwa_512').src = window.URL.createObjectURL(this.files[0])")->attribute('data-filename', 'pwa_icon_512')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                                {!! html()->form()->close() !!}
                            </div>
                        </div>

                        <div id="analytics-dashboard-setting" class="card">
                            <div class="col-md-12">
                                <div class="card-header">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-6">
                                            <h5 class="mb-2">{{ __('Analytics Dashboard Setting') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="title_text"
                                                class="form-label">{{ __('Set this url as Authorized redirect URIs') }}</label>
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="outh_path"
                                                        value="<?= url('/') . '/analytics-dashboard/oauth2callback' ?>"
                                                        id="url-to-copy" disabled>
                                                    <button class="btn btn-primary btn-sm"
                                                        onclick="copyToClipboard()">{{ __('Copy') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
        <link href="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    @endpush
    @push('script')
        <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
        <script>
            $(".inputtags").tagsinput('items');
        </script>
        <script>
            var textRemove = new Choices(
                document.getElementById('choices-text-remove-button'), {
                    delimiter: ',',
                    editItems: true,
                    removeItemButton: true,
                }
            );
            feather.replace();
            var pctoggle = document.querySelector("#pct-toggler");
            if (pctoggle) {
                pctoggle.addEventListener("click", function() {
                    if (
                        !document.querySelector(".pct-customizer").classList.contains("active")
                    ) {
                        document.querySelector(".pct-customizer").classList.add("active");
                    } else {
                        document.querySelector(".pct-customizer").classList.remove("active");
                    }
                });
            }
            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });

            var themescolors = document.querySelectorAll(".themes-color > a");
            for (var h = 0; h < themescolors.length; h++) {
                var c = themescolors[h];
                c.addEventListener("click", function(event) {
                    var targetElement = event.target;
                    if (targetElement.tagName == "SPAN") {
                        targetElement = targetElement.parentNode;
                    }
                    var temp = targetElement.getAttribute("data-value");
                    removeClassByPrefix(document.querySelector("body"), "theme-");
                    document.querySelector("body").classList.add(temp);
                });
            }
            var custdarklayout = document.querySelector("#cust-darklayout");
            custdarklayout.addEventListener("click", function() {
                if (custdarklayout.checked) {
                    document.querySelector(".m-header > .b-brand > img").setAttribute("src",
                        "{{ Storage::url(Utility::getsettings('app_logo')) }}");
                    document.querySelector("#main-style-link").setAttribute("href",
                        "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    document.querySelector(".m-header > .b-brand > img").setAttribute("src",
                        "{{ Storage::url(Utility::getsettings('app_dark_logo')) }}");
                    document.querySelector("#main-style-link").setAttribute("href",
                        "{{ asset('assets/css/style.css') }}");
                }
            });

            function check_theme(color_val) {
                $('.theme-color').prop('checked', false);
                $('input[value="' + color_val + '"]').prop('checked', true);
            }

            function enablecookie() {
                const element = $('#enable_cookie').is(':checked');
                $('.cookieDiv').addClass('d-none');
                if (element == true) {
                    $('.cookieDiv').removeClass('d-none');
                    $("#cookie_logging").attr('checked', true);
                } else {
                    $('.cookieDiv').addClass('d-none');
                    $("#cookie_logging").attr('checked', false);
                }
            }

            function enableseo() {
                const element = $('#seo_setting').is(':checked');
                $('.seoDiv').addClass('d-none');
                if (element == true) {
                    $('.seoDiv').removeClass('d-none');
                } else {
                    $('.seoDiv').addClass('d-none');
                }
            }

            $('body').on('click', '.send_mail', function() {
                var action = $(this).data('action');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Test Mail') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
            $(document).ready(function() {
                $(".socialsetting").trigger("select");
            });
            $(document).on('change', ".socialsetting", function() {
                var test = $(this).val();
                if ($(this).is(':checked')) {
                    if (test == 'google') {
                        $("#google").fadeIn(500);
                        $("#google").removeClass('d-none');
                    } else if (test == 'facebook') {
                        $("#facebook").fadeIn(500);
                        $("#facebook").removeClass('d-none');
                    } else if (test == 'github') {
                        $("#github").fadeIn(500);
                        $("#github").removeClass('d-none');
                    } else if (test == 'linkedin') {
                        $("#linkedin").fadeIn(500);
                        $("#linkedin").removeClass('d-none');
                    }
                } else {
                    if (test == 'google') {
                        $("#google").fadeOut(500);
                        $("#google").addClass('d-none');
                    } else if (test == 'facebook') {
                        $("#facebook").fadeOut(500);
                        $("#facebook").addClass('d-none');
                    } else if (test == 'github') {
                        $("#github").fadeOut(500);
                        $("#github").addClass('d-none');
                    } else if (test == 'linkedin') {
                        $("#linkedin").fadeOut(500);
                        $("#linkedin").addClass('d-none');
                    }
                }
            });
            $(document).ready(function() {
                if ($("input[name$='captcha']").is(':checked')) {
                    $("#recaptcha").fadeIn(500);
                    $("#recaptcha").removeClass('d-none');
                } else {
                    $("#recaptcha").fadeOut(500);
                    $("#recaptcha").addClass('d-none');
                }
                $(".paymenttsetting").trigger("select");
            });

            $(document).on('change', ".paymenttsetting", function() {
                var test = $(this).val();
                if ($(this).is(':checked')) {
                    if (test == 'razorpay') {
                        $("#razorpay").fadeIn(500);
                        $("#razorpay").removeClass('d-none');
                    } else if (test == 'stripe') {
                        $("#stripe").fadeIn(500);
                        $("#stripe").removeClass('d-none');
                    } else if (test == 'paytm') {
                        $("#paytm").fadeIn(500);
                        $("#paytm").removeClass('d-none');
                    } else if (test == 'paypal') {
                        $("#paypal").fadeIn(500);
                        $("#paypal").removeClass('d-none');
                    } else if (test == 'flutterwave') {
                        $("#flutterwave").fadeIn(500);
                        $("#flutterwave").removeClass('d-none');
                    } else if (test == 'paystack') {
                        $("#paystack").fadeIn(500);
                        $("#paystack").removeClass('d-none');
                    } else if (test == 'mercado') {
                        $("#mercado").fadeIn(500);
                        $("#mercado").removeClass('d-none');
                    } else if (test == 'offline') {
                        $("#offline").fadeIn(500);
                        $("#offline").removeClass('d-none');
                    }
                } else {
                    if (test == 'razorpay') {
                        $("#razorpay").fadeOut(500);
                        $("#razorpay").addClass('d-none');
                    } else if (test == 'paytm') {
                        $("#paytm").fadeOut(500);
                        $("#paytm").removeClass('d-none');
                    } else if (test == 'stripe') {
                        $("#stripe").fadeOut(500);
                        $("#stripe").addClass('d-none');
                    } else if (test == 'flutterwave') {
                        $("#flutterwave").fadeIn(500);
                        $("#flutterwave").removeClass('d-none');
                    } else if (test == 'paypal') {
                        $("#paypal").fadeOut(500);
                        $("#paypal").addClass('d-none');
                    } else if (test == 'paystack') {
                        $("#paystack").fadeOut(500);
                        $("#paystack").addClass('d-none');
                    } else if (test == 'mercado') {
                        $("#mercado").fadeIn(500);
                        $("#mercado").removeClass('d-none');
                    } else if (test == 'offline') {
                        $("#offline").fadeOut(500);
                        $("#offline").addClass('d-none');
                    }
                }
            });
            $(document).on('click', "input[name$='captcha']", function() {
                var test = $(this).val();
                if (test == 'hcaptcha') {
                    $("#hcaptcha").fadeIn(500);
                    $("#hcaptcha").removeClass('d-none');
                    $("#recaptcha").addClass('d-none');
                } else {
                    $("#recaptcha").fadeIn(500);
                    $("#recaptcha").removeClass('d-none');
                    $("#hcaptcha").addClass('d-none');
                }
            });
            $(document).on('click', "input[name$='storage_type']", function() {
                var test = $(this).val();
                if (test == 's3') {
                    $("#s3").fadeIn(500);
                    $("#s3").removeClass('d-none');
                } else {
                    $("#s3").fadeOut(500);
                }
            });
            $(document).on('click', "input[name$='storage_type']", function() {
                var test = $(this).val();
                if (test == 'wasabi') {
                    $("#wasabi").fadeIn(500);
                    $("#wasabi").removeClass('d-none');
                } else {
                    $("#wasabi").fadeOut(500);
                }
            });
            $(document).ready(function() {
                handleSmsChange();

                $(document).on('change', "#multi_sms", function() {
                    handleSmsChange();
                });

                function handleSmsChange() {
                    if ($('#multi_sms').is(':checked')) {
                        $(".multi_sms").fadeIn(500);
                        $('.multi_sms').removeClass('d-none');
                        $('#twilio').removeClass('d-none');
                        $('#smssetting_twilio').prop('checked', true);
                    } else {
                        $(".multi_sms").fadeOut(500);
                        $(".multi_sms").addClass('d-none');
                        $('#smssetting_twilio').prop('checked', false);
                        $('#smssetting_nexmo').prop('checked', false);
                        $('#smssetting_fast2sms').prop('checked', false);
                    }
                }
            });

            $(document).ready(function() {
                function toggleSmsSetting() {
                    var setting = $("input[name='smssetting']:checked").val();
                    $("#twilio, #nexmo").fadeOut(500).addClass('d-none');
                    $("#" + setting).fadeIn(500).removeClass('d-none');
                }

                toggleSmsSetting();

                $(document).on('click', "input[name='smssetting']", toggleSmsSetting); // Toggle on radio button click
            });

            $(document).on('change', "#captchaEnableButton", function() {
                if (this.checked) {
                    $('.captchaSetting').fadeIn(500);
                    $(".captchaSetting").removeClass('d-none');
                } else {
                    $('.captchaSetting').fadeOut(500);
                    $(".captchaSetting").addClass('d-none');
                }
            })
            $(document).ready(function () {
                $(document).on('change', 'input[name="captcha"]', function () {
                    if ($(this).val() == 'recaptcha') {
                        $('#recaptcha').removeClass('d-none').show();
                        $('#hcaptcha').addClass('d-none').hide();
                    } else if ($(this).val() == 'hcaptcha') {
                        $('#hcaptcha').removeClass('d-none').show();
                        $('#recaptcha').addClass('d-none').hide();
                    }
                });

                $('input[name="captcha"]:checked').trigger('change');
            });
            document.addEventListener('DOMContentLoaded', function() {
                var genericExamples = document.querySelectorAll('[data-trigger]');
                for (i = 0; i < genericExamples.length; ++i) {
                    var element = genericExamples[i];
                    new Choices(element, {
                        placeholderValue: 'This is a placeholder set in the config',
                        searchPlaceholderValue: 'This is a search placeholder',
                    });
                }
            });
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300
            });
            $(document).on("change", ".chnageEmailNotifyStatus", function(e) {
                var csrf = $("meta[name=csrf-token]").attr("content");
                var email = $(this).parent().find("input[name=email_notification]").is(":checked");
                var action = $(this).attr("data-url");
                $.ajax({
                    type: "POST",
                    url: action,
                    data: {
                        _token: csrf,
                        type: 'email',
                        email_notification: email,
                    },
                    success: function(response) {
                        if (response.warning) {
                            show_toastr("Warning!", response.warning, "warning");
                        }
                        if (response.is_success) {
                            show_toastr("Success!", response.message, "success");
                        }
                    },
                });
            });

            $(document).on("change", ".chnagesmsNotifyStatus", function(e) {
                var csrf = $("meta[name=csrf-token]").attr("content");
                var sms = $(this).parent().find("input[name=sms_notification]").is(":checked");
                var action = $(this).attr("data-url");
                $.ajax({
                    type: "POST",
                    url: action,
                    data: {
                        _token: csrf,
                        type: 'sms',
                        sms_notification: sms,
                    },
                    success: function(response) {
                        if (response.warning) {
                            show_toastr("Warning!", response.warning, "warning");
                        }
                        if (response.is_success) {
                            show_toastr("Success!", response.message, "success");
                        }
                    },
                });
            });

            $(document).on("change", ".chnageNotifyStatus", function(e) {
                var csrf = $("meta[name=csrf-token]").attr("content");
                var notify = $(this).parent().find("input[name=notify]").is(":checked");
                var action = $(this).attr("data-url");
                $.ajax({
                    type: "POST",
                    url: action,
                    data: {
                        _token: csrf,
                        type: 'notify',
                        notify: notify,
                    },
                    success: function(response) {
                        if (response.warning) {
                            show_toastr("Warning!", response.warning, "warning");
                        }
                        if (response.is_success) {
                            show_toastr("Success!", response.message, "success");
                        }
                    },
                });
            });
        </script>
        <script>
            $('.colorPicker').on('click', function(e) {
                $('body').removeClass('custom-color');
                if (/^theme-\d+$/) {
                    $('body').removeClassRegex(/^theme-\d+$/);
                }
                $('body').addClass('custom-color');
                $('.themes-color-change').removeClass('active_color');
                $(this).addClass('active_color');
                const input = document.getElementById("color-picker");
                setColor();
                input.addEventListener("input", setColor);

                function setColor() {
                    $(':root').css('--color-customColor', input.value);
                }

                $(`input[name='color_flag`).val('true');
            });
            $('.themes-color-change').on('click', function() {
                $(`input[name='color_flag']`).val('false');

                var color_val = $(this).data('value');
                $('body').removeClass('custom-color');
                if ($('body').hasClass(/^theme-\d+$/)) {
                    $('body').removeClassRegex(/^theme-\d+$/);
                }
                $('body').addClass(color_val);
                $('.theme-color').prop('checked', false);
                $('.themes-color-change').removeClass('active_color');
                $('.colorPicker').removeClass('active_color');
                $(this).addClass('active_color');
                $(`input[value=${color_val}]`).prop('checked', true);
            });
            $('.themes-color-change.active_color').trigger('click');

            $.fn.removeClassRegex = function(regex) {
                return $(this).removeClass(function(index, classes) {
                    return classes.split(/\s+/).filter(function(c) {
                        return regex.test(c);
                    }).join(' ');
                });
            };

            function copyToClipboard() {
                var copyText = document.getElementById("url-to-copy");
                navigator.clipboard.writeText(copyText.value).then(function() {
                    show_toastr('Great!', '{{ __('Copy URL Successfully.') }}', 'success');
                }).catch(function(error) {
                    show_toastr("Error!", "Failed to copy URL.", "danger");
                });
            }
        </script>
    @endpush
