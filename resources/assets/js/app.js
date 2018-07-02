var saveButton = '<button type="submit" class="btn btn-default" title="Save"><i class="fa fa-check"></i></button>';
var undoButton = '<button class="btn btn-default btn_undo" title="Undo"><i class="fa fa-undo"></i></button>';
var deleteButton = '<button type="submit" class="delete_cashflow btn btn-xs btn-danger"><i class="fa fa-times"></i></button>';
$.ajaxSetup({
	headers : {
		'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
	}
});

function displayAlert(xhr, message, alertType = "danger") {
	var alertMessage = '<div class="alert alert-' + alertType + ' alert-hide">';
	if (xhr.status == 422) {
		var errors = xhr.responseJSON.errors;
		alertMessage += '<ul>';
		$.each(errors, function(k, v){
			alertMessage += '<li>' + v + '</li>';
		});
		alertMessage += '</ul></div>';
	} else {
		if(alertType == 'danger')
			alertMessage += '[' + xhr.status + ' ' + xhr.statusText + '] ';
		alertMessage += message + '</div>'
	}
	$('#alerts').append(alertMessage);
	$('.alert-hide').delay(5000).fadeOut(1000);
}

/* User */
$(document).on('click', '.edit_user', function(e){
	var name = $(this).data('name');
	var value = $(this).data('value');
	$(this).removeClass('edit_user edit_item');
	$(this).html('<form action="/profile/update" class="edit_user_form form-inline">' 
		+ '<input type="text" name="' + name + '" value="' + value + '" class="form-control" required>' 
		+ saveButton + undoButton + '</form>');
});

$(document).on('submit', '.edit_user_form', function(e){
	e.preventDefault();
	var userItem = $(this).closest('td');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data, status, xhr) {
			userItem.data('value', data.value);
			userItem.addClass('edit_user edit_item');
			userItem.html(data.value);
			displayAlert(xhr, 'Zmiany zostały zapisane.', 'success');
		},
		error : function(xhr) {
			displayAlert(xhr, 'Niestety aktualizacja nie powiodła się.');
		}
	});
});

$(document).on('click', '.edit_user_form button.btn_undo', function(e){
	e.preventDefault();
	var userItem = $(this).closest('td');
	userItem.addClass('edit_user edit_item').html(userItem.data('value'));
});

/* Budget report */
$(document).on('change', '#set_month_report', function(e){
	var form = $(this).closest('form');
	var checked = $(this).is(':checked')? 1 : 0;
	$.ajax({
		url : form.attr('action'),
		type : 'POST',
		data : {'month_report': checked},
		dataType : 'json',
		success: function(data, status, xhr){
			displayAlert(xhr, 'Zmiany zostały zapisane.', 'success');
		},
		error: function(xhr){
			displayAlert(xhr, 'Niestety aktualizacja nie powiodła się.');
		}
	});
});

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
	var budgetRow = $(this).closest('tr');
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
	var id = $(this).data('id');
	var date = $(this).data('date');
	$(this).removeClass('edit_budget edit_item');
	$(this).html('<form action="/budget/update/' + id 
		+ '" class="edit_budget_form form-inline" data-id=' + id 
		+ ' data-date='	+ date + '"><input type="hidden" name="id" value="' + id + '">' 
		+ '<input id="budget_date" class="form-control" type="month" name="date" value="' 
		+ date + '" required>' + saveButton + undoButton + '</form>');
});

$(document).on('submit', '.edit_budget_form', function(e) {
	e.preventDefault();
	var id = $(this).data('id');
	var date = $(this).data('date');
	var budgetDate = $(this).closest('span');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			budgetDate.addClass('edit_budget edit_item')
				.data('date', data.date).data('datetext', data.datetext)
				.html(data.datetext);
		},
		error : function(xhr) {
			displayAlert(xhr, 'Niestety zmiana nie została zapisana. Spróbuj później.');
		}
	});
});

$(document).on('click', '.edit_budget_form button.btn_undo', function(e){
	e.preventDefault();
	var budgetDate = $(this).closest('span');
	budgetDate.addClass('edit_budget edit_item').html(budgetDate.data('datetext'));
});

