@extends('layouts.main')
@section('title', __('Form'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Design Form') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('forms.index'))->text(__('Forms')) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Design Form') }} </li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="main-content">
            @if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'http')
                <div class="alert alert-warning">
                    <b>
                        {{ __('Please note that the video recording and selfie features are only available on HTTPS websites and its not work on HTTP sites.') }}</b>
                </div>
            @endif
            <section class="section">
                <div class="section-body">
                    {!! html()->modelForm($form, 'PUT', route('forms.design.update', $form->id))->id('design-form')->attribute('data-validate', '')->open() !!}
                    <div class="row">
                        <div class="col-xl-12 order-xl-1">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Design Form') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @php
                                                        $array = json_decode($form->json);
                                                    @endphp
                                                    <ul id="tabs"
                                                        class="mb-3 nav nav-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                                                        @if (!empty($form->json))
                                                            @foreach ($array as $key => $data)
                                                                @php
                                                                    $key = $key + 1;
                                                                @endphp
                                                                <li
                                                                    class="nav-item ui-state-default ui-corner-top ui-state-focus">
                                                                    <a href="#page-{{ $key }}"
                                                                        class="nav-link">{{ __('Page') . $key }}</a>
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li>
                                                                <a href="#page-1">{{ __('Page1') }}</a>
                                                            </li>
                                                        @endif
                                                        <li id="add-page-tab">
                                                            <a href="#new-page" class="nav-link">{{ __('+Page') }}</a>
                                                        </li>
                                                    </ul>
                                                    @if (!empty($form->json))
                                                        @foreach ($array as $key => $data)
                                                            <div id="page-{{ $key + 1 }}" class="build-wrap"></div>
                                                        @endforeach
                                                    @else
                                                        <div id="page-1" class="build-wrap"></div>
                                                    @endif

                                                    <div id="new-page"></div>
                                                    <input type="hidden" name="json" value="{{ $form->json }}"
                                                        class="">
                                                    <br>
                                                    <div class="action-buttons">
                                                        {!! html()->button(__('Show Data'))->class('d-none')->id('showData') !!}
                                                        {!! html()->button(__('Clear All Fields'))->class('d-none')->id('clearFields') !!}
                                                        {!! html()->button(__('Get Data'))->class('d-none')->id('getData') !!}
                                                        {!! html()->button(__('Get XML Data'))->class('d-none')->id('getXML') !!}
                                                        {!! html()->button(__('Update'))->class('btn btn-primary')->id('getJSON') !!}
                                                        {!! html()->button(__('Back'))->class('d-none')->id('getJSONs')->attribute('onClick', 'javascript:history.go(-1)') !!}
                                                        {!! html()->button(__('Get JS Data'))->class('d-none')->id('getJS') !!}
                                                        {!! html()->button(__('Set Data'))->class('d-none')->id('setData') !!}
                                                        {!! html()->button(__('Add Field'))->class('d-none')->id('addField') !!}
                                                        {!! html()->button(__('Remove Field'))->class('d-none')->id('removeField') !!}
                                                        {!! html()->button(__('Test Submit'))->type('submit')->class('d-none')->id('testSubmit') !!}
                                                        {!! html()->button(__('Reset Demo'))->class('d-none')->id('resetDemo') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! html()->closeModelForm() !!}
                </div>
            </section>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/jqueryform/css/demo.css') }}">
    <link href="{{ asset('vendor/jqueryform/css/jquery.rateyo.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
@endpush
@push('script')
    <script>
        var lang = '{{ app()->getLocale() }}';
        var lang_other = '{{ __('Other') }}';
        var lang_other_placeholder = '{{ __('Enter Please') }}';
        var lang_Page = '{{ __('Page') }}';
        var lang_Custom_Autocomplete = '{{ __('Custom Autocomplete') }}';
    </script>
    <script src="{{ asset('vendor/jqueryform/js/signaturePad.umd.js') }}"></script>
    <script src="{{ asset('vendor/jqueryform/js/vendor.js') }}"></script>
    <script src="{{ asset('vendor/modules/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('vendor/jqueryform/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/jqueryform/js/form-builder.min.js') }}"></script>
    <script src="{{ asset('vendor/jqueryform/js/form-render.min.js') }}"></script>
    <script src="{{ asset('vendor/jqueryform/js/demoFirst.js') }}"></script>
    <script src="{{ asset('vendor/jqueryform/js/jquery.rateyo.min.js') }}"></script>
@endpush
