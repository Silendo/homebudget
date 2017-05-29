<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UserRepository;
use App\Repositories\BudgetRepository;

class UserController extends Controller {
	protected $userRepository;
	protected $budgetRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(UserRepository $userRepository, BudgetRepository $budgetRepository) {
		$this -> middleware('auth');
		$this -> userRepository = $userRepository;
		$this -> budgetRepository = $budgetRepository;
	}

	/**
	 * Display profile of authenticated user.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$user = Auth::user();
		$budgets = $this -> budgetRepository -> getAll($user);
		return view('users.index', ['user' => $user, 'budgets' => $budgets, 'now' => Carbon::now() -> format('Y-m'), ]);
	}

}
