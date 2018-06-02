<?php

namespace Tests\Feature;

use App\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
	use DatabaseTransactions;
	
    /**
     * Password reset request form can be displayed.
     *
     * @return void
     */
    public function testPasswordResetRequestFormDisplayed()
    {
        $response = $this->get('password/reset');
        $response->assertStatus(200);
    }

    /**
     * If user exists, email is sent.
     *
     * @return void
     */
    public function testResetEmailSent()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create();
        $this->expectsNotification($user, ResetPasswordNotification::class);
        $response = $this->post('password/email', ['email' => $user->email]);
        $response->assertStatus(302);
    }

    /** * If user does not exist, email is not sent.
     *
     * @return void
     */
    public function testResetEmailNotSent()
    {
        $this->withoutMiddleware();
        $this->doesntExpectJobs(ResetPasswordNotification::class);
        $response = $this->post('password/email', ['email' => 'invalid@example.org']);
        $response->assertStatus(302);
    }

    /**
     * Password reset form can be displayed.
     *
     * @return void
     */
    public function testPasswordResetFormDisplayed()
    {
        $response = $this->get('password/reset/token');
        $response->assertStatus(200);
    }

    /**
     * User can reset password.
     *
     * @return void
     */
    public function testPasswordReseting()
    {
        $user = factory(User::class)->create();
        $token = Password::createToken($user);
        $response = $this->post('password/reset', [
        	'token' => $token,
        	'email' => $user->email,
        	'password' => 'secret',
        	'password_confirmation' => 'secret'
        ]);
        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }
}
