@php
    $currantColumn = [];
@endphp
<table>
    <tbody>
        @foreach ($formValues as $key => $formValue)
            @php
                $column = $formValue->columns();
            @endphp
            @if ($currantColumn != $column)
                @php
                    $currantColumn = $column;
                @endphp
                @if ($key != 0)
                    <tr></tr>
                @endif
                <tr>
                    @foreach ($currantColumn as $value)
                        <th>{{ $value }}</th>
                    @endforeach
                </tr>
            @endif
            <tr>
                @foreach (json_decode($formValue->json) as $jsons)
                    @foreach ($jsons as $json)
                        @if (isset($json->value) || isset($json->values))
                            @if (isset($json->value))
                                @if ($json->type == 'starRating')
                                    <td>{{ isset($json->value) ? $json->value : '' }}</td>
                                @elseif ($json->type == 'button')
                                    <td> </td>
                                @elseif ($json->type == 'date')
                                    <td>{{ isset($json->value) ? $json->value : '' }}</td>
                                @elseif ($json->type == 'number')
                                    <td>{{ isset($json->value) ? $json->value : '' }}</td>
                                @elseif ($json->type == 'text')
                                    <td>{{ isset($json->value) ? $json->value : '' }}</td>
                                @elseif ($json->type == 'textarea')
                                    <td>{{ isset($json->value) ? $json->value : '' }}</td>
                                @elseif ($json->type == 'video')
                                    @if ($json->value)
                                        <td>
                                            {!! html()->a(Storage::path($json->value))->text(__('video')) !!}
                                        </td>
                                    @else
                                        <td>{{ __('null') }}</td>
                                    @endif
                                @elseif ($json->type == 'selfie')
                                    @if ($json->value)
                                        <td>
                                            {!! html()->a(Storage::path($json->value))->text(__('photo')) !!}
                                        </td>
                                    @else
                                        <td>{{ __('null') }}</td>
                                    @endif
                                @elseif ($json->type == 'SignaturePad')
                                    @if ($json->value)
                                        <td>
                                            {!! html()->a(Storage::path($json->value))->text(__('image')) !!}
                                        </td>
                                    @else
                                        <td>{{ __('null') }}</td>
                                    @endif
                                @elseif ($json->type == 'autocomplete')
                                    <td>{{ isset($json->value) ? $json->value : null }}</td>
                                @elseif ($json->type == 'location')
                                    @if ($json->value != '')
                                        <td>{{ isset($json->value) }}</td>
                                    @else
                                        <td>{{ __('null') }}</td>
                                    @endif
                                @endif
                            @elseif (isset($json->values))
                                @php
                                    $value = '';
                                @endphp
                                @foreach ($json->values as $subData)
                                    @if ($json->type == 'checkbox-group')
                                        @if (isset($subData->selected))
                                            @php  $value .= $subData->label . ',' @endphp
                                        @endif
                                    @elseif ($json->type == 'radio-group')
                                        @if (isset($subData->selected))
                                            @php  $value .= $subData->label . ',' @endphp
                                        @endif
                                    @elseif($json->type == 'select')
                                        @if (isset($subData->selected))
                                            @php  $value .= $subData->label . ',' @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @php  $value = rtrim($value, ',') @endphp
                                <td>{{ $value ? $value : '' }}</td>
                            @endif
                            @if ($json->type == 'file')
                                @if (isset($json->value))
                                    @php
                                        $fileData = $json->value;
                                    @endphp
                                    @if (is_array($fileData))
                                        <td>
                                            @foreach ($fileData as $key => $subData)
                                                {!! html()->a(Storage::path($subData))->text(__('image')) !!}
                                            @endforeach
                                        </td>
                                    @else
                                        <td>
                                            {!! html()->a(Storage::path($json->value))->text(__('image')) !!}
                                        </td>
                                    @endif
                                @endif
                            @endif
                        @elseif ($json->type == 'header')
                            @if (isset($json->selected) && $json->selected)
                                {{ intval($json->number_of_control) }}
                                <td> {{ __('N/A') }}</td>
                            @else
                                <td>{{ isset($json->value) ? $json->value : '' }}</td>
                            @endif
                        @else
                            @if ($json->type == 'paragraph')
                                <td>{{ isset($json->label) ? $json->label : '' }}</td>
                            @endif
                            @if ($json->type == 'break')
                                <td>{{ isset($json->label) ? $json->label : '' }}</td>
                            @endif
                            @if ($json->type == 'button')
                                <td>{{ isset($json->label) ? $json->label : '' }}</td>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
