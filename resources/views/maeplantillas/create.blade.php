@extends('layouts.main')
@section('title', __('Create Mae Plantilla'))
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('Create Mae Plantilla') }}</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
                        <li class="breadcrumb-item">{!! Html::link(route('maeplantillas.index'), __('Mae Plantillas'), []) !!}</li>
                        <li class="breadcrumb-item active">{{ __('Forms Management') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-6 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <h5>{{ __('Create Mae Plantilla') }}</h5>
                </div>
                {!! Form::open(['route' => 'maeplantillas.store', 'method' => 'Post', 'data-validate' ]) !!}
                <div class="card-body">
                    <div class="row">
                        <div class="mx-auto col-lg-12 col-12">
                            <div class="form-group">
                                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                {!! Form::text('name', null, ['placeholder' => 'Nombre de la plantilla', 'class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('html_template', __('Plantilla Html'), ['class' => 'form-label']) }}
                                {!! Form::textarea('content', null, [
                                    'placeholder' => '',
                                    'class' => 'form-control',
                                ]) !!}
                                {!! Form::hidden('created_by', Auth::id()) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        {!! Html::link(route('maeplantillas.index'), __('Cancel'), ['class'=>'btn btn-secondary']) !!}
                        {{ Form::button(__('Save'),['type' => 'submit','class' => 'btn btn-primary']) }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
