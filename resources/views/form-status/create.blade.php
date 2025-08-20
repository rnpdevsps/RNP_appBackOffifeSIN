{!! html()->form('post', route('form-status.store'))->attribute('data-validate', true)->open() !!}
<div class="modal-body">
    <div class="form-group">
        {!! html()->label(__('Name'), 'name')->class('form-label') !!}
        {!! html()->text('name')->placeholder(__('Enter name'))->class('form-control')->required() !!}
    </div>
    <div class="form-group">
        {!! html()->label(__('Select Color'), 'color')->class('form-label') !!}
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
        {!! html()->select('status', [
                '' => __('Select Form Status'),
                '1' => __('Active'),
                '2' => __('Deactive'),
            ])->class('custom_select form-select')->attribute('id', 'status')->attribute('data-trigger', true) !!}
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        {!! html()->a(route('form-status.index'))->text(__('Cancel'))->class('btn btn-secondary') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
