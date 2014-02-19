<?php

use jyggen\Curl\Exception\CurlErrorException;

/**
 * @class
 * LinksHtmlGrabberQueue
 */

class LinksHtmlGrabberQueue extends HtmlGrabberQueue {
    /**
     * Get new node urls
     *
     * @param string url
     * @return void
     */
    protected function getNewNodeUrls()
    {
        $historyUrls = HistoryUrl::whereRaw('grabbed = 0 and timeout_count < ?', array(3))->get();
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
     * @param boolean $isRoot
     * @return HtmlGrabber
     */
    public function makeGrabber($url, $isRoot = false)
    {
        if ($isRoot) {
            $data = array();
            $data['url'] = $url;
            $data['sales_date'] = HistoryUrl::getDateFromLink($url);
            HistoryUrl::create($data);
        }
        $grabber = new HtmlGrabber($url, function($response) use ($url) // success callback
        {
            // init extracter
            $htmlExtracter = new HtmlExtracter();

            $htmlExtracter->fillHtmlDom($response->getContent());
            $htmlExtracter->setCharset('gb2312');

            $htmlExtracter->bindModel(function()
            {
                return new HistoryUrl();
            });

            $htmlExtracter->extract();
            
            // update url grabbed status
            return HistoryUrl::where('url', $url)->update(array('grabbed' => true));
        }, function($response) // failure callback
        {
        }, function($e) use ($url) // exception callback
        {
            $message = $e->getMessage();
            
            Log::error('HtmlGrabber occur exception: ' . $message);

            if (preg_match('/Connection timed/', $message)) { // timeout process
                $historyUrl = HistoryUrl::where('url', $url)->firstOrFail();
                if ($historyUrl) {
                    $historyUrl->timeout_count = $historyUrl->timeout_count + 1;
                    $historyUrl->update();
                }
                return true;
            }
            else {
                return false;
            }
        });
        return $grabber;
    }
}
