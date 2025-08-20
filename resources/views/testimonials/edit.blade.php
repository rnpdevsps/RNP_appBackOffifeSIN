@extends('layouts.main')
@section('title', __('Edit Testimonial'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('Edit Testimonial') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard'))->attributes(['class' => 'breadcrumb-link']) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('testimonial.index'))->text(__('Testimonial'))->attributes(['class' => 'breadcrumb-link']) !!}</li>
            <li class="breadcrumb-item">{{ __('Edit Testimonial') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="m-auto col-md-6">
                <div class="card ">
                    <div class="card-header">
                        <h5> {{ __('Edit Testimonial') }}</h5>
                    </div>
                    {!! html()->modelForm($testimonial, 'Patch', route('testimonial.update', $testimonial->id))->attribute('data-validate')->attribute('enctype', 'multipart/form-data')->open() !!}
                    <div class="card-body">
                        <div class="form-group ">
                            {!! html()->label(__('Name'))->class('form-label')->for('name') !!}
                            {!! html()->text('name')->class('form-control')->placeholder(__('Enter name'))->required() !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Title'))->class('form-label')->for('title') !!}
                            {!! html()->text('title')->class('form-control')->placeholder(__('Enter title'))->required() !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Image'))->class('form-label')->for('image') !!}
                            {!! html()->file('image')->class('form-control')->id('image')->accept('.jpeg,.jpg,.png') !!}
                            <small>{{ __('NOTE: Allowed file extension : .jpeg,.jpg,.png (Max Size: 2 MB)') }}</small>
                        </div>
                        @if (isset($testimonial->image))
                            <img src="{{ Illuminate\Support\Facades\Storage::url($testimonial->image) }}" width="100"
                                height="100" alt="" class="mb-2">
                        @endif
                        <div class="form-group">
                            {!! html()->label(__('Designation'))->class('form-label')->for('designation') !!}
                            {!! html()->text('designation')->class('form-control')->placeholder(__('Enter designation'))->required() !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Description'))->class('form-label')->for('description') !!}
                            {!! html()->textarea('desc')->class('form-control')->rows(3)->placeholder(__('Enter description'))->required() !!}
                        </div>
                        <div class="form-group">
                            {!! html()->label(__('Star Rating'))->class('form-label')->for('rating') !!}
                            <div id="rating" class="starRating jq-ry-container" data-value="{{ $testimonial->rating }}"
                                data-num_of_star="5"></div>
                            {!! html()->hidden('rating', $testimonial->rating)->class('calculate')->attribute('data-star', 5) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3 btn-flt float-end">
                            {!! html()->a(route('testimonial.index'), __('Cancel'))->class('btn btn-secondary') !!}
                            {!! html()->button(__('Save'))->type('submit')->class('btn btn-primary') !!}
                        </div>
                    </div>
                    {!! html()->closeModelForm() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('style')
    <link href="{{ asset('vendor/jqueryform/css/jquery.rateyo.min.css') }}" rel="stylesheet" />
@endpush
@push('script')
    <script src="{{ asset('vendor/jqueryform/js/jquery.rateyo.min.js') }}"></script>
    <script>
        var $starRating = $('.starRating');
        if ($starRating.length) {
            $starRating.each(function() {
                var val = $(this).attr('data-value');
                var num_of_star = $(this).attr('data-num_of_star');
                $(this).rateYo({
                    rating: val,
                    halfStar: true,
                    numStars: num_of_star,
                    precision: 2,
                    onSet: function(rating, rateYoInstance) {
                        num_of_star = $(rateYoInstance.node).attr('data-num_of_star');
                        var input = ($(rateYoInstance.node).attr('id'));
                        if (num_of_star == 10) {
                            rating = rating * 2;
                        }
                        $('input[name="' + input + '"]').val(rating);
                    }
                })
            });
        }
    </script>
@endpush
