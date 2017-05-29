@extends('layouts.app')

@section('content')
<div class="container">
	<div class="col-sm-offset-2 col-sm-8">
		<h2 class="text-center">Notatki</h2>
		<!-- Display Validation Errors -->
		@include('common.errors')

		<!-- New Task Form -->
		<form action="{{ url('task') }}" method="POST" class="form-horizontal">
			{{ csrf_field() }}

			<!-- Task Name -->
			<div class="form-group">
				<div class="col-sm-10">
					<input type="text" name="name" id="task-name" class="form-control" placeholder="Add new plan" value="{{ old('task') }}">
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-default">
						<i class="fa fa-btn fa-plus"></i>Dodaj
					</button>
				</div>
			</div>
		</form>

		<!-- Current Tasks -->
		@if (count($tasks) > 0)
		<table class="table table-hover task-table">
			<thead>
				<th >Notatki</th>
				<th>&nbsp;</th>
			</thead>
			<tbody>
				@foreach ($tasks as $task)
				<tr>
					<td class="table-text">
					<div>
						{{ $task->name }}
					</div></td>

					<!-- Task Delete Button -->
					<td class="text-right">
					<form action="{{url('task/' . $task->id)}}" method="POST">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}

						<button type="submit" id="delete-task-{{ $task->id }}" class="btn btn-danger">
							<i class="fa fa-btn fa-trash"></i>Usuń
						</button>
					</form></td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>

	<div class="row">
		<div class="col-sm-offset-1 col-sm-10">
			<a id="back_to" href="{{route('user')}}"><< Wróć do profilu</a>
		</div>
	</div>

</div>
@endsection
