<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Task;
use App\Budget;
use App\Category;

class User extends Authenticatable {
	use Notifiable;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', ];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', ];

	/**
	 * Get all of the tasks for the user.
	 */
	public function tasks() {
		return $this -> hasMany('App\Task');
	}

	public function budgets() {
		return $this -> hasMany('App\Budget');
	}

	public function categories() {
		return $this -> hasMany('App\Category');
	}

}
