<?php

class BuildingPropertySales extends Eloquent implements HtmlExtractableInterface {
	protected $guarded = array();

	public static $rules = array(
        'region' => 'max:64',
        'total_qty' => 'required|numeric',
        'total_area' => 'required|numeric',
        'sales_qty' => 'required|numeric',
        'sales_area' => 'required|numeric',
        'sales_average' => 'required|numeric',
        'house_sales_average' => 'required|numeric',
        'sales_date' => 'required|date_format:Y-m-d',
    );

    public static function boot()
    {
        parent::boot();

        self::creating(function($sales)
        {
            if ( ! $sales->isValid()) return false;
        });
    }

    public function isValid()
    {
        $validator =  Validator::make(
            $this->toArray(),
            self::$rules
        );
        if ($validator->passes()) {
            return true;
        }
        else {
            Log::warning($validator->messages());
            return false;
        }
    }

    public function extract(HtmlExtracter $extracter) {
        $cursor = 0;
        $charset = $extracter->getCharset();
        $html = $extracter->getHtmlDOM();

        foreach ($html->find('#news_body table tr') as $element) {
            if ($cursor == 0) {
                $cursor ++;
                continue;
            }
            else {
                $row = array();
                foreach ($element->find('td') as $element) {

                    if (!empty($charset)) {
                        $str = mb_convert_encoding($element->innertext, 'utf-8', $charset);
                        $row[] = trim($str);
                    }
                    else {
                        $row[] = trim($element->innertext);
                    }
                }

                $salesInfo = array();
                list(
                    $salesInfo['region'],
                    $salesInfo['total_qty'],
                    $salesInfo['total_area'],
                    $salesInfo['sales_qty'],
                    $salesInfo['sales_area'],
                    $salesInfo['sales_average'],
                    $salesInfo['house_sales_average']) = $row;

                $salesInfo['sales_date'] = $this->sales_date;
                self::create($salesInfo);
                $cursor ++;
            }
        }
    }

    /**
     * Scope of region
     */
    public function scopeOfRegion($query, $region)
    {
        return $query->where('region', 'LIKE', $region . '%');
    }
    
    /**
     * Scope of sales date
     */
    public function scopeOfSalesDate($query, $salesDate)
    {
        return $query->where('sales_date', 'LIKE', $salesDate . '%');
    }
}
