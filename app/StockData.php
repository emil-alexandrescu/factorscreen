<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockData extends Model{

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'stockdata';

	public static $validation_rule = ['title' => 'required', 'file' => 'required'];

	public function stockdata_rows() {
		return $this->hasMany('App\StockDataRow');
	}
}
