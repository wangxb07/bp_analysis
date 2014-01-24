<?php

class HistoryUrl extends Eloquent implements HtmlExtractableInterface {
	protected $guarded = array();

	public static $rules = array();

    public function extract(HtmlExtracter $extracter) 
    {
        $html = $extracter->getHtmlDOM();
        
        foreach ($html->find('#news_body p a') as $element) {
            $data = array();
            $data['url'] = $element->href;
            $data['sales_date'] = $this->_getDateFromLink($element->href);
            
            $validator = Validator::make($data, array(
                'url' => 'required|url',
                'sales_date' => 'required|date_format:Y-m-d',
            ));
            if ($validator->fails()) {
                Log::error('data from extract validate fails, this data is ' . $element->href);
                continue;
            }
            else {
                $exist = self::where('url', $element->href)->count();
                if ($exist == 0) {
                    self::create($data);
                }
            }
        }
    }
    
    private function _getDateFromLink($link) {
        $matches = array();
        if (!preg_match('/\d{4}-\d{2}-\d{2}/', $link, $matches)) {
            return '';
        }
        // Date minus one
        $date = new DateTime(end($matches));
        $date->sub(new DateInterval('P1D'));
        return date_format($date, 'Y-m-d');
    }
}
