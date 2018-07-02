@include('common.errors')

<table id="{{$type}}_table" class="table table-hover">
	<thead>
		<tr>
			<th> <!--ucfirst($type)--> Nazwa </th><th>Kategoria</th><th>Kwota</th><th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($cashflows as $cashflow)
		<tr id="cashflow_{{$cashflow->id}}" data-id="{{$cashflow->id}}" data-type="{{$type}}" data-category="{{$cashflow->category_id}}" data-categoryid="{{$cashflow->getOriginal('category_id')}}"  data-name="{{$cashflow->name}}" data-amount="{{$cashflow->amount}}" data-budgetid="{{ $budget->id }}">
			<td class="edit_cashflow edit_item" title="Edit">{{$cashflow->name}}</td>
			<td class="edit_cashflow edit_item" title="Edit">{{$cashflow->category_id}}</td>
			<td class="edit_cashflow edit_item" title="Edit">{{$cashflow->amount}}</td>
			<td>
			<form id="delete_cashflow_{{$cashflow->id}}" class="delete_cashflow" action="{{url('cashflow/' . $cashflow->id)}}" method="POST">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<input type="hidden" name="budget_id" value="{{$budget->id}}"/>
				<button type="submit" id="delete-cashflow-{{ $cashflow->id }}" class="delete_cashflow btn btn-xs btn-danger">
					<i class="fa fa-times"></i>
				</button>
			</form></td>
		</tr>
		@endforeach
	</tbody>
</table>
<form id="add_{{$type}}_form" class="add_cashflow_form" data-type="{{$type}}" action="{{ url('cashflow') }}" method="POST" class="form-horizontal">
	{{ csrf_field() }}
	<input type="hidden" name="budget_id" value="{{$budget->id}}"/>
	<div class="col-sm-3">
		<input class="cashflow_name form-control" type="text" name="name" placeholder="New item" id="cashflow_name">
	</div>
	<div class="col-sm-3">
		<select class="category_select cashflow_category form-control" name="category_id">
			@foreach ($categories as $category)
			<option value="{{$category->id}}" data-default="{{$category->default}}">{{$category->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-sm-3">
		<input id="{{$type}}_amount" class="cashflow_amount form-control" type="text" name="amount" placeholder="PLN" id="cashflow_amount" value="@isset($categories[0]){{$categories[0]->default}}@endisset">
	</div>
	<div class="col-sm-3">
		@if($type=='revenue')
		<button name="add_revenue" value="revenue" type="submit" class="btn btn-default">
			<i class="fa fa-btn fa-plus"></i>Dodaj
		</button>
		@else
		<button name="add_expense" value="expense" type="submit" class="btn btn-default">
			<i class="fa fa-btn fa-plus"></i>Dodaj
		</button>
		@endif
	</div>
</form>
