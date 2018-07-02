@extends('layouts.app') <!-- tutaj można zrobic rozwijana liste-->
@section('content')
<div class="container">
	<h2 class="text-center">Kategorie</h2>
	@include('common.errors')
	<div class="row">
		<div class="col-sm-offset-1 col-sm-5">
			<h3>Przychody</h3>
			@include("categories/categories", ['type'=>'revenue','categories'=>$revenues])
		</div>
		<div class="col-sm-5">
			<h3>Wydatki</h3>
			@include("categories/categories", ['type'=>'expense', 'categories'=>$expenses])
		</div>
	</div>
	@include('common.back_to')
</div>
@endsection
