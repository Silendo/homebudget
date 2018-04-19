<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Budget;
use App\Category;

class Cashflow extends Model {
	protected $fillable = ['name', 'amount', 'type', 'category_id', 'budget_id'];

	public function budget() {
		return $this -> belongsTo('App\Budget');
	}

	public function getCategoryIdAttribute($value) {
		$category = Category::where('id', $value) -> first();
		return $category -> name;
	}

	public function getTypeAttribute($value) {
		if ($type == true)
			return 'revenue';
		else
			return 'expense';
	}

}
