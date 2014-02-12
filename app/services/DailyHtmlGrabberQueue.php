<?php

/**
 * @class
 * DailyHtmlGrabberQueue
 */

class DailyHtmlGrabberQueue extends HtmlGrabberQueue {
    /**
     * Initialization of Queue
     * 
     * @return void
     */
    public function init()
    {
        BuildingSalesDaily::created(function($model) 
        {
            // TODO 从属关系弄错了，history url 1 - N bps
            $historyUrl = HistoryUrl::whereRaw('daily_grabbed = 0 AND sales_date = ? AND grabbed = 1', array($model->sales_date))->first();
            if ($historyUrl) {
                $historyUrl->daily_grabbed = true;
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
    public function getNewNodeUrls() 
    {
        $historyUrls = HistoryUrl::whereRaw('grabbed = 1 AND daily_grabbed = 0')->take(20)->get();
        $urls = array();

        $pattern = '/(\d+)(\.htm)$/i';
        $replacement = '${1}_2$2';

        foreach ($historyUrls as $url) {
            $urls[] = preg_replace($pattern, $replacement, $url->url);
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
                $pattern = '/(\d+)(_2)(\.htm)$/i';
                $replacement = '$1$3';
                $url = preg_replace($pattern, $replacement, $url);

                // receive history url sales date and set to sales model
                $historyUrl = HistoryUrl::where('url', $url)->firstOrFail();
                $model = new BuildingSalesDaily;
                $model->sales_date = $historyUrl->sales_date;

                return $model;
            });
            $htmlExtracter->extract();
        }, function($response) // failure callback
        {}, function($e) // exception callback
        {});

        return $grabber;
    }
}
