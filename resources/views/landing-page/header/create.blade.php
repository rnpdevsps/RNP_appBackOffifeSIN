{!! html()->form('POST', route('header.menu.store'))->attribute('class', 'form-horizontal')->attribute('data-validate')->attribute('novalidate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! html()->label(__('Select page'), 'select_page')->class('col-form-label') !!}
                {!! html()->select('page_id', $pages)->class('form-select')->id('page_name')->attribute('required') !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
