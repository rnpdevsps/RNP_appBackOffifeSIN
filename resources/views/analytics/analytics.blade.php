@extends('layouts.main')
@section('title', __('Analytics Dashboard'))
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
@endpush
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Analytics Dashboard') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item" id="current_site" data-siteid=""></li>
        </ul>
    </div>
@endsection
@section('content')
    @if (\Auth::user()->is_json_upload == 1 && count($site) > 0)
        <div class="row align-items-center justify-content-end gx-2 gy-1">
            <div class="col-4 col-md-2">
                <div class="select-box">
                    <select class="form-select" name="site_name select2" id="site-list" onchange="dash_site_detail()"
                        data-trigger data-id="$val->site_name">
                        @foreach ($site as $val)
                            <option value="{{ $val->id }}" data-site="{{ $val->site_name }}">{{ $val->site_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if (\Auth::user()->can('create-analytics-dashboard'))
                <div class="col-auto">
                    <a class="btn btn-primary" href="{{ route('analytics.Manage.site') }}" data-bs-toggle="tooltip"
                        data-bs-original-title="{{ __('Manage Site') }}">
                        {{ __('Manage Site') }}
                    </a>
                </div>
            @endif
            @if (\Auth::user()->can('delete-analytics-dashboard'))
                <div class="col-auto">
                    <a href="javascript:void(0)" class="btn btn-danger  delete-btn"
                        id="delete-form-{{ Auth::user()->id }}"data-url="{{ route('credential.destroy', Auth::user()->id) }}"
                        data-id="{{ Auth::user()->id }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-original-title="{{ __('Delete Json Credential') }}"><i
                            class="ti ti-trash me-2"></i>{{ __('Delete Credential') }}</a>
                </div>
            @endif
            @if (\Auth::user()->can('export-analytics-dashboard'))
                <div class="col-auto">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="saveAsPDF('dashboard page')" data-bs-toggle="tooltip"
                        title="{{ __('Download') }}">
                        <i class="ti ti-download"></i>
                    </a>
                </div>
            @endif
        </div>
    @endif
    @if (\Auth::user()->can('manage-analytics-dashboard'))
        @if (count($site) == 0 && \Auth::user()->can('create-analytics-dashboard'))
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-xxl-4">
                        @if (\Auth::user()->is_json_upload == 1)
                            <a href="{{ route('analytics.add.site') }}">
                        @else
                            <a data-bs-toggle="modal" data-bs-target="#json_upload">
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-plus"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-3">{{ __('Add New Site') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div id="printableArea">
                <div class="mt-3 col-sm-12">
                    <div class="card">
                        <div class="card-header row">
                            <div class="col">
                                <h5>{{ __('Visitor') }}</h5>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="week-chart" data-bs-toggle="pill"
                                            data-bs-target="#timeline-chart-week"
                                            type="button">{{ __('Week') }}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="15daysago-chart" data-bs-toggle="pill"
                                            data-bs-target="#timeline-chart-month"
                                            type="button">{{ __('Month') }}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="year-chart" data-bs-toggle="pill"
                                            data-bs-target="#timeline-chart-year"
                                            type="button">{{ __('Year') }}</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="timeline-chart-week" role="tabpanel"
                                    aria-labelledby="pills-user-tab-1">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">{{ __('Total New Users') }}</h6>
                                                    <h5 id="total_New_Users_week">0</h5>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">{{ __('Total Active Users') }}</h6>
                                                    <h5 id="total_Active_Users_week">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div id="user-timeline-chart-week">
                                                <div class="loader" id="progress">
                                                    <div class="text-center spinner" style="align-items: center;">
                                                        <img height="320px"
                                                            src="{{ asset('assets/images/loader.gif') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="timeline-chart-month" role="tabpanel"
                                    aria-labelledby="pills-user-tab-2">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">{{ __('Total New Users') }}</h6>
                                                    <h5 id="total_New_Users_15daysago">0</h5>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">{{ __('Total Active Users') }}</h6>
                                                    <h5 id="total_Active_Users_15daysago">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div id="user-timeline-chart-month">
                                                <div class="loader " id="progress">
                                                    <div class="text-center spinner" style="align-items: center;">
                                                        <img height="264px"
                                                            src="{{ asset('assets/images/loader.gif') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="timeline-chart-year" role="tabpanel"
                                    aria-labelledby="pills-user-tab-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">{{ __('Total New Users') }}</h6>
                                                    <h5 id="total_New_Users_year">0</h5>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-3">{{ __('Total Active Users') }}</h6>
                                                    <h5 id="total_Active_Users_year">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div id="user-timeline-chart-year">
                                                <div class="loader " id="progress">
                                                    <div class="text-center spinner" style="align-items: center;">
                                                        <img height="264px"
                                                            src="{{ asset('assets/images/loader.gif') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Users') }}</h5>
                                </div>
                                <div class="card-body" style="padding: 0px!important">
                                    <div id="usersChart">
                                        <div class="loader " id="progress">
                                            <div class="text-center spinner" style="align-items: center;">
                                                <img height="264px" src="{{ asset('assets/images/loader.gif') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Bounce Rate') }}</h5>
                                </div>
                                <div class="card-body" style="padding: 0px!important">
                                    <div id="bounceRateChart">
                                        <div class="loader " id="progress">
                                            <div class="text-center spinner" style="align-items: center;">
                                                <img height="264px" src="{{ asset('assets/images/loader.gif') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Session Duration') }}</h5>
                                </div>
                                <div class="card-body" style="padding: 0px!important">
                                    <div id="sessionDuration">
                                        <div class="loader " id="progress">
                                            <div class="text-center spinner" style="align-items: center;">
                                                <img height="264px" src="{{ asset('assets/images/loader.gif') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('New Users by Location') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mapcontainer" id="mapcontainer" style="height: 350px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3">
                            <div class="card bg-primary">
                                <div class="card-header">
                                    <h5 class="text-light">{{ __('Live Active Users') }}</h5>
                                </div>
                                <div class="card-body text-light" style="text-align:  center!important;">
                                    <div class="display-2">
                                        <i class="ti ti-antenna-bars-5"> </i>
                                    </div>
                                    <div class="display-6" id="live_users">0</div>
                                    <h5 class="text-light">{{ __('Active Users') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-xxl-7 d-flex">
                            <div class="card w-100 active-page-table">
                                <div class="card-header">
                                    <h5>{{ __('Top Active Pages') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="pc-dt-simple">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">{{ __('No') }}</th>
                                                    <th scope="col">{{ __('Active Page') }}</th>
                                                    <th scope="col">{{ __('screenPageViews') }}</th>
                                                    <th scope="col">{{ __('screenPageViewsPerUser') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="active_pages"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-5 d-flex">
                            <div class="card w-100">
                                <div class="card-header">
                                    <h5>{{ __('Sessions by device') }}</h5>
                                </div>
                                <div class="card-body" style="text-align: center!important;padding: 23px 0px!important">
                                    <div id="session_by_device">
                                        <div class="loader " id="progress">
                                            <div class="text-center spinner" style="align-items: center;">
                                                <img height="500px" src="{{ asset('assets/images/loader.gif') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-xl-4 col-md-6">
            <div id="json_upload" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalPopoversLabel">{{ __('JSON Update') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('analytics.save.json') }}" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf
                            <div class="modal-body">
                                <div class="form-group col-md-12">
                                    <label for="json_file" class="col-form-label">{{ __('Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="json_file" name="json_file" required
                                        accept=".json" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('script')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/analytics.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-jvectormap-world-mill.js') }}"></script>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.extension.js') }}"></script>
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/loader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <script>
        const get_chart_data_url = "{{ route('analytics.get.chart') }}";
        const get_live_user_url = "{{ route('analytics.get.live.users') }}";
        const get_active_pages_url = "{{ route('analytics.active.page') }}";
        document.addEventListener('DOMContentLoaded', function() {
            $('.form-select[data-trigger]').each(function() {
                new Choices(this);
            });
        });
        $('body').on('click', '.delete-btn', function(e) {
            let deleteUrl = $(this).data('url');
            let userId    = $(this).data('id');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "This action can not be undone. Do you want to continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('<form>', {
                        method: 'POST',
                        action: deleteUrl
                    });
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: $('meta[name="csrf-token"]').attr('content')
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            })
        });
        $(document).ready(function() {
            let selectedOption = $('#site-list option:selected');
            $('#site-list').attr("data-siteid", selectedOption.data('site'));
            $('#site-list').on('change', function() {
                let newSelectedOption = $(this).find('option:selected');
                $(this).attr("data-siteid", newSelectedOption.data('site'));
            });
            $('#site-list').trigger('change');
        });
    </script>
@endpush
