<?php

class BuildingPropertySales extends Eloquent implements HtmlExtractableInterface {
	protected $guarded = array();

	public static $rules = array(
        'url' => 'required|url',
        'date' => 'required|date_format:Y-m-d',
    );

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
                        $row[] = $str;
                    }
                    else {
                        $row[] = $element->innertext;
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
}
