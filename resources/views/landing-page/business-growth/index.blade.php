@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Bussiness Growth') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{{ __('Bussiness Growth') }}</li>
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
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                aria-labelledby="landing-apps-setting">
                                {!! html()->form('POST', route('landing.business.growth.store'))->id('froentend-form')->attribute('enctype', 'multipart/form-data')->attribute('data-validate', true)->attribute('novalidate', true)->open() !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Business Growth Setting') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                {!! html()->checkbox('business_growth_setting_enable')->checked(Utility::getsettings('business_growth_setting_enable') == 'on')->value('on')->attribute('id', 'startViewSettingEnableBtn')->attribute('data-onstyle', 'primary')->attribute('data-toggle', 'switchbutton')->class('custom-control custom-switch form-check-input input-primary') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Thumbnail Image'), 'business_growth_front_image')->class('form-label') !!} *
                                                {!! html()->file('business_growth_front_image')->class('form-control')->id('business_growth_front_image')->attribute('accept', '.jpeg,.jpg,.png') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Video'), 'business_growth_video')->class('form-label') !!} *
                                                {!! html()->file('business_growth_video')->class('form-control')->id('video')->attribute('accept', '.mp4,.avi,.wmv,.mov,.webm') !!}
                                                <small>{{ __('NOTE: Allowed file extension : .mp4,.avi,.wmv,.mov,.webm (Max Size: 2 MB)') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Business Growth Name'), 'business_growth_name')->class('form-label') !!}
                                                {!! html()->text('business_growth_name', Utility::getsettings('business_growth_name'))->class('form-control')->placeholder(__('Enter business growth name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                {!! html()->label(__('Business Growth Bold Name'), 'business_growth_bold_name')->class('form-label') !!}
                                                {!! html()->text('business_growth_bold_name', Utility::getsettings('business_growth_bold_name'))->class('form-control')->placeholder(__('Enter business growth bold name')) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {!! html()->label(__('Business Growth Detail'), 'business_growth_detail')->class('form-label') !!}
                                                {!! html()->text('business_growth_detail', Utility::getsettings('business_growth_detail'))->class('form-control')->placeholder(__('Enter business growth detail')) !!}
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
                                    <h5>{{ __('Business Growth View') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a href="javascript:void(0);" data-url="{{ route('business.growth.view.create') }}"
                                        data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        class="mx-1 btn btn-sm btn-primary business-growth-view-create"
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
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (is_array($businessGrowthViewSettings) || is_object($businessGrowthViewSettings))
                                            @php
                                                $ff_no = 1;
                                            @endphp
                                            @foreach ($businessGrowthViewSettings as $key => $businessGrowthViewSetting)
                                                <tr>
                                                    <td>{{ $ff_no++ }}</td>
                                                    <td>{{ $businessGrowthViewSetting['business_growth_view_name'] }}</td>
                                                    <td>{{ $businessGrowthViewSetting['business_growth_view_amount'] }}
                                                    </td>
                                                    <td>
                                                        <span>
                                                            <a href="javascript:void(0);"
                                                                data-url="{{ route('business.growth.view.edit', $key) }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom"
                                                                class="mx-1 btn btn-sm btn-primary business-growth-edit"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-light"></i>
                                                            </a>
                                                            {!! html()->form('DELETE', route('business.growth.view.delete', $key))->class('d-inline')->attribute('id', 'delete-form-' . $key)->open() !!}
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm small btn-danger show_confirm"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                id="delete-form-1"
                                                                data-bs-original-title="{{ __('Delete') }}">
                                                                <i class="text-white ti ti-trash"></i>
                                                            </a>
                                                            {!! html()->form()->close() !!}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Business Growth') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a href="javascript:void(0);" data-url="{{ route('business.growth.create') }}"
                                        data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        class="mx-1 btn btn-sm btn-primary business-growth-create"
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
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (is_array($businessGrowthSettings) || is_object($businessGrowthSettings))
                                            @php
                                                $ff_no = 1;
                                            @endphp
                                            @foreach ($businessGrowthSettings as $key => $businessGrowthSetting)
                                                <tr>
                                                    <td>{{ $ff_no++ }}</td>
                                                    <td>{{ $businessGrowthSetting['business_growth_title'] }}</td>
                                                    <td>
                                                        <span>
                                                            <a href="javascript:void(0);"
                                                                data-url="{{ route('business.growth.edit', $key) }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom"
                                                                class="mx-1 btn btn-sm btn-primary business-growth-edit"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-light"></i>
                                                            </a>
                                                            {!! html()->form('DELETE', route('business.growth.delete', $key))->class('d-inline')->attribute('id', 'delete-form-' . $key)->open() !!}
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm small btn-danger show_confirm"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                id="delete-form-1"
                                                                data-bs-original-title="{{ __('Delete') }}">
                                                                <i class="text-white ti ti-trash"></i>
                                                            </a>
                                                            {!! html()->form()->close() !!}
                                                        </span>
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
            $('body').on('click', '.business-growth-create', function() {
                var action = $(this).data('url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Create Business Growth') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
        });
        $(document).ready(function() {
            $('body').on('click', '.business-growth-edit', function() {
                var action = $(this).data('url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Edit Business Growth') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
        });
        $(document).ready(function() {
            $('body').on('click', '.business-growth-view-create', function() {
                var action = $(this).data('url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Create Business Growth View') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
        });
        $(document).ready(function() {
            $('body').on('click', '.business-growth-edit', function() {
                var action = $(this).data('url');
                var modal = $('#common_modal');
                $.get(action, function(response) {
                    modal.find('.modal-title').html('{{ __('Edit Business Growth View') }}');
                    modal.find('.body').html(response);
                    modal.modal('show');
                })
            });
        });
    </script>
@endpush
