<?php

/**
 * @class
 * SalesHtmlGrabberQueue
 */

class SalesHtmlGrabberQueue extends HtmlGrabberQueue {
    /**
     * Initialization of Queue
     * 
     * @return void
     */
    public function init()
    {
        BuildingPropertySales::created(function($model) 
        {
            // TODO 从属关系弄错了，history url 1 - N bps
            $historyUrl = HistoryUrl::whereRaw('sales_date = ? AND grabbed = 1', array($model->sales_date))->firstOrFail();
            if ($historyUrl) {
                $historyUrl->bps_id = $model->id;
                $historyUrl->save();
            }
        });
    }

    /**
     * Get new node urls
     *
     * @param string url
     * @return void
     */
    protected function getNewNodeUrls()
    {
        $historyUrls = HistoryUrl::whereRaw('grabbed = 1 AND bps_id IS NULL')->take(20)->get();
        $urls = array();
        foreach ($historyUrls as $url) {
            $urls[] = $url->url;
        }
        return $urls;
    }

    /**
     * Make new grabber
     * 
     * @param string $url
     * @return HtmlGrabber
     */
    public function makeGrabber($url)
    {
        $grabber = new HtmlGrabber($url, function($response) use ($url) // success callback
        {
            $htmlExtracter = new HtmlExtracter();

            $htmlExtracter->fillHtmlDom($response->getContent());
            $htmlExtracter->setCharset('gb2312');

            $htmlExtracter->bindModel(function() use ($url)
            {
                // receive history url sales date and set to sales model
                $historyUrl = HistoryUrl::where('url', $url)->firstOrFail();
                $model = new BuildingPropertySales;
                $model->sales_date = $historyUrl->sales_date;

                return $model;
            });
            $htmlExtracter->extract();
            // update bps 
        }, function($response) // failure callback
        {}, function($e) // exception callback
        {});

        return $grabber;
    }
}
