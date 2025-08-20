@can('edit-dashboardwidget')
    <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-dashboard" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}"
        data-url="edit-dashboard/{{ $dashboard->id }}/edit"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-dashboardwidget')
    {!! html()->form('DELETE', route('delete.dashboard', $dashboard->id))->id('delete-form-' . $dashboard->id)->class('d-inline')->open() !!}
    {!! html()->a('javascript:void(0)')->class('btn btn-danger btn-sm show_confirm')->attribute('data-bs-toggle', 'tooltip')->attribute('data-bs-placement', 'bottom')->attribute('data-bs-original-title', __('Delete'))->id('delete-form-' . $dashboard->id)->open() !!}
    <i class="mr-0 ti ti-trash"></i>
    {!! html()->form()->close() !!}
@endcan
