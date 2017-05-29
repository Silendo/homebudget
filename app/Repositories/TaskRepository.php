<?php

namespace App\Repositories;

use App\User;

class TaskRepository {
	public function forUser(User $user) {
		/**
		 * Get all of the tasks for a given user.
		 *
		 * @param  User  $user
		 * @return Collection
		 */
		return $user -> tasks() -> orderBy('created_at', 'asc') -> get();
	}

}
