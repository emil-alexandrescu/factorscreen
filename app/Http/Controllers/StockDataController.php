<?php namespace App\Http\Controllers;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

use Auth;
use Validator;
use Input;
use App\User;
use App\UserSetting;
use App\StockData;
use App\StockDataRow;
use App\UserUsage;

class StockDataController extends Controller {

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
		$this->middleware('auth');
	}

	/**
	 * Show the list of stockdata to the user.
	 *
	 * @return Response
	 */
	public function index() {
		return view('stockdata.index')
					->with('title', 'Stock Data')
					->with('stockdata', StockData::get());
	}

    public function listData() {
		echo json_encode(StockData::get());
		exit();
    }

	public function create() {
		return view('stockdata.create')
					->with('title', 'Upload new Stock Data');
	}

	public function store() {

		$validator = Validator::make( Input::all(), StockData::$validation_rule );

		$response = array(
			'status' => 1,
		);
		if ($validator->passes()) {

			$filename = time() . '.csv';
			Input::file('file')->move(base_path() . '/data', $filename);

			$config = new LexerConfig();
			$lexer = new Lexer($config);

			$interpreter = new Interpreter();

			$row = 0;
			$stockdata = null;
			$interpreter->addObserver(function(array $columns) use (&$row, &$stockdata) {
				if($row == 0) {
					if(count($columns) != 28) {
					}
					else {
						$valid = true;
						$stockdata = new StockData;
						$stockdata->user_id = Auth::user()->id;
						$stockdata->title = Input::get('title');

						$stockdata->save();
					}
				}
				else if($stockdata) {
					$stockdata_row = new StockDataRow;

					$tmp = explode(' ', $columns[3]);
					$stockdata_row->stock_data_id = $stockdata->id;
					$stockdata_row->rank = $columns[0];
					$stockdata_row->symbol = $columns[1];
					$stockdata_row->name = $columns[2];
					$stockdata_row->exchange = trim($tmp[0]);
					$stockdata_row->country = trim(str_replace('(', '', str_replace(')', '', $tmp[1])));
					$stockdata_row->industry = $columns[4];
					$stockdata_row->market_cap = $columns[5];
					$stockdata_row->z_score = $columns[6];
					$stockdata_row->m_score = $columns[7];
					$stockdata_row->f_score = $columns[8];
					$stockdata_row->pe_ratio = $columns[9];
					$stockdata_row->forward_pe = $columns[10];
					$stockdata_row->forward_growth = $columns[11];
					$stockdata_row->pb_ratio = $columns[12];
					$stockdata_row->asset_growth = $columns[13];
					$stockdata_row->ret_1y = $columns[14];
					$stockdata_row->off_max_15 = $columns[15];
					$stockdata_row->roe = $columns[16];
					$stockdata_row->basic_nri_pct_diff = $columns[17];
					$stockdata_row->eps_rsd = $columns[18];
					$stockdata_row->eps_gr = $columns[19];
					$stockdata_row->ss_demand = $columns[20];
					$stockdata_row->ss_util = $columns[21];
					$stockdata_row->accruals = $columns[22];
					$stockdata_row->roa = $columns[23];
					$stockdata_row->issuance = $columns[24];
					$stockdata_row->noa = $columns[25];
					$stockdata_row->profitability = $columns[26];
					$stockdata_row->beta = $columns[27];

					$stockdata_row->save();
				}

				$row++;
			});

			$lexer->parse(base_path() . '/data/' . $filename, $interpreter);


			if($stockdata) {
				$response['stockdata'] = $stockdata;
				$response['message'] = $row . " rows inserted.";
			}
			else {
				$response['status'] = 0;
				$response['message'] = "There's an error in the CSV file. Please download the template file.";
			}
		}
		else {
			$response['status'] = 0;
			$response['message'] = $validator->messages()->first();
		}

		echo json_encode($response);
	}

	public function remove($id) {
		$stockdata = StockData::find($id);

		if($stockdata == null) {
			return redirect('stockdata')
	        				->with('notice', ['title' => 'Error!', 'text' => 'Stock data not found.']);
		}

		$stockdata->delete();

		return redirect('stockdata')
	        				->with('notice', ['title' => 'Stock data removed', 'text' => 'You have removed stock data.']);
	}

	public function show($id, $format = '') {
		$stockdata = StockData::with('stockdata_rows')
							->find($id);
		if($stockdata == null) {
			if($format == 'json') {
				echo json_encode([]);
				exit();
			}
			return redirect('stockdata')
	        				->with('notice', ['title' => 'Error!', 'text' => 'Stock data not found.']);
		}
		if($format == 'json') {
			echo json_encode($stockdata->stockdata_rows);
			exit();
		}
		return view('stockdata/show')
					->with('title', 'Stock data : ' . $stockdata->name)
					->with('stockdata', $stockdata)
					->with('tableRowsCount', UserSetting::getSetting(Auth::user()->id, 'STOCKDATA_DETAIL_TABLE_ROWS'));
	}
}
