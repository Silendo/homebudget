<?php

namespace Tests\Browser;

use App\User;
use App\Budget;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BudgetTest extends DuskTestCase
{
    /**
     * Budget date can be changed.
     */
    public function testBudgetDateCanBeUpdated(){
        $user = factory(User::class)->create();
        $budget = factory(Budget::class)->create(['user_id' => $user->id, 'date' => '2018-03']);
        $this->browse(function ($browser) use ($user, $budget) {
            $budgetPath = '/budget/'.$budget->id;
        $browser->loginAs($user)
                ->visit($budgetPath)
                ->click('.edit_budget')
                ->value('#budget_date', '2018-04')
                ->click('.edit_budget_form button')
                ->waitUntilMissing('.edit_budget_form')
                ->assertPathIs($budgetPath)
                ->assertSeeIn('#budget_title .edit_budget', date('F Y', strtotime($budget->fresh()->date)));
        });
    }

    /**
     * Budget can be deleted.
     *
     * @return void
     */
    public function testBudgetCanBeDeleted()
    {
        $user = factory(User::class)->create();
        $budget = factory(Budget::class)->create(['user_id' => $user->id]);
        $selector = '#delete_budget_'.$budget->id.' button';
        $this->browse(function ($browser) use ($user, $selector) {
        $browser->loginAs($user)
                ->visit('/dashboard')
                ->click($selector)
                ->waitUntilMissing($selector)
                ->assertPathIs('/dashboard');
        });
    }
}
