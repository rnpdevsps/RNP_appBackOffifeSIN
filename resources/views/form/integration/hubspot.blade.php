@php
    $hubspotJsonkey = isset($hubspotJsonkey) ? $hubspotJsonkey : '';
@endphp
<div class="accordion-item card aria-hubspot">
    <h2 class="accordion-header" id="heading-hubspot">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapsehubspot-{{ $hubspotJsonkey }}" aria-expanded="false" aria-controls="collapsehubspot">
            <span class="flex-1 d-flex align-items-center">
                <i class="ti ti-layout-bottombar text-primary"></i>
                {{ __('Hubspot') }}
            </span>
            <div class="d-flex align-items-center">
                <a href="javascript:void(0)" class="mr-2 btn btn-danger btn-sm me-3 remove-card"><i class="ti ti-trash"></i></a>
            </div>
        </button>
    </h2>
    <div id="collapsehubspot-{{ $hubspotJsonkey }}" class="accordion-collapse collapse"
        aria-labelledby="heading-hubspot" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Hubspot Access Token'), 'hubspot_access_token' . $hubspotJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'hubspot_access_token[]',
                                isset($hubspotJson['hubspot_access_token']) ? $hubspotJson['hubspot_access_token'] : null,
                            )->class('form-control')->id('hubspot_access_token' . $hubspotJsonkey)->required()->placeholder(__('Enter hubspot Access Token')) !!}
                        <small class="text-danger">{{ __('Note: Add Access Token to Create Hubspot contacts') }}</small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Hubspot Field'), 'hubspot_field' . $hubspotJsonkey)->class('form-label') !!}
                        <select name="hubspot_field{{ $hubspotJsonkey }}[]" class="form-select" data-trigger multiple
                            required id="hubspot_field{{ $hubspotJsonkey }}">
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
                                        @isset($hubspotFieldJsons)
                                            @if (isset($hubspotFieldJsons[$hubspotJsonkey]))
                                                @foreach ($hubspotFieldJsons as $hubspotFieldkey => $hubspotFieldJson)
                                                    @php
                                                        $hubspotarr = explode(',', $hubspotFieldJson);
                                                    @endphp
                                                    @if ($hubspotFieldkey == $hubspotJsonkey)
                                                        <option value="{{ $fornVal->name }}"
                                                            {{ in_array($fornVal->name, $hubspotarr) ? 'selected' : '' }}>
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
                        <div class="error-message" id="bouncer-error_hubspot_field{{ $hubspotJsonkey }}[]"></div>
                        <small
                            class="text-danger">{{ __('Note: To create Hubspot contacts, the required fields are firstName, lastName and email as specified.') }}</small>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
