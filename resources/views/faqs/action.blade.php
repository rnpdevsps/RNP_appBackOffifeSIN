<span>
    @can('edit-faqs')
        <a class="btn btn-primary btn-sm" href="faqs/{{ $faqs->id }}/edit" data-url="faqs/{{ $faqs->id }}/edit"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}"><i
                class="ti ti-edit"></i></a>
    @endcan
    @can('delete-faqs')
        {!! html()->form('DELETE', route('faqs.destroy', $faqs->id))->id('delete-form-' . $faqs->id)->class('d-inline')->open() !!}
        <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $faqs->id }}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
                class="mr-0 ti ti-trash"></i></a>
        {!! html()->form()->close() !!}
    @endcan
</span>
