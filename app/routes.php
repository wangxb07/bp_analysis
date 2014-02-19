<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::group(array(), function()
{
    Route::get('/', array(
        'uses' => 'HomeController@index'
    ));
    Route::post('/grab/newest', array('uses' => 'HomeController@grabNewestInfo'));
});

// Route::controller('bp', 'BuildingPropertySalesController');
Route::group(array('prefix' => 'bp'), function()
{
    Route::get('/', 'BuildingPropertySalesController@getIndex');

    Route::get('/region/{region}', array('as' => 'bp_region', 'uses' => 'BuildingPropertySalesController@getRegion'));
});

Route::controller('history-url', 'HistoryUrlController');
Route::controller('daily-info', 'DailyInfoController');
