<?php

/**
 * @class
 * HtmlGrabberQueue
 */
abstract class HtmlGrabberQueue {
    /**
     * Queue of instance of HtmlGrabber
     * @var array
     */
    protected $queue = array();

    /**
     * Grabed list
     * @var array
     */
    protected $grabbed = array();

    /**
     * Get new node urls
     *
     * @param string url
     * @return void
     */
    abstract protected function getNewNodeUrls();

    /**
     * Make new grabber
     * 
     * @param string $url
     * @return HtmlGrabber
     */
    abstract protected function makeGrabber($url);
   
    /**
     * Constructer
     */
    public function __construct() 
    {
        $this->init();
    }
    
    /**
     * Initialization of Queue
     * 
     * @return void
     */
    public function init()
    {
    }

    /**
     * Building grabber queue, if queue not null then rebuild it.
     *
     * @return boolean
     */
    protected function buildQueue()
    {
        $this->queue = array();
        $nodes = $this->getNewNodeUrls();
        if (empty($nodes)) {
            return false;
        }
        
        foreach ($nodes as $i => $nodeUrl) {
            // filte grabbed urls
            if (!in_array($nodeUrl, $this->grabbed)) {
                $this->queue[] = $this->makeGrabber($nodeUrl);
            }
        }

        if (empty($this->queue)) {
            return false;
        }

        return true;
    }
    
    /**
     * Grab all contents in queue.
     * 
     * @param string $url seed url
     *
     * @return boolean
     */
    public function grabAll($url = null)
    {
        if (!empty($url)) {
            $this->grabbed[] = $url;
            $grabber = $this->makeGrabber($url, true);
            $grabber->grab();
        }

        $this->buildQueue();
        return $this->recurse();
    }

    /**
     * Recurse body
     * @return void
     */
    protected function recurse() 
    {
        foreach($this->queue as $grabber) {
            if ($grabber->grab()) {
                $this->grabbed[] = $grabber->getUrl();
            }
        }

        if (!$this->buildQueue()) {
            return true;
        }
        else {
            return $this->recurse();
        }
    }
}
