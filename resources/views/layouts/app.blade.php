<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>homeBUDGET</title>

		<!-- Fonts -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

		<!-- Styles -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<link href="{{ elixir('css/app.css') }}" rel="stylesheet">

		<style>
			body {
				font-family: 'Lato';
			}
			.fa-btn {
				margin-right: 6px;
			}
		</style>
	</head>
	<body id="app-layout">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">

					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<!-- Branding Image -->
					<a class="navbar-brand" href="{{ url('/') }}"> homeBUDGET </a>
				</div>

				<div class="collapse navbar-collapse" id="app-navbar-collapse">
					<!-- Left Side Of Navbar -->
					<ul class="nav navbar-nav"></ul>

					<!-- Right Side Of Navbar -->
					<ul class="nav navbar-nav navbar-right">
						<!-- Authentication Links -->
						@if (Auth::guest())
						<li>
							<a href="{{ url('/login') }}">Logowanie</a>
						</li>
						<li>
							<a href="{{ url('/register') }}">Rejestracja</a>
						</li>
						@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> {{ Auth::user()->name }} <span class="caret"></span> </a>

							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="{{ url('/user') }}"><i class="fa fa-btn fa-money"></i>Budżet</a>
								</li>
								<li>
									<a href="{{ url('/categories') }}"><i class="fa fa-btn fa-tasks"></i>Kategorie</a>
								</li>
								<li>
									<a href="{{ url('/tasks') }}"><i class="fa fa-btn fa-newspaper-o"></i>Notatki</a>
								</li>
								<li role="separator" class="divider"></li>
								<li>
									<a href="{{ url('/about') }}"><i class="fa fa-btn fa-book"></i>O aplikacji</a>
								</li>
								<li>
									<a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Wyloguj</a>
								</li>
							</ul>
						</li>
						@endif
					</ul>
				</div>
			</div>
		</nav>

		@yield('content')

		<!-- JavaScripts -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		{{-- <script src="{{ elixir('js/app.js') }}"></script>
		--}}
		<script>
			$.ajaxSetup({
				headers : {
					'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
				}
			});

			$(document).on('submit', '#send_report', function(e) {
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'POST',
					success : function(json) {
						$('#report_modal .status').text('Raport został wysłany. Sprawdź pocztę.');
						$('#report_modal').modal('show');
					},
					error : function(json) {
						$('#report_modal .status').text('Niestety wysyłanie raportu nie powiodło się. Spróbuj jeszcze raz.');
						$('#report_modal').modal('show');
					}
				});

			});

			$(document).on('submit', '.delete_form', function(e) {

				id = $(this).attr('data-id');
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'DELETE',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						$('#delete_form_' + id).remove();
					},
				});

			});

			$(document).on('click', '.edit_budget', function(e) {
				id = $(this).attr('data-id');
				date = $(this).attr('data-date');
				datetext = $(this).attr('data-datetext');
				$(this).replaceWith('<form action="/budget/update/' + id + '" class="edit_budget_form form-inline" data-id=' + id + ' data-date=' + date + ' data-datetext="' + datetext + '">' + '<input type="month" name="date" value=' + date + ' id="budget-date" class="form-control">' + '<button type="submit" class="btn btn-default"><i class="fa fa-check"></i></button></form>');
			});

			$(document).on('submit', '.edit_budget_form', function(e) {

				id = $(this).attr('data-id');
				date = $(this).attr('data-date');
				datetext = $(this).attr('data-datetext');
				old = $(this);
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'POST',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						old.replaceWith('<span data-id="' + id + '" data-date="' + json.date + '" data-datetext="' + json.datetext + '" class="edit_budget">' + json.datetext + '</span>');
						$('#budget_error').hide();
					},
					error : function(json) {
						if (json.status == 500)
							$('#budget_title').after('<div id="budget_error" class="alert alert-danger">The date has already been taken.</div>');
					}
				});

			});

			function cashflows_summary(revenues, expenses) {
				$('#revenues_summary').html("Przychody: <span class='summary_number'>" + revenues + "</span>");
				$('#expenses_summary').html("Wydatki: <span class='summary_number'>" + expenses + "</span>");
				$('#balance').html("Saldo: <span class='summary_number'>" + Math.round((revenues - expenses) * 100) * 0.01 + "</span>");

			}


			$(document).on('submit', '.add_cashflow_form', function(e) {
				type = $(this).attr('data-type');
				id = $(this).attr('id');
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'POST',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						delete_form = '<form id="delete_cashflow_' + json.cashflow.id + '" class="delete_cashflow" data-id="' + json.cashflow.id + '" data-type="' + type + '" action="/cashflow/' + json.cashflow.id + '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' + '<input type="hidden" name="budget_id" value="' + json.cashflow.budget_id + '"/>' + '<button type="submit" id="delete-cashflow-' + json.cashflow.id + '" class="delete_cashflow btn btn-xs btn-danger"><i class="fa fa-times"></i></button>' + '</form>';
						$('#' + type + '_table tbody').append('<tr id="cashflow_' + json.cashflow.id + '" data-id="' + json.cashflow.id + '" data-type="' + type + '" data-category="' + json.category_id + '" data-name="' + json.cashflow.name + '" data-amount="' + json.cashflow.amount + '">' + '<td class="edit_cashflow" data-id="' + json.cashflow.id + '">' + json.cashflow.name + '</td><td class="edit_cashflow" data-id="' + json.cashflow.id + '">' + json.cashflow.category_id + '</td><td class="edit_cashflow" data-id="' + json.cashflow.id + '">' + json.cashflow.amount + '</td><td>' + delete_form + '</td></tr>');
						$('#' + type + '_errors').hide();
						$('#' + id + ' .cashflow_name').val('');
						$('#' + id + ' .cashflow_amount').val('');
						$('#' + id + ' .cashflow_category').prop('selectedIndex', 0);
						cashflows_summary(json.revenues_summary, json.expenses_summary);
					},
					error : function(json) {
						errors = '';
						for (data in json.responseJSON) {
							errors += '<li>' + json.responseJSON[data] + '</li>';
						}
						$('#' + type + '_errors').show().html('<div class="alert alert-danger"><ul class="list-unstyled">' + errors + '</ul></div>');
					}
				});

			});

			$(document).on('submit', '.delete_cashflow', function(e) {

				id = $(this).attr('data-id');
				type = $(this).attr('data-type');
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'DELETE',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						$('#cashflow_' + id).remove();
						cashflows_summary(json.revenues_summary, json.expenses_summary);
					},
					error : function(json) {
						alert("Element nie może zostać usunięty w tym momencie.");
					}
				});

			});

			$(document).on('submit', '.add_category_form', function(e) {
				type = $(this).attr('data-type');
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'POST',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						delete_form = '<form id="delete_category_' + json.id + '" class="delete_category" data-id="' + json.id + '" data-type="' + type + '" data-name="' + json.name + '" action="/category/' + json.id + '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' + '<button type="submit" id="delete-category-' + json.id + '" class="delete_category btn btn-xs btn-danger"><i class="fa fa-times"></i></button>' + '</form>';
						$('#' + type + '_table tbody').append('<tr id="category_' + json.id + '" data-id=' + json.id + ' data-type=' + type + ' data-default=' + json.default + ' data-name=' + json.name + '>' + '<td class="edit_category" data-id="' + json.id + '">' + json.name + '</td>' + '<td class="edit_category" data-id="' + json.id + '">' + json.default + '</td>' + '<td>' + delete_form + '</td></tr>');
						$('#' + type + '_errors').hide();
						$('#add_' + type + '_form .category_name').val('');
						$('#add_' + type + '_form .category_default').val('');
					},
					error : function(json) {
						errors = '';
						for (data in json.responseJSON) {
							errors += '<li>' + json.responseJSON[data] + '</li>';
						}
						$('#' + type + '_errors').show().html('<div class="alert alert-danger"><ul class="list-unstyled">' + errors + '</ul></div>');
					}
				});

			});

			$(document).on('submit', '.delete_category', function(e) {

				id = $(this).attr('data-id');
				type = $(this).attr('data-type');
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'DELETE',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						$('#category_' + id).remove();
					},
					error : function(json) {
						alert("Kategoria nie może zostać usunięta w tym momencie. Prawdopodobnie masz przypisane elementy do tej kategorii w budżecie.");
					}
				});

			});

			$(document).on('click', '.edit_category', function(e) {
				id = $(this).attr('data-id');
				tr = $('tr#category_' + id);
				name = tr.attr('data-name');
				amount = tr.attr('data-default');
				type = tr.attr('data-type');
				tr.html('<td colspan="3"><div id="category_' + id + '_error"></div><form action="/category/update/' + id + '" class="edit_category_form form-horizontal" data-id=' + id + ' data-name=' + name + ' data-default=' + amount + ' data-type=' + type + '>' + '<input type="hidden" name="category_id" value=' + id + '>' + '<div class="col-sm-5"><input type="text" name="name" value=' + name + ' class="edit_category_name form-control"></div>' + '<div class="col-sm-5"><input type="text" name="default" value=' + amount + ' class="edit_category_amount form-control"></div>' + '<div class="col-sm-2"><button type="submit" class="edit_category_form btn btn-default"><i class="fa fa-check"></i></button></div></form></td>');
			});

			$(document).on('submit', '.edit_category_form', function(e) {

				id = $(this).attr('data-id');
				name = $(this).attr('data-name');
				amount = $(this).attr('data-default');
				type = $(this).attr('data-type');
				old = $(this);
				tr = $('tr#category_' + id);
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'POST',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						delete_form = '<form id="delete_category_' + id + '" class="delete_category" data-id="' + id + '" data-type="' + type + '" action="/category/' + id + '" method="POST">' + '<input type="hidden" />' + '<button type="submit" id="delete-category-' + id + '" class="delete_category btn btn-xs btn-danger"><i class="fa fa-times"></i></button></form>';
						tr.attr('id', 'category_'.id);
						tr.attr('data-name', json.name);
						tr.attr('data-default', json.amount);
						tr.attr('data-type', type);
						tr.html('<td class="edit_category" data-id="' + id + '">' + json.name + '</td>' + '<td class="edit_category" data-id="' + id + '">' + json.amount + '</td>' + '<td>' + delete_form + '</td>');
					},
					error : function(json) {
						if (json.status == 500)
							$('#category_' + id + '_error').html('<div class="alert alert-danger"></div>');
					}
				});

			});

			$('select.category_select').on('change', function(e) {
				var optionSelected = $("option:selected", this);
				var type = $(this).attr('data-type');
				var amount = optionSelected.attr('data-default');
				$('#' + type + '_amount').val(amount);
			});

			$(document).on('click', '.edit_cashflow', function(e) {
				id = $(this).attr('data-id');
				tr = $('tr#cashflow_' + id);
				name = tr.attr('data-name');
				amount = tr.attr('data-amount');
				category = tr.attr('data-category');
				type = tr.attr('data-type');
				select = $('#add_' + type + '_form select').clone();
				tr.html('<td colspan="4"><div id="cashflow_' + id + '_error"></div><form action="/cashflow/update/' + id + '" class="edit_cashflow_form form-horizontal" data-id=' + id + ' data-name=' + name + ' data-amount=' + amount + ' data-type=' + type + ' data-category="' + category + '">' + '<div class="col-sm-3"><input type="text" name="name" value=' + name + ' class="edit_cashflow_name form-control"></div>' + '<div id="select_' + id + '" class="col-sm-3"></div>' + '<div class="col-sm-3"><input type="text" name="amount" value=' + amount + ' class="form-control edit_cashflow_amount"></div>' + '<div class="col-sm-3"><button type="submit" class="edit_category_form btn btn-default"><i class="fa fa-check"></i></button></div></form></td>');
				select.attr('id', 'select_' + id + '_edit').appendTo('#select_' + id);
				$('#select_' + id + '_edit').val(category);
			});

			$(document).on('submit', '.edit_cashflow_form', function(e) {

				id = $(this).attr('data-id');
				name = $(this).attr('data-name');
				amount = $(this).attr('data-default');
				category = $(this).attr('data-category');
				type = $(this).attr('data-type');
				old = $(this);
				tr = $('tr#cashflow_' + id);
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action'),
					type : 'POST',
					data : $(this).serialize(),
					dataType : 'json',
					success : function(json) {
						delete_form = '<form id="delete_cashflow_' + json.cashflow.id + '" class="delete_cashflow" data-id="' + json.cashflow.id + '" data-type="' + type + '" action="/cashflow/' + json.cashflow.id + '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' + '<input type="hidden" name="budget_id" value="' + json.cashflow.budget_id + '"/>' + '<button type="submit" id="delete_cashflow_' + json.cashflow.id + '" class="delete_cashflow btn btn-xs btn-danger"><i class="fa fa-times"></i></button>' + '</form>';
						tr.attr('data-category', json.category_id);
						tr.attr('data-name', json.cashflow.name);
						tr.attr('data-amount', json.cashflow.amount);
						tr.html('<td class="edit_cashflow" data-id="' + json.cashflow.id + '">' + json.cashflow.name + '</td><td class="edit_cashflow" data-id="' + json.cashflow.id + '">' + json.cashflow.category_id + '</td><td class="edit_cashflow" data-id="' + json.cashflow.id + '">' + json.cashflow.amount + '</td><td>' + delete_form + '</td></tr>');
						cashflows_summary(json.revenues_summary, json.expenses_summary);
					},
					error : function(json) {
						errors = '';
						for (data in json.responseJSON) {
							errors += '<li>' + json.responseJSON[data] + '</li>';
						}
						$('#cashflow_' + id + '_error').html('<div class="alert alert-danger"><ul class="list-unstyled">' + errors + '</ul></div>');
						if (json.status == 500)
							$('#cashflow_' + id + '_error').html('<div class="alert alert-danger">Element nie może zostać zaktualizowany.</div>');
					}
				});

			});

		</script>
	</body>
</html>
