@extends('layouts.main')
@section('title', __('Edit Bookings'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Bookings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! Html::a()->href(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! Html::a()->href(route('bookings.index'))->text(__('Bookings')) !!}</li>
            <li class="breadcrumb-item">{{ __('Edit Booking') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5> {{ __('Edit Booking') }}</h5>
                    </div>
                    {!! html()->modelForm($booking, 'PUT', route('bookings.update', $booking->id))->attribute('data-validate')->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() !!}
                    @method('put')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4 text-center">
                                    @if (isset($booking->business_logo))
                                        <img src="{{ Storage::url($booking->business_logo) }}" width="100" height="100"
                                            class="img-fluid rounded-circle" alt="Business Logo">
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! html()->label('business_logo', __('Business Logo'))->class('form-label') !!}
                                    {!! html()->file('business_logo')->class('form-control')->id('business_logo')->accept('.jpeg,.jpg,.png') !!}
                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! html()->label(__('Business Name'), 'business_name')->class('form-label') !!}
                                            {!! html()->text('business_name')->class('form-control')->required()->placeholder(__('Enter business name')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! html()->label(__('Business Email'), 'business_email')->class('form-label') !!}
                                            {!! html()->text('business_email')->class('form-control')->required()->placeholder(__('Enter business email')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! html()->label(__('Business Website URL'), 'business_website')->class('form-label') !!}
                                            {!! html()->text('business_website')->class('form-control')->required()->placeholder(__('Enter business website url')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Business Address'))->class('form-label')->for('business_address') !!}
                                        {!! html()->textarea('business_address')->class('form-control')->required()->rows(3)->placeholder(__('Enter business address')) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! html()->label(__('Business Number'))->class('form-label')->for('business_number') !!}
                                        {!! html()->number('business_number')->class('form-control')->required()->placeholder(__('Enter business number')) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Business Phone'))->class('form-label')->for('business_phone') !!}
                                        {!! html()->input('tel', 'business_phone')->class('form-control')->required()->placeholder(__('Enter business phone'))->value($booking->business_phone)->id('business_phone') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-end">
                            {!! html()->a(route('bookings.index'), __('Cancel'))->class('btn btn-secondary') !!}
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                        </div>
                    </div>
                    {!! html()->closeModelForm() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'This is a search placeholder',
            });
        }
    </script>
@endpush
