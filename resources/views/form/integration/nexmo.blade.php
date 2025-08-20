@php
    $nexmoJsonkey = isset($nexmoJsonkey) ? $nexmoJsonkey : '';
@endphp
<div class="accordion-item card aria-nexmo">
    <h2 class="accordion-header" id="heading-nexmo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapsenexmo-{{ $nexmoJsonkey }}" aria-expanded="false" aria-controls="collapsenexmo">
            <span class="flex-1 d-flex align-items-center">
                <i class="ti ti-layout-bottombar text-primary"></i>
                {{ __('Nexmo') }}
            </span>
            <div class="d-flex align-items-center">
                <a href="javascript:void(0)" class="mr-2 btn btn-danger btn-sm me-3 remove-card"><i class="ti ti-trash"></i></a>
            </div>
        </button>
    </h2>
    <div id="collapsenexmo-{{ $nexmoJsonkey }}" class="accordion-collapse collapse" aria-labelledby="heading-nexmo"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Nexmo Number'), 'nexmo_number' . $nexmoJsonkey)->class('form-label') !!}
                        {!! html()->number('nexmo_number[]', isset($nexmoJson['nexmo_number']) ? $nexmoJson['nexmo_number'] : null)->class('form-control')->id('nexmo_number' . $nexmoJsonkey)->required()->placeholder(__('Enter nexmo number')) !!}
                        <small>{{ __('Note: Please enter the mobile number for send sms. Example: 911234567890') }}</small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Nexmo Key'), 'nexmo_key' . $nexmoJsonkey)->class('form-label') !!}
                        {!! html()->text('nexmo_key[]', isset($nexmoJson['nexmo_key']) ? $nexmoJson['nexmo_key'] : null)->class('form-control')->id('nexmo_key' . $nexmoJsonkey)->required()->placeholder(__('Enter nexmo key')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Nexmo Secret'), 'nexmo_secret' . $nexmoJsonkey)->class('form-label') !!}
                        {!! html()->text('nexmo_secret[]', isset($nexmoJson['nexmo_secret']) ? $nexmoJson['nexmo_secret'] : null)->class('form-control')->id('nexmo_secret' . $nexmoJsonkey)->required()->placeholder(__('Enter nexmo secret')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Nexmo Field'), 'nexmo_field' . $nexmoJsonkey)->class('form-label') !!}
                        <select name="nexmo_field{{ $nexmoJsonkey }}[]" class="form-select" data-trigger multiple
                            required id="nexmo_field{{ $nexmoJsonkey }}">
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
                                        @isset($nexmoFieldJsons)
                                            @if (isset($nexmoFieldJsons[$nexmoJsonkey]))
                                                @foreach ($nexmoFieldJsons as $nexmoFieldkey => $nexmoFieldJson)
                                                    @php
                                                        $nexmoarr = explode(',', $nexmoFieldJson);
                                                    @endphp
                                                    @if ($nexmoFieldkey == $nexmoJsonkey)
                                                        <option value="{{ $fornVal->name }}"
                                                            {{ in_array($fornVal->name, $nexmoarr) ? 'selected' : '' }}>
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
                        <div class="error-message" id="bouncer-error_nexmo_field{{ $nexmoJsonkey }}[]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
