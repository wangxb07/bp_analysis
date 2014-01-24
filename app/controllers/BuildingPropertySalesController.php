<?php

class BuildingPropertySalesController extends BaseController 
{
    /**
     * Instantiate a new BuildingPropertySalesController instance.
     */
    public function __construct() 
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //return View::make('buildingpropertysales.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('buildingpropertysales.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return View::make('buildingpropertysales.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('buildingpropertysales.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
    
    /**
     * Manual fetch form 
     *
     * @retrun Response
     */
    public function getManualFetch() 
    {
        return View::make('buildingpropertysales.manual_fetch');
    }

    public function postManualFetch()
    {
        $sendResponse = function() {
            return Redirect::action('BuildingPropertySalesController@getManualFetch')
            ->with('flash_messages', $this->messages);
        };

        $rules = array(
            'url' => 'required|url',
            'date' => 'required|date_format:Y-m-d',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return $sendResponse()->withErrors($validator);
        }

        $url = Input::get('url');
        $salesDate = Input::get('date');
        $charset = Input::get('charset');
        $contents = $this->getContents($url);

        if (!$contents) {
            return $sendResponse();
        }

        $htmlExtracter = new HtmlExtracter();

        $htmlExtracter->fillHtmlDom($contents);
        $htmlExtracter->setCharset($charset);

        $htmlExtracter->bindModel(function() use ($salesDate)
        {
            $model = new BuildingPropertySales;
            $model->sales_date = $salesDate;
            return $model;
        });

        $htmlExtracter->extract();

        $this->pushMessage('success', 'Fetch data successed!');

        return $sendResponse();
    }
}
