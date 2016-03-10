<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StockDataRow extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'stockdata_row';

	protected $casts = array(
		'market_cap' => 'float',
		'z_score' => 'float',
		'm_score' => 'float',
		'f_score' => 'float',
		'pe_ratio' => 'float',
		'forward_pe' => 'float',
		'forward_growth' => 'float',
		'pb_ratio' => 'float',
		'asset_growth' => 'float',
		'ret_1y' => 'float',
		'off_max_15' => 'float',
		'roe' => 'float',
		'basic_nri_pct_diff' => 'float',
		'eps_rsd' => 'float',
		'eps_gr' => 'float',
		'ss_demand' => 'float',
		'ss_util' => 'float',
		'accruals' => 'float',
		'roa' => 'float',
		'issuance' => 'float',
		'noa' => 'float',
		'profitability' => 'float',
		'beta' => 'float'
	);
}
