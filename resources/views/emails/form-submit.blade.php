@component('mail::message')
# {{ $form_value->Form->title }}
<link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
<div class="section-body">
<div class="row">
<div class="mx-auto card col-md-6">
<div class="card-body">
@if (!empty($form_value->Form->logo))
<div class="text-center">
{!! html()->img(asset('storage/app/' . $form_value->Form->logo))->id('app-dark-logo')->class('img img-responsive my-5 w-30 justify-content-center text-center')->alt('Image Description') !!}
</div>
@endif
<table class="table table-bordered" style="width: 100%">
@foreach ($form_valuearray as $rows)
@foreach ($rows as $row_key => $row)
@if ($row->type == 'checkbox-group')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}@if ($row->required)
*
@endif
</td>
<td>
<ul>
@foreach ($row->values as $key => $options)
@if (isset($options->selected))
<li>
<label>{{ $options->label }}</label>
</li>
@endif
@endforeach
</ul>
</td>
</tr>
@elseif($row->type == 'file')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}@if ($row->required)
*
@endif
</td>
<td>
@if (isset($row->value))
@if ($row->multiple)
<div class="row">
@foreach ($row->value as $img)
<div class="col-6">
{!! html()->img(asset('storage/app/' . $img))->class('img-responsive img-thumbnail mb-2')->alt('') !!}
</div>
@endforeach
</div>
@else
@if (is_array($row->value))
<div class="row">
@foreach ($row->value as $img)
<div class="col-6">
{!! html()->img(asset('storage/app/' . $img))->class('img-responsive img-thumbnail mb-2')->alt('') !!}
</div>
@endforeach
</div>
@else
<div class="row">
<div class="col-6">
{!! html()->img(asset('storage/app/' . $row->value))->class('img-responsive img-thumbnail mb-2')->alt('Image description here')->attribute('data-custom', 'value')!!}
</div>
</div>
@endif
@endif
@endif
</td>
</tr>
@elseif($row->type == 'header')
<tr>
<td colspan="2">
<{{ $row->subtype }}>{{ $row->label }}</{{ $row->subtype }}>
</td>
</tr>
@elseif($row->type == 'paragraph')
<tr>
<td colspan="2">
<{{ $row->subtype }}>{{ $row->label }}</{{ $row->subtype }}>
</td>
</tr>
@elseif($row->type == 'radio-group')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>
@foreach ($row->values as $key => $options)
@if (isset($options->selected))
{{ $options->label }}
@endif
@endforeach
</td>
</tr>
@elseif($row->type == 'select')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>
@foreach ($row->values as $options)
@if (isset($options->selected))
{{ $options->label }}
@endif
@endforeach
</td>
</tr>
@elseif($row->type == 'autocomplete')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>
@foreach ($row->values as $options)
@if (isset($options->selected))
{{ $options->label }}
@endif
@endforeach
</td>
</tr>
@elseif($row->type == 'text')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>{{ isset($row->value) ? $row->value : '' }}</td>
</tr>
@elseif($row->type == 'date')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>{{ isset($row->value) ? date('d-m-Y', strtotime($row->value)) : '' }}</td>
</tr>
@elseif($row->type == 'textarea')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>{{ isset($row->value) ? $row->value : '' }}</td>
</tr>
@elseif($row->type == 'starRating')
<tr>
@php
$attr = ['class' => 'form-control'];
if ($row->required) {
$attr['required'] = 'required';
}
$value = isset($row->value) ? $row->value : 0;
$no_of_star = isset($row->number_of_star) ? $row->number_of_star : 5;
@endphp
<td> {!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>
<div id="{{ $row->name }}" class="starRating"
data-value="{{ $value }}" data-no_of_star="{{ $no_of_star }}">
</div>
@for ($x = 0; $x < $no_of_star; $x++)
@if (floor($value) - $x >= 1)
{!! html()->img(asset('assets/images/star.png'))->class('email_star')->attribute('width', '25px') !!}
@elseif($value - $x > 0)
{!! html()->img(asset('assets/images/half-star.png'))->class('email_star')->attribute('width', '25px') !!}
@else
{!! html()->img(asset('assets/images/black-star.png'))->class('email_star')->attribute('width', '25px') !!}
@endif
@endfor
{!! html()->hidden($row->name, $value) !!}
</td>
</tr>
@elseif($row->type == 'color')
<tr>
<td> {{ $row->label }}</td>
<td>
<div style="background-color: {{ $row->value }};"></div>
</td>
</tr>
@elseif($row->type == 'SignaturePad')
@if (property_exists($row, 'value'))
<tr>
<td>{{ $row->label }}</td>
<td>
<div><img src="{{ Storage::url($row->value) }}" width="55px">
</div>
</td>
</tr>
@endif
@elseif($row->type == 'location')
<tr>
<td>{!! html()->label($row->label, $row->name) !!}
@if ($row->required)
*
@endif
</td>
<td>
{{ $row->value }}
</td>
</tr>
@endif
@endforeach
@endforeach
</table>
</div>
</div>
</div>
</div>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
