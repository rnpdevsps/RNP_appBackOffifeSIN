<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Title'), 'title', ['class' => 'col-form-label']) !!}
            {!! html()->text('title')->placeholder(__('Enter Title'))->required()->class('form-control') !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Category'), 'category', ['class' => 'col-form-label']) !!}
            {!! html()->select('category', $knowledgesCategories, isset($knowledgeBase) ? $knowledgeBase->category_id : null)->class('form-control')->placeholder(__('Select Knowledge Category'))->required()->attribute('data-trigger', '')->id('category') !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Document'), 'document', ['class' => 'col-form-label']) !!}
            {!! html()->select('document', $documents, isset($knowledgeBase) ? $knowledgeBase->document_id : null)->class('form-control')->placeholder(__('Select Document'))->required()->attribute('data-trigger', '')->id('document') !!}
        </div>
        <div class="form-group">
            {!! html()->label(__('Description'), 'description', ['class' => 'col-form-label']) !!}
            {!! html()->textarea('description')->placeholder(__('Enter description'))->required()->class('form-control')->rows(5) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
