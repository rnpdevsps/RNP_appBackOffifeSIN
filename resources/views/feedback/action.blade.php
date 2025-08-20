@can('show-feedback')
    <a class="btn btn-sm small btn-primary" id="view-feedback" href="javascript:void(0);"
        data-url="{{ route('feedback.show', $feedback->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('View') }}">
        <i class="ti ti-eye"></i>
    </a>
@endcan

@can('delete-feedback')
    {!! html()->form('DELETE', route('feedback.destroy', $feedback->id))->id('delete-form-' . $feedback->id)->class('d-inline')->open() !!}
    {!! html()->a('javascript:void(0)')->class('btn btn-sm small btn-danger show_confirm')->id('delete-form-1')->attribute('data-bs-toggle', 'tooltip')->attribute('data-bs-placement', 'bottom')->attribute('data-bs-original-title', __('Delete'))->open() !!}<i class="text-white ti ti-trash"></i>
    {!! html()->form()->close() !!}
@endcan
