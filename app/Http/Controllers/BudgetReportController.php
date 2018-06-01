<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Repositories\BudgetRepository;
use App\Mail\BudgetSummary;

class BudgetReportController extends Controller
{
    protected $budgetRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(BudgetRepository $budgetRepository) {
		$this -> budgetRepository = $budgetRepository;
		$this -> middleware('auth');
	}

	/**
	 * Send budget summary (revenues,expenses for each month).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function sendSummary(){
    	$user = Auth::user();
    	$budgetSummary = $this->budgetRepository->getBudgetSummary($user);
    	Mail::to($user->email)->send(new BudgetSummary($budgetSummary, $user));
    	return response()->json();
    }
}
