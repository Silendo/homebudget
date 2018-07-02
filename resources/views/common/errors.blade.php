<!-- resources/views/common/errors.blade.php -->
<div id="alerts">
	@if (count($errors) > 0)
	<!-- Form Error List -->
	<div class="alert alert-danger">
		<strong>Whoops! Coś poszło źle!</strong>
		<br>
		<br>
		<ul class="list-unstyled">
			@foreach ($errors->all() as $error)
			<li>
				{{ $error }}
			</li>
			@endforeach
		</ul>
	</div>
	@endif
</div>
