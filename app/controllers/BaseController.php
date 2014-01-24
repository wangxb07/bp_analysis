<?php

use jyggen\Curl\Request;
use jyggen\Curl\Response;

class BaseController extends Controller {

    /**
     * Message collection
     *
     * @var Array
     */
    protected $messages = array();

    /**
     * Push a message to messages collection
     *
     * @param string $status 
     * @param string $message
     * 
     * @return void
     */
    protected function pushMessage($status, $message) 
    {
        $successMessage = new stdClass();

        $successMessage->status = $status;
        $successMessage->message = $message;

        $this->messages[] = $successMessage;
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
        $this->layout = View::make('layout');
	}

}
