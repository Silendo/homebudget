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
	public function __construct(CashflowRepository $cashflowRepository, BudgetRepository $budgetRepository) {
		$this -> cashflowRepository = $cashflowRepository;
		$this -> budgetRepository = $budgetRepository;
	}

	/**
	 * Create new cashflow.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CashflowFormRequest $request) {
		$cashflow = $this -> cashflowRepository -> createCashflow($request -> all());
		$cashflow = Cashflow::find($cashflow -> id);
		$sumOfRevenues = $this -> budgetRepository -> getSumOfRevenues($cashflow -> budget_id);
		$sumOfExpenses = $this -> budgetRepository -> getSumOfExpenses($cashflow -> budget_id);
		return response() -> json(['cashflow' => $cashflow, 'category_id' => $cashflow -> getOriginal('category_id'), 'revenues_summary' => $sumOfRevenues, 'expenses_summary' => $sumOfExpenses]);
	}

	/**
	 *
	 */
	public function update(CashflowFormRequest $request, Cashflow $cashflow) {
                $this->authorize('update',$cashflow);
		$cashflow -> name = $request -> name;
		$cashflow -> category_id = $request -> category_id;
		$cashflow -> amount = $request -> amount;
		$cashflow -> save();
		$sumOfRevenues = $this -> budgetRepository -> getSumOfRevenues($cashflow -> budget_id);
		$sumOfExpenses = $this -> budgetRepository -> getSumOfExpenses($cashflow -> budget_id);
		return response() -> json(['cashflow' => $cashflow, 'category_id' => $cashflow -> getOriginal('category_id'), 'revenues_summary' => $sumOfRevenues, 'expenses_summary' => $sumOfExpenses]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
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
