@extends('layouts.main')
@section('title', 'View Forms')
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('View Forms') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'))->text(__('Dashboard')) !!}</li>
            <li class="breadcrumb-item">{!! html()->a(route('forms.index'))->text(__('Forms')) !!}</li>
            <li class="breadcrumb-item">{{ __('View Forms') }}</li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="section-body">
            <div class="row">
                @if (!empty($formValue->Form->logo))
                    <div class="mb-2 text-center gallery gallery-md">
                        <img id="app-dark-logo" class="float-none gallery-item"
                            src="{{ Storage::exists($formValue->Form->logo) ? Storage::url($formValue->Form->logo) : Storage::url('not-exists-data-images/78x78.png') }}">
                    </div>
                @endif
                <div
                    class="card col-xl-8 col-lg-7 {{ isset($formValue->submited_forms_latitude) && isset($formValue->submited_forms_longitude) ? '' : 'mx-auto' }}">
                    <div class="card-header">
                        <h5> {{ $formValue->Form->title }}

                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="view-form-data">
                            <div class="row">
                                @foreach ($array as $keys => $rows)
                                    @foreach ($rows as $row_key => $row)
                                        @if ($row->type == 'checkbox-group')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                    <ul>
                                                        @foreach ($row->values as $key => $options)
                                                            @if (isset($options->selected))
                                                                <li>
                                                                    <label>{{ $options->label }}</label>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'file')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}

                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                        @if (property_exists($row, 'value'))
                                                            @if ($row->value)
                                                                @php
                                                                    $allowedExtensions = [
                                                                        'pdf',
                                                                        'pdfa',
                                                                        'fdf',
                                                                        'xdp',
                                                                        'xfa',
                                                                        'pdx',
                                                                        'pdp',
                                                                        'pdfxml',
                                                                        'pdxox',
                                                                        'xlsx',
                                                                        'csv',
                                                                        'xlsm',
                                                                        'xltx',
                                                                        'xlsb',
                                                                        'xltm',
                                                                        'xlw',
                                                                    ];
                                                                @endphp
                                                                @if ($row->multiple)
                                                                    <div class="row">
                                                                        @if (App\Facades\UtilityFacades::getsettings('storage_type') == 'local')
                                                                            @foreach ($row->value as $img)
                                                                                <div class="col-6">
                                                                                    @php
                                                                                        $fileName = explode('/', $img);
                                                                                        $fileName = end($fileName);
                                                                                    @endphp
                                                                                    @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                        <a class="my-2 btn btn-info"
                                                                                            href="{{ asset('storage/app/' . $img) }}"
                                                                                            type="image"
                                                                                            download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                    @else
                                                                                        <img src="{{ Storage::exists($img) ? asset('storage/app/' . $img) : Storage::url('not-exists-data-images/78x78.png') }}"
                                                                                            class="mb-2 img-responsive img-thumbnail">
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach ($row->value as $img)
                                                                                <div class="col-6">
                                                                                    @php
                                                                                        $fileName = explode('/', $img);
                                                                                        $fileName = end($fileName);
                                                                                    @endphp
                                                                                    @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                        <a class="my-2 btn btn-info"
                                                                                            href="{{ Storage::url($img) }}"
                                                                                            type="image"
                                                                                            download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                    @else
                                                                                        <img src="{{ Storage::url($img) }}"
                                                                                            class="mb-2 img-responsive img-thumbnail">
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            @if ($row->subtype == 'fineuploader')
                                                                                @if (App\Facades\UtilityFacades::getsettings('storage_type') == 'local')
                                                                                    @if ($row->value[0])
                                                                                        @foreach ($row->value as $img)
                                                                                            @php
                                                                                                $fileName = explode(
                                                                                                    '/',
                                                                                                    $img,
                                                                                                );
                                                                                                $fileName = end(
                                                                                                    $fileName,
                                                                                                );
                                                                                            @endphp
                                                                                            @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                <a class="my-2 btn btn-info"
                                                                                                    href="{{ asset('storage/app/' . $img) }}"
                                                                                                    type="image"
                                                                                                    download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                            @else
                                                                                                <img src="{{ Storage::exists($img) ? asset('storage/app/' . $img) : Storage::url('not-exists-data-images/78x78.png') }}"
                                                                                                    class="mb-2 img-responsive img-thumbnail">
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @else
                                                                                    @if ($row->value[0])
                                                                                        @foreach ($row->value as $img)
                                                                                            @php
                                                                                                $fileName = explode(
                                                                                                    '/',
                                                                                                    $img,
                                                                                                );
                                                                                                $fileName = end(
                                                                                                    $fileName,
                                                                                                );
                                                                                            @endphp
                                                                                            @if (in_array(pathinfo($img, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                                <a class="my-2 btn btn-info"
                                                                                                    href="{{ Storage::url($img) }}"
                                                                                                    type="image"
                                                                                                    download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                            @else
                                                                                                <img src="{{ Storage::url($img) }}"
                                                                                                    class="mb-2 img-responsive img-thumbnail">
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endif
                                                                            @else
                                                                                @if (App\Facades\UtilityFacades::getsettings('storage_type') == 'local')
                                                                                    @if (in_array(pathinfo($row->value, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                        @php
                                                                                            $fileName = explode('/',$row->value);
                                                                                            $fileName = end($fileName);
                                                                                        @endphp
                                                                                        <a class="my-2 btn btn-info"
                                                                                            href="{{ asset('storage/app/' . $row->value) }}"
                                                                                            type="image"
                                                                                            download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                    @else
                                                                                        <img src="{{ Storage::exists($row->value) ? asset('storage/app/' . $row->value) : Storage::url('not-exists-data-images/78x78.png') }}"
                                                                                            class="mb-2 img-responsive img-thumbnailss">
                                                                                    @endif
                                                                                @else
                                                                                    @if (in_array(pathinfo($row->value, PATHINFO_EXTENSION), $allowedExtensions))
                                                                                        @php
                                                                                            $fileName = explode('/',$row->value);
                                                                                            $fileName = end($fileName);
                                                                                        @endphp
                                                                                        <a class="my-2 btn btn-info"
                                                                                            href="{{ Storage::url($row->value) }}"
                                                                                            type="image"
                                                                                            download="">{!! substr($fileName, 0, 30) . (strlen($fileName) > 30 ? '...' : '') !!}</a>
                                                                                    @else
                                                                                        <img src="{{ Storage::url($row->value) }}"
                                                                                            class="mb-2 img-responsive img-thumbnailss">
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'header')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <{{ $row->subtype }}>
                                                        {{ html_entity_decode($row->label) }}
                                                        </{{ $row->subtype }}>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'paragraph')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <{{ $row->subtype }}>
                                                        {{ html_entity_decode($row->label) }}
                                                        </{{ $row->subtype }}>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'radio-group')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                        @foreach ($row->values as $key => $options)
                                                            @if (isset($options->selected))
                                                                {{ $options->label }}
                                                            @endif
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'select')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                        @foreach ($row->values as $options)
                                                            @if (isset($options->selected))
                                                                {{ $options->label }}
                                                            @endif
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif ($row->type == 'autocomplete')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                        {{ $row->value }}
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'number')
                                            <div class="col-12">
                                                {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                @if ($row->required)
                                                    <span class="text-danger align-items-center">*</span>
                                                @endif
                                                </b>
                                                <p>
                                                    {{ isset($row->value) ? $row->value : '' }}
                                                </p>
                                            </div>
                                        @elseif($row->type == 'text')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    @if ($row->subtype == 'color')
                                                        <div class="p-2" style="background-color: {{ $row->value }};">
                                                        </div>
                                                    @else
                                                        <p>
                                                            {{ isset($row->value) ? $row->value : '' }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($row->type == 'date')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                        {{ isset($row->value) ? date('d-m-Y', strtotime($row->value)) : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'textarea')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    @if ($row->subtype == 'ckeditor')
                                                        {!! isset($row->value) ? $row->value : '' !!}
                                                    @else
                                                        <p>
                                                            {{ isset($row->value) ? $row->value : '' }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($row->type == 'starRating')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    @php
                                                        $attr = ['class' => 'form-control'];
                                                        if ($row->required) {
                                                            $attr['required'] = 'required';
                                                        }
                                                        $value = isset($row->value) ? $row->value : 0;
                                                        $noOfStar = isset($row->number_of_star)
                                                            ? $row->number_of_star
                                                            : 5;
                                                    @endphp
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                    <div id="{{ $row->name }}" class="starRating"
                                                        data-value="{{ $value }}"
                                                        data-no_of_star="{{ $noOfStar }}">
                                                    </div>
                                                    <input type="hidden" name="{{ $row->name }}"
                                                        value="{{ $value }}">
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'SignaturePad')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    @php
                                                        $attr = ['class' => 'form-control'];
                                                        if ($row->required) {
                                                            $attr['required'] = 'required';
                                                        }
                                                        $value = isset($row->value) ? $row->value : 0;
                                                        $noOfStar = isset($row->number_of_star)
                                                            ? $row->number_of_star
                                                            : 5;
                                                    @endphp
                                                    {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                    @if ($row->required)
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    @if (property_exists($row, 'value'))
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <img src="{{ asset(Storage::url($row->value)) }}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($row->type == 'break')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <hr class="hr_border">
                                                </div>
                                            </div>
                                        @elseif($row->type == 'location')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label('Location:', 'location_id') !!}
                                                    <iframe width="100%" height="260" frameborder="0" scrolling="no"
                                                        marginheight="0" marginwidth="0"
                                                        src="https://maps.google.com/maps?q={{ $row->value }}&hl=en&z=14&amp;output=embed">
                                                    </iframe>
                                                </div>
                                            </div>
                                        @elseif($row->type == 'video')
                                            @if (isset($row->value) && Storage::exists($row->value))
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                        <br>
                                                        <a class="p-2 btn btn-primary"
                                                            href="{{ File::exists(Storage::path($row->value)) ? Storage::url($row->value) : '' }}"
                                                            type="video" download="">{{ __('Download Video') }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($row->type == 'selfie')
                                            @if (isset($row->value) && Storage::exists($row->value))
                                                <div class="row">
                                                    <div class="col-12">
                                                        {!! html()->label($row->label, $row->name)->class('form-label') !!}
                                                        <img src="{{ File::exists(Storage::path($row->value)) ? Storage::url($row->value) : Storage::url('app-logo/78x78.png') }}"
                                                            class="mb-2 img-responsive img-thumbnailss" />
                                                        <br>
                                                        <a class="p-2 btn btn-primary"
                                                            href="{{ Storage::url($row->value) }}" type="image"
                                                            download>{{ __('Download Image') }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! html()->label(isset($row->label) ? $row->label : '', $row->name)->class('form-label') !!}
                                                    @if (isset($row->required))
                                                        <span class="text-danger align-items-center">*</span>
                                                    @endif
                                                    <p>
                                                        {{ isset($row->value) ? $row->value : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                                @if ($formValue->payment_type == 'offlinepayment' && isset($formValue->transfer_slip))
                                    <div>
                                        <h5>{{ __('Download Payment Slip') }}</h5>
                                        <a href="{{ route('download.form.values.pdf', $formValue->id) }}"
                                            class="mt-2 btn btn-primary btn-lg">{{ __('Download') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="javascript:javascript:history.go(-1)"
                            class="btn btn-secondary float-end">{{ __('Back') }}</a>
                    </div>
                </div>
                @if (isset($formValue->submited_forms_latitude) && isset($formValue->submited_forms_longitude))
                    <div class="col-xl-4 col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('User Details') }}</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-responsive">
                                    <tr>
                                        <th>{{ __('IP Address') }}</th>
                                        <td>{{ isset($formValue->submited_forms_ip) ? $formValue->submited_forms_ip : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Country Name') }}</th>
                                        <td>{{ isset($formValue->submited_forms_country) ? $formValue->submited_forms_country : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Region Name') }}</th>
                                        <td>{{ isset($formValue->submited_forms_region) ? $formValue->submited_forms_region : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('City Name') }}</th>
                                        <td>{{ isset($formValue->submited_forms_city) ? $formValue->submited_forms_city : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Session Time') }}</th>
                                        <td>{{ isset($formValue) ? $formValue->getTotalSessionTime() : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div id="map" class="mb-0"></div>
                            </div>
                        </div>
                    </div>
                @endif
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
                var noOfStar = $(this).attr('data-no_of_star');
                if (noOfStar == 10) {
                    val = val / 2;
                }
                $(this).rateYo({
                    rating: val,
                    readOnly: true,
                    numStars: noOfStar
                })
            });
        }
    </script>
    @if (isset($formValue->submited_forms_latitude) && isset($formValue->submited_forms_longitude))
        <script>
            function initMap() {
                const myLatLng = {
                    lat: {{ $formValue->submited_forms_latitude }},
                    lng: {{ $formValue->submited_forms_longitude }}
                };

                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 5,
                    center: myLatLng,
                });

                new google.maps.Marker({
                    position: myLatLng,
                    map,
                    title: '{{ $formValue->submited_forms_city }}',
                });
            }
            window.initMap = initMap;
        </script>

        <script src="https://maps.google.com/maps/api/js?key={{ Utility::getsettings('google_map_api') }}&callback=initMap">
        </script>
    @endif
@endpush
