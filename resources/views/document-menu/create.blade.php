{!! html()->form('POST', route('docmenu.store'))
->attribute('data-validate')
->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Title'))->for('title')->class('form-label') !!}
            {!! html()->text('title')
                ->class('form-control')
                ->required()
                ->placeholder(__('Enter title'))
            !!}
        </div>
        <input type="hidden" name="document_id" value="{{ $documents->id }}">
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))
        ->type('submit')
        ->class('btn btn-primary')
    !!}
    </div>
</div>
{!! html()->form()->close() !!}

