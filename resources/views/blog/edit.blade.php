@extends('layouts.main')
@section('title', 'Editar Noticia')
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Editar Noticia') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">{{ __('Noticias') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Editar Noticia') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5> {{ __('Editar Noticia') }}</h5>
                    </div>
                    {!! html()->modelForm($blog, 'PUT', route('blogs.update', $blog->id))->attribute('data-validate')->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() !!}
                    <div class="form-group">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Título'), 'title')->class('form-label') !!}
                                        {!! html()->text('title')->class('form-control')->placeholder(__('Enter title'))->required() !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! html()->label(__('Categoría'), 'category_id')->class('form-label') !!}
                                        {!! html()->select('category_id', $categories, $blog->category_id)->class('form-select')->required()->attribute('data-trigger') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Imagen'), 'images')->class('form-label') !!} *
                                        {!! html()->file('images')->class('form-control')->accept('.jpeg,.jpg,.png') !!}
                                        <small>{{ __('Nota: Extensión de archivo permitida: .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Descripción corta'), 'short_description')->class('form-label') !!} *
                                        {!! html()->textarea('short_description')->class('form-control')->placeholder(__('Enter short description'))->required() !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Descripción'), 'description')->class('form-label') !!} *
                                        {!! html()->textarea('description')->class('form-control')->placeholder(__('Enter description'))->required() !!}
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
                        {!! html()->closeModelForm() !!}
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('script')
        <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
        <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

        <script type="text/javascript">
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
