{!! html()->form('POST', route('knowledges.store'))
->attribute('data-validate')
->open() !!}
@include('knowledge-base.form')
{!! html()->form()->close() !!}


