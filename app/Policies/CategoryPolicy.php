<?php

namespace App\Policies;

use App\User;
use App\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can update the given category.
     *
     * @param  User  $user
     * @param  Category  $category
     * @return bool
     */
    public function update(User $user, Category $category)
    {
        return $this->auth($user, $category);
    }
     /**
     * Determine if the given user can delete the given category.
     *
     * @param  User  $user
     * @param  Category  $category
     * @return bool
     */
    public function destroy(User $user, Category $category)
    {
        return $this->auth($user, $category);
    }

    private function auth(User $user, Category $category)
    { 
        return $user->id === intval($category->user_id);
    }
}
