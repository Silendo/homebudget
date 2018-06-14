<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use Exception;
use Auth;

use App\Repositories\UserRepository;
use App\User;

class GoogleController extends Controller
{
    protected $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Redirect to Google.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->getId())->first();
            if(!$user){
                $user =$this->userRepository->createGoogleUser($googleUser);
            }
            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            report($e);
            return redirect('register')->withErrors(['socialite' => 'User registration with Google failed. Try again.']);
        }
    }
}