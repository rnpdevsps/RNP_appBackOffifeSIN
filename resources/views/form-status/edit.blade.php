{!! html()->modelForm($formStatus, 'put', route('form-status.update', $formStatus->id))->attribute('data-validate', 'true')->attribute('id', 'form-status-modal')->attribute('class', 'modal-form')->open() !!}
<div class="modal-body">
    <div class="form-group">
        {!! html()->label(__('Name'), 'name')->class('form-label') !!}
        {!! html()->text('name')->placeholder(__('Enter name'))->class('form-control')->required() !!}
    </div>
    <div class="form-group">
        {!! html()->label(__('Color'), 'color')->class('form-label') !!}
        {!! html()->hidden('color', null)->attribute('id', 'color-hidden') !!}
        {!! html()->select('color', [
                '' => __('Select Status Color'),
                'danger' => __('Danger'),
                'success' => __('Success'),
                'info' => __('Info'),
                'primary' => __('Primary'),
                'secondary' => __('Secondary'),
                'warning' => __('Warning'),
            ])->class('custom_select form-select')->attribute('id', 'color')->attribute('data-trigger', true) !!}
    </div>
    <div class="form-group">
        {!! html()->label(__('Status'), 'status')->class('form-label') !!}
        {!! html()->hidden('status', null)->attribute('id', 'status-hidden') !!}
        <select name="status" class="custom_select form-select select2" id="status" data-trigger>
            <option value="" selected disabled>{{ __('Select Status') }}</option>
            <option value="1" @if ($formStatus->status == '1') selected @endif>{{ __('Active') }}
            </option>
            <option value="0" @if ($formStatus->status == '0') selected @endif>{{ __('Deactive') }}
            </option>
        </select>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        {!! html()->a(route('form-status.index'))->text(__('Cancel'))->class('btn btn-secondary') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->closeModelForm() !!}
