<?php
namespace App\Repositories;

use App\User;

class UserRepository {
	protected $user;

	public function __construct(User $user) {
		$this -> user = $user;
	}

	public function find($id) {
		return $this -> user -> find($id);
	}

	public function createGoogleUser($user){
		return User::create(['name' =>$user->getName(), 'email' => $user->getEmail(), 'google_id' => $user->getId()]);
	}

}
