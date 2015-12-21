<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Hash;
use Input;
use App\User;
use App\UserSetting;
use App\CampaignDomain;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		// return view('home')
		// 			->with('title', 'Dashboard');
		return redirect('stockdata');
	}


	public function settings() {
		return view('settings')
					->with('title', 'Settings')
					->with('user', Auth::user());
	}

	public function saveSettings() {
		$user = User::find( Auth::user()->id );

		$validator = Validator::make( Input::all(), User::$validation_rule );

		$user->name = Input::get('name');
		$password = Input::get("password");
		if(!empty($password)) $user->password = Hash::make($password);

		if ($validator->passes()) {
			if(Input::hasFile('photo') && Input::file('photo')->isValid()) {
				$extension = Input::file('photo')->getClientOriginalExtension();
				$user->photo = base64_encode(microtime()) . '.' . $extension;
				Input::file('photo')->move(base_path() . '/public' . User::PHOTO_FOLDER, $user->photo);
			}
	        $user->save();

	        return redirect('settings')
	        				->with('notice', ['title' => 'Profile updated', 'text' => 'You have updated your profile.']);
		}
		else {
			$messages = [ ["type" => "danger", "text" => $validator->messages()->first()] ];

			return view('settings')
						->with('title', 'Settings')
						->with('user', $user)
						->with('messages', $messages);
		}
	}
}
