$.ajaxSetup({
	headers : {
		'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
	}
});

function prepareAlert(xhr, message, errorId = '', alertType = "danger") {
	alertMessage = '<div id="' + errorId + '" class="alert alert-' + alertType + '">';
	if (xhr.status == 422) {
		errors = xhr.responseJSON;
		alertMessage += '<ul>';
		$.each(errors, function(k, v){
			if( k == 'message')
				alertMessage += '<li>' + v + '</li>';
		});
		alertMessage += '</ul></div>';
	} else {
		if(alertType == 'danger')
			alertMessage += '[' + xhr.status + ' ' + xhr.statusText + '] ';
		alertMessage += message + '</div>'
	}
	return alertMessage;
}

/* User */
$(document).on('click', '.edit_user', function(e){
	name = $(this).data('name');
	value = $(this).data('value');
	$(this).removeClass('edit_user');
	$(this).html('<form action="/profile/update" class="edit_user_form form-inline">' 
		+ '<input type="text" name="' + name + '" value="' + value + '" class="form-control">' 
		+ '<button type="submit" class="btn btn-default"><i class="fa fa-check"></i></button></form>');
});

$(document).on('submit', '.edit_user_form', function(e){
	e.preventDefault();
	oldData = $(this);
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data, status, xhr) {
			oldData.closest('td').data('value', data.value);
			oldData.closest('td').addClass('edit_user');
			oldData.replaceWith(data.value);
			infoMessage = prepareAlert(xhr, 'Zmiany zostały zapisane.', '', 'success');
			$('#user_error').html(infoMessage);
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Niestety aktualizacja nie powiodła się.', 'budget_error');
			$('#user_error').html(errorMessage);
		}
	});
});

$(document).on('change', '#set_month_report', function(e){
	form = $(this).closest('form');
	checked = $(this).is(':checked')? 1 : 0;
	$.ajax({
		url : form.attr('action'),
		type : 'POST',
		data : {'month_report': checked},
		dataType : 'json',
		success: function(data, status, xhr){
			infoMessage = prepareAlert(xhr, 'Zmiany zostały zapisane.', '', 'success');
			$('#user_error').html(infoMessage);
		},
		error: function(xhr){
			errorMessage = prepareAlert(xhr, 'Niestety aktualizacja nie powiodła się.');
			$('#user_error').html(errorMessage);
		}
	});
});

/* Budget report */
$(document).on('submit', '#send_report', function(e) {
	e.preventDefault();
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		success : function(data) {
			$('#report_modal .status').text('Raport został wysłany. Sprawdź pocztę.');
			$('#report_modal').modal('show');
		},
		error : function(xhr) {
			$('#report_modal .status').text('Niestety wysyłanie raportu nie powiodło się. Spróbuj jeszcze raz.');
			$('#report_modal').modal('show');
		}
	});
});

/* Budget */
$(document).on('submit', '.delete_budget_form', function(e) {
	e.preventDefault();
	budgetRow = $(this).closest('tr');
	$.ajax({
		url : $(this).attr('action'),
		type : 'DELETE',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			budgetRow.remove();
		},
	});
});

$(document).on('click', '.edit_budget', function(e) {
	id = $(this).data('id');
	date = $(this).data('date');
	$(this).replaceWith('<form action="/budget/update/' + id 
		+ '" class="edit_budget_form form-inline" data-id=' + id 
		+ ' data-date='	+ date + '">' 
		+ '<input type="month" name="date" value=' + date 
		+ ' id="budget_date" class="form-control">' 
		+ '<button type="submit" class="btn btn-default"><i class="fa fa-check"></i></button></form>');
});

$(document).on('submit', '.edit_budget_form', function(e) {
	e.preventDefault();
	id = $(this).data('id');
	date = $(this).data('date');
	oldBudget = $(this);
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			oldBudget.replaceWith('<span data-id="' + id + '" data-date="' 
				+ data.date + '" class="edit_budget">' + data.datetext + '</span>');
			$('#budget_error').remove();
		},
		error : function(xhr) {
			$('#budget_error').remove();
			errorMessage = prepareAlert(xhr, 'Niestety zmiana nie została zapisana. Spróbuj później.', 'budget_error');
			$('#budget_title').after(errorMessage);
		}
	});
});

