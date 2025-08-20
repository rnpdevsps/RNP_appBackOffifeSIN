@php
    $salesforceJsonkey = isset($salesforceJsonkey) ? $salesforceJsonkey : '';
@endphp
<div class="accordion-item card aria-salesforce">
    <h2 class="accordion-header" id="heading-salesforce">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapsesalesforce-{{ $salesforceJsonkey }}" aria-expanded="false"
            aria-controls="collapsesalesforce">
            <span class="flex-1 d-flex align-items-center">
                <i class="ti ti-layout-bottombar text-primary"></i>
                {{ __('salesforce') }}
            </span>
            <div class="d-flex align-items-center">
                <a href="javascript:void(0)" class="mr-2 btn btn-danger btn-sm me-3 remove-card"><i class="ti ti-trash"></i></a>
            </div>
        </button>
    </h2>
    <div id="collapsesalesforce-{{ $salesforceJsonkey }}" class="accordion-collapse collapse"
        aria-labelledby="heading-salesforce" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="row">
                <small class="text-danger">{{ __('Note: Add Access Token to Create salesforce contacts') }}</small>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Salesforce Consumer Key'), 'sf_consumer_key' . $salesforceJsonkey)->class('form-label') !!}
                        {!! html()->text('sf_consumer_key[]', isset($salesforceJson['sf_consumer_key']) ? $salesforceJson['sf_consumer_key'] : null)->class('form-control')->id('sf_consumer_key'. $salesforceJsonkey)->required()->placeholder(__('Enter Salesforce Consumer Key')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Salesforce Consumer Secret'), 'sf_consumer_secret' . $salesforceJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'sf_consumer_secret[]',
                                isset($salesforceJson['sf_consumer_secret']) ? $salesforceJson['sf_consumer_secret'] : null,
                            )->class('form-control')->id('sf_consumer_secret' . $salesforceJsonkey)->required()->placeholder(__('Enter Salesforce Consumer secret')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Salesforce Callback Uri'), 'sf_callback_uri' . $salesforceJsonkey)->class('form-label') !!}
                        {!! html()->text('sf_callback_uri[]', isset($salesforceJson['sf_callback_uri']) ? $salesforceJson['sf_callback_uri'] : null)->class('form-control')->id('sf_callback_uri' . $salesforceJsonkey)->required()->placeholder(__('Enter Salesforce Callback Uri')) !!}

                    </div>
                </div>
                <div class="col-lg-12">
                    <p class="text-danger">
                        {{ __('Please authorize your Salesforce account Click.') }}
                        <a href="javascript:void(0)"
                            onclick="openAuthenticationWindow()">{{ __('here') }}</a>{{ __(' to authorize.') }}
                    </p>
                    <small
                        class="text-danger">{{ __('Note: To create Salesforce Leads, the required fields are firstName,
                                                                                                                        lastName, company, and status, as specified.') }}</small>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Salesforce Field'), 'salesforce_field' . $salesforceJsonkey)->class('form-label') !!}
                        <select name="salesforce_field{{ $salesforceJsonkey }}[]" class="form-select" data-trigger
                            multiple required id="salesforce_field{{ $salesforceJsonkey }}">
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
                                        @isset($salesforceFieldJsons)
                                            @if (isset($salesforceFieldJsons[$salesforceJsonkey]))
                                                @foreach ($salesforceFieldJsons as $salesforceFieldkey => $salesforceFieldJson)
                                                    @php
                                                        $salesforcearr = explode(',', $salesforceFieldJson);
                                                    @endphp
                                                    @if ($salesforceFieldkey == $salesforceJsonkey)
                                                        <option value="{{ $fornVal->name }}"
                                                            {{ in_array($fornVal->name, $salesforcearr) ? 'selected' : '' }}>
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
                        <div class="error-message" id="bouncer-error_salesforce_field{{ $salesforceJsonkey }}[]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
