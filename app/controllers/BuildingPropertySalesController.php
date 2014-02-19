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

        $sales->appends(array(
            'region' => Input::get('region'),
            'sales_date' => Input::get('sales_date'),
        ));
        
        // get current sales date
        if (count($sales) > 0) {
            $summary = $this->buildTotalSummary($region, $sales[0]->sales_date);
            $salesDate = $sales[0]->sales_date;        
            $this->buildSalesQtyPieChart($salesDate);
        }

        return View::make('buildingpropertysales.index')->with('sales', $sales)
            ->with('summary', $summary)
            ->with('date', $salesDate);
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
     * region view action
     * @param string $region region name
     * @return void
     */
    public function getRegion($region)
    {
        $total = $this->buildTotalSummary();
        $regionTotal = $this->buildTotalSummary($region);

        $properties = BuildingSalesDaily::select(DB::raw('name, SUM(qty) as total_qty, AVG(price_average) as price_avg'))
            ->ofRegion($region)->groupBy('name')->orderBy('total_qty', 'DESC')->get();

        // building chart
        $this->buildRegionViewChart($region);

        return View::make('buildingpropertysales.region')
            ->with('region', $region)
            ->with('total', $total)
            ->with('regionTotal', $regionTotal)
            ->with('properties', $properties);
    }

    /**
     * build region view chart
     * @param string $region
     * @return void
     */
    private function buildRegionViewChart($region) 
    {
        $regiionSalesTable = Lava::DataTable('RegionSalesAvg');
     
        $regiionSalesTable->addColumn('string', 'Date', 'date')
            ->addColumn('number', 'SalesPrice', 'sales_price')
            ->addColumn('number', 'HouseSalesPrice', 'house_sales_price');
        
        $rows = BuildingPropertySales::select(DB::raw('avg(sales_average) as sales_avg, avg(house_sales_average) as house_sales_avg, concat(year(sales_date), "/", month(sales_date)) as sales_month'))
            ->ofRegion($region)
            ->groupBy(DB::raw('concat(year(sales_date), "/", month(sales_date))'))
            ->orderBy('sales_date')->get();

        foreach ($rows as $i => $row) {
            $data = array(
                $row->sales_month,
                round($row->sales_avg, 2), 
                round($row->house_sales_avg, 2),
            );
     
            $regiionSalesTable->addRow($data);
        }
     
        Lava::LineChart('RegionSalesAvg')->title('Region Sales Price Average');
    }

    /**
     * build pie chart for sales qty 
     */
    private function buildSalesQtyPieChart($salesDate)
    {
        $rows = BuildingPropertySales::ofSalesDate($salesDate)->where('sales_qty', '>', 0)->get();
        
        $table = Lava::DataTable('RegionSalesQty');
        $table->addColumn('string', 'Region', 'region')
            ->addColumn('number', 'Qty', 'sales_qty');
        
        foreach ($rows as $key => $row) {
            $table->addRow(array(
                $row->region,
                $row->sales_qty,
            ));
        }

        Lava::PieChart('RegionSalesQty')->title('Region Sales Qty');
    }
    
    /**
     * build total summary helper method
     * @param string $region
     * @param string $salesDate
     *
     * @return stdClass
     */
    private function buildTotalSummary($region = '', $salesDate = '') 
    {
        $total = new stdClass;

        $total->totalQty = 0;
        $total->totalArea = 0;
        $total->totalSalesQty = 0;
        $total->totalSalesArea = 0;
        $total->totalSalesAvg = 0;

        if (empty($region) && empty($salesDate)) {
            $newest = BuildingPropertySales::orderBy('sales_date', 'DESC')->first();
            $newsetDate = $newest->sales_date;
            
            $total->totalQty = BuildingPropertySales::ofSalesDate($newsetDate)->sum('total_qty');
            $total->totalArea = BuildingPropertySales::ofSalesDate($newsetDate)->sum('total_area');
        }
        elseif (!empty($salesDate) && empty($region)) {
            $total->totalQty = BuildingPropertySales::ofSalesDate($salesDate)->sum('total_qty');
            $total->totalArea = BuildingPropertySales::ofSalesDate($salesDate)->sum('total_area');
        }
        else {
            $newest = BuildingPropertySales::ofRegion($region)->ofSalesDate($salesDate)
                ->orderBy('sales_date', 'DESC')->first();
            $total->totalQty = $newest->total_qty;
            $total->totalArea = $newest->total_area;
        }

        $total->totalSalesQty = BuildingPropertySales::ofRegion($region)
            ->ofSalesDate($salesDate)->sum('sales_qty');
        $total->totalSalesArea = BuildingPropertySales::ofRegion($region)
            ->ofSalesDate($salesDate)->sum('sales_area');
        $total->totalSalesAvg = BuildingPropertySales::ofRegion($region)
            ->ofSalesDate($salesDate)->avg('sales_average');

        return $total;
    }
}
