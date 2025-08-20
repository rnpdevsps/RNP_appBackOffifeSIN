{!! html()->form('POST', route('menu.store'))->class('form-horizontal')->attributes([
        'enctype' => 'multipart/form-data',
        'data-validate',
        'novalidate',
    ])->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Menu Name'))->for('menu_name')->class('form-label') !!}
                {!! html()->text('menu_name', Utility::getsettings('menu_name'))->class('form-control')->placeholder(__('Enter menu name')) !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Menu Bold Name'))->for('menu_bold_name')->class('form-label') !!}
                {!! html()->text('menu_bold_name', Utility::getsettings('menu_bold_name'))->class('form-control')->placeholder(__('Enter menu bold name')) !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Menu Detail'))->for('menu_detail')->class('form-label') !!}
                {!! html()->textarea('menu_detail', Utility::getsettings('menu_detail'))->class('form-control')->rows(3)->placeholder(__('Enter menu detail')) !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                {!! html()->label(__('Image'))->for('menu_image')->class('form-label') !!} *
                {!! html()->file('menu_image')->class('form-control')->id('menu_image') !!}
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
