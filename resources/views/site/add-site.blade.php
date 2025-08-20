@extends('layouts.main')
@section('title', __('Add Site'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Add Site') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a()->href(route('analytics.dashboard'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item active">{{ __('add-site') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Add Site') }}</h5>
                </div>
                <div class="card-body">
                    {!! html()->form('POST', route('analytics.save.site'))->class('form-horizontal')->attribute('data-validate')->open() !!}
                    <div class="row">
                        <div class="form-group">
                            {!! html()->label(__('Select Account Id'))->for('select_account_id')->class('form-label') !!}
                            <select class="form-select" name="account_id" id="select_account_id" onchange="getProperty()" data-trigger>
                                <option selected="" disabled="">{{ __('Select Account Id') }}</option>
                                @foreach ($account as $account_val)
                                    <option value="{{ $account_val['id'] }}" data-id="{{ $account_val['name'] }}">
                                        {{ $account_val['id'] }} - {{ $account_val['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group d-none" id="site-name-div">
                            {!! html()->label(__('Name'))->for('site_name')->class('form-label') !!}
                            {!! html()->text('site_name')->placeholder('Enter Name')->class('form-control')->id('site_name') !!}
                        </div>
                    </div>
                    <div id="spinner-placeholder" class="d-none">
                        <div class="loading" style="position: unset;">{{ __('Loading…') }}</div>
                    </div>
                    <div class="form-group d-none" id="select-property-div">
                        {!! html()->label(__('Select Property Id'), 'select_property_id')->class('form-label') !!}
                        <select class="form-select" name="property_id" id="select_property_id">
                            <option selected="" disabled="">{{ __('Select Property Id') }} </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group d-none" id="property-name-div">
                            {!! html()->label(__('Property Name'))->class('form-label') !!}
                            {!! html()->text('property_name')->placeholder('Enter Property Name')->class('form-control')->id('property_name') !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        {!! html()->button(__('Save'), 'submit')->class('btn btn-primary')->id('site_save') !!}
                    </div>
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.form-select[data-trigger]').each(function() {
                new Choices(this);
            });
        });

        function getProperty() {
            var accountId = $("#select_account_id option:selected").val();
            var siteName = $("#select_account_id option:selected").text();
            $("#site_name").val(siteName);

            $('#site_save').attr('disabled', true);
            $('#select_account_id').attr('disabled', true);

            $('#spinner-placeholder').removeClass('d-none');

            $('#select-property-div').addClass('d-none');
            $('#select_property_id').empty();
            if (window.propertyChoices) {
                window.propertyChoices.destroy();
            }

            $.ajax({
                url: "{{ route('analytics.getproperty') }}",
                method: "POST",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "account_id": accountId
                },
                success: function(data) {
                    if ($('input[name="property_id"]:checked').length <= 0) {
                        $('#select_property_id').html(data);
                    }
                    window.propertyChoices = new Choices('#select_property_id');
                    $('#select-property-div').removeClass('d-none');
                    $('#site_save').attr('disabled', false);
                    $('#spinner-placeholder').addClass('d-none');
                    $('#select_account_id').attr('disabled', false);
                },
                error: function() {
                    show_toastr('Error!', "{{ __('Failed to fetch properties. Please Select Account Id.') }}",
                        'danger');
                    $('#site_save').attr('disabled', false);
                    $('#select_account_id').attr('disabled', false);
                    $('#spinner-placeholder').addClass('d-none');
                }
            });
        }
    </script>
@endpush
