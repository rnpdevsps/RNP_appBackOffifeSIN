{!! html()->form('POST', route('test.send.mail'))->attribute('data-validate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12 ">
            {!! html()->label(__('Email'), 'email', ['class' => 'form-label']) !!}
            {!! html()->text('email')->class('form-control')->placeholder(__('Enter email'))->required() !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        {!! html()->button(__('Cancel'))->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal') !!}
        {!! html()->button(__('Send'))->type('submit')->class('btn btn-primary')->id('save-btn') !!}
    </div>
</div>
{!! html()->form()->close() !!}
