<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class AppController {

	public function __construct() {
		# Activate User(s) on $status === "active"
		$this->activate("User", "status", "active", null, null);
	}

	/**
	 * Activate model items on field === value.
	 *
	 * @param String $model Model name (in title case) e.g. User
	 * @param String $on_key Model table field
	 * @param String $on_value Table field possible value
	 * @param String $operator Use custom operator
	 * @param Int $id Activate a specific item by ID
	 * @uses __construct() Check uses
	 */
	function activate(String $model, String $on_key, String $on_value, String $operator = null, Int $id = null) {
		$cls = "App\Models\\" . Str::title($model);
		if (class_exists($cls)) {
			$model = new $cls;
			if ($id > 0 and $item = $model->find($id)) {
				$item->active = true;
				$item->save();
			} else if ($model->all()->where($on_key, $operator, $on_value)->where('active', 0)->isNotEmpty()) {
				$model->all()->where($on_key, $operator, $on_value)->where('active', 0)->each(function ($item) {
					$item->active = true;
					$item->save();
				});
			}
		}
	}
}
