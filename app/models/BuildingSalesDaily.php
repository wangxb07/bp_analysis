<?php

class BuildingSalesDaily extends Eloquent implements HtmlExtractableInterface {
	protected $guarded = array();

	public static $rules = array(
        'name' => 'max:45',
        'region' => 'max:64',
        'qty' => 'required|numeric',
        'price_average' => 'required|numeric',
        'area_average' => 'required|numeric',
        'type' => 'max:24',
        'area' => 'required|numeric',
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
        return Validator::make(
            $this->toArray(),
            self::$rules
        )->passes();
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
                if (count($row) == 7) {
                    list(
                        $salesInfo['name'],
                        $salesInfo['region'],
                        $salesInfo['qty'],
                        $salesInfo['price_average'],
                        $salesInfo['area_average'],
                        $salesInfo['type'],
                        $salesInfo['area']) = $row;

                    $salesInfo['sales_date'] = $this->sales_date;

                    self::create($salesInfo);
                }
                $cursor ++;
            }
        }
    }
}
