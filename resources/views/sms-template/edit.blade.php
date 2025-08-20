@extends('layouts.main')
@section('title', __('Edit Sms Template'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Sms Template') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sms-template.index') }}">{{ __('Sms Templates') }}</a></li>
            <li class="breadcrumb-item">{{ __('Edit Sms Template') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="pt-5 main-content">
        <section class="section">
            <div class="m-auto col-sm-8">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Edit Sms Template') }}</h5>
                    </div>

                    <div class="card-body">
                        {!! html()->modelForm($smsTemplate, 'PATCH', route('sms-template.update', $smsTemplate->id))->attribute('data-validate')->open() !!}
                        <div class="form-group">
                            <span class="fw-bolder text-dark fs-6">{{ <?php echo __('name'); ?> }}
                                {{ <?php echo __('code'); ?> }}</span>
                        </div>
                        <div class="form-group fv-row mb-7">
                            {!! html()->label(__('Event'), 'event', ['class' => 'form-label fw-bolder text-dark fs-6']) !!}
                            {!! html()->text('event', null)->attribute('autofocus', true)->attribute('required', true)->attribute('autocomplete', 'off')->class('form-control form-control-lg form-control-solid' . ($errors->has('event') ? ' is-invalid' : '')) !!}
                            @error('event')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group fv-row mb-7">
                            {!! html()->label(__('HTML Template'), 'template', ['class' => 'form-label fw-bolder text-dark fs-6']) !!}
                            {!! html()->textarea('template', null)->attribute('required', true)->attribute('autocomplete', 'off')->class('form-control form-control-lg form-control-solid' . ($errors->has('template') ? ' is-invalid' : '')) !!}
                            @error('template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-end">
                            <a href="{{ route('sms-template.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}

                        </div>
                        {!! html()->closeModelForm() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
