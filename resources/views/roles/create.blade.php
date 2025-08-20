{!! html()->form('POST', route('roles.store'))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Name'))->for('name')->class('col-form-label') !!}
            {!! html()->text('name')->placeholder(__('Enter name'))->class('form-control')->required() !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
