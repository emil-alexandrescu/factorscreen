<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, SoftDeletes;

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


	public static $validation_rule = ['name' => 'required', 'password' => 'confirmed' ];

	const TYPE_USER = 0;
	const TYPE_ADMIN = 1;

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	const PHOTO_FOLDER = 'photo';
	
	public function settings() {
		return $this->hasMany('App\UserSetting');
	}

	public function stockdatas() {
		return $this->hasMany('App\StockData');
	}

	public function usage() {
		return $this->hasMany('App\UserUsage');
	}
}
