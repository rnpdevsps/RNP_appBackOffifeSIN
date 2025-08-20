@extends('layouts.main')
@section('title', 'Create Category')
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Category') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item"><a href="{{ route('blog-category.index') }}">{{ __('Blog Category') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Edit Category') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="m-auto col-xl-6 col-lg-8 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Edit Category') }}</h5>
                </div>
                {!! html()->modelForm($category, 'PUT', route('blog-category.update', $category->id))->attribute('data-validate')->open() !!}
                <div class="card-body">
                    <div class="form-group">
                        {{ html()->label(__('Name'), 'name')->class('form-label') }}
                        {!! html()->text('name', null)->placeholder(__('Enter name'))->class('form-control')->required() !!}
                    </div>
                    <div class="form-group">
                        {{ html()->label(__('Status'), 'status')->class('form-label') }}
                        {!! html()->select(
                                'status',
                                [
                                    '' => __('Select Category Status'),
                                    '1' => __('Active'),
                                    '2' => __('Deactive'),
                                ],
                                $category->status,
                            )->class('form-select')->id('status')->attribute('data-trigger', true) !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        {!! html()->a(route('blog-category.index'), __('Cancel'))->class('btn btn-secondary') !!}
                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                    </div>
                </div>
                {!! html()->closeModelForm() !!}
            </div>
        </div>
    </div>
@endsection
