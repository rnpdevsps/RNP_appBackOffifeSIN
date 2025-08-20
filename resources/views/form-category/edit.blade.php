{!! html()->modelForm($formCategory, 'PUT', route('form-category.update', $formCategory->id))->attribute('data-validate')->open() !!}
</div>
<div class="modal-body">
    <div class="form-group">
        {!! html()->label(__('Name'))->for('name')->class('form-label') !!}
        {!! html()->text('name')->placeholder(__('Enter name'))->class('form-control')->required() !!}
    </div>
    <div class="form-group">
        {!! html()->label(__('Status'))->for('status')->class('form-label') !!}
        {!! html()->select(
                'status',
                [
                    '' => __('Select Form Status'),
                    '1' => __('Active'),
                    '2' => __('Deactive'),
                ],
                $formCategory->status,
            )->class('custom_select form-select')->id('status')->attribute('data-trigger') !!}
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        {!! html()->a(route('form-category.index'))->text(__('Cancel'))->class('btn btn-secondary') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->closeModelForm() !!}
</div>
