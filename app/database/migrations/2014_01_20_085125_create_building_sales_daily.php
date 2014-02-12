<?php

use Illuminate\Database\Migrations\Migration;

class CreateBuildingSalesDaily extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// create building_sales_daily table
        Schema::create('building_sales_dailies', function($table) 
        {
            $table->increments('id');
            $table->integer('bps_id')->unsigned()->nullable();
            $table->string('name', 45);
            $table->string('region', 64);
            $table->integer('qty');
            $table->decimal('price_average', 10, 2);
            $table->decimal('area_average', 10, 2);
            $table->string('type', 24);
            $table->decimal('area', 10, 2);
            $table->date('sales_date');
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
        Schema::drop('building_sales_dailies');
	}
}
