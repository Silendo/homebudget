<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    /**
     * Categories can be displayed.
     *
     * @return void
     */
    public function testCategoriesCanBeDisplayed()
    {
        $user = factory(User::class)->create();
    	$response = $this->actingAs($user)->get('categories');
    	$response->assertStatus(200);
    }
}
