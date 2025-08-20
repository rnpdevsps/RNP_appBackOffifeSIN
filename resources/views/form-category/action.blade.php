@can('edit-form-category')
    <a class="btn btn-sm small btn-primary" href="javascript:void(0);"
        data-url="{{ route('form-category.edit', $category->id) }}" id="edit-form-category" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}">
        <i class="text-white ti ti-edit"></i>
    </a>
@endcan
@can('delete-form-category')
    {!! html()->form('DELETE', route('form-category.destroy', $category->id))->attributes(['class' => 'd-inline', 'id' => 'delete-form-' . $category->id])->open() !!}
    {!! html()->a('javascript:void(0)')->class('btn btn-sm small btn-danger show_confirm')->attribute('data-bs-toggle', 'tooltip')->attribute('data-bs-placement', 'bottom')->id('delete-form-1')->attribute('data-bs-original-title', __('Delete'))->html('<i class="text-white ti ti-trash"></i>') !!}
    {!! html()->form()->close() !!}
@endcan
