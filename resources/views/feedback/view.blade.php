<div class="card-body">
    <div class="col-12">
        <div class="form-group">
            {!! html()->label(' Name', $feedback->name)->class('form-label') !!}
            <p>
                {{ $feedback->name }}
            </p>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {!! html()->label(' Description', $feedback->description)->class('form-label') !!}
            <p>
                {{ $feedback->description }}
            </p>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {!! html()->label(' Star Rating', $feedback->rating)->class('form-label') !!}
            <div class="text-left">
                @php
                    $rating = $feedback->rating;
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    @if ($rating < $i)
                        @if (is_float($rating) && round($rating) == $i)
                            <i class="text-warning fas fa-star-half-alt"></i>
                        @else
                            <i class="fas fa-star"></i>
                        @endif
                    @else
                        <i class="text-warning fas fa-star"></i>
                    @endif
                @endfor
                <br>
                <span class="theme-text-color">({{ number_format($rating, 1) }})</span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        {!! html()->button(__('Back'))->type('button')->class('btn btn-secondary')->attribute('data-bs-dismiss', 'modal')->attribute('aria-label', 'Close') !!}
    </div>
</div>
