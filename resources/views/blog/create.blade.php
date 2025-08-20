@extends('layouts.main')
@section('title', 'Crear Noticia')
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Crear Noticia') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item"><a href="{{ route('blog-category.index') }}">{{ __('Noticia') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Crear Noticia') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5> {{ __('Crear Noticia') }}</h5>
                    </div>
                    {!! html()->form('POST', route('blogs.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-validate', 'true')->open() !!}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! html()->label(__('Título'), 'title')->class('form-label') !!}
                                    {!! html()->text('title', null)->class('form-control')->placeholder(__('Enter title'))->required() !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! html()->label(__('Categoría'), 'category_id')->class('form-label') !!}
                                    {!! html()->select('category_id', $categories, null)->class('form-control')->required()->attribute('data-trigger') !!}

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! html()->label(__('Imagen'), 'images')->class('form-label') !!}
                                    {!! html()->file('images')->class('form-control')->required()->accept('.jpeg,.jpg,.png') !!}
                                    <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                     {!! html()->label(__('Descripción corta'), 'short_description')->class('form-label') !!}
                                     {!! html()->textarea('short_description', null)->class('form-control')->placeholder(__('Enter short description'))->required() !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! html()->label(__('Descripción'), 'description')->class('form-label') !!}
                                    {!! html()->textarea('description', null)->class('form-control')->placeholder(__('Enter description'))->required() !!}
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-end">
                            {!! html()->a(route('blogs.index'), __('Cancelar'))->class('btn btn-secondary') !!}
                            {!! html()->button(__('Guardar'))->type('submit')->class('btn btn-primary') !!}
                        </div>
                    </div>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('short_description', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
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
