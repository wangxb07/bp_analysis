<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

    public function index() {
        // 所有地区时间段销售均价变动lineChart
        return View::make('index');
    }

    public function grabNewestInfo()
    {
        // get index page html and fetch data page link.
        $url = '';
        $grabber = new HtmlGrabber('http://nb.soufun.com/', function($response) use (&$url)
        {
            $htmlDOM = new Htmldom();
            $htmlDOM->load($response->getContent());

            foreach ($htmlDOM->find('.area310 ul #dsy_B03_38 a') as $key => $element) {
                if (preg_match('/^http:\/\/news\.nb\.soufun\.com\/\d{4}\-\d{2}\-\d{2}/', $element->href)) {
                    $url = $element->href;
                    continue;
                }
            }
        }, function($response)
        {}, function($e)
        {});
        if (!$grabber->grab()) {
            return 'Grab failed';
        }

        $linkGrabberQueue = new LinksHtmlGrabberQueue();
        $linkGrabberQueue->grabAll($url);

        foreach (array(new DailyHtmlGrabberQueue(), new SalesHtmlGrabberQueue()) as $grabberQueue) {
            $grabberQueue->grabAll();
        }

        return 'success';
    }
}
