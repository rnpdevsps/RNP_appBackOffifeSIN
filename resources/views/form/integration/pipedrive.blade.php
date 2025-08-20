@php
    $pipedriveJsonkey = isset($pipedriveJsonkey) ? $pipedriveJsonkey : '';
@endphp

<div class="accordion-item card aria-pipedrive">
    <h2 class="accordion-header" id="heading-pipedrive">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapsepipedrive-{{ $pipedriveJsonkey }}" aria-expanded="false"
            aria-controls="collapsepipedrive">
            <span class="flex-1 d-flex align-items-center">
                <i class="ti ti-layout-bottombar text-primary"></i>
                {{ __('PipeDrive') }}
            </span>
            <div class="d-flex align-items-center">
                <a href="javascript:void(0)" class="mr-2 btn btn-danger btn-sm me-3 remove-card"><i class="ti ti-trash"></i></a>
            </div>
        </button>
    </h2>
    <div id="collapsepipedrive-{{ $pipedriveJsonkey }}" class="accordion-collapse collapse"
        aria-labelledby="heading-pipedrive" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Pipedrive API Token'), 'pipedrive_api_token' . $pipedriveJsonkey)->class('form-label') !!}
                        {!! html()->text(
                                'pipedrive_api_token[]',
                                isset($pipedriveJson['pipedrive_api_token']) ? $pipedriveJson['pipedrive_api_token'] : null,
                            )->class('form-control')->id("pipedrive_api_token$pipedriveJsonkey")->required()->placeholder(__('Enter API Token')) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            {!! html()->checkbox(
                                    'pipedrive_type[]',
                                    isset($pipedriveJson['pipedrive_type']) ? in_array('activity', $pipedriveJson['pipedrive_type']) : null,
                                    'activity',
                                )->class('form-check-input')->id('activity' . $pipedriveJsonkey) !!}
                            {!! html()->label(__('Create Activity'), "  riveJsonkey")->class('form-check-label') !!}
                        </div>
                        <div class="form-check form-check-inline">
                            {!! html()->checkbox(
                                    'pipedrive_type[]',
                                    isset($pipedriveJson['pipedrive_type']) ? in_array('person', $pipedriveJson['pipedrive_type']) : null,
                                    'person',
                                )->class('form-check-input')->id('person' . $pipedriveJsonkey) !!}
                            {!! html()->label(__('Create Person'), 'person' . $pipedriveJsonkey)->class('form-check-label') !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! html()->label(__('Pipedrive Field'), 'pipedrive_field' . $pipedriveJsonkey)->class('form-label') !!}
                        <select name="pipedrive_field{{ $pipedriveJsonkey }}[]" class="form-select" data-trigger
                            multiple required id="pipedrive_field{{ $pipedriveJsonkey }}">
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
                                        @isset($pipedriveFieldJsons)
                                            @if (isset($pipedriveFieldJsons[$pipedriveJsonkey]))
                                                @foreach ($pipedriveFieldJsons as $pipedriveFieldkey => $pipedriveFieldJson)
                                                    @php
                                                        $pipedrivearr = explode(',', $pipedriveFieldJson);
                                                    @endphp
                                                    @if ($pipedriveFieldkey == $pipedriveJsonkey)
                                                        <option value="{{ $fornVal->name }}"
                                                            {{ in_array($fornVal->name, $pipedrivearr) ? 'selected' : '' }}>
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
                        <div class="error-message" id="bouncer-error_pipedrive_field{{ $pipedriveJsonkey }}[]"></div>
                        <small
                            class="text-danger">{{ __('Note: To create Person or Activity name ,email, phone and due_date feild is required') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
