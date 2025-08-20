@can('edit-blog')
    <a class="btn btn-sm small btn-primary" href="blogs/{{ $blog->id }}/edit" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}"><i class="text-white ti ti-edit"></i>
    </a>
@endcan
@can('delete-blog')
    {!! html()->form('DELETE', route('blogs.destroy', $blog->id))->class('d-inline')->id('delete-form-' . $blog->id)->open() !!}
    {!! html()->a('#')->class('btn btn-sm small btn-danger show_confirm')->attribute('data-bs-toggle', 'tooltip')->attribute('data-bs-placement', 'bottom')->id('delete-form-1')->attribute('data-bs-original-title', __('Delete'))->open() !!}<i class="text-white ti ti-trash"></i>
    {!! html()->form()->close() !!}
@endcan
