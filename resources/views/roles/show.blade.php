@extends('layouts.main')
@section('title', __('Permissions'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Permissions') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('roles.index'), __('Roles')) !!}</li>
            <li class="breadcrumb-item active">@yield('title')</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="mx-auto col-md-12">
            <div class="card responsive-card">
                <div class="card-header">
                    <h5>{{ __('All Permissions') }}</h5>
                </div>
                {!! html()->form('POST', route('roles.permit', $role->id))->class('form-horizontal')->attribute('data-validate')->open() !!}
                <div class="card-body responive-body">
                    <table class="table table-flush permission-table">
                        <thead>
                            <tr>
                                <th>{{ __('Module') }}</th>
                                <th>{{ __('Permissions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="mb-2">
                            @foreach ($allmodules as $module)
                                @if ($module != 'module')
                                    <tr>
                                        <td> {{ __(ucfirst($module)) }}</td>
                                        <td>
                                            <div class="row">
                                                <?php
                                                $default_permissions = ['manage', 'create', 'edit', 'delete', 'view', 'impersonate', 'fill', 'design', 'show', 'download', 'duplicate', 'theme-setting', 'integration', 'payment', 'result', 'vote', 'export', 'dashboard-qrcode', 'phoneverified', 'emailverified', 'document-generate', 'access-all', 'change-status'];
                                                ?>
                                                @foreach ($default_permissions as $permission)
                                                    @if (in_array($permission . '-' . $module, $allpermissions))
                                                        @php($key = array_search($permission . '-' . $module, $allpermissions))
                                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 form-check">
                                                            {!! html()->checkbox('permissions[]', in_array($permission . '-' . $module, $permissions), $key)->class('form-check-input')->id('permission_' . $key) !!}
                                                            {!! html()->label(ucfirst($permission), 'permission_' . $key)->class('form-check-label') !!}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                    </table>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        {!! html()->a(route('roles.index'), __('Cancel'))->class('btn btn-secondary') !!}
                        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                    </div>
                </div>
                {!! html()->form()->close() !!}
            </div>
        </div>
    </div>
@endsection
