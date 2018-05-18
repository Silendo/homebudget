<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Login form can be displayed.
     *
     * @return void
     */
    public function testLoginFormDisplayed()
    {
    	$response = $this->get('login');
        $response->assertStatus(200);
    }
	
	/**
     * Valid user can be logged in.
     *
     * @return void
     */
    public function testLoginValidUser()
    {
    	$user = factory(User::class)->create();
    	$response = $this->post('login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);
        //$response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }
    
    /**
     * Invalid user can not be logged in.
     *
     * @return void
     */
    public function testDoesNotLoginInvalidUser()
    {
    	$user = factory(User::class)->create();
    	$response = $this->post('login', [
            'email' => $user->email,
            'password' => 'invalid'
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Logged in user can be logged out.
     *
     * @return void
     */
    public function testLogoutAuthenticatedUser()
    {
    	$user = factory(User::class)->create();
    	$response = $this->actingAs($user)->post('logout');
        //$response->assertStatus(302);
        $this->assertGuest();
    }
}
