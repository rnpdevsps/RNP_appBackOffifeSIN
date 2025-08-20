@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Feature Settings') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Feature Settings') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('landing-page.landingpage-sidebar')
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="menu-setting" role="tabpanel"
                                aria-labelledby="landing-menu-setting">
                                {!! html()->form('POST', route('landing.feature.store'))->id('froentend-form')->class('form-horizontal')->attribute('data-validate')->attribute('no-validate')->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Feature Setting') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('feature_setting_enable', Utility::getsettings('feature_setting_enable') == 'on', null)->class('custom-control custom-switch form-check-input input-primary')->id('featureSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Feature Name'), 'feature_name')->class('form-label') !!}
                                                {!! html()->text('feature_name', Utility::getsettings('feature_name'))->class('form-control')->placeholder(__('Enter feature name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Feature Bold Name'))->for('feature_bold_name')->class('form-label') !!}
                                                {!! html()->textarea('feature_bold_name', Utility::getsettings('feature_bold_name'))->class('form-control')->rows(1)->placeholder(__('Enter feature bold Name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {!! html()->label(__('Feature Detail'))->for('feature_detail')->class('form-label') !!}
                                                {!! html()->textarea('feature_detail', Utility::getsettings('feature_detail'))->class('form-control')->rows(3)->placeholder(__('Enter feature detail')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                                    </div>
                                </div>
                                {!! html()->form()->close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Feature') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a href="javascript:void(0);" data-url="{{ route('feature.create') }}"
                                        data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        class="mx-1 btn btn-sm btn-primary feature-create"
                                        data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Bold Text') }}</th>
                                            <th>{{ __('Detail') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (is_array($featureSettings) || is_object($featureSettings))
                                            @php
                                                $ff_no = 1;
                                            @endphp
                                            @foreach ($featureSettings as $key => $featureSetting)
                                                <tr>
                                                    <td>{{ $ff_no++ }}</td>
                                                    <td>{{ $featureSetting['feature_name'] }}</td>
                                                    <td>{{ $featureSetting['feature_bold_name'] }}</td>
                                                    <td>{{ $featureSetting['feature_detail'] }}</td>
                                                    <td>
                                                        <span>
                                                            <a href="javascript:void(0);"
                                                                data-url="{{ route('feature.edit', $key) }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom"
                                                                class="mx-1 btn btn-sm btn-primary feature-edit"
                                                                data-bs-original-title="{{ __('Create') }}">
                                                                <i class="ti ti-pencil text-light"></i>
                                                            </a>
                                                            {!! html()->form('DELETE', route('feature.delete', $key))->attribute('class', 'd-inline')->attribute('id', 'delete-form-' . $key)->open() !!} </span>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-sm small btn-danger show_confirm"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            id="delete-form-1"
                                                            data-bs-original-title="{{ __('Delete') }}">
                                                            <i class="text-white ti ti-trash"></i>
                                                        </a>
                                                        {!! html()->form()->close() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '.feature-create', function() {
                var action = $(this).data('url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Create Feature') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
        });
        $(document).ready(function() {
            $('body').on('click', '.feature-edit', function() {
                var action = $(this).data('url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Edit Feature') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
        });
    </script>
@endpush
