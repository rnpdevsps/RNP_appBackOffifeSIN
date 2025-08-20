@can('design-form-template')
    <a class="btn btn-info btn-sm" href="{{ route('formTemplate.design', $FormTemplate->id) }}" id="design-form"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Design') }}"><i
            class="ti ti-brush"></i></a>
@endcan
@can('edit-form-template')
    <a class="btn btn-sm small btn-primary" href="{{ route('form-template.edit', $FormTemplate->id) }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}"
        aria-label="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-form-template')
    {!! html()->form('DELETE', route('form-template.destroy', $FormTemplate->id))->class('d-inline')->id('delete-form-' . $FormTemplate->id)->open() !!}
    {!! html()->a('javascript:void(0)')->class('btn btn-sm small btn-danger show_confirm')->id('delete-form-1')->attribute('data-bs-toggle', 'tooltip')->attribute('data-bs-placement', 'bottom')->attribute('data-bs-original-title', __('Delete'))->open() !!}<i class="text-white ti ti-trash"></i>
    {!! html()->form()->close() !!}
@endcan
