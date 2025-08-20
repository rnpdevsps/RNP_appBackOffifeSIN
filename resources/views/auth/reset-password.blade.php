@php
    $languages = \App\Facades\UtilityFacades::languages();
@endphp
@extends('layouts.app')
@section('title', __('Reset Password'))
@section('auth-topbar')
    <li class="language-btn">
        <select class="my-1 btn btn-primary me-2 nice-select"
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            id="language">
            @foreach ($languages as $language)
                <option class="" @if ($lang == $language) selected @endif
                    value="{{ route('change.lang', [$request->route('token'), $language]) }}">{{ Str::upper($language) }}
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
                    <div class="login-content-inner">
                        <div class="login-title">
                            <h3>{{ __('Reset Password') }}</h3>
                        </div>
                        {!! html()->form('POST', route('password.update'))->attribute('data-validate')->open() !!}
                        {!! html()->hidden('token', $request->route('token'))->class('form-control') !!}
                        <div class="mb-3 form-group">
                            {!! html()->label(__('E-Mail Address'))->class('form-label mb-2')->for('email') !!}
                            {!! html()->email('email', $email ?? old('email'))->class('form-control')->id('email')->required()->placeholder(__('E-Mail Address'))->attribute('autocomplete', 'email')->attribute('onfocus', true) !!}
                        </div>
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Password'))->class('form-label')->for('password') !!}
                            {!! html()->password('password')->class('form-control')->id('password')->required()->placeholder(__('Password'))->attribute('autocomplete', 'new-password') !!}
                        </div>
                        <div class="mb-3 form-group">
                            {!! html()->label(__('Confirm Password'))->class('form-label')->for('password_confirmation') !!}
                            {!! html()->password('password_confirmation')->class('form-control')->id('password-confirm')->required()->placeholder(__('Confirm Password'))->attribute('autocomplete', 'new-password') !!}
                        </div>
                        <div class="d-grid">
                            {!! html()->button(__('Reset Password'))->type('submit')->class('btn btn-primary btn-block mt-2') !!}
                        </div>
                        {!! html()->form()->close() !!}
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
