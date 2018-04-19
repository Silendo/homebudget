<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Category extends Model {
	protected $attributes = array('default' => 0, );

	protected $fillable = ['name', 'type', 'default', 'user_id'];

	public function user() {
		return $this -> belongsTo('App\User');
	}

	public function getTypeAttribute($value) {
		if ($value == true)
			return 'revenue';
		else
			return 'expense';
	}

}
