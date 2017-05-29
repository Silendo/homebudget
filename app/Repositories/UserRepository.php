<?php
namespace App\Repositories;

use App\User;

class UserRepository {
	protected $user;

	public function __construct(User $user) {
		$this -> user = $user;
	}

	public function fund($id) {
		return $this -> user -> find($id);
	}

}
