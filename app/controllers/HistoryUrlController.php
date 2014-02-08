<?php

class HistoryUrlController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $urls = HistoryUrl::orderBy('sales_date', 'DESC')->paginate(15);
        return View::make('historyurl.index')->with('urls', $urls);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
        return View::make('historyurl.create');
	}

    public function postCreate() 
    {
        $sendResponse = function() {
            return Redirect::action('HistoryUrlController@getCreate')
            ->with('flash_messages', $this->messages);
        };

        $rules = array(
            'url' => 'required|url',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return $sendResponse()->withErrors($validator);
        }

        $url = Input::get('url');

        // Init links grabber queue object and call init method
        $queue = new LinksHtmlGrabberQueue();
        // grab all links from remote page.
        $queue->grabAll($url);

        $this->pushMessage('success', 'Fetch data successed!');

        return $sendResponse();
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
        return View::make('historyurl.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('historyurl.edit');
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
}
