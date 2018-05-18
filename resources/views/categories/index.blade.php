@extends('layouts.app') <!-- tutaj można zrobic rozwijana liste-->
@section('content')
<div class="container">
	<h2 class="text-center">Kategorie</h2>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-5">
			<h3>Przychody</h3>
		</div>
		<div class="col-sm-5">
			<h3>Wydatki</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-10">
			<div class="row">
				<div class="col-sm-6">
					@include("categories/categories", ['type'=>'revenue','categories'=>$revenues, 'errors'=>$errors->revenues_errors])
				</div>
				<div class="col-sm-6">
					@include("categories/categories", ['type'=>'expense', 'categories'=>$expenses, 'errors'=>$errors->expenses_errors])
				</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10">
				<a id="back_to" href="{{route('dashboard')}}"><< Wróć do Panelu</a>
			</div>
		</div>
	</div>
	@endsection
