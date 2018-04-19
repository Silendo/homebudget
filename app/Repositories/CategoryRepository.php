<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Category;
use App\User;

class CategoryRepository {
	protected $categories;

	public function getCategories(User $user) {
		return $this -> categories = $user -> categories() -> get();
	}

	private function getCategoriesByType(User $user, $type) {
		if ($this -> categories == null)
			$this -> getCategories($user);
		$categoriesByType = collect();
		foreach ($this->categories as $category) {
			if ($category -> type == $type) {
				$categoriesByType -> push($category);
			}
		}
		return $categoriesByType;
	}

	public function getRevenuesCategories(User $user) {
		return $this -> getCategoriesByType($user, "revenue");
	}

	public function getExpensesCategories(User $user) {
		return $this -> getCategoriesByType($user, "expense");
	}

	public function find($id) {
		return DB::table('categories') -> where('id', $id) -> first();
	}

	public function createCategory(array $data) {
		return Category::create($data);
	}

	public function updateCategory($id, array $data) {
		$category = Category::find($id);
		if ($category -> name != $data['name'])
			$category -> name = $data['name'];
		$category -> default = $data['default'];
		$category -> save();
		return $category;
	}

	public function deleteCategory($category) {
		$category -> delete();
	}

}
