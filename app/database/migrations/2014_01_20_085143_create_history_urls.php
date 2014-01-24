<?php

use Illuminate\Database\Migrations\Migration;

class CreateHistoryUrls extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// create history_urls table
        Schema::create('history_urls', function($table) 
        {
            $table->increments('id');
            $table->integer('bps_id')->unsigned()->nullable();
            $table->string('url', 2048);
            $table->date('sales_date');
            $table->boolean('grabbed')->default(false);
            $table->timestamps();

            $table->foreign('bps_id')->references('id')->on('building_property_sales');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('history_urls');
	}

}
