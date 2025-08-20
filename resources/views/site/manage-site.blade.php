@extends('layouts.main')
@section('title', __('Analytics Sites'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Analytics Sites') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('analytics.dashboard'))->text(__(' Analytics Dashboard')) !!}</li>
            <li class="breadcrumb-item active">{{ __('Manage Site') }}</li>
        </ul>
        <div class="float-end d-flex align-items-center">
            @if (\Auth::user()->can('create-analytics-dashboard'))
                <div class="p-0 btn">
                    <a class="btn btn-primary add-site" href="{{ route('analytics.add.site') }}" data-bs-toggle="tooltip"
                        data-bs-original-title="{{ __('Add Site') }}"><span><i class="ti ti-plus">
                            </i></span>{{ __('Add Site') }}</a>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    @include('layouts.includes.datatable-css')
@endpush
@push('script')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
@endpush
