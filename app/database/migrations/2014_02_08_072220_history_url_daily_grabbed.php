<?php

use Illuminate\Database\Migrations\Migration;

class HistoryUrlDailyGrabbed extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// add daily_grabbed column to history_urls
        Schema::table('history_urls', function($table)
        {
            $table->boolean('daily_grabbed')->default(false)->after('grabbed');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop columns of this migration
        Schema::table('history_urls', function($table)
        {
            $table->dropColumn('daily_grabbed');
        });
	}

}
