@extends('layouts.main')
@section('title', __('Module'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Module') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('module.index'))->text(__('Module')) !!}</li>
            <li class="breadcrumb-item active"> {{ __('Create') }} </li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        {!! html()->form('POST', route('module.store'))->class('form-horizontal')->open() !!}
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 heading-small text-muted">{{ __('Create Module') }}</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Role'))->class('form-label')->for('name') !!}
                                        {!! html()->text('name')->class('form-control')->id('password')->placeholder(__('Enter module name')) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! html()->label(__('Permission'))->class('form-label')->for('permission') !!}
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                        </div>
                                        {!! html()->checkbox('permissions[]', 'M')->class('custom-control-input')->id('managepermission') !!}
                                        {!! html()->label(__('Manage'))->class('form-label custom-control-label')->for('managepermission') !!}
                                        <div class="custom-control custom-checkbox custom-control-inline ">
                                            {!! html()->checkbox('permissions[]', 'C')->class('custom-control-input')->id('createpermission') !!}
                                            {!! html()->label(__('Create'))->class('form-label custom-control-label')->for('createpermission') !!}
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            {!! html()->checkbox('permissions[]', 'E')->class('custom-control-input')->id('editpermission') !!}
                                            {!! html()->label(__('Edit'))->class('form-label custom-control-label')->for('editpermission') !!}
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            {!! html()->checkbox('permissions[]', 'D')->class('custom-control-input')->id('deletepermission') !!}
                                            {!! html()->label(__('Delete'))->class('form-label custom-control-label')->for('deletepermission') !!}
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            {!! html()->checkbox('permissions[]', 'S')->class('custom-control-input')->id('showpermission') !!}
                                            {!! html()->label(__('Show'))->class('form-label custom-control-label')->for('showpermission') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            {!! html()->a(route('module.index'), __('Cancel'))->class('btn btn-secondary') !!}
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary col-md-2 float-end') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! html()->form()->close() !!}
    </div>
@endsection
