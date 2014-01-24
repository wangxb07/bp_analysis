<?php

use Illuminate\Database\Migrations\Migration;

class CreateRegionBuilding extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// create region building property table
        Schema::create('building_property_sales', function($table)
        {
            $table->increments('id');
            $table->string('region', 64);
            $table->integer('total_qty')->unsigned()->default(0);
            $table->decimal('total_area', 10, 2)->unsigned()->default(0);
            $table->integer('sales_qty')->unsigned()->default(0);
            $table->decimal('sales_area', 10, 2)->unsigned()->default(0);
            $table->decimal('sales_average', 10, 2)->unsigned()->default(0);
            $table->decimal('house_sales_average', 10, 2)->unsigned()->default(0);
            $table->date('sales_date');
            $table->timestamps();

            $table->unique(array('region', 'sales_date'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//drop regin building property table
        Schema::drop('building_property_sales');
	}

}
