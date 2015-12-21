<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Input;
use App\User;
use App\UserSetting;
use App\Website;
use App\Campaign;
use App\CampaignDomain;

class CampaignsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Campaigns Controller
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
	 * Show the list of campaigns to the user.
	 *
	 * @return Response
	 */
	public function index() {
		return view('campaigns.index')
					->with('title', 'Campaigns')
					->with('campaigns', Auth::user()->campaigns()
													->with('domains')
													->with('scrappings')
													->with('website')
													->get());
	}

	public function create() {
		return view('campaigns.create')
					->with('title', 'Create a New Campaign')
					->with('websites', Website::where('status', '=', 1)->get());
	}

	public function store() {

		$validator = Validator::make( Input::all(), Campaign::$validation_rule );

		if ($validator->passes()) {

			$campaign = new Campaign;
			$campaign->user_id = Auth::user()->id;
			$campaign->name = Input::get('name');
			$campaign->website_id = Input::get('website_id');
			$campaign->keywords = Input::get('keywords');
			$campaign->date_start = date('Y-m-d', strtotime(Input::get('date_start')));
			$campaign->date_end = date('Y-m-d', strtotime(Input::get('date_end')));
			$campaign->date_start = $campaign->date_start == '1970-01-01' ? null : $campaign->date_start;
			$campaign->date_end = $campaign->date_end == '1970-01-01' ? null : $campaign->date_end;

			$campaign->save();

	        return redirect('campaigns')
	        				->with('notice', ['title' => 'Campaign created', 'text' => 'You have created a new campaign.']);
		}
		else {
			$messages = [ ["type" => "danger", "text" => $validator->messages()->first()] ];

			return redirect('campaigns/create')
					->with('messages', $messages)
					->withInput();
		}
	}

	public function remove($id) {
		$campaign = Campaign::find($id);

		if($campaign == null) {
			return redirect('campaigns')
	        				->with('notice', ['title' => 'Error!', 'text' => 'Campaign not found.']);
		}

		$campaign->delete();

		return redirect('campaigns')
	        				->with('notice', ['title' => 'Campaign removed', 'text' => 'You have removed campaign.']);
	}

	public function show($id, $sort = 'domain', $dir = 'ASC') {
		$campaign = Campaign::with('scrappings')
							->with('website')
							->find($id);
		if($campaign == null || $campaign->status == Campaign::STATUS_CREATED || $campaign->status == Campaign::STATUS_IN_PROGRESS ) {
			return redirect('campaigns')
	        				->with('notice', ['title' => 'Error!', 'text' => 'Campaign not found.']);
		}
		$domain_count = $campaign->domains()
									->count();
		$campaign->domains = $campaign->domains()
										->orderBy($sort, $dir)
										->paginate(10);
		$fields = [
			'domain' => 'Domain',
			'created_at' => 'Scrapped on',
			'PA' => 'PA',
			'TF' => 'TF',
			'CF' => 'CF',
		];
		return view('campaigns/show')
					->with('title', 'Campaign : ' . $campaign->name)
					->with('campaign', $campaign)
					->with('domain_count', $domain_count)
					->with('sort', $sort)
					->with('dir', $dir)
					->with('fields', $fields);
	}

	public function export($id, $sort = 'domain', $dir = 'ASC') {
		$campaign = Campaign::find($id);
		if($campaign == null || $campaign->status == Campaign::STATUS_CREATED || $campaign->status == Campaign::STATUS_IN_PROGRESS ) {
			return redirect('campaigns')
	        				->with('notice', ['title' => 'Error!', 'text' => 'Campaign not found.']);
		}

		$campaign->domains = $campaign->domains()
										->orderBy($sort, $dir)
										->get();

		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=" . $campaign->name . ".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo '"' . implode('","', ['Domain', 'Scrapped on', 'PA', 'TF', 'CF']) . '"' . PHP_EOL;
		foreach($campaign->domains as $i => $domain) {
			echo '"' . implode('","', [$domain->domain, date('m/d/Y', strtotime($domain->created_at)), $domain->PA, $domain->TF, $domain->CF]) . '"' . PHP_EOL;
		}

	}
	public function remove_domain($id, $domain_id, $sort = 'domain', $dir = 'ASC') {
		$campaign = Campaign::find($id);
		if($campaign == null || $campaign->status == Campaign::STATUS_CREATED || $campaign->status == Campaign::STATUS_IN_PROGRESS ) {
			return redirect('campaigns')
	        				->with('notice', ['title' => 'Error!', 'text' => 'Campaign not found.']);
		}

		$domain = CampaignDomain::find($domain_id);
		if($domain == null) {
			return redirect('campaigns/' . $id . '/' . $sort . '/' . $dir)
	        				->with('notice', ['title' => 'Error!', 'text' => 'Domain not found.']);
		}

		$domain->delete();

		return redirect('campaigns/' . $id . '/' . $sort . '/' . $dir)
	        				->with('notice', ['title' => 'Domain removed', 'text' => 'You have removed domain.']);
	}

	public function favorite($id, $domain_id, $favorited) {
		$domain = CampaignDomain::find($domain_id);

		$response = ['success' => false];
		if($domain != null) {
			$domain->favorited = $favorited;

			$domain->save();

			$response['success'] = true;
		}

		echo json_encode($response);

		exit();
	}
}