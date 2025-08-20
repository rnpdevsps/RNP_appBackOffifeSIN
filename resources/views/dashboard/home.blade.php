@php
    $user_theme = \Auth::user();
    $color = $user_theme->theme_color;
    $chatcolor = '#145388';
    if ($color == 'theme-1') {
        $chatcolor = '#0CAF60';
    } elseif ($color == 'theme-2') {
        $chatcolor = '#584ED2';
    } elseif ($color == 'theme-3') {
        $chatcolor = '#6FD943';
    } elseif ($color == 'theme-4') {
        $chatcolor = '#145388';
    } elseif ($color == 'theme-5') {
        $chatcolor = '#B9406B';
    } elseif ($color == 'theme-6') {
        $chatcolor = '#008ECC';
    } elseif ($color == 'theme-7') {
        $chatcolor = '#922C88';
    } elseif ($color == 'theme-8') {
        $chatcolor = '#C0A145';
    } elseif ($color == 'theme-9') {
        $chatcolor = '#48494B';
    } elseif ($color == 'theme-10') {
        $chatcolor = '#0C7785';
    }
@endphp
@extends('layouts.main')
@section('title', __('Dashboard'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Dashboard') }}</h4>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 d-flex">
            <div class="mb-4 row">
                <div class="mb-3 col-xxl-7">
                    <div class="row h-100">
                        @if (\Auth::user()->can('manage-user'))
                            <div class="col-lg-3 col-6 card-event">
                                <a href="users">
                                    <div class="card comp-card number-card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12 m-b-20">
                                                    <i class="text-white ti ti-users bg-primary"></i>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="m-b-20 text-muted">{{ __('Total User') }}</h6>
                                                    <h3 class="text-primary">{{ isset($user) ? $user : 0 }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (\Auth::user()->can('manage-user'))
                            <div class="col-lg-3 col-6 card-event">
                                <a href="forms">
                                    <div class="card comp-card number-card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12 m-b-20">
                                                    <i class="text-white ti ti-file-text bg-success"></i>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="m-b-20 text-muted">{{ __('Total User') }}</h6>
                                                    <h3 class="text-success">{{ isset($users) ? $users : 0 }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (\Auth::user()->can('manage-user'))
                            <div class="col-lg-3 col-6 card-event">
                                <div class="card comp-card number-card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12 m-b-20">
                                                <i class="text-white ti ti-ad-2 bg-danger"></i>
                                            </div>
                                            <div class="col-12">
                                                <h6 class="m-b-20 text-muted">{{ __('Total User') }}</h6>
                                                <h3 class="text-danger">
                                                    {{ isset($users) ? $users : 0 }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (\Auth::user()->can('manage-user'))
                            <div class="col-lg-3 col-6 card-event">
                                <a href="poll">
                                    <div class="card comp-card number-card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12 m-b-20">
                                                    <i class="text-white ti ti-ad-2 bg-warning"></i>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="m-b-20 text-muted">{{ __('Total User') }}</h6>
                                                    <h3 class="text-warning">{{ isset($users) ? $users : 0 }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 col-xxl-5">
                    <div class="row">
                       
                        <div class="col-lg-10 col-sm-6 col-12 dash-card-responsive">
                            <div class="m-0 card comp-card">
                                <div class="card-body admin-wish-card">
                                    <div class="row h-100">
                                        <div class="col-xxl-12">
                                            <div class="row">
                                                <h4 id="wishing">{{ 'Good morning ,' }}</h4>
                                            </div>
                                        </div>
                                        <h4 class="f-w-400">
                                            <a href="{{ File::exists(Storage::path(Auth::user()->avatar)) ? Storage::url(Auth::user()->avatar) : Auth::user()->avatar_image }}" target="_new">
                                                <img src="{{ File::exists(Storage::path(Auth::user()->avatar)) ? Storage::url(Auth::user()->avatar) : Auth::user()->avatar_image }}"
                                                    class="me-2 img-thumbnail rounded-circle" width="50px"
                                                    height="50px"></a>
                                            <span class="text-muted">{{ Auth::user()->name }}</span>
                                        </h4>
                                        <p>
                                            {{ __('¡Que tenga un lindo día! Puede agregar rápidamente. ') }}
                                        </p>
                                        <div class="dropdown quick-add-btn">
                                            @canany(['create-form', 'create-poll', 'create-event'])
                                                <a class="btn-q-add dropdown-toggle dash-btn btn btn-default btn-light-primary"
                                                    data-bs-toggle="dropdown" href="javascript:void(0)" role="button"
                                                    aria-haspopup="false" aria-expanded="false">
                                                    <i class="ti ti-plus drp-icon"></i>
                                                    <span class="ms-1">{{ __('Acceso Rapido') }}</span>
                                                </a>
                                            @endcanany
                                            <div class="dropdown-menu">
                                                @if (\Auth::user()->can('create-form'))
                                                    <a href="{{ route('forms.create') }}" data-size="lg" data-url=""
                                                        data-ajax-popup="true" data-title="Add Product"
                                                        class="dropdown-item"
                                                        data-bs-placement="top "><span>{{ __('Add New Form') }}</span></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/dragdrop/dragula.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('vendor/apex-chart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/dragdrop/dragula.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>


    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).attr('data-url')).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Great!', '{{ __('Copy Link Successfully.') }}', 'success');
        }

        var today = new Date()
        var curHr = today.getHours()
        var target = document.getElementById("wishing");

        if (curHr < 12) {
            target.innerHTML = "Buen día,";
        } else if (curHr < 17) {
            target.innerHTML = "Buenas tardes,";
        } else {
            target.innerHTML = "Buenas noches,";
        }
    </script>
@endpush
