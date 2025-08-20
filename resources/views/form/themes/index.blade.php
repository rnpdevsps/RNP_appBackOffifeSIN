{!! html()->form('POST', route('form.theme.update', $form->id))->attributes([
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
        'data-validate' => true,
    ])->open() !!}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            {!! html()->hidden('theme', $slug) !!}
            <div class="form-group d-flex align-items-center row">
                <div class="setting-card setting-logo-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <h6 class="mt-2">
                                    <i data-feather="credit-card" class="me-2"></i>{{ __('Primary color settings') }}
                                </h6>
                                <div class="theme-color themes-color">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-1' ? 'active_color' : '' }}"
                                        data-value="theme-1"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-1' ? 'checked' : '' }} name="color"
                                        value="theme-1">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-2' ? 'active_color' : '' }}"
                                        data-value="theme-2"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-2' ? 'checked' : '' }} name="color"
                                        value="theme-2">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-3' ? 'active_color' : '' }}"
                                        data-value="theme-3"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-3' ? 'checked' : '' }} name="color"
                                        value="theme-3">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-4' ? 'active_color' : '' }}"
                                        data-value="theme-4"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-4' ? 'checked' : '' }} name="color"
                                        value="theme-4">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-5' ? 'active_color' : '' }}"
                                        data-value="theme-5"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-5' ? 'checked' : '' }} name="color"
                                        value="theme-5">
                                    <br>
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-6' ? 'active_color' : '' }}"
                                        data-value="theme-6"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-6' ? 'checked' : '' }} name="color"
                                        value="theme-6">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-7' ? 'active_color' : '' }}"
                                        data-value="theme-7"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-7' ? 'checked' : '' }} name="color"
                                        value="theme-7">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-8' ? 'active_color' : '' }}"
                                        data-value="theme-8"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-8' ? 'checked' : '' }} name="color"
                                        value="theme-8">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-9' ? 'active_color' : '' }}"
                                        data-value="theme-9"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-9' ? 'checked' : '' }} name="color"
                                        value="theme-9">
                                    <a href="javascript:void(0);"
                                        class="themes-color-change {{ $form->theme_color == 'theme-10' ? 'active_color' : '' }}"
                                        data-value="theme-10"></a>
                                    <input type="radio" class="theme_color d-none"
                                        {{ $form->theme_color == 'theme-10' ? 'checked' : '' }} name="color"
                                        value="theme-10">
                                </div>
                                <div class="color-picker-wrp">
                                    <input type="color" value="{{ $form->theme_color ? $form->theme_color : '' }}"
                                        class="colorPicker {{ isset($form->color_flag) && $form->color_flag == 'true' ? 'active_color' : '' }}"
                                        name="custom_color" id="color-picker">
                                    <input type='hidden' name="color_flag"
                                        value={{ isset($form->color_flag) && $form->color_flag == 'true' ? 'true' : 'false' }}>
                                </div>
                            </div>
                            @if ($slug == 'theme3')
                                <div class="form-group">
                                    {!! html()->label(__('Background Image'), 'background_image')->class('form-label') !!}
                                    {!! html()->file('background_image')->class('form-control') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="float-end">
        {!! html()->button(__('Cancel'))->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal') !!}
        {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary')->attribute('data-bs-dismiss', 'modal')->attribute('id', 'save-btn') !!}

    </div>
</div>
{!! html()->form()->close() !!}
<script>
    $('.colorPicker').on('click', function(e) {
        $('body').removeClass('custom-color');
        if (/^theme-\d+$/) {
            $('body').removeClassRegex(/^theme-\d+$/);
        }
        $('body').addClass('custom-color');
        $('.themes-color-change').removeClass('active_color');
        $(this).addClass('active_color');
        const input = document.getElementById("color-picker");
        setColor();
        input.addEventListener("input", setColor);

        function setColor() {
            $(':root').css('--color-customColor', input.value);
        }

        $(`input[name='color_flag`).val('true');
    });
    $('.themes-color-change').on('click', function() {
        $(`input[name='color_flag']`).val('false');

        var color_val = $(this).data('value');
        $('body').removeClass('custom-color');
        if ($('body').hasClass(/^theme-\d+$/)) {
            $('body').removeClassRegex(/^theme-\d+$/);
        }
        $('body').addClass(color_val);
        $('.theme-color').prop('checked', false);
        $('.themes-color-change').removeClass('active_color');
        $('.colorPicker').removeClass('active_color');
        $(this).addClass('active_color');
        $(`input[value=${color_val}]`).prop('checked', true);
    });
    $('.themes-color-change.active_color').trigger('click');
    $.fn.removeClassRegex = function(regex) {
        return $(this).removeClass(function(index, classes) {
            return classes.split(/\s+/).filter(function(c) {
                return regex.test(c);
            }).join(' ');
        });
    };
</script>
