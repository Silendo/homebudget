<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesTable2 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('categories', function(Blueprint $table) {
			$table -> dropUnique(['name', ]);
			$table->unique(array('user_id', 'name'))->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('categories', function(Blueprint $table) {
			//
		});
	}

}