/* Cashflow */
function cashflows_summary(revenues, expenses) {
	$('#revenues_summary').html("Przychody: <span class='summary_number'>" + revenues + "</span>");
	$('#expenses_summary').html("Wydatki: <span class='summary_number'>" + expenses + "</span>");
	$('#balance').html("Saldo: <span class='summary_number'>" + Math.round((revenues - expenses) * 100) * 0.01 + "</span>");
}

$(document).on('submit', '.add_cashflow_form', function(e) {
	e.preventDefault();
	type = $(this).data('type');
	formId = $(this).attr('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			delete_form = '<form id="delete_cashflow_' + data.cashflow.id 
			+ '" class="delete_cashflow" action="/cashflow/' + data.cashflow.id 
			+ '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' 
			+ '<input type="hidden" name="budget_id" value="' + data.cashflow.budget_id 
			+ '"/>' + '<button type="submit" id="delete-cashflow-' + data.cashflow.id 
			+ '" class="delete_cashflow btn btn-xs btn-danger"><i class="fa fa-times"></i></button>'
			+ '</form>';
			$('#' + type + '_table tbody').append('<tr id="cashflow_' + data.cashflow.id 
				+ '" data-id="' + data.cashflow.id + '" data-type="' + type 
				+ '" data-category="' + data.category_id + '" data-name="' 
				+ data.cashflow.name + '" data-amount="' + data.cashflow.amount 
				+ '">' + '<td class="edit_cashflow">' + data.cashflow.name 
				+ '</td><td class="edit_cashflow">' + data.cashflow.category_id 
				+ '</td><td class="edit_cashflow">' + data.cashflow.amount 
				+ '</td><td>' + delete_form + '</td></tr>');
			$('#' + type + '_errors').empty();
			$('#' + formId + ' .cashflow_name').val('');
			$('#' + formId + ' .cashflow_amount').val('');
			$('#' + formId + ' .cashflow_category').prop('selectedIndex', 0);
			cashflows_summary(data.revenues_summary, data.expenses_summary);
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Niestety zmiana nie została zapisana. Spróbuj później.');
			$('#' + type + '_errors').html(errorMessage);
		}
	});
});

$('.add_cashflow_form select.category_select').on('change', function(e) {
	var optionSelected = $("option:selected", this);
	var type = $(this).closest('form').data('type');
	var amount = optionSelected.data('default');
	$('#' + type + '_amount').val(amount);
});

$(document).on('submit', '.delete_cashflow', function(e) {
	e.preventDefault();
	id = $(this).closest('tr').data('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'DELETE',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			$('#cashflow_' + id).remove();
			cashflows_summary(data.revenues_summary, data.expenses_summary);
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Element nie może zostać usunięty w tym momencie.');
			$('#cashflow_' + id + '_error').html(errorMessage);
		}
	});
});

$(document).on('click', '.edit_cashflow', function(e) {
	cashflowRow = $(this).closest('tr');
	id = cashflowRow.data('id');
	name = cashflowRow.data('name');
	amount = cashflowRow.data('amount');
	category = cashflowRow.data('category');
	type = cashflowRow.data('type');
	select = $('#add_' + type + '_form select').clone();
	cashflowRow.html('<td colspan="4"><div id="cashflow_' + id + '_error"></div>' 
		+ '<form action="/cashflow/update/' + id + '" class="edit_cashflow_form form-horizontal">' 
		+ '<div class="col-sm-3"><input type="text" name="name" value=' + name 
		+ ' class="edit_cashflow_name form-control"></div>' + '<div id="select_' 
		+ id + '" class="col-sm-3"></div>' + '<div class="col-sm-3">'
		+ '<input type="text" name="amount" value=' + amount 
		+ ' class="form-control edit_cashflow_amount"></div>' 
		+ '<div class="col-sm-3"><button type="submit" class="edit_category_form '
		+ 'btn btn-default"><i class="fa fa-check"></i></button></div></form></td>');
	select.attr('id', 'select_' + id + '_edit').appendTo('#select_' + id);
	$('#select_' + id + '_edit').val(category);
});

