@extends('layouts.app')

@section('content')
<div class="container">
	<div class="col-sm-offset-2 col-sm-3">
		<h2>Mój profil</h2>
		<img src="http://www.gravatar.com/avatar/<?php echo md5($user->email) ?>" class="img-circle" style="display:block;margin-bottom:20px;" height="80px" width="80px"/>
		<p>
			<a href="mailto:{{$user->email}}">{{$user->name}}</a>
		</p>
		<p class="text-muted">
			(od {{$user->created_at->format('d F Y')}})
		</p>
		<form id="send_report" action="{{url('report')}}" method="POST">
			{{ csrf_field() }}
			<button class="btn btn-default" type="submit" data-toggle="tooltip" data-placement="bottom" title="Raport obejmuje wszystkie wprowadzone dane.">Wyślij raport</button>
		</form>
		<h2>Kategorie</h2>
		<a href="{{route('categories')}}">Zarządzaj kategoriami</a>
		<h2>Notatki</h2>
		<a href="{{route('tasks')}}">Zarządzaj notatkami</a>
	</div>
	<div class="col-sm-5">
		<h2>Budżety</h2>
		<!-- Display Validation Errors -->
		@include('common.errors')
		<table id="budgets_table" class="table table-hover">
			<thead>
				<th>Miesiąc</th><th></th>
			</thead>
			<tbody>
				@foreach ($budgets as $budget)
				<tr id="delete_form_{{$budget->id}}">
					<td><a href="{{route('budget',['id'=>$budget->id])}}">{{$budget->date}}</a></td>
					<td>
					<form class="delete_form" data-id="{{ $budget->id }}" action="{{url('budget/' . $budget->id)}}" method="POST">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}
						<button type="submit"  class="btn btn-xs btn-danger">
							<i class="fa fa-times"></i>
						</button>
					</form></td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div class="text-center">
			{{$budgets->links()}}
		</div>
		<form id="add_form" action="{{ url('budget') }}" method="POST" class="form-horizontal">
			{{ csrf_field() }}
			<div class="col-sm-6">
				<input type="month" name="date" value="{{$now}}" placeholder="Add new monthly budget" id="budget-date" class="form-control" value="{{ old('budget') }}">
			</div>
			<div class="col-sm-6">
				<button type="submit" class="btn btn-default">
					<i class="fa fa-btn fa-plus"></i>Dodaj budżet
				</button>
			</div>
		</form>
	</div>
</div>
<div id="report_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Wysyłanie raportu</h4>
      </div>
      <div class="modal-body">
        <p class="status"></p>
      </div>
    </div>
  </div>
</div>
@endsection
