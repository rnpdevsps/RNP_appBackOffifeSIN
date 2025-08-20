@can('edit-module')
    <a class="btn btn-primary btn-sm edit_module" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Edit') }}" href="{{ route('module.edit', $module->id) }}" id="edit-module"
        data-action="module/{{ $module->id }}/edit"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-module')
    {!! html()->form('DELETE', route('module.destroy', $module->id))->id('delete-form-' . $module->id)->class('d-inline')->open() !!}
    <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
        title="Delete" data-bs-original-title="{{ __('Delete') }}" id="delete-form-{{ $module->id }}">
        <i class="mr-0 ti ti-trash"></i>
    </a>
    {!! html()->form()->close() !!}
@endcan
