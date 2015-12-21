<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Input;
use App\User;
use App\UserSetting;
use App\StockData;
use App\StockDataRow;
use App\UserUsage;
use Hash;
use Mail;

class UserController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| StockData Controller
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('admin');
	}

	/**
	 * Show the list of stockdata to the user.
	 *
	 * @return Response
	 */
	public function index() {
		return view('users.index')
					->with('title', 'Users')
					->with('users', User::with('usage')
										->orderBy('name', 'asc')
										->get());
	}

	public function destroy($id) {
		$user = User::find($id);

		if($user == null) {
			return redirect('users')
	        				->with('notice', ['title' => 'Error!', 'text' => 'User not found.']);
		}

		$user->delete();

		return redirect('users')
	        				->with('notice', ['title' => 'User removed', 'text' => 'You have removed user.']);
	}

	public function show($id) {
		$user = User::with('usage')
					->find($id);
		if($user == null) {
			return redirect('user')
	        				->with('notice', ['title' => 'Error!', 'text' => 'User not found.']);
		}
		return view('users.show')
					->with('title', 'User : ' . $user->name)
					->with('user', $user);
	}

	public function login($id) {
		$user = User::with('usage')
					->find($id);
		if($user == null) {
			return redirect('user')
	        				->with('notice', ['title' => 'Error!', 'text' => 'User not found.']);
		}

		Auth::loginUsingId($id);

		return redirect('home');
	}

	public function create() {
		return view('users.create')
					->with('title', 'Invite new User');
	}

	public function store() {
		$validator = Validator::make(Input::all(), [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
			'password' => 'required|confirmed|min:6',
		]);

		if($validator->passes()) {
			$user = new User;
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->password = Hash::make(Input::get('password'));
			$user->status = 1;
			$user->save();

			Mail::send('emails.invite', ['email' => $user->email, 'password' => Input::get('password'), 'name' => $user->name], function($message) use($user)
			{
			    $message->to($user->email, $user->name)->subject('You are invited to factorscreen.com!');
			});

			return redirect()->route('users.edit', array('id' => $user->id))
						->with('notice', ['title' => 'User added', 'text' => 'You have invited new user.']);
		}
		else {
			$messages = [ ["type" => "danger", "text" => $validator->messages()->first()] ];
			return redirect('users/create')
						->withInput()
						->with('messages', $messages);
		}
	}

	public function edit($id) {
		$user = User::find($id);

		if($user == NULL) {
			return redirect('user')
	        				->with('notice', ['title' => 'Error!', 'text' => 'User not found.']);
		}

		return view('users.edit')
					->with('user', $user);
	}

	public function update($id) {

		$user = User::find($id);

		if($user == NULL) {
			return redirect('user')
	        				->with('notice', ['title' => 'Error!', 'text' => 'User not found.']);
		}
		$validator = Validator::make(Input::all(), [
			'name' => 'required|max:255',
			'password' => 'confirmed|min:6',
		]);

		$user->name = Input::get('name');
		$password = Input::get('password');
		if(!empty($password)) $user->password = Hash::make($password);

		if($validator->passes()) {
			$user->save();

			return redirect()->route('users.edit', array('id' => $user->id))
						->with('notice', ['title' => 'User updated', 'text' => 'You have updated user.']);

		}
		else {
			$messages = [ ["type" => "danger", "text" => $validator->messages()->first()] ];
			return view('users.edit')
						->with('user', $user)
						->with('messages', $messages);
		}
	}
}