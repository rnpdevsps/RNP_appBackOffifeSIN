@php
    $user = Auth::user();
    $color = $user->theme_color;
    $usr = $user->admin_id;
    if ($color == 'theme-1') {
        $chatcolor = '#0CAF60';
    } elseif ($color == 'theme-2') {
        $chatcolor = '#584ED2';
    } elseif ($color == 'theme-3') {
        $chatcolor = '#6FD943';
    } elseif ($color == 'theme-4') {
        $chatcolor = '#145388';
    } elseif ($color == 'theme-5') {
        $chatcolor = '#B9406B';
    } elseif ($color == 'theme-6') {
        $chatcolor = '#008ECC';
    } elseif ($color == 'theme-7') {
        $chatcolor = '#922C88';
    } elseif ($color == 'theme-8') {
        $chatcolor = '#C0A145';
    } elseif ($color == 'theme-9') {
        $chatcolor = '#48494B';
    } elseif ($color == 'theme-10') {
        $chatcolor = '#0C7785';
    } else {
        $chatcolor = '#584ED2';
    }
@endphp
@extends('layouts.main')
@section('title', __('Submitted Booking'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Submitted Bookings of ' . ' ' . $booking->business_name) }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Submitted Bookings of ' . ' ' . $booking->business_name) }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="main-content">
            <section class="section">
                @if (!empty($booking->logo))
                    <div class="text-center gallery gallery-md">
                        <img id="app-dark-logo" class="float-none gallery-item"
                            src="{{ Storage::exists($booking->logo)
                                ? asset('storage/app/' . $booking->logo)
                                : Storage::url('app-logo/78x78.png') }}">
                    </div>
                @endif
                <h2 class="text-center">{{ $booking->business_name }}</h2>
                <div class="section-body filter">
                    <div class="row">
                        <div class="mt-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 responsive-search">
                                            <div class="form-group d-flex justify-content-start">
                                                {!! html()->text('user')->class('form-control mr-1')->placeholder(__('Search here'))->attribute('data-kt-ecommerce-category-filter', 'search') !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 responsive-search">
                                            <div class="form-group row d-flex justify-content-start">
                                                {!! html()->text('duration')->class('form-control mr-1 created_at')->placeholder(__('Select Date Range'))->id('pc-daterangepicker-1') !!}
                                                {!! html()->hidden('booking_id', $booking->id)->id('booking_id') !!}

                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 btn-responsive-search">
                                            {!! html()->button(__('Filter'))->class('btn btn-primary btn-lg add_filter button-left') !!}
                                            {!! html()->button(__('Clear Filter'))->class('btn btn-secondary btn-lg clear_filter') !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt-5 col-xl-12">
                                            <div class="table-responsive">
                                                {{ $dataTable->table(['width' => '100%']) }}
                                                {!! html()->form('POST', route('mass.export.csv'))->id('mass_export')->open() !!}
                                                {!! html()->hidden('booking_id', $booking->id) !!}
                                                {!! html()->hidden('start_date') !!}
                                                {!! html()->hidden('end_date') !!}
                                                {!! html()->form()->close() !!}
                                                {!! html()->form('POST', route('mass.export.xlsx'))->id('mass_export_xlsx')->open() !!}
                                                {!! html()->hidden('booking_id', $booking->id) !!}
                                                {!! html()->hidden('start_date') !!}
                                                {!! html()->hidden('end_date') !!}
                                                {!! html()->form()->close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">
    @include('layouts.includes.datatable-css')
@endpush
@push('script')
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/apex-chart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).data('url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copied.') }}', 'success');
        }
        document.querySelector("#pc-daterangepicker-1").flatpickr({
            mode: "range"
        });
    </script>
@endpush
