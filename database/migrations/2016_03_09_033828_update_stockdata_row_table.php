<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStockdataRowTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('stockdata')->truncate();
        Schema::dropIfExists('stockdata_row');
        Schema::create('stockdata_row', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_data_id')->length(11)->unsigned()->nullable();
            $table->integer('rank')->length(11)->unsigned()->nullable();
            $table->string('symbol')->length(16)->nullable();
            $table->string('name')->length(255)->nullable();
            $table->string('exchange')->length(32)->nullable();
            $table->string('country')->length(30)->nullable();
            $table->string('industry')->length(255)->nullable();
            $table->float('market_cap')->nullable();
            $table->float('z_score')->nullable();
            $table->float('m_score')->nullable();
            $table->float('f_score')->nullable();
            $table->float('pe_ratio')->nullable();
            $table->float('forward_pe')->nullable();
            $table->float('forward_growth')->nullable();
            $table->float('pb_ratio')->nullable();
            $table->float('asset_growth')->nullable();
            $table->float('ret_1y')->nullable();
            $table->float('off_max_15')->nullable();
            $table->float('roe')->nullable();
            $table->float('basic_nri_pct_diff')->nullable();
            $table->float('eps_rsd')->nullable();
            $table->float('eps_gr')->nullable();
            $table->float('ss_demand')->nullable();
            $table->float('ss_util')->nullable();
            $table->float('accruals')->nullable();
            $table->float('roa')->nullable();
            $table->float('issuance')->nullable();
            $table->float('noa')->nullable();
            $table->float('profitability')->nullable();
            $table->float('beta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stockdata_row');
    }

}
