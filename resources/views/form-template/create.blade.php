@extends('layouts.main')
@section('title', __('Create Form Template'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Create Form Template') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('form-template.index'))->text(__('Form Template')) !!}</li>
            <li class="breadcrumb-item">{{ __('Create Form Template') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5> {{ __('Create Form Template') }}</h5>
                    </div>
                    {!! html()->form('POST', route('form-template.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->open() !!}
                    <div class="card-body">
                        <div class="form-group">
                            {!! html()->label(__('Title'))->for('title')->class('form-label') !!}
                            {!! html()->text('title')->class('form-control')->required()->placeholder(__('Enter title')) !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label('image', __('Image'))->class('form-label') !!}
                            {!! html()->file('image')->class('form-control')->id('image')->attribute('accept', '.jpeg,.jpg,.png') !!}
                            <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-end">
                            {!! html()->a(route('form-template.index'))->class('btn btn-secondary')->text(__('Cancel')) !!}
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                        </div>
                    </div>
                    {!! html()->form()->close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