/* Cashflow */
function cashflows_summary(revenues, expenses) {
	$('#revenues_summary').html("Przychody: <span class='summary_number'>" + revenues + "</span>");
	$('#expenses_summary').html("Wydatki: <span class='summary_number'>" + expenses + "</span>");
	$('#balance').html("Saldo: <span class='summary_number'>" + Math.round((revenues - expenses) * 100) * 0.01 + "</span>");
}

function deleteCashflowForm(cashflowId, budgetId) {
	return '<form id="delete_cashflow_' + cashflowId
			+ '" class="delete_cashflow" action="/cashflow/' + cashflowId 
			+ '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">' 
			+ '<input type="hidden" name="budget_id" value="' + budgetId
			+ '"/>' + deleteButton + '</form>';
}

function cashflowData(name, category, amount, deleteForm) {
	return '<td class="edit_cashflow edit_item" title="Edit">' + name 
			+ '</td><td class="edit_cashflow edit_item" title="Edit">' + category
			+ '</td><td class="edit_cashflow edit_item" title="Edit">' + amount
			+ '</td><td>' + deleteForm + '</td>';
}

$(document).on('submit', '.add_cashflow_form', function(e) {
	e.preventDefault();
	var type = $(this).data('type');
	var formId = $(this).attr('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			var deleteForm = deleteCashflowForm(data.cashflow.id, data.cashflow.budget_id);
			var cashData = cashflowData(data.cashflow.name, data.category.name, parseInt(data.cashflow.amount).toFixed(2), deleteForm);
			$('#' + type + '_table tbody').append('<tr id="cashflow_' + data.cashflow.id 
				+ '" data-id="' + data.cashflow.id + '" data-type="' + type 
				+ '" data-categoryid="' + data.category.id + '" data-category="' + data.category.name 
				+ '" data-name="' + data.cashflow.name + '" data-amount="' + parseInt(data.cashflow.amount).toFixed(2)
				+ '">' + cashData + '</tr>');
			$('#' + formId + ' .cashflow_name').val('');
			$('#' + formId + ' .cashflow_amount').val('');
			$('#' + formId + ' .cashflow_category').prop('selectedIndex', 0);
			cashflows_summary(data.revenues_summary, data.expenses_summary);
		},
		error : function(xhr) {
			displayAlert(xhr, 'Niestety zmiana nie została zapisana. Spróbuj później.');
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
	var id = $(this).closest('tr').data('id');
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
			displayAlert(xhr, 'Element nie może zostać usunięty w tym momencie.');
		}
	});
});

$(document).on('click', '.edit_cashflow', function(e) {
	var cashflowRow = $(this).closest('tr');
	var id = cashflowRow.data('id');
	var name = cashflowRow.data('name');
	var amount = cashflowRow.data('amount');
	var categoryId = cashflowRow.data('categoryid');
	var type = cashflowRow.data('type');
	var select = $('#add_' + type + '_form select').clone();
	cashflowRow.html('<td colspan="4"><form action="/cashflow/update/' + id 
		+ '" class="edit_cashflow_form form-horizontal">' 
		+ '<div class="col-sm-3"><input type="text" name="name" value="' + name 
		+ '" class="edit_cashflow_name form-control" required></div>' + '<div id="select_' 
		+ id + '" class="col-sm-3"></div>' + '<div class="col-sm-3">'
		+ '<input type="text" name="amount" value="' + amount
		+ '" class="form-control edit_cashflow_amount" required></div>' 
		+ '<div class="col-sm-3">' + saveButton + undoButton + '</div></form></td>');
	select.attr('id', 'select_' + id + '_edit').appendTo('#select_' + id);
	$('#select_' + id + '_edit').val(categoryId);
});

$(document).on('submit', '.edit_cashflow_form', function(e) {
	e.preventDefault();
	var cashflowRow = $(this).closest('tr');
	var id = cashflowRow.data('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			deleteForm = deleteCashflowForm(data.cashflow.id, data.cashflow.budget_id);
			cashflowRow.data('categoryid', data.category.id)
				.data('category', data.category.name)
				.data('name', data.cashflow.name)
				.data('amount', parseInt(data.cashflow.amount).toFixed(2))
				.data('budgetid', data.cashflow.budget_id);
			cashData = cashflowData(data.cashflow.name, data.category.name, parseInt(data.cashflow.amount).toFixed(2), deleteForm);
			cashflowRow.html(cashData);
			cashflows_summary(data.revenues_summary, data.expenses_summary);
		},
		error : function(xhr) {
			displayAlert(xhr, 'Element nie może zostać zaktualizowany w tym momencie.');
		}
	});
});

