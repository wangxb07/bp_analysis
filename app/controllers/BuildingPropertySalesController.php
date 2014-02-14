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
	public function getIndex()
	{
        $region = Input::get('region');
        $salesDate = Input::get('sales_date');

        $sales = BuildingPropertySales::ofRegion($region)
            ->ofSalesDate($salesDate)
            ->orderBy('sales_date', 'DESC')
            ->paginate(12);

        return View::make('buildingpropertysales.index')->with('sales', $sales);
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

    public function getRegion($region)
    {
        $total = $this->buildTotalSummary();
        $regionTotal = $this->buildTotalSummary($region);

        $properties = BuildingSalesDaily::select(DB::raw('name, SUM(qty) as total_qty, AVG(price_average) as price_avg'))
            ->ofRegion($region)->groupBy('name')->orderBy('total_qty', 'DESC')->get();

        return View::make('buildingpropertysales.region')
            ->with('region', $region)
            ->with('total', $total)
            ->with('regionTotal', $regionTotal)
            ->with('properties', $properties);
    }
    
    private function buildTotalSummary($region = '') {
        $total = new stdClass;

        $total->totalQty = 0;
        $total->totalArea = 0;
        $total->totalSalesQty = 0;
        $total->totalSalesArea = 0;
        $total->totalSalesAvg = 0;

        if (empty($region)) {
            $newest = BuildingPropertySales::ofRegion($region)
                ->orderBy('sales_date', 'DESC')->first();
            $newsetDate = $newest->sales_date;
            
            $total->totalQty = BuildingPropertySales::ofSalesDate($newsetDate)->sum('total_qty');
            $total->totalArea = BuildingPropertySales::ofSalesDate($newsetDate)->sum('total_area');
        }
        else {
            $newest = BuildingPropertySales::ofRegion($region)
                ->orderBy('sales_date', 'DESC')->first();
            $total->totalQty = $newest->total_qty;
            $total->totalArea = $newest->total_area;
        }

        $total->totalSalesQty = BuildingPropertySales::ofRegion($region)->sum('sales_qty');
        $total->totalSalesArea = BuildingPropertySales::ofRegion($region)->sum('sales_area');
        $total->totalSalesAvg = BuildingPropertySales::ofRegion($region)->avg('sales_average');

        return $total;
    }
}
