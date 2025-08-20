{!! html()->form('POST')->attribute('data-validate')->attribute('id', 'feedbackForm')->open() !!}
<div @if (!Str::contains($currentUrl, 'forms/survey')) class="modal-body" @endif>
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Name'))->for('name')->class('form-label') !!}
            {!! html()->text('name')->placeholder(__('Enter name'))->class('form-control')->required() !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Description'))->class('form-label')->for('desc') !!}
            {!! html()->textarea('desc')->class('form-control')->rows(3)->placeholder(__('Enter description'))->required() !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            {!! html()->label(__('Star Rating'))->class('form-label')->for('rating') !!}
            <div id="rating" class="starRating jq-ry-container" data-value="0" data-num_of_star="5">
            </div>
            {!! html()->hidden('rating', 0)->class('calculate')->attribute('data-star', 5) !!}
        </div>
    </div>
    {!! html()->hidden('formId', $formId) !!}
    {!! html()->hidden('formValueId', $formValueId) !!}
</div>

<div class="modal-footer">
    <div class="float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {!! html()->button(__('Save'))->type('button')->id('feedbackSubmitButton')->class('btn btn-primary') !!}
    </div>
</div>
{!! html()->form()->close() !!}
<script>
    $(document).ready(function() {
        $(document).on('click', '#feedbackSubmitButton', function(e) {
            e.preventDefault();
            let form = $('#feedbackForm');
            let formData = form.serialize();
            $.ajax({
                url: "{{ route('feedback.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#common_modal').modal('hide');
                        show_toastr('Success', response.message, 'success');
                        $('#btn-feedback').remove();
                        form[0].reset();
                    }
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON ?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors)
                            .flat()
                            .join('\n');
                        show_toastr('error', errorMessages, 'error');
                    } else {
                        show_toastr('error', 'An error occurred. Please try again.',
                            'error');
                    }
                }
            });
        });
    });
</script>
