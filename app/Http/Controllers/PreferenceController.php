<?php namespace App\Http\Controllers;

use Auth;
use Input;
use App\User;
use App\UserSetting;

class PreferenceController extends Controller {
	public function update($settings_key) {
		UserSetting::setSetting(Auth::user()->id, $settings_key, Input::get('value'));

		echo '{}';
	}
}