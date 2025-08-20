@extends('layouts.main')
@section('title', __('Create Form'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Create Form') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('forms.index'), __('Forms')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('forms.add'), __('Add Form')) !!}</li>
            <li class="breadcrumb-item active">{{ __('Create Form') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        {!! html()->form('POST', route('forms.store'))->id('payment-form')->class('form-horizontal')->attributes(['data-validate' => true, 'enctype' => 'multipart/form-data'])->open() !!}
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
                                        ])) active @endif" id="pills-form-setting-tab"
                                    data-bs-toggle="pill" href="#pills-form-setting" role="tab"
                                    aria-controls="pills-form-setting" aria-selected="false">{{ __('Form Setting') }}</a>
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
                                                {!! html()->label(__('Title of form'), 'title', ['class' => 'form-label']) !!}
                                                {!! html()->text('title')->class('form-control preview-input')->id('form-title')->placeholder(__('Enter title of form'))->attribute('data-preview', 'title-preview')->required() !!}
                                                @if ($errors->has('form'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('form') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                {!! html()->label(__('Category'), 'category_id', ['class' => 'form-label']) !!}
                                                {!! html()->select('category_id', $category)->class('form-select')->attribute('data-trigger')->required() !!}
                                                <small>{{ __('Create Category') }} <a
                                                        href="{{ route('form-category.index') }}">{{ __('Click here') }}</a></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                {!! html()->label(__('Select Logo'), 'form_logo', ['class' => 'form-label']) !!}
                                                <div class="setting-block position-relative">
                                                    <img src="{{ Storage::url('app-logo/78x78.png') }}" alt="images"
                                                        class="imagepreview" id="form_logo">
                                                    <div class="text-center position-absolute top-50 end-0 start-0">
                                                        <div class="choose-file">
                                                            <label for="file-2">
                                                                <button type="button" onclick="selectFile('form_logo')"
                                                                    class="btn btn-sm btn-primary"><i class="me-2"
                                                                        data-feather="upload"></i>{{ __('Select Logo') }}</button>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <input class="form-control d-none form_logo" type="file" name="form_logo"
                                                        id="file-2" multiple="" accept=".jpeg,.jpg,.png" required>
                                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Recipient Email'), 'email[]', ['class' => 'form-label']) !!}
                                                {!! html()->text('email[]')->class('form-control preview-input')->placeholder(__('Enter recipient email'))->data('preview', 'email-preview')->required() !!}

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Bcc Emails (Optional)'), 'bccemail[]', ['class' => 'form-label']) !!}
                                                {!! html()->text('bccemail[]')->class('form-control inputtags')->placeholder(__('Enter recipient bcc email')) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Cc Emails (Optional)'), 'ccemail[]', ['class' => 'form-label']) !!}
                                                {!! html()->text('ccemail[]')->class('form-control inputtags')->placeholder(__('Enter recipient cc email')) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Select Status'), 'form_status', ['class' => 'form-label']) !!}
                                                {!! html()->select('form_status', $status)->class('form-select')->data('trigger')->required() !!}
                                                <small>{{ __('Create Form Status') }} <a
                                                        href="{{ route('form-status.index') }}">{{ __('Click here') }}</a></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Short Description'), 'form_description', ['class' => 'form-label']) !!}
                                            <small>{{ __('Note') }} :-
                                                {{ __('This Description Only Show in front side') }}</small>
                                            {!! html()->textarea('form_description')->id('form_description')->placeholder(__('Enter short description'))->rows(3)->class('form-control preview-input')->data('preview', 'description-preview') !!}
                                            @if ($errors->has('form_description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('form_description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Success Message'), 'success_msg', ['class' => 'form-label']) !!}
                                            {!! html()->textarea('success_msg')->id('success_msg')->placeholder(__('Enter success message'))->class('form-control preview-input')->data('preview', 'success-message-preview') !!}

                                            @if ($errors->has('success_msg'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('success_msg') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Thanks Message'), 'thanks_msg', ['class' => 'form-label']) !!}
                                            {!! html()->textarea('thanks_msg')->id('thanks_msg')->placeholder(__('Enter client message'))->class('form-control preview-input')->data('preview', 'thanks-message-preview') !!}
                                            @if ($errors->has('thanks_msg'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('thanks_msg') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Assign Form'), 'assignform', ['class' => 'form-label']) !!}
                                            <div class="assignform" id="assign_form">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                {!! html()->label(__('Role'), 'assign_type_role', ['class' => 'form-label']) !!}
                                                                <label class="form-switch custom-switch-v1 ms-2">
                                                                    <input type="radio" name="assign_type" value="role"
                                                                        checked class="form-check-input input-primary"
                                                                        id="assign_type_role">
                                                                </label>
                                                            </div>
                                                            <div>
                                                                {!! html()->label(__('User'))->for('assign_type_user')->class('form-label') !!}
                                                                <label class="form-switch custom-switch-v1 ms-2">
                                                                    <input type="radio" name="assign_type" value="user"
                                                                        class="form-check-input input-primary"
                                                                        id="assign_type_user">
                                                                </label>
                                                            </div>
                                                            <div>
                                                                {!! html()->label(__('Public'))->for('assign_type_public')->class('form-label') !!}
                                                                <label class="form-switch custom-switch-v1 ms-2">
                                                                    <input type="radio" name="assign_type" value="public"
                                                                        class="form-check-input input-primary"
                                                                        id="assign_type_public">
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div id="role" class="desc">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        {!! html()->label(__('Role'))->for('roles')->class('form-label') !!}
                                                                        {!! html()->select('roles[]', $roles, null)->class('form-control role-remove')->id('choices-multiple-remove-button')->multiple() !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="user" class="desc d-none">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        {!! html()->label(__('User'))->for('users')->class('form-label') !!}
                                                                        {!! html()->select('users[]', $users, null)->class('form-control')->id('choices-multiples-remove-button')->multiple() !!}
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
                                            {!! html()->label(__('Form Fill Edit Lock'))->for('form_fill_edit_lock')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="form_fill_edit_lock" id="form_fill_edit_lock"
                                                    class="form-check-input input-primary"
                                                    {{ old('form_fill_edit_lock') == 'on' ? 'checked' : (!old() ? 'checked' : '') }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Enable Session Timer'))->for('enable_session_timer')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="enable_session_timer" id="enable_session_timer"
                                                    class="form-check-input input-primary"
                                                    {{ old('enable_session_timer') == 'on' ? 'checked' : '' }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Allow Comments'))->for('allow_comments')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="allow_comments" id="allow_comments"
                                                    class="form-check-input input-primary"
                                                    {{ old('allow_comments') == 'on' ? 'checked' : 'unchecked' }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Allow Share Section'))->for('allow_share_section')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="allow_share_section" id="allow_share_section"
                                                    class="form-check-input input-primary"
                                                    {{ old('allow_share_section') == 'on' ? 'checked' : 'unchecked' }}>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Set limit'))->for('limit_status')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="hidden" name="limit_status" value="0">
                                                <input type="checkbox" name="limit_status" id="m_limit_status"
                                                    class="form-check-input input-primary"
                                                    {{ old('limit_status') == 1 ? 'checked' : 'unchecked' }}
                                                    value="1">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="limit_status" class="d-none">
                                        <div class="form-group">
                                            {!! html()->number('limit')->class('form-control preview-input')->placeholder(__('limit'))->id('form-limit')->attribute('data-preview', 'limit-preview') !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Password Protection Enable'))->for('form_password_enable')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="hidden" name="password_enable" value="0">
                                                <input type="checkbox" name="password_enable" id="form_password_enable"
                                                    class="form-check-input input-primary"
                                                    {{ old('password_enable') == 1 ? 'checked' : 'unchecked' }}
                                                    value="1">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="password_enable" class="{{ 'd-none' }}">
                                        <div class="form-group">
                                            <div class="position-relative password-toggle">
                                                {!! html()->password('form_password')->class('form-control password-toggle-input')->placeholder(__('************'))->id('form_protection_password')->attribute('autocomplete', 'off') !!}

                                                <div class="input-group-append password-toggle-icon" id="togglePassword">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </div>

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
                                                    {{ old('set_end_date') == 1 ? 'checked' : 'unchecked' }}
                                                    value="1">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="set_end_date" class="{{ 'd-none' }}">
                                        <div class="form-group">
                                            <input class="form-control preview-input" name="set_end_date_time"
                                                id="set_end_date_time" data-preview ="end-date-preview"
                                                type="datetime-local">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Feedback Enable'))->for('feedback_enabled')->class('form-label') !!}
                                            <label class="mt-2 form-switch float-end custom-switch-v1">
                                                <input type="checkbox" name="feedback_enabled" id="feedback_enabled"
                                                    class="form-check-input input-primary"
                                                    {{ old('feedback_enabled') == 'on' ? 'checked' : 'unchecked' }}>
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
                                {!! html()->a(route('forms.index'), __('Cancel'))->class('btn btn-secondary') !!}
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
                                                        <img src="{{ Storage::url('app-logo/78x78.png') }}"
                                                            id="form_logo_preview" alt="image">
                                                    </div>
                                                    <h3 id="title-preview" class="text-black">
                                                    </h3>
                                                    <h6 id="email-preview" class="text-black">
                                                    </h6>
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
                                                    <span id="limit-preview"></span>
                                                </div>
                                                <div class="mt-2 mb-0 col-lg-12 set-end-date-time d-none">
                                                    <h6>{{ __('Set End Date Time:') }}</h6>
                                                    <span id="end-date-preview"></span>
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
        {!! html()->form()->close() !!}
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}" />
    <link href="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <style>
        #bouncer-error_form_logo {
            width: 75%;
            margin-left: auto;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
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
        $(document).ready(function() {
            function toggleLimitField(checkbox) {
                var isChecked = checkbox.checked;
                var targetClass = checkbox.name === 'set_end_date' ? '.set-end-date-time' : '.limit_preview';
                $(targetClass).toggleClass('d-none', !isChecked);
                $('#' + checkbox.name).toggleClass('d-none', !isChecked);

                $('.form-setting').toggleClass('d-none', !($('#m_limit_status').is(':checked') || $(
                    '#m_set_end_date').is(':checked')));
            }

            ['#m_limit_status', '#m_set_end_date'].forEach(function(id) {
                var checkbox = $(id)[0];
                toggleLimitField(checkbox);
                $(checkbox).on('change', function() {
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
        $(document).on('click', '.theme-button button', function() {
            var url = $(this).attr('data-url');
            var modal = $('#common_modal');
            $.ajax({
                type: "GET",
                url: url,
                data: {},
                success: function(response) {
                    modal.find('.modal-title').html('{{ __('Select Theme Color') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                    modal.find('.theme-colors').click(function() {
                        $('.theme-colors').removeClass('active_color');
                        $(this).addClass('active_color');

                    });
                    modal.find('#save-btn').click(function() {
                        var color = $('.active_color').attr('data-value');
                        $('input[name="theme_color"]').val(color);
                    });
                },
                error: function(error) {}
            });
        });
        $(document).on('click', '.theme-view-hover', function() {
            var theme = $(this).find('img').attr('data-id');
            $('input[name="theme"]').val(theme);
            $('.theme-view-card').removeClass('selected-theme');
            $(this).parents('.theme-view-card').addClass('selected-theme');
        });
    </script>
    <script>
        $(function() {
            $('#set_end_date_time').on('click', function() {
                this.showPicker();
            });
        });

        function toggleElementVisibility(inputName, elementId) {
            const $element = $("#" + elementId);
            const isChecked = $("input[name$='" + inputName + "']").is(":checked");

            if (isChecked) {
                $element.fadeIn(500).removeClass('d-none').addClass('d-block').find('input, select').attr('required',
                    'required');
            } else {
                $element.fadeOut(500).removeClass('d-block').addClass('d-none').find('input, select').removeAttr(
                'required');
            }
        }

        toggleElementVisibility('set_end_date', 'set_end_date');
        toggleElementVisibility('limit_status', 'limit_status');
        toggleElementVisibility('password_enable', 'password_enable');

        $(document).on('click', "input[name$='set_end_date']", function() {
            toggleElementVisibility('set_end_date', 'set_end_date');
        });

        $(document).on('click', "input[name$='limit_status']", function() {
            toggleElementVisibility('limit_status', 'limit_status');
        });

        $(document).on('click', "input[name$='password_enable']", function() {
            toggleElementVisibility('password_enable', 'password_enable');
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
        function toggleDivs(selectedValue) {
            $("#role, #user, #public").addClass('d-none').find('select').removeAttr('required');
            $("#" + selectedValue).fadeIn(500).removeClass('d-none').find('select').attr('required', 'required');
        }
        var selectedValue = $("input[name='assign_type']:checked").val();
        toggleDivs(selectedValue);
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
