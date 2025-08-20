@can('edit-knowledge')
    <a class="btn btn-primary btn-sm edit-knowledge-base" href="javascript:void(0);" id="edit-knowledge-base"
        data-url="{{ route('knowledges.edit', $knowledgeBase->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
@endcan
@can('delete-knowledge')
    {!! html()->form('DELETE', route('knowledges.destroy', $knowledgeBase->id))->id('delete-form-' . $knowledgeBase->id)->class('d-inline')->open() !!}
    <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
    data-bs-original-title="{{ __('Delete') }}" id="delete-form-{{ $knowledgeBase->id }}"><i
        class="ti ti-trash"></i></a>
    {!! html()->form()->close() !!}
@endcan
