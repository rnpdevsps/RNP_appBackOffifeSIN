@extends('layouts.main')
@section('title', __('Forms Management'))
@section('breadcrumb')
<div class="col-md-12">
    <div class="page-header-title">
        <h4 class="m-b-10">{{ __('Forms Management') }}</h4>
    </div>
    <ul class="breadcrumb">
        <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
        <li class="breadcrumb-item active">{{ __('Forms Management') }} </li>
    </ul>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <div class="form-group">
                        <form method="GET" action="{{ route('maeplantillas.index') }}" class="form-inline">
                            <button type="submit" name="status" value="0" class="btn btn-primary {{ request()->query('status') == '1' ? 'active' : '' }}">Formularios activos</button>
                            <button type="submit" name="status" value="1" class="btn btn-primary {{ request()->query('status') == '0' ? 'active' : '' }}">Formularios inactivos</button>
                            <button type="submit" class="btn btn-primary {{ request()->query('status') == null ? 'active' : '' }}">Ver todos</button>
                        </form>
                    </div>
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
