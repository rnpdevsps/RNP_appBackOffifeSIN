{!! html()->form('POST', route('permission.store'))->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {!! html()->label(__('Name'))->for('name')->class('form-label') !!}
            {!! html()->text('name')->placeholder('Enter name')->class('form-control') !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        {!! html()->button(__('Cancel'))->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
