<table id="{{$type}}_table" class="table table-hover">
	<thead>
		<th>Kategoria</th><th>Domyślna kwota</th><th></th>
	</thead>
	<tbody>
		@if(isset($categories))
		@foreach ($categories as $category)
		<tr id="category_{{$category->id}}" data-name="{{$category->name}}" data-default="{{$category->default}}" data-type="{{$type}}">
			<td class="edit_category" data-id="{{$category->id}}">{{$category->name}}</td>
			<td class="edit_category" data-id="{{$category->id}}">{{$category->default}}</td>
			<td>
			<form id="delete_category_{{$category->id}}" class="delete_category" data-id="{{$category->id}}" data-type="{{$type}}" action="{{url('category/' . $category->id)}}" method="POST">
				{{ method_field('DELETE') }}
				<button type="submit" id="delete-category-{{ $category->id }}" class="delete_category btn btn-xs btn-danger">
					<i class="fa fa-times"></i>
				</button>
			</form></td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>
<div id="{{$type}}_errors"></div>
<form id="add_{{$type}}_form" class="add_category_form" data-type="{{$type}}" action="{{ url('category') }}" method="POST" class="form-horizontal">
	{{ csrf_field() }}
	@if ($type)
		<input type="hidden" name="type" value="1"/>
	@else
		<input type="hidden" name="type" value="0"/>
	@endif
	<input type="hidden" name="user_id" value="{{Auth::id()}}"/>
	<div class="col-sm-5">
		<input class="category_name form-control" type="text" placeholder="New category" name="name"/>
	</div>
	<div class="col-sm-5">
		<input class="category_default form-control" type="text" placeholder="PLN" name="default"/>
	</div>
	<div class="col-sm-2">
		<button name="add_revenue" value="{{$type}}" type="submit" class="btn btn-default">
			<i class="fa fa-btn fa-plus"></i>Dodaj
		</button>
	</div>
</form>

