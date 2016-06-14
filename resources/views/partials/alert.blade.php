@if(session('message'))
	<div class="alert alert-{{ session('message.type') }}">
		{{ session('message.text') }}
	</div>
@endif