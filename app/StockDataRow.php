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
		'z_score_rank' => 'float',
		'm_score_rank' => 'float',
		'f_score_rank' => 'float',
		'pe_ratio_rank' => 'float',
		'forward_pe_rank' => 'float',
		'forward_growth_rank' => 'float',
		'pb_ratio_rank' => 'float',
		'asset_growth_rank' => 'float',
		'ret_1y_rank' => 'float',
		'off_max_15_rank' => 'float',
		'roe_rank' => 'float',
		'basic_nri_pct_diff_rank' => 'float',
		'eps_rsd_rank' => 'float',
		'eps_gr_rank' => 'float',
		'ss_demand_rank' => 'float',
		'ss_util_rank' => 'float',
		'accruals_rank' => 'float',
		'roa_rank' => 'float',
		'issuance_rank' => 'float',
		'noa_rank' => 'float',
		'profitability_rank' => 'float',
		'beta_rank' => 'float'
	);
}
