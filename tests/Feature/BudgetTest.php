<?php

namespace Tests\Feature;

use App\User;
use App\Budget;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Dashboard can be displayed.
     *
     * @return void
     */
    public function testDashboardDisplayed()
    {
        $user = factory(User::class)->create();
    	$response = $this->actingAs($user)->get('dashboard');
    	$response->assertStatus(200);
    }

    /**
     * If date is valid, user can create new budget.
     * 
     * @return @void
     */
    public function testBudgetCanBeStored(){
    	$numOfBudgets = Budget::count();
    	$user = factory(User::class)->create();
    	$response = $this->actingAs($user)->post('budget', ['date' => '2018-04']);
    	$response->assertStatus(302);
    	$this->assertEquals($numOfBudgets+1, Budget::count());
    }

    /**
     * If date is not given, user can not create new budget.
     * 
     * @return @void
     */
    public function testBudgetCanNotBeStored(){
    	$user = factory(User::class)->create();
    	$response = $this->actingAs($user)->post('budget', ['date' => '']);
    	$response->assertRedirect('/'); //TODO Should be dashboard, check later
    	$response->assertSessionHasErrors(['date']);
    }

    /**
     * Month budget can be displayed.
     * 
     * @return @void
     */
    public function testBudgetCanBeDisplayed(){
    	$user = factory(User::class)->create();
    	$budget = factory(Budget::class)->create(['user_id' => $user->id]);
    	$response = $this->actingAs($user)->get('budget/'.$budget->id);
    	$response->assertStatus(200);
    }

    /**
     * Month budget can not be displayed, if it does not exist.
     * 
     * @return @void
     */
    public function testBudgetCanNotBeDisplayed(){
    	$user = factory(User::class)->create();
    	$response = $this->actingAs($user)->get('budget/123456');
    	$response->assertStatus(404);
    }

    //TODO Check if user can see budget created by other person - should be 403.
}
