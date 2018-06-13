<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Cashflow;
use Carbon\Carbon;

class Budget extends Model {
	protected $fillable = ['date', ];
	public function budget() {
		return $this -> belongsTo('App\User');
	}

	public function cashflows() {
		return $this -> hasMany('App\Cashflow');
	}

	public function getDateAttribute($value) {
		return Carbon::parse($value) -> format('F Y');
	}

}
