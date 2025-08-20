@extends('layouts.main')
@section('title', __('Booking Payment Integration'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <div class="previous-next-btn">
                <div class="page-header-title">
                    <h4 class="m-b-10">{{ __('Booking Payment Integration') }}</h4>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"> {!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
                    <li class="breadcrumb-item"> {!! html()->a(route('bookings.index'))->text(__('Bookings')) !!}</li>
                    <li class="breadcrumb-item"> {{ __('Booking Payment Integration') }} </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="m-auto col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Payment Setting') }}</h5>
                </div>
                {!! html()->modelForm($booking, 'POST', route('booking.payment.integration.store', $booking->id))->attribute('enctype', 'multipart/form-data')->attribute('data-validate', true)->open() !!}
                <div class="card-body">
                    @if ($paymentType)
                        <div class="row">
                            <div class="col-md-8">
                                <b>
                                    {!! html()->label(__('Payment getway (active)'))->class('d-block')->for('customswitchv1-1') !!}
                                </b>
                            </div>
                            <div class="mb-3 col-md-4">
                                <div class="form-check form-switch custom-switch-v1 float-end">
                                    <input type="checkbox" name="payment" id="customswitchv1-1"
                                        class="form-check-input input-primary"
                                        {{ $booking->payment_status == '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-lg-12 paymenttype {{ $booking->payment_status == '1' ? 'd-block' : 'd-none' }}">
                                <div class="form-group">
                                    {!! html()->label(__('Payment Type'), 'payment_type')->class('form-label') !!}
                                    {!! html()->select('payment_type', $paymentType, $booking->payment_type)->class('form-control')->attribute('data-trigger', true) !!}
                                </div>
                                <div class="form-group">
                                    {!! html()->label(__('Amount'), 'amount')->class('form-label') !!}
                                    {!! html()->number('amount', $booking->amount)->id('amount')->placeholder(__('Enter amount'))->class('form-control') !!}
                                    @if ($errors->has('amount'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! html()->label(__('Currency symbol'), 'currency_symbol')->class('form-label') !!}
                                    {!! html()->text('currency_symbol', $booking->currency_symbol)->id('currency_symbol')->placeholder(__('Enter currency symbol'))->class('form-control') !!}
                                    @if ($errors->has('currency_symbol'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('currency_symbol') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! html()->label(__('Currency'), 'currency_name')->class('form-label') !!}
                                    {!! html()->text('currency_name', $booking->currency_name)->id('currency_name')->placeholder(__('Enter currency name'))->class('form-control') !!}
                                    @if ($errors->has('currency_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('currency_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        {!! html()->a(route('bookings.index'))->class('btn btn-secondary')->text(__('Cancel')) !!}
                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                    </div>
                </div>
                {!! html()->closeModelForm() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'Select Option',
                    searchPlaceholderValue: 'Select Option',
                });
            }
        });
        $(document).on('click', "#customswitchv1-1", function() {
            if (this.checked) {
                $(".paymenttype").fadeIn(500);
                $('.paymenttype').removeClass('d-none');
            } else {
                $(".paymenttype").fadeOut(500);
                $('.paymenttype').addClass('d-none');
            }
        });
    </script>
@endpush
