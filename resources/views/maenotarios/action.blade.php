<span>
    @can('edit-user')
        <a class="btn btn-primary btn-sm" href="javascript:void(0);" id="edit-maenotario"
            data-url="{{ route('maenotarios.edit', $maenotario->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-original-title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
    @endcan
    @can('delete-user')
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['maenotarios.destroy', $maenotario->id],
            'id' => 'delete-form-' . $maenotario->id,
            'class' => 'd-inline',
        ]) !!}
        <a href="#" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $maenotario->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="ti ti-trash"></i></a>
        {!! Form::close() !!}
    @endcan
</span>
