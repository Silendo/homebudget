<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashflowFormRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		if ($this -> has('add_revenue'))
			$this -> errorBag = 'revenues_errors';
		else
			$this -> errorBag = 'expenses_errors';
		$this -> amount = round($this -> amount, 2);
		return ['name' => 'required|max:255', 'amount' => 'required|numeric'];
	}

}
