@extends('layouts.main')
@section('title', __('Edit Form'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <div class="previous-next-btn">
                <div class="page-header-title">
                    <h4 class="m-b-10">{{ __('Edit Form') }}</h4>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('forms.index') }}">{{ __('Forms') }}</a></li>
                    <li class="breadcrumb-item"> {{ __('Edit Form') }} </li>
                </ul>
            </div>
            <div class="float-end">
                <div class="gap-2 d-flex align-items-center">
                    @if (request()->query->get('view') == null)
                        @can('edit-form')
                            @if ($form->json)
                                @if ($form->is_active)
                                    @can('theme-setting-form')
                                        <a class="btn btn-secondary btn-sm" href="{{ route('form.theme', $form->id) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="{{ __('Theme Setting') }}"><i class="ti ti-layout-2"></i></a>
                                    @endcan
                                    @can('payment-form')
                                        <a class="btn btn-warning btn-sm" href="{{ route('form.payment.integration', $form->id) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="{{ __('Payment Integration') }}"><i
                                                class="ti ti-report-money"></i></a>
                                    @endcan
                                    @can('integration-form')
                                        <a class="btn btn-info btn-sm" href="{{ route('form.integration', $form->id) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="{{ __('Integration') }}"><i class="ti ti-send"></i></a>
                                    @endcan
                                    @can('manage-form-rule')
                                        <a class="btn btn-secondary btn-sm" href="{{ route('form.rules', $form->id) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="{{ __('Conditional Rules') }}"><i
                                                class="ti ti-notebook"></i></a>
                                    @endcan
                                    @if ($form->limit_status == 1)
                                        @if ($form->limit > $formValueCount)
                                            <a class="btn btn-success btn-sm copy_form "
                                                onclick="copyToClipboard('#copy-form-{{ $form->id }}')"
                                                href="javascript:void(0)" id="copy-form-{{ $form->id }}"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-original-title="{{ __('Copy Form URL') }}"
                                                data-url="{{ route('forms.survey', $hashId) }}"><i class="ti ti-copy"></i></a>
                                        @endif
                                    @else
                                        <a class="btn btn-success btn-sm copy_form "
                                            onclick="copyToClipboard('#copy-form-{{ $form->id }}')"
                                            href="javascript:void(0)" id="copy-form-{{ $form->id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="{{ __('Copy Form URL') }}"
                                            data-url="{{ route('forms.survey', $hashId) }}"><i class="ti ti-copy"></i></a>
                                    @endif

                                    <a class="text-white btn btn-info btn-sm cust_btn"
                                        data-share="{{ route('forms.survey.qr', $hashId) }}" id="share-qr-code"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        data-bs-original-title="{{ __('Show QR Code') }}"><i class="ti ti-qrcode"></i></a>
                                    <a class="btn btn-secondary btn-sm" href="{{ route('view.form.values', $form->id) }}"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        data-bs-original-title="{{ __('View Submited forms') }}"><i
                                            class="ti ti-clipboard-check"></i></a>
                                @endif
                            @endif
                        @endcan
                        @can('fill-form')
                            @if ($form->json)
                                @if ($form->limit_status == 1)
                                    @if ($form->limit > $formValueCount)
                                        <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="{{ __('Fill Form') }}"
                                            href="{{ route('forms.fill', $form->id) }}"><i class="ti ti-list"></i></a>
                                    @endif
                                @else
                                    <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        data-bs-original-title="{{ __('Fill Form') }}"
                                        href="{{ route('forms.fill', $form->id) }}"><i class="ti ti-list"></i></a>
                                @endif
                            @endif
                        @endcan

                        @can('design-form')
                            <a class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                data-bs-original-title="{{ __('Design Form') }}"
                                href="{{ route('forms.design', $form->id) }}"><i class="ti ti-brush"></i></a>
                        @endcan
                    @endif
                    <a href="@if (!empty($previous)) {{ route('forms.edit', [$previous->id]) }}@else javascript:void(0) @endif"
                        type="button" class="btn btn-outline-primary"><i class="me-2"
                            data-feather="chevrons-left"></i>{{ __('Previous') }}</a>
                    <a href="@if (!empty($next)) {{ route('forms.edit', [$next->id]) }}@else javascript:void(0) @endif"
                        class="btn btn-outline-primary ms-1"><i class="me-2"
                            data-feather="chevrons-right"></i>{{ __('Next') }}</a>

                </div>
            </div>
        </div>
    </div>
