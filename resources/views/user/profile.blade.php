@extends('layouts.app')

@section('content')
<div class="container">
	<h2 id="budget_title" class="text-center">Mój profil</h2>
	<img src="http://www.gravatar.com/avatar/<?php echo md5($user->email) ?>" class="center-block img-thumbnail" style="margin:20px auto" height="80px" width="80px"/>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-10">
			<div id="user_error"></div>
			<table class="table table-hover">
				<tr>
					<td><strong>Imię</strong></td><td class="edit_user" data-name="name" data-value="{{$user->name}}">{{$user->name}}</td>
				</tr>
				<tr>
					<td><strong>E-Mail</strong></td><td class="edit_user" data-name="email" data-value="{{$user->email}}">{{$user->email}}</td>
				</tr>
				<tr>
					<td><strong>Data rejestracji</strong></td><td>{{$user->created_at->format('d F Y')}}</td>
				</tr>
				<tr>
					<td><strong>Wysyłanie miesięcznych raportów</strong></td>
					<td>
						<form action="/profile/update">
							<label>
								<input type="checkbox" id="set_month_report" value="yes"
								@if ($user->month_report)
									checked
								@endif
								> Tak
							</label>
						</form>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-1 col-sm-10">
			<a id="back_to" href="{{route('dashboard')}}"><< Wróć do Panelu</a>
		</div>
	</div>
</div>
@endsection