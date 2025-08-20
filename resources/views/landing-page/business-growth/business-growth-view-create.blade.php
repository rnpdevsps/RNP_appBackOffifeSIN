{!! html()->form('POST', route('business.growth.view.store'))->attribute('class', 'form-horizontal')->attribute('data-validate', true)->attribute('novalidate', true)->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Business Growth View Name'), 'business_growth_view_name')->class('form-label') !!}
                {!! html()->text('business_growth_view_name')->class('form-control')->placeholder(__('Enter business growth view name')) !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Business Growth View Amount'), 'business_growth_view_amount')->class('form-label') !!}
                {!! html()->text('business_growth_view_amount')->class('form-control')->placeholder(__('Enter business growth view amount')) !!}
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