@endsection
@php
    $storageType = App\Facades\UtilityFacades::getsettings('storage_type');
    $logoPath =
        isset($form->logo) && Storage::exists($form->logo)
            ? asset('storage/app/' . $form->logo)
            : Storage::url('app-logo/78x78.png');
@endphp
@section('content')
    <div class="row">
        {!! html()->modelForm($form, 'PUT', route('forms.update', $form->id))->class('form-horizontal')->attribute('data-validate')->attribute('novalidate')->attribute('enctype', 'multipart/form-data')->open() !!}
            <div class="row gy-4 align-items-center">
                <div class="col-md-9">
                </div>
                <div class="col-md-3">
                    <div class="card-body tab-view information-tab">
                        <ul class="mb-3 nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if (
                                    !session('errors') or
                                        session('errors') and
                                            $errors->hasAny([
                                                'title',
                                                'form_logo',
                                                'category_id',
                                                'form_status',
                                                'email',
                                                'bccemail',
                                                'ccemail',
                                                'mail_mailer',
                                                'mail_host',
                                                'mail_port',
                                                'mail_username',
                                                'mail_username',
                                                'mail_password',
                                                'mail_encryption',
                                                'mail_from_address',
                                                'mail_from_address',
                                                'mail_from_name',
                                            ])) active @endif" id="pills-general-tab"
                                    data-bs-toggle="pill" href="#pills-general" role="tab" aria-controls="pills-general"
                                    aria-selected="true">{{ __('General') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (session('errors') &&
                                        $errors->hasAny([
                                            'assign_type',
                                            'roles',
                                            'users',
                                            'limit_status',
                                            'limit',
                                            'password_enable',
                                            'form_password',
                                            'set_end_date',
                                            'set_end_date_time',
                                        ])) active @endif"
                                    id="pills-form-setting-tab" data-bs-toggle="pill" href="#pills-form-setting"
                                    role="tab" aria-controls="pills-form-setting"
                                    aria-selected="false">{{ __('Form Setting') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-8">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade @if (
                            !session('errors') or
                                session('errors') and
                                    $errors->hasAny([
                                        'title',
                                        'form_logo',
                                        'category_id',
                                        'form_status',
                                        'email',
                                        'bccemail',
                                        'ccemail',
                                        'mail_mailer',
                                        'mail_host',
                                        'mail_port',
                                        'mail_username',
                                        'mail_username',
                                        'mail_password',
                                        'mail_encryption',
                                        'mail_from_address',
                                        'mail_from_address',
                                        'mail_from_name',
                                        'assign_type',
                                        'roles',
                                        'users',
                                    ])) show active @endif" id="pills-general"
                            role="tabpanel" aria-labelledby="pills-general-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('General') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                {!! html()->label(__('Title of form'), 'title')->class('form-label') !!}
                                                {!! html()->text('title', $form->title)->class('form-control preview-input')->id('form-title')->placeholder(__('Enter title of form'))->attribute('data-preview', 'title-preview')->required() !!}
                                                @if ($errors->has('form'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('form') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                {!! html()->label(__('Category'), 'category_id')->class('form-label') !!}
                                                {!! html()->select('category_id', $categories, $form->category_id)->class('form-select')->attribute('data-trigger')->required() !!}
                                                <small>{{ __('Create Category') }} <a
                                                        href="{{ route('form-category.index') }}">{{ __('Click here') }}</a></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                {!! html()->label(__('Select Logo'), 'form_logo')->class('form-label') !!}
                                                <div class="setting-block position-relative">
                                                    <img src="{{ $storageType == 'local' ? $logoPath : Storage::url($form->logo) }}"
                                                        alt="images" class="imagepreview" id="form_logo">
                                                    <div class="text-center position-absolute top-50 end-0 start-0">
                                                        <div class="choose-file">
                                                            <label for="file-2">
                                                                <button type="button" onclick="selectFile('form_logo')"
                                                                    class="btn btn-sm btn-primary"><i class="me-2"
                                                                        data-feather="upload"></i>{{ __('Select Logo') }}</button>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <input class="form-control d-none form_logo" type="file"
                                                        name="form_logo" id="file-2" multiple="">
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Recipient Email'), 'email[]')->class('form-label') !!}
                                                {!! html()->text('email[]', $form->email)->class('form-control preview-input')->placeholder(__('Enter recipient email'))->attribute('data-preview', 'email-preview')->required() !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Select Status'), 'form_status')->class('form-label') !!}
                                                {!! html()->select('form_status', $status, $form->form_status)->class('form-select')->attribute('data-trigger')->required() !!}
                                                <small>{{ __('Create Form Status') }} <a
                                                        href="{{ route('form-status.index') }}">{{ __('Click here') }}</a></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Bcc Emails (Optional)'), 'bccemail[]')->class('form-label') !!}
                                                {!! html()->text('bccemail[]', $form->bccemail)->class('form-control inputtags')->placeholder(__('Enter recipient bcc email')) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Cc Emails (Optional)'), 'ccemail[]')->class('form-label') !!}
                                                {!! html()->text('ccemail[]', $form->ccemail)->class('form-control inputtags')->placeholder(__('Enter recipient cc email')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Short Description'), 'form_description')->class('form-label') !!}
                                            <small>{{ __('Note') }} :-
                                                {{ __('This Description Only Show in front side') }}</small>
                                            {!! html()->textarea('form_description', $form->description)->id('form_description')->placeholder(__('Enter short description'))->rows(3)->class('form-control preview-input')->attribute('data-preview', 'description-preview') !!}
                                            @if ($errors->has('form_description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('form_description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Success Message'), 'success_msg')->class('form-label') !!}
                                            {!! html()->textarea('success_msg', $form->success_msg)->id('success_msg')->placeholder(__('Enter success message'))->class('form-control preview-input')->attribute('data-preview', 'success-message-preview') !!}

                                            @if ($errors->has('success_msg'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('success_msg') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Thanks Message'), 'thanks_msg')->class('form-label') !!}
                                            {!! html()->textarea('thanks_msg', $form->thanks_msg)->id('thanks_msg')->placeholder(__('Enter client message'))->class('form-control preview-input')->attribute('data-preview', 'thanks-message-preview') !!}
                                            @if ($errors->has('thanks_msg'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('thanks_msg') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Assign Form'), 'assignform')->class('form-label') !!}
                                            <div class="assignform" id="assign_form">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                {!! html()->label(__('Role'), 'assign_type_role')->class('form-label') !!}
                                                                <label class="form-switch custom-switch-v1 ms-2">
                                                                    {!! html()->radio('assign_type', $form->assign_type === 'role', 'role')->class('form-check-input input-primary')->id('assign_type_role') !!}
                                                                </label>
                                                            </div>
                                                            <div>
                                                                {!! html()->label(__('User'), 'assign_type_user')->class('form-label tesk-1') !!}
                                                                <label class="form-switch custom-switch-v1 ms-2">
                                                                    {!! html()->radio('assign_type', $form->assign_type === 'user', 'user')->class('form-check-input input-primary')->id('assign_type_user') !!}
                                                                </label>
                                                            </div>
                                                            <div>
                                                                {!! html()->label(__('Public'), 'assign_type_public')->class('form-label tesk-1') !!}
                                                                <label class="form-switch custom-switch-v1 ms-2">
                                                                    {!! html()->radio('assign_type', $form->assign_type === 'public', 'public')->class('form-check-input input-primary')->id('assign_type_public') !!}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div id="role"
                                                            class="desc {{ $formRole ? 'd-block' : 'd-none' }}">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        {!! html()->label(__('Role'), 'roles')->class('form-label') !!}
                                                                        <select name="roles[]" class="form-select" multiple
                                                                            id="choices-multiple-remove-button">
                                                                            @foreach ($getFormRole as $k => $role)
                                                                                <option value="{{ $k }}"
                                                                                    {{ in_array($k, $formRole) ? 'selected' : '' }}>
                                                                                    {{ $role }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="user"
                                                            class="desc {{ $formUser ? 'd-block' : 'd-none' }}">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        {!! html()->label(__('User'), 'users')->class('form-label') !!}
                                                                        <select name="users[]" class="form-select" multiple
                                                                            id="choices-multiples-remove-button">
                                                                            @foreach ($GetformUser as $key => $user)
                                                                                <option value="{{ $key }}"
                                                                                    {{ in_array($key, $formUser) ? 'selected' : '' }}>
                                                                                    {{ $user }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
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
                        </div>
                        <div class="tab-pane fade @if (session('errors') &&
                                $errors->hasAny([
                                    'limit_status',
                                    'limit',
                                    'password_enable',
                                    'form_password',
                                    'set_end_date',
                                    'set_end_date_time',
                                    'feedback_enabled',
                                ])) show active @endif"
                            id="pills-form-setting" role="tabpanel" aria-labelledby="pills-form-setting-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Form Setting') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Form Fill Edit Lock'), 'form_fill_edit_lock')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="form_fill_edit_lock" id="form_fill_edit_lock"
                                                    class="form-check-input input-primary"
                                                    {{ old('form_fill_edit_lock') == 'on' ? 'checked' : (!old() && $form->form_fill_edit_lock == 1 ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Enable Session Timer'))->for('enable_session_timer')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="enable_session_timer" id="enable_session_timer"
                                                    class="form-check-input input-primary"
                                                    {{ old('enable_session_timer') == 'on' ? 'checked' : ($form->enable_session_timer == 1 ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Allow comments'), 'allow_comments')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="allow_comments" id="allow_comments"
                                                    class="form-check-input input-primary"
                                                    {{ old('allow_comments') == 'on' ? 'checked' : (!old() && $form->allow_comments == 1 ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Allow Share Section'), 'allow_share_section')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="allow_share_section" id="allow_share_section"
                                                    class="form-check-input input-primary"
                                                    {{ old('allow_share_section') == 'on' ? 'checked' : (!old() && $form->allow_share_section == 1 ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Set limit'), 'limit_status')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="hidden" name="limit_status" value="0">
                                                <input type="checkbox" name="limit_status" id="m_limit_status"
                                                    class="form-check-input input-primary"
                                                    {{ old('limit_status') == 'on' ? 'checked' : (!old() && $form->limit_status == 1 ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="limit_status" class="{{ $form->limit_status == '1' ? 'd-block' : 'd-none' }}">
                                        <div class="form-group">
                                            {!! html()->number('limit')->class('form-control preview-input')->placeholder(__('limit'))->id('form-limit')->attribute('data-preview', 'limit-preview') !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Password Protection Enable'), 'password_enable')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="hidden" name="password_enable" value="0">
                                                <input type="checkbox" name="password_enable" id="form_password_enable"
                                                    class="form-check-input input-primary"
                                                    {{ old('password_enable') == '1' ? 'checked' : (!old() && $form->password_enabled == 1 ? 'checked' : '') }}
                                                    value="1">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="password_enable" class="{{ $form->form_password ? 'd-block' : 'd-none' }}">
                                        <div class="form-group">
                                            <div class="position-relative password-toggle">
                                                <div class="input-group-append password-toggle-icon" id="togglePassword">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </div>
                                                {!! html()->password('form_password')->class('form-control password-toggle-input')->placeholder(__('************'))->attribute('autocomplete', 'off')->id('form_protection_password') !!}

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Set end date'))->for('set_end_date')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="hidden" name="set_end_date" value="0">
                                                <input type="checkbox" name="set_end_date" id="m_set_end_date"
                                                    class="form-check-input input-primary"
                                                    {{ old('set_end_date') == 'on' ? 'checked' : (!old() && $form->set_end_date == 1 ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="set_end_date" class="{{ $form->set_end_date == '1' ? 'd-block' : 'd-none' }}">
                                        <div class="form-group">
                                            <input class="form-control preview-input" name="set_end_date_time"
                                                id="set_end_date_time" value="{{ $form->set_end_date_time }}"
                                                data-preview ="end-date-preview" type="datetime-local">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Feedback Enable'))->for('feedback_enabled')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="feedback_enabled" id="feedback_enabled"
                                                    class="form-check-input input-primary"
                                                    {{ old('feedback_enabled') == 'on' ? 'checked' : (!old() && $form->feedback_enabled == 1 ? 'checked' : '')  }}>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="card-footer">
                            <div class="text-end">
                                {!! html()->a(route('forms.index'))->class('btn btn-secondary')->text(__('Cancel')) !!}
                                {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Preview') }}</h5>
                            </div>
                            <section class="tab-view">
                                <div class="card-body">
                                    <section class="client-info-section">
                                        <div class="container">
                                            <div class="bg-white client-info-wrapper">
                                                <div class="text-center client-intro">
                                                    <div class="client-image">
                                                        <img src="{{ $storageType == 'local' ? $logoPath : Storage::url($form->logo) }}"
                                                            id="form_logo_preview" alt="image">
                                                    </div>
                                                    <h3 id="title-preview" class="text-black">{{ $form->title }}</h3>
                                                    <h6 id="email-preview" class="text-black">{{ $form->email }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="client-text-section d-none padding-top description-div"
                                        id="description-div">
                                        <div class="container">
                                            <div class="bg-white client-text">
                                                <p id="description-preview"></p>
                                                <p id="success-message-preview"></p>
                                                <p id="thanks-message-preview"></p>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="form-setting-section padding-top form-setting-div form-setting d-none"
                                        id="description-div">
                                        <div class="container">
                                            <div class="bg-white client-text">
                                                <div class="mt-0 mb-1 col-lg-12 limit_preview d-none">
                                                    <h6>{{ __('Limit:') }}</h6>
                                                    <span id="limit-preview">{{ $form->limit }}</span>
                                                </div>
                                                <div class="mt-2 mb-0 col-lg-12 set-end-date-time d-none">
                                                    <h6>{{ __('Set End Date Time:') }}</h6>
                                                    <span id="end-date-preview">{{ $form->set_end_date_time }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        {!! html()->closeModelForm() !!}
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
    <link href="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endpush
@push('script')
    <script src="{{ asset('vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script>
        var multipleCancelButton = new Choices(
            '#choices-multiple-remove-button', {
                removeItemButton: true,
            }
        );
        var multipleCancelButton = new Choices(
            '#choices-multiples-remove-button', {
                removeItemButton: true,
            }
        );
        $(".inputtags").tagsinput('items');
    </script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).on('click', "input[name$='payment']", function() {
            $('#payment').fadeToggle(500).toggleClass('d-none d-block', this.checked);
        });

        $(document).on('click', "#customswitchv1-1", function() {
            $(".paymenttype").fadeToggle(500).toggleClass('d-none', !this.checked);
        });
    </script>
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).attr('data-url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copy Link Successfully.') }}', 'success');
        }
        $(function() {
            $('body').on('click', '#share-qr-code', function() {
                var action = $(this).data('share');
                var modal = $('#common_modal2');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('QR Code') }}');
                    modal.find('.modal-body').html(response.html);
                    feather.replace();
                    modal.modal('show');
                })
            });
        });
        $(document).ready(function() {
            function toggleLimitField(checkbox) {
                var formSetting = $('.form-setting');
                if (checkbox.name === 'set_end_date') {
                    $('.set-end-date-time').toggleClass('d-none', !checkbox.checked);
                    $('#set_end_date').toggleClass('d-none', !checkbox.checked);
                } else if (checkbox.name === 'limit_status') {
                    $('.limit_preview').toggleClass('d-none', !checkbox.checked);
                    $('#limit_status').toggleClass('d-none', !checkbox.checked);
                }
                formSetting.toggleClass('d-none', !($('#m_limit_status').is(':checked') || $('#m_set_end_date').is(
                    ':checked')));
            }

            ['#m_limit_status', '#m_set_end_date'].forEach(function(id) {
                var checkbox = $(id)[0];
                toggleLimitField(checkbox);
                $(id).on('change', function() {
                    toggleLimitField(this);
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            function setupPreview(inputElement) {
                var previewId = inputElement.getAttribute('data-preview');
                var previewElement = document.getElementById(previewId);
                if (previewElement) {
                    if (inputElement.type === 'textarea') {
                        $('.client-text-section').removeClass('d-none');
                    }
                    previewElement.textContent = inputElement.value;
                }

                inputElement.addEventListener('input', function() {
                    if (previewElement) {
                        if (inputElement.type === 'textarea') {
                            $('.client-text-section').removeClass('d-none');
                        }
                        previewElement.textContent = inputElement.value;
                    }
                });
            }

            document.querySelectorAll('.preview-input').forEach(setupPreview);

            function setupCKEditorPreview(editorInstance, previewId) {
                function updatePreview() {
                    var data = editorInstance.getData();
                    if (data) {
                        $('.client-text-section').removeClass('d-none');
                    }
                    document.getElementById(previewId).innerHTML = data;
                }
                editorInstance.on('change', updatePreview);
                updatePreview();
            }

            if (!CKEDITOR.instances.success_msg) {
                CKEDITOR.replace('success_msg', {
                    filebrowserUploadUrl: "{{ route('ckeditors.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form',
                    on: {
                        instanceReady: function(evt) {
                            setupCKEditorPreview(evt.editor, 'success-message-preview');
                        }
                    }
                });
            }

            if (!CKEDITOR.instances.thanks_msg) {
                CKEDITOR.replace('thanks_msg', {
                    filebrowserUploadUrl: "{{ route('ckeditors.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form',
                    on: {
                        instanceReady: function(evt) {
                            setupCKEditorPreview(evt.editor, 'thanks-message-preview');
                        }
                    }
                });
            }
        });

        function selectFile(elementid) {
            $(`.${elementid}`).trigger('click');
            $(`.${elementid}`).change(function() {
                var url = this.value;
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (this.files && this.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext ==
                        "jpg")) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(`#${elementid}`).attr('src', e.target.result);
                        $(`#section_${elementid}`).attr('src', e.target.result);
                        $(`#${elementid}_preview`).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    $(`#${elementid}`).attr('src', '/assets/no_preview.png');

                    $(`#section_${elementid}`).attr('src', '/assets/no_preview.png');
                }
            });
        }
        $(function() {
            $('#set_end_date_time').on('click', function() {
                this.showPicker();
            });
        });
        $(document).on('click', "input[name$='password_enable']", function() {
            const target = $('#password_enable');
            if (this.checked) {
                target.fadeIn(500).removeClass('d-none').addClass('d-block');
            } else {
                target.fadeOut(500).removeClass('d-block').addClass('d-none');
            }
        });

        function toggleElementVisibility(inputName, elementId) {
            const $element = $("#" + elementId);
            const isChecked = $("input[name$='" + inputName + "']").is(":checked");

            if (isChecked) {
                $element.fadeIn(500).removeClass('d-none').addClass('d-block');
                $element.find('input, select').attr('required', 'required');
            } else {
                $element.fadeOut(500).removeClass('d-block').addClass('d-none');
                $element.find('input, select').removeAttr('required');
            }
        }
        toggleElementVisibility('set_end_date', 'set_end_date');
        toggleElementVisibility('limit_status', 'limit_status');

        $(document).on('click', "input[name$='set_end_date']", function() {
            toggleElementVisibility('set_end_date', 'set_end_date');
        });

        $(document).on('click', "input[name$='limit_status']", function() {
            toggleElementVisibility('limit_status', 'limit_status');
            if (!$("input[name$='limit_status']").is(":checked")) {
                $(".limit").val(null);
            }
        });

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('form_protection_password');
            const toggleButton = document.getElementById('togglePassword');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
            } else {
                passwordField.type = 'password';
                toggleButton.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
            }
        }
        document.getElementById('togglePassword').addEventListener('click', togglePasswordVisibility);
    </script>
    <script>
        $(document).on('click', "input[name$='assignform']", function() {
            $('#assign_form').fadeToggle(500).toggleClass('d-none d-block', this.checked);
        });

        function toggleDivs(selectedValue) {
            $("#role, #user, #public").addClass('d-none').find('select').removeAttr('required');
            $("#" + selectedValue).fadeIn(500).removeClass('d-none').find('select').attr('required', 'required');
        }
        $(document).ready(function() {
            var selectedValue = $("input[name='assign_type']:checked").val();
            toggleDivs(selectedValue);
        });
        $(document).on('click', "input[name='assign_type']", function() {
            toggleDivs($(this).val());
        });
    </script>
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
