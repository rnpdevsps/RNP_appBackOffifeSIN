@php
    $languages = \App\Facades\UtilityFacades::languages();
    config([
        'captcha.sitekey' => Utility::getsettings('recaptcha_key'),
        'captcha.secret' => Utility::getsettings('recaptcha_secret'),
    ]);
    $roles = App\Models\Role::whereNotIn('name', ['Super Admin', 'Admin'])
        ->pluck('name', 'name')
        ->all();
@endphp
@extends('layouts.app')
@section('title', __('Sign Up'))
@section('auth-topbar')
    <li class="language-btn">
        <select class="my-1 btn btn-primary me-2 nice-select"
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            id="language">
            @foreach ($languages as $language)
                <option class="" @if ($lang == $language) selected @endif
                    value="{{ route('change.lang', $language) }}">{{ Str::upper($language) }}
                </option>
            @endforeach
        </select>
    </li>
@endsection
@section('content')
    <div class="login-page-wrapper">
        <div class="login-container">
            <div class="login-row d-flex">
                <div class="login-col-6">
                    <div class="login-content-inner register-form">
                        <div class="login-title">
                            <h3>{{ __('Sign Up') }}</h3>
                        </div>
                        {!! html()->form('POST', route('register'))->attribute('data-validate')->open() !!}
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Name'))->for('name')->class('form-label') !!}
                            {!! html()->text('name', old('name'))->class('form-control')->id('name')->placeholder(__('Enter name'))->required()->attribute('autocomplete', 'name')->attribute('autofocus') !!}
                        </div>
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Email'))->for('email')->class('form-label') !!}
                            {!! html()->email('email', old('email'))->class('form-control')->id('email')->placeholder(__('Enter email'))->required()->attribute('autocomplete', 'email')->attribute('autofocus') !!}
                        </div>
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Password'))->for('password')->class('form-label') !!}
                            {!! html()->password('password')->class('form-control')->id('password')->placeholder(__('Enter password'))->required()->attribute('autocomplete', 'new-password')->attribute('autofocus') !!}
                        </div>
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Confirm Password'))->for('password_confirmation')->class('form-label') !!}
                            {!! html()->password('password_confirmation')->class('form-control')->id('password-confirm')->placeholder(__('Enter confirm password'))->required()->attribute('autocomplete', 'new-password')->attribute('autofocus') !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Country Code'))->class('d-block form-label')->for('country_code') !!}
                            <select id="country_code" name="country_code"class="form-control" data-trigger>
                                @foreach (\App\Core\Data::getCountriesList() as $key => $value)
                                    <option data-kt-flag="{{ $value['flag'] }}" value="{{ $key }}">
                                        +{{ $value['phone_code'] }} {{ $value['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Phone Number'))->for('phone')->class('form-label') !!}
                            {!! html()->number('phone',NULL)->class('form-control')->placeholder(__('Enter phone number'))->required()->attribute('autocomplete', 'off')->attribute('autofocus') !!}
                        </div>
                        @if (Utility::getsettings('login_recaptcha_status') == '1')
                            <div class="my-3 text-center">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                        @endif
                        <div class="d-grid">
                            {!! html()->button(__('Register'))->type('submit')->class('btn btn-primary btn-block mt-3') !!}
                        </div>
                        {!! html()->form()->close() !!}
                        <div class="mt-4 text-center create_user text-muted">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}">{{ __('Sign In') }}</a>
                        </div>
                    </div>
                </div>
                <div class="login-media-col">
                    <div class="login-media-inner">
                        <img src="{{ Utility::getsettings('login_image')
                            ? Storage::url(Utility::getsettings('login_image'))
                            : asset('assets/images/auth/img-auth-3.svg') }}"
                            class="img-fluid" />
                        <h3>
                            {{ Utility::getsettings('login_title') ? Utility::getsettings('login_title') : 'Attention is the new currency' }}
                        </h3>
                        <p>
                            {{ Utility::getsettings('login_subtitle') ? Utility::getsettings('login_subtitle') : 'The more effortless the writing looks, the more effort the writer actually put into the process.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
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
    </script>
@endpush
