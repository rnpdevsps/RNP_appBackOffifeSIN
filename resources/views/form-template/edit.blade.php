@extends('layouts.main')
@section('title', __('Edit Form Template'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Form Template') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('form-template.index'))->text(__('Form Template')) !!}</li>
            <li class="breadcrumb-item">{{ __('Edit Testimonial') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5> {{ __('Edit Form Template') }}</h5>
                    </div>
                    {!! html()->modelForm($formTemplate, 'PATCH', route('form-template.update', $formTemplate->id))->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->open() !!}
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            {!! html()->label('title', __('Title'))->class('form-label') !!}
                            {!! html()->text('title', $formTemplate->title)->class('form-control')->required()->placeholder(__('Enter title')) !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label('image', __('Image'))->class('form-label') !!}
                            {!! html()->file('image')->class('form-control')->id('image')->accept('.jpeg,.jpg,.png') !!}
                            <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                            @if (isset($formTemplate->image))
                                <div class="text-center">
                                    <img src="{{ Storage::url($formTemplate->image) }}" width="100" height="100"
                                        class="mt-2">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-end">
                            {!! html()->a(route('form-template.index'), __('Cancel'))->class('btn btn-secondary') !!}
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                        </div>
                    </div>
                    {!! html()->closeModelForm() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
