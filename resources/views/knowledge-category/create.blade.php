{!! html()->form('POST', route('knowledge-category.store'))->attribute('data-validate')->open() !!}
@include('knowledge-category.form')
{!! html()->form()->close() !!} 
