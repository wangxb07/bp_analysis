<?php

use jyggen\Curl\Request;
use jyggen\Curl\Response;
use jyggen\Curl\Exception\CurlErrorException;

class HtmlGrabber {
    /**
     * Main request
     * @var jyggen\Curl\Request
     */
    protected $request = null;

    /**
     * Success callback for request
     * @var Closure
     */
    protected $successCallback;
    /**
     * Failure callback for request
     * @var Closure
     */
    protected $failureCallback;
    /**
     * Exception callback for request
     * 
     * @var Closure
     */
    protected $exceptionCallback;

    /**
     * Constructor
     *
     * @param string $url
     * @param Closure $successCallback with arguments Response
     * @param Closure $failureCallback with arguments Response
     * @param Closure $exceptionCallback with arguments Exception
     * 
     * @return void
     */
    public function __construct($url, Closure $successCallback, Closure $failureCallback, Closure $exceptionCallback)
    {
        $this->request = new Request($url);
        $this->request->setOption(CURLOPT_ENCODING, "gzip");
        $this->request->setOption(CURLOPT_CONNECTTIMEOUT, 30);

        // all callback closure call in method $this->grab().
        $this->successCallback = $successCallback;
        $this->failureCallback = $failureCallback;
        $this->exceptionCallback = $exceptionCallback;
    }
    
    /**
     * Get url of request
     *
     * @return string
     */
    public function getUrl() 
    {
        return $this->request->getInfo(CURLINFO_EFFECTIVE_URL);
    }

    /**
     * Set grabber request option 
     * 
     * @param string $key
     * @param string $value
     */
    public function setRequestOption($key, $value) 
    {
        $this->request->setOption($key, $value);
    }

    /**
     * The helper method using to grab remote html.
     * 
     * @return string|boolean
     */
    public function grab()
    {
        // execute request and get response
        try {
            $this->request->execute();
        }
        catch (CurlErrorException $e) {
            // if callback return false then throw exception again.
            if (!$this->exceptionCallback($e)) {
                throw $e;
            }
            return false;
        }

        $response = $this->request->getResponse();
        if ($response->getStatusCode() == '200') {
            $this->successCallback($response);
            return $response->getContent();
        }
        else {
            $this->failureCallback($response);
            return false;
        }
    }

    public function __call($method, $args) {
        if(isset($this->$method) && is_callable($this->$method)) {
            return call_user_func_array($this->$method, $args);
        }
    }
}
