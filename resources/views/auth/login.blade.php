@php
    $languages = \App\Facades\UtilityFacades::languages();
    config([
        'captcha.sitekey' => Utility::getsettings('recaptcha_key'),
        'captcha.secret' => Utility::getsettings('recaptcha_secret'),
    ]);
@endphp
@extends('layouts.app-auth')
@section('title', __('AppTest'))
@section('content')

   <section class="wizard-section">
		<div class="row no-gutters">
            <div class="col-lg-6 col-md-6">
				<div class="wizard-content-left d-flex justify-content-center align-items-center">
					<img src="{{ Utility::getsettings('login_image')
                            ? Storage::url(Utility::getsettings('login_image'))
                            : asset('assets/images/auth/LogoRNPBlanco.png') }}"
                            class="img-fluid" width="450px" />
				</div>
			</div>

		    <div class="col-lg-6 col-md-6">
				<div class="form-wizard">

                {!! html()->form('POST', route('login'))->attribute('data-validate')->class('needs-validation')->open() !!}

						<div class="form-wizard-header">
                            <br>
                            <h1 class="text-color2">{{__('Sign In')}}</h1>
                            <hr>
						</div>
						<br><br><br>
						<fieldset class="wizard-fieldset show">

							<div class="form-group">
                            {!! html()->label(__('Email'), 'email')->class('form-label mb-2') !!}
                            {!! html()->email('email', old('email'))->class('form-control wizard-required')->id('email')->placeholder(__('Enter email address'))->required() !!}

                            </div>
                            <div class="form-group">
                            {!! html()->label(__('Enter Password'), 'password')->class('form-label') !!}
                            {!! html()->a(route('password.request'), __('Forgot Password ?'))->class('float-end forget-password') !!}
                            {!! html()->password('password')->class('form-control wizard-required')->placeholder(__('Enter password'))->required()->attribute('tabindex', '2')->id('password')->attribute('autocomplete', 'current-password') !!}

                            </div>
                            @if (Utility::getsettings('login_recaptcha_status') == '1')
                                <div class="my-3 text-center">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                </div>
                            @endif
                            <div class="d-grid">
                            {!! html()->button(__('Sign In'))->type('submit')->class('btn btn-primary login-do-btn btn-block mt-3') !!}
                            </div>

						</fieldset>

                        {!! html()->form()->close() !!}
				</div>
			</div>


		</div>
	</section>

@endsection
