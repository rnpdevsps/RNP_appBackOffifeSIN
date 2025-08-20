@if (\Auth::user()->can('delete-analytics-dashboard'))
    {!! html()->form('DELETE', route('analytics.delete.site', $site->id))->id('delete-form-' . $site->id)->class('d-inline')->open() !!}
    <a href="javascript:void(0)" class="btn btn-danger btn-sm show_confirm" id="delete-form-{{ $site->id }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Delete') }}"><i
            class="ti ti-trash"></i></a>
    {!! html()->form()->close() !!}
@endif
