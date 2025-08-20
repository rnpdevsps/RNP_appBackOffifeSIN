{!! html()->modelForm($permission, 'PUT', route('permission.update', $permission->id))->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-6 ">
            {!! html()->label(__('Enter first name'))->for('name')->class('form-label') !!}
            {!! html()->text('name')->class('form-control')->placeholder('Enter Name') !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="btn-flt float-end">
        {!! html()->button(__('Cancel'))->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->closeModelForm() !!}
