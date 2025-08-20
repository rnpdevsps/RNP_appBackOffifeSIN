@can('edit-announcement')
    <a class="btn btn-sm small btn-primary" href="{{ route('announcement.edit', $announcement->id) }}" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}">
        <i class="text-white ti ti-edit"></i>
    </a>
@endcan
@can('delete-announcement')
    {!! html()->form('DELETE', route('announcement.destroy', $announcement->id))->class('d-inline')->attribute('id', 'delete-form-' . $announcement->id)->open() !!}
    
    {!! html()->a('javascript:void(0);', '<i class="text-white ti ti-trash"></i>')
    ->class('btn btn-sm small btn-danger show_confirm')
    ->attribute('data-bs-toggle', 'tooltip')
    ->attribute('data-bs-placement', 'bottom')
    ->attribute('id', 'delete-form-1')
    ->attribute('data-bs-original-title', __('Delete')) !!}
    
    {!! html()->form()->close() !!}
@endcan
