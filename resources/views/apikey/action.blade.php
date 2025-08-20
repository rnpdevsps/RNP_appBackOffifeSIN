<span>
    

    @can('edit-apikey')
        <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-apikey"
            data-url="{{ route('apikey.edit', $apikey->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan
    @can('delete-apikey')
        {!! html()->form('DELETE', route('apikey.destroy', $apikey->id))->id('delete-form-' . $apikey->id)->class('d-inline')->open() !!}
        <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $apikey->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! html()->form()->close() !!}
   @endcan
</span>
