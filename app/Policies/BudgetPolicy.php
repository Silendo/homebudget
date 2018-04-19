<?php

namespace App\Policies;

use App\User;
use App\Budget;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can update the given budget.
     *
     * @param  User  $user
     * @param  Budget  $budget
     * @return bool
     */
    public function update(User $user, Budget $budget)
    {
        return $this->auth($user, $budget);
    }
    
    /**
     * Determine if the given user can delete the given budget.
     *
     * @param  User  $user
     * @param  Budget  $budget
     * @return bool
     */
    public function destroy(User $user, Budget $budget)
    {
        return $this->auth($user, $budget);
    }

    private function auth(User $user, Budget $budget)
    {
        return $user->id === intval($budget->user_id);
    }
}