$(document).on('submit', '.edit_cashflow_form', function(e) {
	e.preventDefault();
	cashflowRow = $(this).closest('tr');
	id = $(this).closest('tr').data('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			delete_form = '<form id="delete_cashflow_' + data.cashflow.id 
			+ '" class="delete_cashflow" action="/cashflow/' + data.cashflow.id 
			+ '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' 
			+ '<input type="hidden" name="budget_id" value="' + data.cashflow.budget_id 
			+ '"/>' + '<button type="submit" id="delete_cashflow_' + data.cashflow.id 
			+ '" class="delete_cashflow btn btn-xs btn-danger"><i class="fa fa-times"></i></button>' 
			+ '</form>';
			cashflowRow.data('category', data.category_id);
			cashflowRow.data('name', data.cashflow.name);
			cashflowRow.data('amount', data.cashflow.amount);
			cashflowRow.html('<td class="edit_cashflow">' + data.cashflow.name 
				+ '</td><td class="edit_cashflow">' + data.cashflow.category_id 
				+ '</td><td class="edit_cashflow">' + data.cashflow.amount 
				+ '</td><td>' + delete_form + '</td>');
			cashflows_summary(data.revenues_summary, data.expenses_summary);
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Element nie może zostać zaktualizowany w tym momencie.');
			$('#cashflow_' + id + '_error').html(errorMessage);
		}
	});
});

/* Category */
$(document).on('submit', '.add_category_form', function(e) {
	e.preventDefault();
	type = $(this).data('type');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			delete_form = '<form id="delete_category_' + data.id 
			+ '" class="delete_category" action="/category/' + data.id 
			+ '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' 
			+ '<button type="submit" id="delete_category_' + data.id 
			+ '" class="delete_category btn btn-xs btn-danger"><i class="fa fa-times"></i></button>' 
			+ '</form>';
			$('#' + type + '_table tbody').append('<tr id="category_' + data.id 
				+ '" data-id=' + data.id + ' data-type=' + type + ' data-default=' 
				+ data.default + ' data-name=' + data.name + '>' + '<td class="edit_category">' 
				+ data.name + '</td>' + '<td class="edit_category">' + data.default 
				+ '</td>' + '<td>' + delete_form + '</td></tr>');
			$('#' + type + '_errors').empty();
			$('#add_' + type + '_form .category_name').val('');
			$('#add_' + type + '_form .category_default').val('');
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Niestety zmiana nie została zapisana. Spróbuj później.');
			$('#' + type + '_errors').html(errorMessage);
		}
	});
});

$(document).on('submit', '.delete_category', function(e) {
	e.preventDefault();
	id = $(this).closest('tr').data('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'DELETE',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			$('#category_' + id).remove();
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Kategoria nie może zostać usunięta w tym momencie. Prawdopodobnie masz przypisane elementy do tej kategorii w budżecie.');
			$('#category_' + id + '_error').html(errorMessage);
		}
	});
});

$(document).on('click', '.edit_category', function(e) {
	categoryRow = $(this).closest('tr');
	id = categoryRow.data('id');
	name = categoryRow.data('name');
	amount = categoryRow.data('default');
	categoryRow.html('<td colspan="3"><div id="category_' + id + '_error"></div>'
		+ '<form action="/category/update/' + id + '" class="edit_category_form form-horizontal">' 
		+ '<input type="hidden" name="category_id" value="' + id + '">' 
		+ '<div class="col-sm-5"><input type="text" name="name" value=' + name 
		+ ' class="edit_category_name form-control"></div>' 
		+ '<div class="col-sm-5"><input type="text" name="default" value=' + amount 
		+ ' class="edit_category_amount form-control"></div>' 
		+ '<div class="col-sm-2"><button type="submit" class="edit_category_form '
		+ 'btn btn-default"><i class="fa fa-check"></i></button></div></form></td>');
});

$(document).on('submit', '.edit_category_form', function(e) {
	e.preventDefault();
	categoryRow = $(this).closest('tr');
	id = categoryRow.data('id');
	name = categoryRow.data('name');
	amount = categoryRow.data('default');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			delete_form = '<form id="delete_category_' + id + '" class="delete_category" '
				+ 'action="/category/update/' + id + '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">'
				+ '<button type="submit" id="delete-category-' + id 
				+ '" class="delete_category btn btn-xs btn-danger"><i class="fa fa-times"></i></button></form>';
			categoryRow.data('name', data.name);
			categoryRow.data('default', data.amount);
			categoryRow.html('<td class="edit_category">' + data.name + '</td>' 
				+ '<td class="edit_category">' + data.amount + '</td>' 
				+ '<td>' + delete_form + '</td>');
		},
		error : function(xhr) {
			errorMessage = prepareAlert(xhr, 'Kategoria nie może zostać zaktualizowana w tym momencie.');
			$('#category_' + id + '_error').html(errorMessage);
		}
	});
});