<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Input;
use DB;
use App\User;
use App\UserSetting;
use App\StockData;
use App\StockDataRow;
use App\UserUsage;

class UsageController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Usage Controller
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

		$start = Input::get('start');
		$end = Input::get('end');

		echo $start . '-' . $end;

		$usages = DB::table('user_usage')
                     ->select(DB::raw('count(*) as `usage`, user_id'));

        if(!empty($start)) $usages = $usages->where('created_at', '>=', $start);
        if(!empty($end)) $usages = $usages->where('created_at', '<=', $end);

        $usages = $usages->groupBy('user_id')
        				->get();

		return view('usage.index')
					->with('title', 'Usages')
					->with('usages', $usages)
					->with("start", $start)
					->with('end', $end);
	}
}
