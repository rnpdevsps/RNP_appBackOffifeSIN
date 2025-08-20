@can('edit-page-setting')
    <a class="btn btn-sm small btn-primary" href="{{ route('page-setting.edit', $row->id) }}" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}">
        <i class="text-white ti ti-edit"></i>
    </a>
@endcan
@can('delete-page-setting')
    {!! html()->form('DELETE', route('page-setting.destroy', $row->id))->class('d-inline')->id('delete-form-' . $row->id)->open() !!}
    <a href="javascript:void(0);" class="btn btn-sm small btn-danger show_confirm" data-bs-toggle="tooltip"
        data-bs-placement="bottom" id="delete-form-1" data-bs-original-title="{{ __('Delete') }}">
        <i class="text-white ti ti-trash"></i>
    </a>
    {!! html()->form()->close() !!}
@endcan
