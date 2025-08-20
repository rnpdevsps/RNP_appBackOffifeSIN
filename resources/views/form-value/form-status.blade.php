{!! html()->modelForm($formValue, 'POST', route('form-value.status.update', $formValue->id))->open() !!}
<div class="row">
    <div class="form-group">
        {!! html()->select('form_status', $formStatus, $formValue->form_status)->class('form-control form-select')->attribute('data-trigger') !!}
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary"><a href="{{ route('forms.index') }}"
            class="text-white">{{ __('Back') }}</a></button>
    {!! html()->button(__('Save'))->class('btn btn-primary')->type('submit') !!}
</div>
{!! html()->closeModelForm() !!}
