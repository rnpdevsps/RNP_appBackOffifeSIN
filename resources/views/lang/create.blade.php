@php
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
@endphp
@extends('layouts.main')
@section('title', 'Create Language')
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Create Language') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.language', [$currantLang]) }}">{{ __('Languages') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('Create Language') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        {!! html()->form('POST', route('store.language'))->class('form-horizontal')->attribute('data-validate')->open() !!}
        <div class="row">
            <div class="mx-auto col-xl-4 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Create Language') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label(__('Language Code'))->for('code')->class('form-label') !!}
                                        {!! html()->text('code')->class('form-control')->required()->placeholder('Enter language code') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        {!! html()->a(route('manage.language', [$currantLang]), __('Cancel'))->class('btn btn-secondary mr-1') !!}
                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}
    </div>
@endsection
