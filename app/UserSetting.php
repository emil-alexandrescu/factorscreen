<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_settings';

	public static function getSetting($user_id, $key) {
		$setting = UserSetting::where('user_id', '=', $user_id)
								->where('settings_key', '=', $key)
								->first();
		if($setting == null) {
			$setting = UserSetting::where('user_id', '=', null)
				->where('settings_key', '=', $key)
				->first();

			if($setting == null) return null;
		}

		return $setting->settings_value;		
	}

	public static function setSetting($user_id, $key, $value) {
		$setting = UserSetting::where('user_id', '=', $user_id)
								->where('settings_key', '=', $key)
								->first();
									
		if($setting == null) {
			$setting = new UserSetting;
			$setting->user_id = $user_id;
		}

		$setting->settings_key = $key;
		$setting->settings_value = $value;
		$setting->save();
	}
}
