<?php

namespace App\Policies;

use App\User;
use App\Cashflow;
use App\Budget;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashflowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can update the given cashflow.
     *
     * @param  User  $user
     * @param  Budget  $cashflow
     * @return bool
     */
    public function update(User $user, Cashflow $cashflow)
    {
        return $this->auth($user, $cashflow);
    }
    /**
     * Determine if the given user can delete the given cashflow.
     *
     * @param  User  $user
     * @param  Budget  $cashflow
     * @return bool
     */
    public function destroy(User $user, Cashflow $cashflow)
    {        
	return $this->auth($user, $cashflow);
    }
    
    private function auth(User $user, Cashflow $cashflow)
    { 
        return $user->id === intval(Budget::find($cashflow->budget_id)['user_id']);
    }
}
