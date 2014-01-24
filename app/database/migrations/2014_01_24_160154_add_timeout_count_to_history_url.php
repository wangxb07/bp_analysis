<?php

use Illuminate\Database\Migrations\Migration;

class AddTimeoutCountToHistoryUrl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // add timeout count column to history_urls
        Schema::table('history_urls', function($table)
        {
            $table->smallInteger('timeout_count')->unsigned()->default(0)->after('grabbed');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('history_urls', function($table)
        {
            $table->dropColumn('timeout_count');
        });
	}

}
