<span>

    @can('edit-candidatos')
        <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-candidato"
            data-url="{{ route('candidatos.edit', $candidato->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan
    @can('delete-candidatos')
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['candidatos.destroy', $candidato->id],
            'id' => 'delete-form-' . $candidato->id,
            'class' => 'd-inline',
        ]) !!}
        <a href="#" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $candidato->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! Form::close() !!}
    @endcan

</span>
