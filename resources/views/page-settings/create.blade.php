@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Page Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Create Page') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="mx-auto col-xl-7 col-lg-7">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="card">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('page-setting.store'))->id('froentend-form')->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Page Setting') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {!! html()->label(__('Title'))->for('title')->class('form-label') !!} *
                                                {!! html()->text('title')->class('form-control')->placeholder('Enter Page Title')->id('title') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="exampleFormControlSelect1">
                                                    {{ __('Select Type') }}
                                                </label>
                                                <select class="form-select" id="type" name="type" data-trigger>
                                                    <option selected disabled value="all" class="link">
                                                        {{ __('Select type') }}
                                                    </option>
                                                    <option value="link" class="link"> {{ __('Link') }} </option>
                                                    <option value="desc" class="description"> {{ __('Descrtiption') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-none" id="link">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    {!! html()->label(__('Link Type'))->for('url_type')->class('form-label') !!}
                                                    <select name="url_type" class="form-control">
                                                        <option value="" selected disabled>
                                                            {{ __('Select Page Type') }}</option>
                                                        <option value="ifream">{{ __('Ifream') }}</option>
                                                        <option value="internal link">{{ __('Internal Link') }}</option>
                                                        <option value="external link">{{ __('External Link') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    {!! html()->label(__('Page URL'))->for('page_url')->class('form-label') !!}
                                                    {!! html()->text('page_url')->class('form-control')->placeholder(__('Enter Link URL')) !!}
                                                    <small class="text-muted"><b>{{ __('Simple Page') }}</b>
                                                        {{ __(':- Leave it Blank') }} </small><br>
                                                    <small class="text-muted"><b>{{ __('Internal Link') }}</b>
                                                        {{ __(':- http://localhost/Prime-Laravel-Form-Builder/main_file') }}
                                                    </small><br>
                                                    <small class="text-muted"><b>{{ __('External Link') }}</b> :-
                                                        {{ __('http://google.com/') }} </small>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    {!! html()->label(__('Search Friendly URL'))->for('friendly_url')->class('form-label') !!}
                                                    {!! html()->text('friendly_url')->class('form-control')->placeholder(__('Enter Search Friendly URL')) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-none" id="description">
                                            <div class="form-group">
                                                {!! html()->label(__('Page Detail'))->for('description')->class('form-label') !!}
                                                {!! html()->textarea('descriptions')->class('form-control')->rows(1)->placeholder(__('Enter Page detail')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        <a href="{{ route('page-setting.index') }}"
                                            class="btn btn-secondary">{{ __('Cancel') }}</a>
                                        {!! html()->button(__('Save'))->type('submit')->id('save-btn')->class('btn btn-primary') !!}

                                    </div>
                                </div>
                                {!! html()->form()->close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('descriptions', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
        $("select[name='type']").change(function() {
            $('#link').hide();
            $('#description').hide();
            var test = $(this).val();
            if (test == 'link') {
                $('#description').hide();
                $('#link').show();
                $("#link").fadeIn(500);
                $("#link").removeClass('d-none');
                $('#description').fadeOut(500);
            } else {
                $('#link').hide();
                $('#description').show();
                $("#link").fadeOut(500);
                $("#description").fadeIn(500);
                $("#description").removeClass('d-none');
            }
        });
    </script>
@endpush
