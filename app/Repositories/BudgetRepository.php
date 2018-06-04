<?php

namespace App\Repositories;

use App\Budget;
use App\User;
use App\Repositories\CategoryRepository;

class BudgetRepository {

	protected $categoryRepository;
	protected $budget;
	public function __construct() {
		$this -> categoryRepository = new CategoryRepository();
	}

	public function setBudget($id) {
		$this -> budget = Budget::find($id);
	}

	public function getAll(User $user) {
		return $user -> budgets() -> orderBy('date', 'asc') -> paginate(5);
	}

	public function getBudgetSummary(User $user){
		$budgets = $user -> budgets() -> orderBy('date', 'asc')->getResults();
		return $this->prepareBudgetSummary($budgets);
	}

	public function getMonthBudgetSummary(User $user, string $date){
		$budgets = $user -> budgets() -> where('date', 'like', $date) -> orderBy('date', 'asc')->getResults();
		return $this->prepareBudgetSummary($budgets);
	}

	public function createBudget(array $data) {
		return User::find($data['id']) -> budgets() -> create(['date' => $data['date']]);
	}

	public function getCashflows(Budget $budget) {
		return $budget -> cashflows() -> get();
	}

	public function getRevenues(Budget $budget) {
		return $this -> getCashflowsByType($budget, true);
	}

	public function getExpenses(Budget $budget) {
		return $this -> getCashflowsByType($budget, false);
	}

	private function getCashflowsByType(Budget $budget, bool $type) {
		$cashflows = $this -> getCashflows($budget);
		$cashflowsByType = collect();
		foreach ($cashflows as $cashflow) {
			$category = $this -> categoryRepository -> find($cashflow -> getOriginal('category_id'));
			if ($category -> type == $type) {
				$cashflowsByType -> push($cashflow);
			}
		}
		return $cashflowsByType;
	}

	public function getSumOfCashflows($cashflows) {
		$sumOfCashflows = 0;
		foreach ($cashflows as $cashflow) {
			$sumOfCashflows += $cashflow -> amount;
		}
		return $sumOfCashflows;
	}

	public function getSumOfRevenues($budgetId) {
		$this -> setBudget($budgetId);
		$revenues = $this -> getCashflowsByType($this -> budget, true);
		return $this -> getSumOfCashflows($revenues);

	}

	public function getSumOfExpenses($budgetId) {
		$this -> setBudget($budgetId);
		$expenses = $this -> getCashflowsByType($this -> budget, false);
		return $this -> getSumOfCashflows($expenses);

	}

	public function delete(Budget $budget) {
		$budget -> delete();
	}

	private function prepareBudgetSummary($budgets){
		$budgetSummary = [];
		foreach($budgets as $budget){
			$revenues = $this->getSumOfRevenues($budget->id);
			$expenses = $this->getSumOfExpenses($budget->id);
			$budgetSummary[] = array('date' => $budget->date, 'revenues' => $revenues, 'expenses' => $expenses);
		}
		return $budgetSummary;
	}
}
