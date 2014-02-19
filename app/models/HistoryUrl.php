<?php

class HistoryUrl extends Eloquent implements HtmlExtractableInterface {
	protected $guarded = array();

	public static $rules = array(
        'url' => 'required|unique:history_urls',
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

    public function extract(HtmlExtracter $extracter) 
    {
        $html = $extracter->getHtmlDOM();
        
        foreach ($html->find('#news_body p a') as $element) {
            $data = array();
            $data['url'] = $element->href;
            $data['sales_date'] = self::getDateFromLink($element->href);
            
            self::create($data);
        }
    }
    
    public static function getDateFromLink($link) {
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
