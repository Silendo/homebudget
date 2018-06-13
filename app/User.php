<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Budget;
use App\Category;
use App\Notifications\ResetPasswordNotification as ResetPasswordNotification; 

class User extends Authenticatable {
	use Notifiable;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'provider', 'provider_id'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', ];

	/**
	 * Get all budgets defined by the user.
	 */
	public function budgets() {
		return $this -> hasMany('App\Budget');
	}

	/**
	 * Get all categories defined by the user.
	 */
	public function categories() {
		return $this -> hasMany('App\Category');
	}

	/**
	 * Send the password reset notification.
 	 *
 	 * @param  string  $token
 	 * @return void
 	*/
	public function sendPasswordResetNotification($token)
	{
    	$this->notify(new ResetPasswordNotification($token));
	}
}
