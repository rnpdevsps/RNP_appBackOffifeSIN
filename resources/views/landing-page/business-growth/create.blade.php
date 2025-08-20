{!! html()->form('POST', route('business.growth.store'))->class('form-horizontal')->attribute('data-validate', true)->attribute('novalidate', true)->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Business Growth Title'), 'business_growth_title')->class('form-label') !!}
                {!! html()->text('business_growth_title')->class('form-control')->placeholder(__('Enter business growth title')) !!}
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
