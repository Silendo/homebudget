<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Cashflow;
use App\Category;
use App\Budget;
use App\Repositories\CashflowRepository;
use App\Http\Requests\CashflowFormRequest;
use App\Repositories\BudgetRepository;

class CashflowController extends Controller {
	protected $cashflowRepository;
	protected $budgetRepository;

	/**
	* Create a new controller instance.
	*
	* @param \app\Repositories\CashflowRepository $cashflowRepository
	* @param \app\Repositories\BudgetRepository $budgetRepository
	* @return void
	*/
	public function __construct(CashflowRepository $cashflowRepository, BudgetRepository $budgetRepository) {
		$this -> cashflowRepository = $cashflowRepository;
		$this -> budgetRepository = $budgetRepository;
	}

	/**
	 * Create new cashflow.
	 *
	 * @param \app\Http\Requests\CashflowFormRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(CashflowFormRequest $request) {
		$cashflow = $this -> cashflowRepository -> createCashflow($request -> all());
		$category = Category::find($cashflow -> getOriginal('category_id'));
		$sumOfRevenues = $this -> budgetRepository -> getSumOfRevenues($cashflow -> budget_id);
		$sumOfExpenses = $this -> budgetRepository -> getSumOfExpenses($cashflow -> budget_id);
		return response() -> json(['cashflow' => $cashflow, 'category' => $category, 'revenues_summary' => $sumOfRevenues, 'expenses_summary' => $sumOfExpenses]);
	}

	/**
	 * Update a resource in storage.
	 *
	 * @param  \app\Http\Requests\CashflowFormRequest $request
	 * @param  \app\Cashflow $cashflow
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(CashflowFormRequest $request, Cashflow $cashflow) {
		$this->authorize('update', $cashflow);
		$cashflow -> name = $request -> name;
		$cashflow -> category_id = $request -> category_id;
		$cashflow -> amount = $request -> amount;
		$cashflow -> save();
		$category = Category::find($cashflow -> getOriginal('category_id'));
		$sumOfRevenues = $this -> budgetRepository -> getSumOfRevenues($cashflow -> budget_id);
		$sumOfExpenses = $this -> budgetRepository -> getSumOfExpenses($cashflow -> budget_id);
		return response() -> json(['cashflow' => $cashflow, 'category' => $category, 'revenues_summary' => $sumOfRevenues, 'expenses_summary' => $sumOfExpenses]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \app\Cashflow $cashflow
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Cashflow $cashflow) {
                $this->authorize('destroy',$cashflow);
		$this -> cashflowRepository -> deleteCashflow($cashflow);
		$sumOfRevenues = $this -> budgetRepository -> getSumOfRevenues($cashflow -> budget_id);
		$sumOfExpenses = $this -> budgetRepository -> getSumOfExpenses($cashflow -> budget_id);
		return response() -> json(['revenues_summary' => $sumOfRevenues, 'expenses_summary' => $sumOfExpenses]);
	}

}
