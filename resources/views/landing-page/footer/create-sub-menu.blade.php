@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Footer Sub Menu Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Footer Sub Menu Settings') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="mx-auto col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Create Footer Sub Menu') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! html()->form('POST', route('footer.sub.menu.store'))->class('form-horizontal')->attribute('data-validate')->attribute('novalidate')->open() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Select Page'), 'page_id')->class('form-label') !!}
                                        {!! html()->select('page_id', $pages)->class('form-control')->attribute('required')->attribute('data-trigger') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Select Parent Menu'), 'parent_id')->class('form-label') !!}
                                        {!! html()->select('parent_id', $footers)->class('form-control')->attribute('required')->attribute('data-trigger') !!}
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        <a href="{{ route('landing.footer.index') }}"><button type="button"
                                                class="btn btn-secondary">{{ __('Close') }}</button></a>
                                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
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
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'This is a search placeholder',
                });
            }
        });
    </script>
@endpush
