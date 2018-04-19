@extends('layouts.app')

@section('content')
<div class="container">
	<h2 id="budget_title" class="text-center">Budżet dla <span data-id="{{$budget->id}}" data-date="{{$date}}" data-datetext="{{$budget->date}}" class="edit_budget">{{$budget->date}}</span></h2>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-5">
			<h3>Przychody</h3>
			@include('budgets/cashflows', ['type'=>'revenue' ,'cashflows'=>$revenues, 'categories'=>$revenues_categories, 'errors' => $errors->revenues_errors])
		</div>
		<div class="col-sm-5">
			<h3>Wydatki</h3>
			@include('budgets/cashflows', ['type'=>'expense' , 'cashflows'=>$expenses, 'categories'=>$expenses_categories, 'errors' => $errors->expenses_errors])
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-10">
			<h3>Podsumowanie</h3>
			<p id="revenues_summary">
				Przychody: <span class="summary_number">{{$revenues_sum}}</span>
			</p>
			<p id="expenses_summary">
				Wydatki: <span class="summary_number">{{$expenses_sum}}</span>
			</p>
			<p id="balance">
				Saldo: <span class="summary_number">{{$balance}}</span>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-10">
			<a id="back_to" href="{{route('user')}}"><< Wróć do profilu</a>
		</div>
	</div>
</div>
@endsection