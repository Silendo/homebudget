<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();   
    }   

    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();   
		$user = $this->findOrCreateUser($providerUser, $provider);
		auth()->login($user, true);
		return redirect()->to('/user');
    }
	private function findOrCreateUser($providerUser, $provider){
		$user = User::where('provider_id', $providerUser->id)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $providerUser->id
                ]);
            }
			return $user;
	}
}
