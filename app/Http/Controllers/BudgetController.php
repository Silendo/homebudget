<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Repositories\BudgetRepository;
use App\Repositories\CategoryRepository;
use App\Http\Requests\BudgetFormRequest;
use App\Budget;
use App\Cashflow;

class BudgetController extends Controller {
	protected $budgetRepository;
	protected $categoryRepository;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(BudgetRepository $budgetRepository, CategoryRepository $categoryRepository) {
		$this -> budgetRepository = $budgetRepository;
		$this -> categoryRepository = $categoryRepository;
		$this -> middleware('auth');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(BudgetFormRequest $request) {
		$user = $request -> user();
		$budget = $this -> budgetRepository -> createBudget(['id' => Auth::id(), 'date' => $request -> input('date'), ]);
		return redirect('/budget/' . $budget -> id);
	}

	public function update(Request $request, Budget $budget) {
                $this -> authorize('update', $budget);
		$budget -> date = $request -> input('date');
		$budget -> save();
		return response() -> json(['datetext' => $budget -> date, 'date' => $budget -> getOriginal('date')]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Budget $budget) {
		$user = $request -> user();
		$cashflows = $this -> budgetRepository -> getCashflows($budget);

		$revenues = $this -> budgetRepository -> getRevenues($budget);
		$expenses = $this -> budgetRepository -> getExpenses($budget);
		$sumOfRevenues = $this -> budgetRepository -> getSumOfCashflows($revenues);
		$sumOfExpenses = $this -> budgetRepository -> getSumOfCashflows($expenses);

		$revenuesCategories = $this -> categoryRepository -> getRevenuesCategories($user);
		$expensesCategories = $this -> categoryRepository -> getExpensesCategories($user);
		$balance = round($sumOfRevenues - $sumOfExpenses,2);
		return view('budgets.index', ['budget' => $budget, 'date' => $budget -> getOriginal('date'), 'revenues_categories' => $revenuesCategories, 'expenses_categories' => $expensesCategories, 'revenues' => $revenues, 'expenses' => $expenses, 'revenues_sum' => $sumOfRevenues, 'expenses_sum' => $sumOfExpenses, 'balance' => $balance]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Budget $budget) {
		$this -> authorize('destroy', $budget);
		$this -> budgetRepository -> delete($budget);
		return response() -> json();
	}

}
