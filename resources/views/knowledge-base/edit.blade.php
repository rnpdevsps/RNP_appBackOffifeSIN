{!! html()->modelForm($knowledgeBase, 'PUT', route('knowledges.update', $knowledgeBase->id))->attribute('data-validate')->open() !!}
@include('knowledge-base.form')
{!! html()->closeModelForm() !!}

