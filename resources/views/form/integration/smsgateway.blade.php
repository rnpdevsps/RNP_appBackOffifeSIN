@php
    $smsgatewayJsonkey = isset($smsgatewayJsonkey) ? $smsgatewayJsonkey : '';
@endphp
<div class="accordion-item card aria-smsgateway">
    <h2 class="accordion-header" id="heading-smsgateway">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapsesmsgateway-{{ $smsgatewayJsonkey }}" aria-expanded="false"
            aria-controls="collapsesmsgateway">
            <span class="flex-1 d-flex align-items-center">
                <i class="ti ti-layout-bottombar text-primary"></i>
                {{ __('SmsGateway') }}
            </span>
            <div class="d-flex align-items-center">
                <a href="javascript:void(0)" class="mr-2 btn btn-danger btn-sm me-3 remove-card"><i class="ti ti-trash"></i></a>
            </div>
        </button>
    </h2>
    <div id="collapsesmsgateway-{{ $smsgatewayJsonkey }}" class="accordion-collapse collapse"
        aria-labelledby="heading-smsgateway" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Smsgateway Number'), 'smsgateway_number' . $smsgatewayJsonkey)->class('form-label') !!}
                        {!! html()->number(
                                'smsgateway_number[]',
                                isset($smsgatewayJson['smsgateway_number']) ? $smsgatewayJson['smsgateway_number'] : null,
                            )->class('form-control')->id('smsgateway_number' . $smsgatewayJsonkey)->required()->placeholder(__('Enter smsgateway number')) !!}
                        <small>{{ __('Note: Please enter the mobile number for send sms. Example: 911234567890') }}</small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Smsgateway Api Key'), 'smsgateway_api_key' . $smsgatewayJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'smsgateway_api_key[]',
                                isset($smsgatewayJson['smsgateway_api_key']) ? $smsgatewayJson['smsgateway_api_key'] : null,
                            )->class('form-control')->id('smsgateway_api_key' . $smsgatewayJsonkey)->required()->placeholder(__('Enter smsgateway api key')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Smsgateway User Id'), 'smsgateway_user_id' . $smsgatewayJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'smsgateway_user_id[]',
                                isset($smsgatewayJson['smsgateway_user_id']) ? $smsgatewayJson['smsgateway_user_id'] : null,
                            )->class('form-control')->id('smsgateway_user_id' . $smsgatewayJsonkey)->required()->placeholder(__('Enter smsgateway user id')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Smsgateway User Password'), 'smsgateway_user_password' . $smsgatewayJsonkey)->class('form-label') !!}
                        {!! html()->password('smsgateway_user_password[]')->class('form-control')->id('smsgateway_user_password' . $smsgatewayJsonkey)->required()->placeholder(__('Enter smsgateway user password'))->value(isset($smsgatewayJson['smsgateway_user_password']) ? $smsgatewayJson['smsgateway_user_password'] : '') !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Smsgateway Sender Id'), 'smsgateway_sender_id' . $smsgatewayJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'smsgateway_sender_id[]',
                                isset($smsgatewayJson['smsgateway_sender_id']) ? $smsgatewayJson['smsgateway_sender_id'] : null,
                            )->class('form-control')->id('smsgateway_sender_id' . $smsgatewayJsonkey)->required()->placeholder(__('Enter smsgateway sender id')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Smsgateway Field'), 'smsgateway_field' . $smsgatewayJsonkey)->class('form-label') !!}
                        <select name="smsgateway_field{{ $smsgatewayJsonkey }}[]" class="form-select" data-trigger
                            required multiple id="smsgateway_field{{ $smsgatewayJsonkey }}">
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
                                            'file',
                                            'SignaturePad',
                                        ];
                                    @endphp
                                    @if (!in_array($fornVal->type, $excludedTypes))
                                        @isset($smsgatewayFieldJsons)
                                            @if (isset($smsgatewayFieldJsons[$smsgatewayJsonkey]))
                                                @foreach ($smsgatewayFieldJsons as $smsgatewayFieldkey => $smsgatewayFieldJson)
                                                    @php
                                                        $smsgatewayarr = explode(',', $smsgatewayFieldJson);
                                                    @endphp
                                                    @if ($smsgatewayFieldkey == $smsgatewayJsonkey)
                                                        <option value="{{ $fornVal->name }}"
                                                            {{ in_array($fornVal->name, $smsgatewayarr) ? 'selected' : '' }}>
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
                        <div class="error-message" id="bouncer-error_smsgateway_field{{ $smsgatewayJsonkey }}[]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
