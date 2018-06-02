<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;
    /**
	 * Registration form can be displayed.
	 *
	 * @return void
	 */
    public function testRegisterFormDisplayed()
    {
    	$response = $this->get('/register');
    	$response->assertStatus(200);
    }

    /**
     * Valid user can be registered.
     *
     * @return void
     */
    public function testRegistersValidUser()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->make();
        $response = $this->post('register',[
        	'name' => $user->name,
        	'email' => $user->email,
        	'password' => 'secret',
        	'password_confirmation' => 'secret',
        	'provider' => $user->provider_id,
        	'provider_id' => $user->provider_id
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    /**
     * Invalid user can not be registered.
     *
     * @return void
     */
    public function testDoesNotRegisterInvalidUser()
    {
        $this->withoutMiddleware();
    	$user = factory(User::class)->make();
    	$response = $this->post('register',[
    		'name' => $user->name,
    		'email' => $user->email,
    		'password' => 'secret',
    		'password_confirmation' => 'invalid',
    		'provider' => $user->provider_id,
        	'provider_id' => $user->provider_id
    	]);
    	$response->assertSessionHasErrors();
    	$this->assertGuest();
    }
}
