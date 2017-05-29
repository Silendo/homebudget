<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Category;

class CategoryFormRequest extends FormRequest {
	public function __construct() {
	}

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
		$this -> default = round($this -> default, 2);
		return ['name' => 'required|max:255|unique:categories,name,' . $this -> category_id . ',id,user_id,' . $this -> user() -> id, 'default' => 'nullable|numeric'];
	}

}
