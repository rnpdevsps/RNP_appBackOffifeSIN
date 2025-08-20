@extends('layouts.main')
@section('title', __('Profile'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Profile') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"> {!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Profile') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1" class="border-0 list-group-item list-group-item-action">{{ __('Profile') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-2"
                                class="border-0 list-group-item list-group-item-action">{{ __('Basic Info') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-3"
                                class="border-0 list-group-item list-group-item-action">{{ __('Login Details') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            @if (setting('2fa') == '1')
                                <a href="#useradd-4"
                                    class="border-0 list-group-item list-group-item-action">{{ __('2FA') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            <a href="#useradd-5"
                                class="border-0 list-group-item list-group-item-action">{{ __('Delete Account') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="text-white card bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="{{ Storage::exists($user->avatar) ? Storage::url($user->avatar) : $user->avatar_image }}"
                                        class="img-user wid-80 rounded-circle">
                                </div>
                                <div class="d-block d-sm-flex align-items-center justify-content-between w-100">
                                    <div class="mb-3 mb-sm-0">
                                        <h4 class="mb-1 text-white">{{ $user->name }}</h4>
                                        <p class="mb-0 text-sm">{{ $user->email }}</p>
                                        <p class="mb-0 text-sm">{{ $role ? $role->name : 'Role Not Set' }}</p>
                                        @if (\Auth::user()->social_type != null)
                                            <p class="mb-0 text-sm"><b>{{ __('Login with:') }}</b>
                                                {{ ucfirst($user->social_type) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <h5>{{ __('Basic info') }}</h5>
                            <small class="text-muted">{{ __('Mandatory informations') }}</small>
                        </div>
                        {!! html()->form('POST', route('profile.update.basicinfo'))->class('form-horizontal')->attributes(['enctype' => 'multipart/form-data'])->open() !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Full Name'), 'fullname')->class('form-label') !!}
                                        {!! html()->text('fullname', $user->name)->class('form-control')->placeholder(__('Enter full name')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Phone'), 'phone')->class('form-label') !!}
                                        {!! html()->text('phone', $user->phone)->class('form-control')->placeholder(__('Enter phone')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Country'), 'country')->class('form-label') !!}
                                        {!! html()->select('country', $countries, $user->country)->class('form-control form-control-inline-block')->attributes(['data-trigger']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Address'), 'address')->class('form-label') !!}
                                        {!! html()->text('address', $user->address)->class('form-control')->id('address')->placeholder(__('Enter address')) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Role'), 'role')->class('form-label') !!}
                                        {!! html()->text('role', $role ? $role->name : __('Role Not Set'))->class('form-control')->disabled() !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Update Avatar'), 'avatarCrop')->class('avatar_crop btn btn-primary btn-lg d-block mx-auto col-sm-12 mb-0') !!}
                                        {!! html()->file('avatarCrop')->class('d-none')->id('avatarCrop')->accept('.jpeg,.jpg,.png') !!}
                                        <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png') }}</small>
                                    </div>
                                    <div id="avatar-updater" class="col-xs-12 d-none">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="image-preview"></div>
                                            </div>
                                            <div class="col-md-12">
                                                {!! html()->text('avatar-url', route('update.avatar'))->class('d-none') !!}
                                                {!! html()->button(__('Rotate Image'))->class('btn btn-gradient-info col-sm-12 mb-1')->id('rotate-image') !!}
                                                {!! html()->button(__('Crop Image'))->class('btn btn-gradient-primary col-sm-12')->id('crop_image') !!}
                                                {!! html()->button(__('Cancel'))->class('btn btn-gradient-danger col-sm-12 mt-1')->id('avatar-cancel-btn') !!}
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
                    <div id="useradd-3" class="card">
                        <div class="card-header">
                            <h5>{{ __('Login Details') }}</h5>
                            <small class="text-muted">{{ __('Login informations') }}</small>
                        </div>
                        {!! html()->form('POST', route('update.login.details'))->class('form-horizontal')->open() !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Email'), 'email')->class('form-label') !!}
                                        {!! html()->text('email', $user->email)->placeholder(__('Enter email address'))->class('form-control') !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Password'), 'password')->class('form-label') !!}
                                        {!! html()->password('password')->placeholder(__('Leave blank if you don’t want to change'))->class('form-control')->attributes(['autocomplete' => 'off']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Confirm Password'), 'password_confirmation')->class('form-label') !!}
                                        {!! html()->password('password_confirmation')->placeholder(__('Leave blank if you don’t want to change'))->class('form-control')->attributes(['autocomplete' => 'off']) !!}
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
                    @if (setting('2fa') == '1')
                        <div id="useradd-4" class="card">
                            <div class="card-header">
                                <h5>{{ __('Two Factor Authentication') }}</h5>
                            </div>
                            <div class="card-body">
                                {{-- <ul class="mt-2 list-group list-group-flush"> --}}
                                @if (extension_loaded('imagick') && setting('2fa'))
                                    <div class="tab-pane" id="tfa-settings" role="tabpanel"
                                        aria-labelledby="tfa-settings-tab">
                                        <!--Google Two Factor Authentication card-->
                                        <div class="col-md-12">
                                            @if (empty(auth()->user()->loginSecurity))
                                                <!--=============Generate QRCode for Google 2FA Authentication=============-->
                                                <div class="p-0 row">
                                                    <div class="col-md-12">
                                                        <p>{{ __('To activate Two factor Authentication Generate QRCode') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        {!! html()->form('POST', route('generate2faSecret'))->class('tfa-form')->open() !!}
                                                        {!! html()->button(__('Activate 2FA'))->type('submit')->class('btn btn-primary col-md-6') !!}
                                                    </div>
                                                    <hr>
                                                    <h3 class="">
                                                        {{ __('Two Factor Authentication(2FA) Setup Instruction') }}
                                                    </h3>
                                                    <hr>
                                                    <div class="mt-4">
                                                        <h4>{{ __('Below is a step by step instruction on setting up Two Factor Authentication') }}
                                                        </h4>
                                                        <p><label>{{ __('Step 1') }}:</label>
                                                            {{ __('Download') }}
                                                            <strong>{{ __('Google Authenticator App') }}</strong>
                                                            {{ __('Application for Andriod or iOS') }}
                                                        </p>
                                                        <p class="text-center">
                                                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                                                target="_blank"
                                                                class="btn btn-success">{{ __('Download for Andriod') }}<i
                                                                    class="fa fa-android fa-2x ms-1"></i></a>
                                                            <a href="https://apps.apple.com/us/app/google-authenticator/id388497605"
                                                                target="_blank"
                                                                class="btn btn-dark ms-1">{{ __('Download for iPhones') }}<i
                                                                    class="fa fa-apple fa-2x ms-1"></i></a>
                                                        </p>
                                                        <p><label>{{ __('Step 2') }}:</label>
                                                            {{ __('Click on Generate Secret Key on the platform to generate a QRCode') }}
                                                        </p>
                                                        <p><label>{{ __('Step 3') }}:</label>
                                                            {{ __('Open the') }}
                                                            <strong>{{ __('Google Authenticator App') }}</strong>
                                                            {{ __('and clcik on') }}
                                                            <strong>{{ __('Begin') }}</strong>
                                                            {{ __('on the mobile app') }}
                                                        </p>
                                                        <p><label>{{ __('Step 4') }}:</label>
                                                            {{ __('After which click on') }}
                                                            <strong>{{ __('Scan a QRcode') }}</strong>
                                                        </p>
                                                        <p><label>{{ __('Step 5') }}:</label>
                                                            {{ __('Then scan the barcode on the platform') }}
                                                        </p>
                                                        <p><label>{{ __('Step 6') }}:</label>
                                                            {{ __('Enter the verification code generated on the platform and Enable 2FA') }}
                                                        </p>
                                                        <hr>
                                                        <p><label>{{ __('Note') }}:</label>
                                                            {{ __('To disable 2FA enter code from the Google Authenticator App and account password to disable 2FA') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <!--=============Generate QRCode for Google 2FA Authentication=============-->
                                            @elseif(!auth()->user()->loginSecurity->google2fa_enable)
                                                <!--=============Enable Google 2FA Authentication=============-->
                                             
                                                {!! html()->form('POST', route('enable2fa'))->class('form-horizontal')->open() !!}
                                                <div class="row">
                                                    <div class="col-md-12 form-group">
                                                        <p><strong>{{ __('Scan the QRCode with') }}
                                                                <dfn>{{ __('Google Authenticator App') }}</dfn>
                                                                {{ __('Enter the generated code below') }}</strong>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        @if (!extension_loaded('imagick'))
                                                            {!! $google2fa_url !!}
                                                        @else
                                                            <img src="{{ $google2fa_url }}" />
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p>{{ __('To enable 2-Factor Authentication verify QRCode') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            {!! html()->label(__('Verification code'), 'secret')->class('form-label') !!}
                                                            {!! html()->password('secret')->class('form-control')->id('code')->placeholder(__('Enter verification code'))->required() !!}
                                                        </div>
                                                        @if ($errors->has('verify-code'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('verify-code') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="mt-2 col-md-12 text-end">
                                                    {!! html()->button(__('Enable 2FA'))->type('submit')->class('btn btn-primary col-sm-2') !!}
                                                </div>
                                                {!! html()->form()->close() !!}

                                                <!--=============Enable Google 2FA Authentication=============-->
                                            @elseif(auth()->user()->loginSecurity->google2fa_enable)
                                                <!--=============Disable Google 2FA Authentication=============-->
                                                {!! html()->form('POST', route('disable2fa'))->class('form-horizontal')->open() !!}
                                                <div class="row form-group">
                                                    <div class="col-md-12">
                                                        @if (!extension_loaded('imagick'))
                                                            {!! $google2fa_url !!}
                                                        @else
                                                            <img src="{{ $google2fa_url }}" />
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p>{{ __('To disable 2-Factor Authentication verify QRCode') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {!! html()->label(__('Current Password'), 'current-password')->class('form-label') !!}
                                                        {!! html()->password('current-password')->class($errors->has('password') ? 'form-control is-invalid' : 'form-control')->placeholder(__('Enter current password'))->required() !!}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="col-md-12 text-end">
                                                    {!! html()->button(__('Disable 2FA'))->type('submit')->class('btn btn-danger col-sm-2') !!}
                                                </div>
                                                {!! html()->form()->close() !!}
                                                <!--=============Disable Google 2FA Authentication=============-->
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                {{-- </ul> --}}
                            </div>
                        </div>
                    @endif
                    <div id="useradd-5" class="card">
                        <div class="card-header">
                            <h5>{{ __('Delete Account') }}</h5>
                            <small
                                class="text-muted">{{ __('Once you delete your account, there is no going back. Please be certain.') }}</small>
                        </div>
                        {!! html()->form('DELETE', route('users.destroy', $user->id))->id('delete-form-' . $user->id)->open() !!}
                        <div class="card-footer">
                            <div class="mb-3 col-sm-auto text-sm-end d-flex float-end">
                                @if ($user->active_status == 1)
                                    {!! html()->a('profile-status')->class('btn btn-secondary me-3')->text(__('Deactivate')) !!}
                                @endif
                                <a class="btn btn-danger show_confirm d-flex" data-toggle="tooltip"
                                    href="#!">{{ __('Delete Account') }}<i
                                        class="ti ti-chevron-right ms-1 ms-sm-2"></i></a>
                            </div>
                        </div>
                        {!! html()->form()->close() !!}
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
    </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('vendor/js/plugins/croppie/css/croppie.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('vendor/js/plugins/croppie/js/croppie.min.js') }}"></script>
    <script src="{{ asset('vendor/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.setTimeout(function() {}, 9e3), $image_crop = $(".image-preview").croppie({
                    enableExif: !0,
                    enforceBoundary: !1,
                    enableOrientation: !0,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: "square"
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                }), $("#avatarCrop").change(function() {
                    $("#avatar-holder").addClass("d-none"), $("#avatar-updater").removeClass("d-none");
                    var e = new FileReader;
                    e.onload = function(e) {
                        $image_crop.croppie("bind", {
                            url: e.target.result
                        })
                    }, e.readAsDataURL(this.files[0])
                }),
                $("#toggleClose").click(function() {
                    $("#toggleClose").css("display", "none"), $(".app-logo").css("display", "none"), $(
                        ".toggleopen").css("display", "block")
                }), $(".toggleopen").click(function() {
                    $(".toggleopen").css("display", "none"), $(".app-logo").css("display", "block"), $(
                        "#toggleClose").css("display", "block")
                }), $("#rotate-image").click(function(e) {
                    $image_crop.croppie("rotate", 90)
                }), $("#crop_image").click(function() {
                    $image_crop.croppie("result", {
                        type: "canvas",
                        size: "viewport"
                    }).then(function(e) {
                        var a = $("input[name=avatar-url]").val(),
                            t = $('meta[name="csrf-token"]').attr("content"),
                            o = $("#crop_image");
                        o.html("Saving Avatar...");
                        o.attr("disabled", "disabled");
                        $.ajaxSetup({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            }
                        });

                        $.ajax({
                            url: a,
                            type: "POST",
                            data: {
                                avatar: e,
                                _token: t
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    new swal({
                                        text: response.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    new swal({
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(response) {
                                new swal({
                                    text: response.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });
                    });
                });
            $("#avatar-cancel-btn").click(function() {
                $("#avatar-holder").removeClass("d-none");
                $("#avatar-updater").addClass("d-none");
            });
            $("#backup-file-btn").click(function() {
                swal({
                    text: "Application file backup is disabled by Administrator",
                    icon: 'error',
                });
            });
            $("#backup-database-btn").click(function() {
                swal({
                    text: "Database backup is disabled by Administrator",
                    icon: 'error',
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'This is a search placeholder.',
                });
            }
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 200
        })
    </script>
@endpush
