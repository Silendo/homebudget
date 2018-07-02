<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Category;
use App\Repositories\CategoryRepository;
use App\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller {
	protected $category;

	/**
	* Create a new controller instance.
	*
	* @param \app\Repositories\CategoryRepository $categoryRepository
	* @return void
	*/
	public function __construct(CategoryRepository $categoryRepository) {
		$this -> categoryRepository = $categoryRepository;
	}

	/**
	 * Display list of categories.
	 *
	 * @param \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$user = $request -> user();
		//$local_revs = ['salary', 'divident', 'credit', 'scholarship', 'pension', 'gift', 'others'];
		//$local_exps = ['food', 'home', 'clothes', 'healthy', 'transport', 'education', 'entertainment', 'bills', 'others'];
		$revenuesCategories = $this -> categoryRepository -> getRevenuesCategories($user);
		$expensesCategories = $this -> categoryRepository -> getExpensesCategories($user);
		return view('categories.index', ['revenues' => $revenuesCategories, 'expenses' => $expensesCategories]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \app\Http\Requests\CategoryFormRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(CategoryFormRequest $request) {
		$user = $request -> user();
		$category = $this -> categoryRepository -> createCategory($request -> all());
		$category = $this -> categoryRepository -> find($category -> id);
		return response() -> json($category);
	}

	/**
	 * Update a resource in storage.
	 *
	 * @param  \app\Http\Requests\BudgetFormRequest $request
	 * @param  \app\Category $category
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(CategoryFormRequest $request, Category $category) {
                $this -> authorize('update', $category);
		$category = $this -> categoryRepository -> updateCategory($category -> id, $request -> all());
		return response() -> json(['name' => $category -> name, 'default' => $category -> default]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \app\Category $category
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(Request $request, Category $category) {
		$this -> authorize('destroy', $category);
		$this -> categoryRepository -> deleteCategory($category);
		return response() -> json();
	}
}
