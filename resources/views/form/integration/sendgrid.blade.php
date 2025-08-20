@php
    $sendgridJsonkey = isset($sendgridJsonkey) ? $sendgridJsonkey : '';
@endphp
<div class="accordion-item card aria-sendgrid">
    <h2 class="accordion-header" id="heading-sendgrid">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapsesendgrid-{{ $sendgridJsonkey }}" aria-expanded="false"
            aria-controls="collapsesendgrid">
            <span class="flex-1 d-flex align-items-center">
                <i class="ti ti-layout-bottombar text-primary"></i>
                {{ __('SendGrid') }}
            </span>
            <div class="d-flex align-items-center">
                <a href="javascript:void(0)" class="mr-2 btn btn-danger btn-sm me-3 remove-card"><i class="ti ti-trash"></i></a>
            </div>
        </button>
    </h2>
    <div id="collapsesendgrid-{{ $sendgridJsonkey }}" class="accordion-collapse collapse"
        aria-labelledby="heading-sendgrid" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Email'), 'sendgrid_email' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->email('sendgrid_email[]', isset($sendgridJson['sendgrid_email']) ? $sendgridJson['sendgrid_email'] : null)->class('form-control')->id('sendgrid_email' . $sendgridJsonkey)->required()->placeholder(__('Enter SendGrid email')) !!}
                        <small>{{ __("Note: Please enter the email address to be used as the sender's email for sendgrid.") }}</small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Host'), 'sendgrid_host' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->text('sendgrid_host[]', isset($sendgridJson['sendgrid_host']) ? $sendgridJson['sendgrid_host'] : null)->class('form-control')->id('sendgrid_host' . $sendgridJsonkey)->required()->placeholder(__('Enter sendgrid host')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Port'), 'sendgrid_port' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->text('sendgrid_port[]', isset($sendgridJson['sendgrid_port']) ? $sendgridJson['sendgrid_port'] : null)->class('form-control')->id('sendgrid_port' . $sendgridJsonkey)->required()->placeholder(__('Enter sendgrid port')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Username'), 'sendgrid_username' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->text('sendgrid_username[]', isset($sendgridJson['sendgrid_username']) ? $sendgridJson['sendgrid_username'] : null)->class('form-control')->id('sendgrid_username' . $sendgridJsonkey)->required()->placeholder(__('Enter sendgrid username')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Password'), 'sendgrid_password' . $sendgridJsonkey)->class('form-label') !!}
                        <input name="sendgrid_password[]" type="password" class="form-control" required
                            placeholder="{{ __('Enter sendgrid password') }}"
                            value="{{ isset($sendgridJson['sendgrid_password']) ? $sendgridJson['sendgrid_password'] : '' }}"
                            id="sendgrid_password{{ $sendgridJsonkey }}">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Encryption'), 'sendgrid_encryption' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'sendgrid_encryption[]',
                                isset($sendgridJson['sendgrid_encryption']) ? $sendgridJson['sendgrid_encryption'] : null,
                            )->class('form-control')->id('sendgrid_encryption' . $sendgridJsonkey)->required()->placeholder(__('Enter sendgrid encryption')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid From Address'), 'sendgrid_from_address' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'sendgrid_from_address[]',
                                isset($sendgridJson['sendgrid_from_address']) ? $sendgridJson['sendgrid_from_address'] : null,
                            )->class('form-control')->id('sendgrid_from_address' . $sendgridJsonkey)->required()->placeholder(__('Enter sendgrid from address')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid From Name'), 'sendgrid_from_name' . $sendgridJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'sendgrid_from_name[]',
                                isset($sendgridJson['sendgrid_from_name']) ? $sendgridJson['sendgrid_from_name'] : null,
                            )->class('form-control')->id('sendgrid_from_name' . $sendgridJsonkey)->required()->placeholder(__('Enter sendgrid from name')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('SendGrid Field'), 'sendgrid_field' . $sendgridJsonkey)->class('form-label') !!}
                        <select name="sendgrid_field{{ $sendgridJsonkey }}[]" class="form-select" data-trigger multiple
                            required id="sendgrid_field{{ $sendgridJsonkey }}">
                            <option value="">{{ __('Select Value') }}</option>
                            @foreach ($formJsons as $formJson)
                                @foreach ($formJson as $fornVal)
                                    @php
                                        $excludedTypes = [
                                            'button',
                                            'header',
                                            'hidden',
                                            'paragraph',
                                            'video',
                                            'selfie',
                                            'break',
                                            'location',
                                        ];
                                    @endphp
                                    @if (!in_array($fornVal->type, $excludedTypes))
                                        @isset($sendgridFieldJsons)
                                            @if (isset($sendgridFieldJsons[$sendgridJsonkey]))
                                                @foreach ($sendgridFieldJsons as $sendgridFieldkey => $sendgridFieldJson)
                                                    @php
                                                        $sendgridarr = explode(',', $sendgridFieldJson);
                                                    @endphp
                                                    @if ($sendgridFieldkey == $sendgridJsonkey)
                                                        <option value="{{ $fornVal->name }}"
                                                            {{ in_array($fornVal->name, $sendgridarr) ? 'selected' : '' }}>
                                                            {{ $fornVal->label . ' (' . $fornVal->name . ')' }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @else
                                                <option value="{{ $fornVal->name }}">
                                                    {{ $fornVal->label . ' (' . $fornVal->name . ')' }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $fornVal->name }}">
                                                {{ $fornVal->label . ' (' . $fornVal->name . ')' }}
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                        <div class="error-message" id="bouncer-error_sendgrid_field{{ $sendgridJsonkey }}[]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
