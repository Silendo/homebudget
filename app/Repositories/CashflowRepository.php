<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Cashflow;

class CashflowRepository {
	public function createCashflow(array $data) {
		return Cashflow::create($data);
	}

	public function deleteCashflow(Cashflow $cashflow) {
		$cashflow -> delete();
	}

}