$(document).on('click', '.edit_cashflow_form button.btn_undo', function(e){
	e.preventDefault();
	var cashflowRow = $(this).closest('tr');
	deleteForm = deleteCashflowForm(cashflowRow.data('id'), cashflowRow.data('budgetid'));
	data = cashflowData(cashflowRow.data('name'), cashflowRow.data('category'), cashflowRow.data('amount'), deleteForm);
	cashflowRow.html(data);
});

/* Category */
function deleteCategoryForm(id) {
	return '<form id="delete_category_' + id + '" class="delete_category" '+ 'action="/category/update/' 
		+ id + '" method="POST">' + '<input type="hidden" name="_method" value="DELETE">'
		+ '<button type="submit" id="delete-category-' + id 
		+ '" class="delete_category btn btn-xs btn-danger"><i class="fa fa-times"></i></button></form>';
}

function categoryData(name, defaultAmount, deleteForm) {
	return '<td class="edit_category edit_item" title="Edit">' + name + '</td>' 
		+ '<td class="edit_category edit_item" title="Edit">' + defaultAmount + '</td>' 
		+ '<td>' + deleteForm + '</td>';
}

$(document).on('submit', '.add_category_form', function(e) {
	e.preventDefault();
	var type = $(this).data('type');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			var deleteForm = deleteCategoryForm(data.id);
			var catData = categoryData(data.name, data.default, deleteForm);
			$('#' + type + '_table tbody').append('<tr id="category_' + data.id 
				+ '" data-id=' + data.id + ' data-type=' + type + ' data-default=' 
				+ data.default + ' data-name=' + data.name + '>' + catData + '</tr>');
			$('#add_' + type + '_form .category_name').val('');
			$('#add_' + type + '_form .category_default').val('');
		},
		error : function(xhr) {
			displayAlert(xhr, 'Niestety zmiana nie została zapisana. Spróbuj później.');
		}
	});
});

$(document).on('submit', '.delete_category', function(e) {
	e.preventDefault();
	var id = $(this).closest('tr').data('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'DELETE',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			$('#category_' + id).remove();
		},
		error : function(xhr) {
			displayAlert(xhr, 'Kategoria nie może zostać usunięta w tym momencie. Prawdopodobnie masz przypisane elementy do tej kategorii w budżecie.');
		}
	});
});

$(document).on('click', '.edit_category', function(e) {
	var categoryRow = $(this).closest('tr');
	var id = categoryRow.data('id');
	var name = categoryRow.data('name');
	var defaultAmount = categoryRow.data('default');
	categoryRow.html('<td colspan="3"><form action="/category/update/' + id 
		+ '" class="edit_category_form form-horizontal">' 
		+ '<input type="hidden" name="category_id" value="' + id + '">' 
		+ '<div class="col-sm-5"><input type="text" name="name" value="' + name 
		+ '" class="edit_category_name form-control" required></div>' 
		+ '<div class="col-sm-5"><input type="text" name="default" value="' + defaultAmount
		+ '" class="edit_category_default form-control" required></div>' 
		+ '<div class="col-sm-2">' + saveButton + undoButton + '</div></form></td>');
});

$(document).on('submit', '.edit_category_form', function(e) {
	e.preventDefault();
	var categoryRow = $(this).closest('tr');
	var id = categoryRow.data('id');
	$.ajax({
		url : $(this).attr('action'),
		type : 'POST',
		data : $(this).serialize(),
		dataType : 'json',
		success : function(data) {
			var deleteForm = deleteCategoryForm(id);
			var catData = categoryData(data.name, parseInt(data.default).toFixed(2), deleteForm);
			categoryRow.data('name', data.name)
				.data('default', parseInt(data.default).toFixed(2));
			categoryRow.html(catData);
		},
		error : function(xhr) {
			displayAlert(xhr, 'Kategoria nie może zostać zaktualizowana w tym momencie.');
		}
	});
});

$(document).on('click', '.edit_category_form button.btn_undo', function(e){
	e.preventDefault();
	var categoryRow = $(this).closest('tr');
	var deleteForm = deleteCategoryForm(categoryRow.data('id'));
	var data = categoryData(categoryRow.data('name'), categoryRow.data('default'), deleteForm);
	categoryRow.html(data);
});