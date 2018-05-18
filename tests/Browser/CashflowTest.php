<?php

namespace Tests\Browser;

use App\User;
use App\Budget;
use App\Cashflow;
use App\Category;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CashflowTest extends DuskTestCase
{
    /**
     * Revenue can be added and displayed in table.
     *
     * @return void
     */
    public function testRevenueCanBeAdded()
    {
        $this->addAndTestCashflow(true);
    }

    /**
     * Expense can be added and displayed in table.
     *
     * @return void
     */
    public function testExpenseCanBeAdded()
    {
        $this->addAndTestCashflow(false);
    }

    /**
     * Cashflows can be added and then summed up.
     */
    public function testCashflowsCanBeAddedAndSummarized(){
        $user = factory(User::class)->create();
        $budget = factory(Budget::class)->create(['user_id' => $user->id]);
        $revenueCategory = factory(Category::class)->create(['user_id' => $user->id, 'type' => true]);
        $expenseCategory = factory(Category::class)->create(['user_id' => $user->id, 'type' => false]);
        $this->browse(function ($browser) use ($user, $budget) {
        $revenueSelector = '#add_revenue_form';
        $expenseSelector = '#add_expense_form';
        $revenueAmount = 5000;
        $expenseAmount = 2000;
        $summaryAmount = $revenueAmount - $expenseAmount;
        $budgetPath = '/budget/'.$budget->id;
        $browser->loginAs($user)
                ->visit($budgetPath)
                ->value($revenueSelector.' .cashflow_name', 'salary')
                ->value($revenueSelector.' .cashflow_amount', $revenueAmount)
                ->click($revenueSelector.' button')
                ->value($expenseSelector.' .cashflow_name', 'food')
                ->value($expenseSelector.' .cashflow_amount', $expenseAmount)
                ->click($expenseSelector.' button')
                ->pause(1000)
                ->assertPathIs($budgetPath)
                ->assertSeeIn('#revenues_summary .summary_number', $revenueAmount)
                ->assertSeeIn('#expenses_summary .summary_number', $expenseAmount)
                ->assertSeeIn('#balance .summary_number', $summaryAmount);
        });
    }

    /**
     * Cashflow can be updated.
     */
    public function testCashflowCanBeUpdated(){
        $user = factory(User::class)->create();
        $budget = factory(Budget::class)->create(['user_id' => $user->id, 'date' => '2018-03']);
        $category = factory(Category::class)->create(['user_id' => $user->id, 'type' => true]);
        $cashflow = factory(Cashflow::class)->create(['budget_id' => $budget->id, 'category_id' => $category->id, 'amount' => 5000]);
        $this->browse(function ($browser) use ($user, $budget, $cashflow) {
            $budgetPath = '/budget/'.$budget->id;
            $cashflowSelector = '#cashflow_'.$cashflow->id;
            $newCashflowAmount = '6000';
        $browser->loginAs($user)
                ->visit($budgetPath)
                ->click($cashflowSelector)
                ->value($cashflowSelector.' input.edit_cashflow_amount', $newCashflowAmount)
                ->click($cashflowSelector.' button')
                ->waitUntilMissing($cashflowSelector.' button.edit_cashflow_form')
                ->assertPathIs($budgetPath)
                ->assertSeeIn($cashflowSelector, $newCashflowAmount);
        });
    }

    /**
     * Cashflow can be deleted.
     */
    public function testCashflowCanBeDeleted(){
        $user = factory(User::class)->create();
        $budget = factory(Budget::class)->create(['user_id' => $user->id, 'date' => '2018-03']);
        $category = factory(Category::class)->create(['user_id' => $user->id, 'type' => true]);
        $cashflow = factory(Cashflow::class)->create(['budget_id' => $budget->id, 'category_id' => $category->id]);
        $this->browse(function ($browser) use ($user, $budget, $cashflow) {
            $budgetPath = '/budget/'.$budget->id;
            $deleteCashflowSelector = '#delete_cashflow_'.$cashflow->id;
        $browser->loginAs($user)
                ->visit($budgetPath)
                ->click($deleteCashflowSelector)
                ->waitUntilMissing($deleteCashflowSelector)
                ->assertPathIs($budgetPath);
        });
    }

    /**
     * Cashflow can be added.
    */
    private function addAndTestCashflow($cashflowType){
        $user = factory(User::class)->create();
        $budget = factory(Budget::class)->create(['user_id' => $user->id]);
        $category = factory(Category::class)->create(['user_id' => $user->id, 'type' => $cashflowType]);
        $this->browse(function ($browser) use ($user, $budget, $cashflowType) {
        if($cashflowType){
            $cashflowSelector = '#add_revenue_form';
            $cashflowTableSelector = '#revenue_table';
        }
        else{
            $cashflowSelector = '#add_expense_form';
            $cashflowTableSelector = '#expense_table';
        }
        $budgetPath = '/budget/'.$budget->id;
        $cashflowName = 'not important now';
        $browser->loginAs($user)
                ->visit($budgetPath)
                ->value($cashflowSelector.' .cashflow_name', $cashflowName)
                ->value($cashflowSelector.' .cashflow_amount', '5000')
                ->click($cashflowSelector.' button')
                ->pause(1000)
                ->assertPathIs($budgetPath)
                ->assertSeeIn($cashflowTableSelector, $cashflowName);
        });
    }

    // TODO Awful error if 0 categories available.
    // TODO Default amount for category not displaying now. 
    // TODO If required data not set, error should appear. 
}
