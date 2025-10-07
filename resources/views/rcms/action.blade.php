<span>

    @can('edit-rcms')
        <a class="btn btn-warning btn-sm" href="javascript:void(0);" id="edit-rcm"
            data-url="{{ route('rcms.edit', $rcm->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan

    @can('delete-rcms')
        {!! html()->form('DELETE', route('rcms.destroy', $rcm->id))->id('delete-form-' . $rcm->id)->class('d-inline')->open() !!}
        <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $rcm->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! html()->form()->close() !!}
    @endcan


</span>
