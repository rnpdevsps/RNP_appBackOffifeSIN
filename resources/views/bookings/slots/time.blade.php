@extends('layouts.main')
@section('title', __('Time Wise Booking'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Time Wise Booking') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('bookings.index'))->text(__('Booking')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('booking.design', $booking->id))->text(__('Booking Design')) !!}</li>
            <li class="breadcrumb-item">{{ __('Time Wise Booking') }}</li>
        </ul>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5> {{ __('Time Wise Booking') }}</h5>
                    </div>
                    {!! html()->modelForm($timeWiseBooking, 'POST', route('booking.slots.setting.update', $booking->id))->attribute('enctype', 'multipart/form-data')->attribute('data-validate', true)->open() !!}
                    <div class="card-body">
                        <div id="step1" class="row step">
                            <div class="mb-2 col-lg-12">
                                <strong>{{ __('Slot Duration') }}</strong>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {{-- Blade Template --}}
                                    <!-- HTML Template -->
                                    <input type="radio" name="slot_duration" value="15" id="slot_duration_1"
                                        class="btn-check" {!! $timeWiseBooking && $timeWiseBooking->slot_duration_minutes == '15' ? 'checked' : '' !!} />
                                    <label for="slot_duration_1" class="btn btn-outline-primary">{{ __('15 Min') }}</label>

                                    <input type="radio" name="slot_duration" value="30" id="slot_duration_2"
                                        class="btn-check" {!! $timeWiseBooking && $timeWiseBooking->slot_duration_minutes == '30' ? 'checked' : '' !!} />
                                    <label for="slot_duration_2" class="btn btn-outline-primary">{{ __('30 Min') }}</label>

                                    <input type="radio" name="slot_duration" value="45" id="slot_duration_3"
                                        class="btn-check" {!! $timeWiseBooking && $timeWiseBooking->slot_duration_minutes == '45' ? 'checked' : '' !!} />
                                    <label for="slot_duration_3" class="btn btn-outline-primary">{{ __('45 Min') }}</label>

                                    <input type="radio" name="slot_duration" value="60" id="slot_duration_4"
                                        class="btn-check" {!! $timeWiseBooking && $timeWiseBooking->slot_duration_minutes == '60' ? 'checked' : '' !!} />
                                    <label for="slot_duration_4" class="btn btn-outline-primary">{{ __('60 Min') }}</label>

                                    <input type="radio" name="slot_duration" value="custom_min" id="slot_duration_5"
                                        class="btn-check" {!! $timeWiseBooking && $timeWiseBooking->slot_duration == 'custom_min' ? 'checked' : '' !!} />
                                    <label for="slot_duration_5"
                                        class="btn btn-outline-primary">{{ __('Custom Min') }}</label>

                                </div>
                                <div id="custom_min"
                                    class="row {{ $timeWiseBooking && $timeWiseBooking->slot_duration == 'custom_min' ? '' : 'd-none' }}">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! html()->label(__('Slot Duration Minutes'), 'slot_duration_minutes')->class('form-label') !!}
                                            {!! html()->number(
                                                    'slot_duration_minutes',
                                                    $timeWiseBooking && $timeWiseBooking->slot_duration_minutes ? $timeWiseBooking->slot_duration_minutes : null,
                                                )->class('form-control')->attribute('placeholder', __('Enter slot duration minutes')) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Services'), 'services')->class('form-label') !!}
                                        {!! html()->textarea('services')->class('form-control')->attribute('required')->attribute('placeholder', __('Enter services')) !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step2" class="row step d-none">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! html()->label(__('Week Time'))->class('form-label')->for('week_time') !!}
                                    {!! html()->select('week_time[]', $weekTimes, $selectedweektime)->class('form-select')->required()->multiple()->id('week_time')->attribute('data-trigger', '') !!}
                                    <div class="error-message" id="bouncer-error_week_time"></div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! html()->label(__('Session Time'), 'intervals_time')->class('form-label') !!}
                                        </div>
                                        <div class="col-md-4 form-check form-switch custom-switch-v1">
                                            <input class="form-check-input float-end" id="intervals_time"
                                                {{ $timeWiseBooking && $timeWiseBooking->intervals_time_status == '1' ? 'checked' : 'unchecked' }}
                                                name="intervals_time" type="checkbox">
                                            <span class="custom-switch-indicator"></span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="form-group intervals-time {{ $timeWiseBooking && $timeWiseBooking->intervals_time_status == '1' ? '' : 'd-none' }}">
                                    <div class="repeater1">
                                        <div data-repeater-list="interval_times">
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            {!! html()->label(__('To'), 'start_time')->class('form-label') !!}
                                                            {!! html()->text('start_time')->class('form-control timePicker')->placeholder(__('Select start time'))->id('start_time') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            {!! html()->label(__('From'), 'end_time')->class('form-label') !!}
                                                            {!! html()->text('end_time')->class('form-control timePicker')->placeholder(__('Select end time'))->id('end_time') !!}
                                                        </div>
                                                    </div>
                                                    <div class="text-right mt-27 col-md-2">
                                                        <input data-repeater-delete class="btn btn-danger" type="button"
                                                            value="Delete" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input data-repeater-create class="btn btn-primary" type="button" value="Add" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    {!! html()->label(__('Start Booking & End Booking Date'), 'limit_time_status')->class('form-label') !!}
                                                </div>
                                                <div class="col-md-4 form-check form-switch custom-switch-v1">
                                                    <input class="form-check-input float-end" id="limit_time_status"
                                                        {{ $timeWiseBooking && $timeWiseBooking->limit_time_status == '1' ? 'checked' : 'unchecked' }}
                                                        name="limit_time_status" type="checkbox">
                                                    <span class="custom-switch-indicator"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="limitTimeDate"
                                    class="row {{ $timeWiseBooking && $timeWiseBooking->limit_time_status == '1' ? '' : 'd-none' }}">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! html()->label(__('Start Date'), 'start_date')->class('form-label') !!}
                                            {!! html()->text(
                                                    'start_date',
                                                    $timeWiseBooking && $timeWiseBooking->start_date
                                                        ? \Carbon\Carbon::parse($timeWiseBooking->start_date)->format('d/m/Y')
                                                        : null,
                                                )->class('form-control datePicker')->placeholder(__('Select start date'))->id('start_date') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! html()->label(__('End Date'), 'end_date')->class('form-label') !!}

                                            {!! html()->text(
                                                    'end_date',
                                                    $timeWiseBooking && $timeWiseBooking->end_date
                                                        ? \Carbon\Carbon::parse($timeWiseBooking->end_date)->format('d/m/Y')
                                                        : null,
                                                )->class('form-control datePicker')->placeholder(__('Select end date'))->id('end_date') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! html()->label(__('Rolling Days'), 'rolling_days_status')->class('form-label') !!}
                                        </div>
                                        <div class="col-md-4 form-check form-switch custom-switch-v1">
                                            <input class="form-check-input float-end" id="rolling_days_status"
                                                {{ $timeWiseBooking && $timeWiseBooking->rolling_days_status == '1' ? 'checked' : 'unchecked' }}
                                                name="rolling_days_status" type="checkbox">
                                            <span class="custom-switch-indicator"></span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="form-group rolling_days {{ $timeWiseBooking && $timeWiseBooking->rolling_days_status == '1' ? '' : 'd-none' }}">
                                    {!! html()->label(__('Rolling Days'), 'rolling_days')->class('form-label') !!}
                                    {!! html()->number('rolling_days')->class('form-control')->placeholder(__('Enter rolling days'))->id('rolling_days') !!}
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! html()->label(__('Maximum Limit'), 'maximum_limit_status')->class('form-label') !!}
                                        </div>
                                        <div class="col-md-4 form-check form-switch custom-switch-v1">
                                            <input class="form-check-input float-end" id="maximum_limit_status"
                                                {{ $timeWiseBooking && $timeWiseBooking->maximum_limit_status == '1' ? 'checked' : 'unchecked' }}
                                                name="maximum_limit_status" type="checkbox">
                                            <span class="custom-switch-indicator"></span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="form-group maximum_limit {{ $timeWiseBooking && $timeWiseBooking->maximum_limit_status == '1' ? '' : 'd-none' }}">
                                    {!! html()->label(__('Maximum Limit'), 'maximum_limit')->class('form-label') !!}
                                    {!! html()->number('maximum_limit')->class('form-control')->placeholder(__('Enter maximum limit'))->id('maximum_limit') !!}
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! html()->label(__('Multiple Booking'), 'multiple_booking')->class('form-label') !!}
                                        </div>
                                        <div class="col-md-4 form-check form-switch custom-switch-v1">
                                            <input class="form-check-input float-end" id="multiple_booking"
                                                {{ $timeWiseBooking && $timeWiseBooking->multiple_booking == '1' ? 'checked' : 'unchecked' }}
                                                name="multiple_booking" type="checkbox">
                                            <span class="custom-switch-indicator"></span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="form-group multiple_booking_limit {{ $timeWiseBooking && $timeWiseBooking->multiple_booking == '1' ? '' : 'd-none' }}">
                                    {!! html()->label(__('Maximum Booking Limit'), 'multiple_booking_limit')->class('form-label') !!}
                                    {!! html()->input('number', 'multiple_booking_limit', null)->class('form-control')->placeholder(__('Enter maximum booking limit'))->id('multiple_booking_limit') !!}
                                </div>
                            </div>
                        </div>
                        <div id="step3" class="row step d-none">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! html()->label(__('Email Notification'), 'email_notification')->class('form-label') !!}
                                        </div>
                                        <div class="col-md-4 form-check form-switch custom-switch-v1">
                                            <input class="form-check-input float-end" id="email_notification"
                                                {{ $timeWiseBooking && $timeWiseBooking->email_notification == '1' ? 'checked' : 'unchecked' }}
                                                name="email_notification" type="checkbox">
                                            <span class="custom-switch-indicator"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! html()->label(__('Time Zone'), 'time_zone')->class('form-label') !!}
                                    <select class="form-control" name="time_zone" id="time_zone" data-trigger>
                                        <option value="">{{ __('Select time zone') }}</option>
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}"
                                                {{ $timeWiseBooking && $timeWiseBooking->time_zone == $timezone ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-lg-12">
                                        <strong>{{ __('Date Format') }}</strong>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="radio" name="date_format" value="m/d/Y" id="date_format_1"
                                                class="btn-check"
                                                {{ $timeWiseBooking && $timeWiseBooking->date_format == 'm/d/Y' ? 'checked' : '' }}>
                                            <label for="date_format_1"
                                                class="btn btn-outline-primary">{{ __('MM/DD/YYYY') }}</label>

                                            <input type="radio" name="date_format" value="d/m/Y" id="date_format_2"
                                                class="btn-check"
                                                {{ $timeWiseBooking && $timeWiseBooking->date_format == 'd/m/Y' ? 'checked' : '' }}>
                                            <label for="date_format_2"
                                                class="btn btn-outline-primary">{{ __('DD/MM/YYYY') }}</label>

                                            <input type="radio" name="date_format" value="Y/m/d" id="date_format_3"
                                                class="btn-check"
                                                {{ $timeWiseBooking && $timeWiseBooking->date_format == 'Y/m/d' ? 'checked' : '' }}>
                                            <label for="date_format_3"
                                                class="btn btn-outline-primary">{{ __('YYYY/MM/DD') }}</label>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-lg-12">
                                        <strong>{{ __('Time Format') }}</strong>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="radio" name="time_format" value="24_hour" id="time_format_1"
                                                class="btn-check"
                                                {{ $timeWiseBooking && $timeWiseBooking->time_format == '24_hour' ? 'checked' : '' }}>
                                            <label for="time_format_1"
                                                class="btn btn-outline-primary">{{ __('24 Hour') }}</label>

                                            <input type="radio" name="time_format" value="am/pm" id="time_format_2"
                                                class="btn-check"
                                                {{ $timeWiseBooking && $timeWiseBooking->time_format == 'am/pm' ? 'checked' : '' }}>
                                            <label for="time_format_2"
                                                class="btn btn-outline-primary">{{ __('AM/PM') }}</label>

                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    {!! html()->label(__('Enable Note'), 'enable_note')->class('form-label') !!}
                                                </div>
                                                <div class="col-md-4 form-check form-switch custom-switch-v1">
                                                    <input class="form-check-input float-end" id="enable_note"
                                                        {{ $timeWiseBooking && $timeWiseBooking->enable_note == '1' ? 'checked' : 'unchecked' }}
                                                        name="enable_note" type="checkbox">
                                                    <span class="custom-switch-indicator"></span>
                                                </div>
                                            </div>
                                            <div
                                                class="form-group enable_note {{ $timeWiseBooking && $timeWiseBooking->enable_note == '1' ? '' : 'd-none' }}">
                                                {!! html()->label(__('Note'), 'note')->class('form-label') !!}
                                                {!! html()->textarea('note', null)->class('form-control')->placeholder(__('Enter note'))->rows(3)->id('note') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-start">
                            <button id="prevButton" type="button" class="btn btn-secondary d-none"><i
                                    class="ti ti-chevron-left me-2"></i>{{ __('Previous') }}</button>
                        </div>
                        <div class="mb-3 btn-flt float-end">
                            <button id="nextButton" type="button" class="btn btn-primary">{{ __('Next') }}<i
                                    class="ti ti-chevron-right ms-2"></i></button>
                            <button id="submitButton" type="submit"
                                class="btn btn-primary d-none">{{ __('Submit') }}</button>
                        </div>
                    </div>
                    {!! html()->form()->close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/repeater/reapeater.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var currentStep = 1;

            function showStep(stepNumber) {
                $('.step').addClass('d-none');
                $('#step' + stepNumber).removeClass('d-none');
                if (currentStep === 3) {
                    $('#nextButton').addClass('d-none');
                    $('#submitButton').removeClass('d-none');
                    $('#prevButton').removeClass('d-none');
                } else if (currentStep === 1) {
                    $('#nextButton').removeClass('d-none');
                    $('#submitButton').addClass('d-none');
                    $('#prevButton').addClass('d-none');
                } else {
                    $('#submitButton').addClass('d-none');
                    $('#nextButton').removeClass('d-none');
                    $('#prevButton').removeClass('d-none');
                }
            }
            // Function to go to the next step
            function nextStep() {
                if (currentStep < 3) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
            // Function to go back to the previous step
            function prevStep() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            }
            // Attach event handlers for navigation buttons
            $('#nextButton').click(nextStep);
            $('#prevButton').click(prevStep);
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).data('url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copy Link Successfully.') }}', 'success');
        }

        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'This is a search placeholder',
                removeItemButton: true,
            });
        }
        var genericExamples = document.querySelectorAll('.datePicker');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Datepicker(element, {
                buttonClass: 'btn',
                format: 'dd/mm/yyyy'
            });
        }
        $(document).on('click', 'input[name="slot_duration"]', function() {
            var val = $(this).val();
            if (val == 'custom_min') {
                $('#custom_min').removeClass('d-none');
                $('#custom_min').find('input[name="slot_minutes"]').attr('required', true);
            } else {
                $('#custom_min').addClass('d-none');
                $('#custom_min').find('input[name="slot_minutes"]').attr('required', false);
            }
        });

        $(document).on('click', 'input[name="intervals_time"]', function() {
            if ($(this).is(':checked')) {
                $('.intervals-time').removeClass('d-none');
            } else {
                $('.intervals-time').addClass('d-none');
            }
        });

        $(document).on('click', 'input[name="limit_time_status"]', function() {
            if ($(this).is(':checked')) {
                $('#limitTimeDate').removeClass('d-none');
                $('#limitTimeDate').find('input[name="start_date"]').attr('required', true);
                $('#limitTimeDate').find('input[name="end_date"]').attr('required', true);
            } else {
                $('#limitTimeDate').addClass('d-none');
                $('#limitTimeDate').find('input[name="start_date"]').attr('required', false);
                $('#limitTimeDate').find('input[name="end_date"]').attr('required', false);
            }
        });

        $(document).on('click', 'input[name="enable_note"]', function() {
            if ($(this).is(':checked')) {
                $('.enable_note').removeClass('d-none');
                $('.enable_note').find('textarea[name="note"]').attr('required', true);
            } else {
                $('.enable_note').find('textarea[name="note"]').attr('required', false);
                $('.enable_note').addClass('d-none');
            }
        });

        $(document).on('click', 'input[name="rolling_days_status"]', function() {
            if ($(this).is(':checked')) {
                $('.rolling_days').find('input[name="rolling_days"]').attr('required', true);
                $('.rolling_days').removeClass('d-none');
            } else {
                $('.rolling_days').find('input[name="rolling_days"]').attr('required', false);
                $('.rolling_days').addClass('d-none');
            }
        });

        $(document).on('click', 'input[name="maximum_limit_status"]', function() {
            if ($(this).is(':checked')) {
                $('.maximum_limit').find('input[name="maximum_limit"]').attr('required', true);
                $('.maximum_limit').removeClass('d-none');
            } else {
                $('.maximum_limit').addClass('d-none');
                $('.maximum_limit').find('input[name="maximum_limit"]').attr('required', false);
            }
        });

        $(document).on('click', 'input[name="multiple_booking"]', function() {
            if ($(this).is(':checked')) {
                $('.multiple_booking_limit').find('input[name="multiple_booking_limit"]').attr('required', true);
                $('.multiple_booking_limit').removeClass('d-none');
            } else {
                $('.multiple_booking_limit').addClass('d-none');
                $('.multiple_booking_limit').find('input[name="multiple_booking_limit"]').attr('required', false);
            }
        });

        var $repeater1 = $('.repeater1').repeater({
            initEmpty: false,
            defaultValues: {},
            show: function() {
                var data = $(this).find('input,textarea,select').toArray();
                data.forEach(function(val) {
                    $(val).parent('.form-group').find('label').attr('for', $(val).attr(
                        'name'));
                    $(val).attr('id', $(val).attr('name'));
                });
                var genericExamples = document.querySelectorAll('.timePicker');
                for (i = 0; i < genericExamples.length; ++i) {
                    var element = genericExamples[i];
                    element.flatpickr({
                        enableTime: true,
                        noCalendar: true,
                    });
                }
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function(setIndexes) {
                var genericExamples = document.querySelectorAll('.timePicker');
                for (i = 0; i < genericExamples.length; ++i) {
                    var element = genericExamples[i];
                    element.flatpickr({
                        enableTime: true,
                        noCalendar: true,
                    });
                }
            }
        });
        @if ($timeWiseBooking && $timeWiseBooking->intervals_time_json)
            var Json = JSON.parse({!! json_encode($timeWiseBooking->intervals_time_json) !!});
            $repeater1.setList(Json);
        @endif
        CKEDITOR.replace('services', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
