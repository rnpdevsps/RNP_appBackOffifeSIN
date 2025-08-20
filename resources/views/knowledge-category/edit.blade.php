{!! html()->modelForm($knowledgeCategory, 'PUT', route('knowledge-category.update', $knowledgeCategory->id))
    ->attribute('data-validate')
    ->open() !!}
@include('knowledge-category.form')
{{-- <div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('Enter first name')]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</div> --}}
{!! html()->closeModelForm() !!}

