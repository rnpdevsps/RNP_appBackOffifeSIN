{!! html()->form('POST', route('feature.store'))->attribute('class', 'form-horizontal')->attribute('enctype', 'multipart/form-data')->attribute('data-validate')->attribute('novalidate')->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Feature Name'), 'feature_name')->class('form-label') !!}
                <input type="text" name="feature_name" id="feature_name" class="form-control"
                    placeholder="{{ __('Enter feature name') }}" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Feature Bold Name'), 'feature_bold_name')->class('form-label') !!}
                <input type="text" name="feature_bold_name" id="feature_bold_name" class="form-control"
                    placeholder="{{ __('Enter feature Bold name') }}" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Feature Detail'), 'feature_detail')->class('form-label') !!}
                {!! html()->textarea('feature_detail')->class('form-control')->rows(3)->placeholder(__('Enter feature detail')) !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Image'), 'feature_image')->class('form-label') !!}
                {{ __('* (svg)') }}
                {!! html()->file('feature_image')->class('form-control')->id('feature_image')->accept('.svg') !!}
                <small>{{ __('NOTE: Allowed file extension : .svg (Max Size: 2 MB)') }}</small>
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
