<?php

/**
 * @class HtmlExtractableInterface
 */

interface HtmlExtractableInterface {
    /**
     * 
     * @param HtmlExtracter $extracter
     * 
     * @return boolean
     */
    public function extract(HtmlExtracter $extracter);
}
