@can('edit-knowledge-category')
    <a class="btn btn-primary btn-sm edit-knowledge-category" href="javascript:void(0);" id="edit-knowledge-category"
        data-url="{{ route('knowledge-category.edit', $knowledgeCategory->id) }}" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-knowledge-category')
    {!! html()->form('DELETE', route('knowledge-category.destroy', $knowledgeCategory->id))->id('delete-form-' . $knowledgeCategory->id)->class('d-inline')->open() !!}
    <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Delete') }}" id="delete-form-{{ $knowledgeCategory->id }}">
        <i class="ti ti-trash"></i>
    </a>
    {!! html()->form()->close() !!}
@endcan
