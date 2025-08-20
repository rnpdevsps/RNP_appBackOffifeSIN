@extends('layouts.main')
@section('title', __('Edit Announcement'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Announcement') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item"><a href="{{ route('announcement.index') }}">{{ __('Announcement') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Edit Announcement') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="m-auto col-lg-6 col-md-8 col-xxl-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Edit Announcement') }}</h5>
                </div>
                <div class="card-body">
                    {!! html()->form('PUT', route('announcement.update', $announcement->id))->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->open() !!}
                    <div class="row">
                        <div class="form-group col-6">
                            {!! html()->label(__('Title'), 'title')->class('form-label') !!}
                            {!! html()->text('title', $announcement->title)->class('form-control')->required()->placeholder(__('Enter title')) !!}
                        </div>
                        <div class="form-group col-6">
                            {!! html()->label(__('Image'), 'image')->class('form-label') !!}
                            {!! html()->file('image')->class('form-control')->attribute('accept', '.jpeg,.jpg,.png') !!}
                            <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! html()->label(__('Description'), 'description')->class('form-label') !!}
                        {!! html()->textarea('description', $announcement->description)->class('form-control')->rows(3)->required()->placeholder(__('Enter description')) !!}
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            {!! html()->label(__('Start Date'), 'start_date')->class('form-label') !!}
                            {!! html()->text('start_date', $startDate)->class('form-control')->id('datepicker-start-date')->required()->placeholder(__('Start Date')) !!}
                        </div>
                        <div class="form-group col-6">
                            {!! html()->label(__('End Date'), 'end_date')->class('form-label') !!}
                            {!! html()->text('end_date', $endDate)->class('form-control')->id('datepicker-end-date')->required()->placeholder(__('End Date')) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="share_with_public"
                                    {{ $announcement->share_with_public == 1 ? 'checked' : '' }} class="form-check-input"
                                    id="share_with_public">
                                {!! html()->label(__('Share With Public'), 'share_with_public')->class('form-check-label') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="show_landing_page_announcebar"
                                    {{ $announcement->show_landing_page_announcebar == 1 ? 'checked' : '' }}
                                    class="form-check-input" id="show_landing_page_announcebar">
                                {!! html()->label(__('Show Landing Page Announcebar'), 'show_landing_page_announcebar')->class('form-check-label') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        {!! html()->a(route('announcement.index'), __('Cancel'))->class('btn btn-secondary') !!}
                        {!! html()->button(__('Save'), 'submit')->class('btn btn-primary') !!}
                    </div>
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>

    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        (function() {
            const d_week = new Datepicker(document.querySelector('#datepicker-start-date'), {
                buttonClass: 'btn',
                format: 'dd/mm/yyyy'
            });
        })();
    </script>
    <script>
        (function() {
            const d_week = new Datepicker(document.querySelector('#datepicker-end-date'), {
                buttonClass: 'btn',
                format: 'dd/mm/yyyy'
            });
        })();
    </script>
@endpush
