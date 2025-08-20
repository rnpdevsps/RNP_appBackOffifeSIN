@can('edit-form-status')
    <a class="btn btn-sm small btn-primary" id="edit-form-status" href="javascript:void(0);"
        data-url="{{ route('form-status.edit', $status->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="text-white ti ti-edit"></i>
    </a>
@endcan
@can('delete-form-status')
    {!! html()->form('DELETE', route('form-status.destroy', $status->id))->class('d-inline')->attribute('id', 'delete-form-' . $status->id)->open() !!}
    {!! html()->a('javascript:void(0)')->class('btn btn-sm small btn-danger show_confirm')->id('delete-form-1')->attribute('data-bs-toggle', 'tooltip')->attribute('data-bs-placement', 'bottom')->attribute('data-bs-original-title', __('Delete'))->open() !!}<i class="text-white ti ti-trash"></i>

    {!! html()->form()->close() !!}
@endcan
