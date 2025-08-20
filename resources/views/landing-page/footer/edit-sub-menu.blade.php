@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Landing page') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('edit sub menu') }}</li>
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
                            <h5>{{ __('Edit Footer Sub Menu') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('footer.sub.menu.update', $footerPage->id) }}" method="POST"
                                class="form-horizontal" data-validate novalidate>
                                @csrf <div class="row">
                                    <div class="form-group">
                                        {!! html()->label(__('Select Page'), 'page_id', ['class' => 'form-label']) !!}
                                        {!! html()->select('page_id', $pages, $footerPage->page_id)->class('form-select')->required()->attribute('data-trigger') !!}
                                    </div>
                                    <div class="form-group">
                                        {!! html()->label(__('Menu'), 'parent_id', ['class' => 'form-label']) !!}
                                        {!! html()->select('parent_id', $footer, $footerPage->parent_id)->class('form-select')->required()->attribute('data-trigger') !!}
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-end">
                                            <a href="{{ route('landing.footer.index') }}"><button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">{{ __('Close') }}</button></a>
                                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                                        </div>
                                    </div>
                            </form>
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
