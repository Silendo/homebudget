<?php

namespace Tests\Browser;

use App\User;
use App\Category;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends DuskTestCase
{
    /**
     * Revenue category can be added and displayed in table.
     *
     * @return void
     */
    public function testRevenueCategoryCanBeAdded()
    {
        $this->addAndTestCategory(true);
    }

    /**
     * Expense category can be added and displayed in table.
     *
     * @return void
     */
    public function testExpenseCategoryCanBeAdded()
    {
        $this->addAndTestCategory(false);
    }

    /**
     * Category can be updated.
     */
    public function testCategoryCanBeUpdated(){
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create(['user_id' => $user->id, 'type' => true]);
        $this->browse(function ($browser) use ($user, $category) {
            $categoriesPath = '/categories';
            $categorySelector = '#category_'.$category->id;
            $newCategoryAmount = 5000;
        $browser->loginAs($user)
                ->visit($categoriesPath)
                ->click($categorySelector)
                ->value($categorySelector.' input.edit_category_amount', $newCategoryAmount)
                ->click($categorySelector.' button')
                ->waitUntilMissing($categorySelector.' button.edit_category_form')
                ->assertPathIs($categoriesPath)
                ->assertSeeIn($categorySelector, $newCategoryAmount);
        });
    }

    /**
     * Category can be deleted.
     */
    public function testCategoryCanBeDeleted(){
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create(['user_id' => $user->id, 'type' => true]);
        $this->browse(function ($browser) use ($user, $category) {
            $categoriesPath = '/categories';
            $deleteCategorySelector = '#delete_category_'.$category->id;
        $browser->loginAs($user)
                ->visit($categoriesPath)
                ->click($deleteCategorySelector)
                ->waitUntilMissing($deleteCategorySelector)
                ->assertPathIs($categoriesPath);
        });
    }

    /**
     * Category can be added.
    */
    private function addAndTestCategory($categoryType){
        $user = factory(User::class)->create();
        $this->browse(function ($browser) use ($user, $categoryType) {
        if($categoryType){
            $categorySelector = '#add_revenue_form';
            $categoryTableSelector = '#revenue_table';
        }
        else{
            $categorySelector = '#add_expense_form';
            $categoryTableSelector = '#expense_table';
        }
        $categoriesPath = '/categories';
        $categoryName = 'not important now';
        $browser->loginAs($user)
                ->visit($categoriesPath)
                ->value($categorySelector.' .category_name', $categoryName)
                ->value($categorySelector.' .category_default', '5000')
                ->click($categorySelector.' button')
                ->pause(2000)
                ->assertPathIs($categoriesPath)
                ->assertSeeIn($categoryTableSelector, $categoryName);
        });
    }

    // TODO If required data not set, specific error should appear. 
    // TODO Problem if the same category name inserted.
}
