@php
    $hashids = new Hashids('', 20);
    $id = $hashids->encodeHex($contratos->id);
    $view = request()->query->get('view');
@endphp

@if ($view == null)
    @can('edit-contratos')
        <a href="{{ route('contratos.edit', $contratos->id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}"><i
                class="ti ti-edit"></i> </a>
    @endcan

    @can('delete-contratos')
    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['contratos.destroy', $contratos->id],
        'id' => 'delete-form-' . $contratos->id,
        'class' => 'd-inline',
    ]) !!}
    <a href="#" class="btn btn-danger btn-sm show_confirm_submited_form_delete " id="delete-form-{{ $contratos->id }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
            class="ti ti-trash"></i></a>
    {!! Form::close() !!}
    @endcan
@endif

@if ($view !== null && $view == 'trash')
    <a class="btn btn-success btn-sm" href="{{ route('form.restore', $contratos->id) }}" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ __('Restore') }}"><i class="ti ti-recycle"></i></a>

    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['form.force.delete', $contratos->id],
        'id' => 'formforcedelete-' . $contratos->id,
        'class' => 'd-inline',
    ]) !!}
    <a href="#" class="btn btn-danger btn-sm show_confirm" id="formforcedelete-{{ $contratos->id }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ __('Delete Pemanently') }}"><i class="mr-0 ti ti-trash"></i></a>
    {!! Form::close() !!}
@endif
