<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserUsage extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_usage';

	const TYPE_LOGIN = 0;
	const TYPE_UPLOAD = 1;

	public function user() {
		return $this->belongsTo('App\User');
	}
}
