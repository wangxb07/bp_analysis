<?php

/**
 * @class
 * HtmlExtracter
 */
class HtmlExtracter {
    
    /**
     * @var \Yangqi\Htmldom\Htmldom
     */
    protected $htmlDOM = NULL;

    /**
     * From charset
     * @var string
     */
    protected $charset = NULL;

    /**
     * @var array
     */
    protected $models = array();

    /**
     * Set extractor from charset
     * 
     * @param string $charset
     * 
     * @return void
     */
    public function setCharset($charset) 
    {
        $this->charset = $charset;
    }
    
    // getter of property charset
    public function getCharset() {
        return $this->charset;
    }

    /**
     * Init htmldom by html string, if load failure return 'false'.
     *
     * @param string $html
     */
    public function fillHtmlDom($html) 
    {        
        $this->htmlDOM = new Htmldom();
        $this->htmlDOM->load($html);
    }

    public function getHtmlDOM() 
    {
        return $this->htmlDOM;
    }

    /**
     * Bind extractable model
     *
     * @param closures $builder
     *
     * @return void
     */
    public function bindModel(Closure $builder) {
        $instance = $builder();

        if ($instance instanceof HtmlExtractableInterface) {
            $className = get_class($instance);
            if (!isset($this->models[$className])) {
                $this->models[$className] = $instance;
            }
        }
        else {
            throw new ErrorException('bindModel arguments closures return instance must be implement [HtmlExtractableInterface];');
        }
    }

    /**
     * Extract assigned html from htmldom object.
     * @param Closures $callback with argument model
     * 
     * @return boolean
     */
    public function extract() 
    {
        foreach ($this->models as $key => $model) {
            if (!$model->extract($this)) {
                return false;
            }
        }
        return true;
    }
}
