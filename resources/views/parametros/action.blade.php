<span>
    @can('edit-parametros')
        <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-parametro"
            data-url="{{ route('parametros.edit', $parametro->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan
    @can('delete-parametros')
        {!! html()->form('DELETE', route('parametros.destroy', $parametro->id))->id('delete-form-' . $parametro->id)->class('d-inline')->open() !!}
        <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $parametro->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! html()->form()->close() !!}
   @endcan
</span>
