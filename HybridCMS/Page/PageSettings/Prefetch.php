<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class Prefetch
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Prefetch implements \HybridCMS\Page\PageSettings\IPageSetting 
{    
    
    /**
     * Urls to prefetch
     * @var String[]
     */
    private $arrUrls = array();
    
    /**
     * __construct
     * @param String $url
     * @param Boolean $showIndex
     */
    public function __construct() {}

    /**
     * addPrefetch
     * @param String $url
     * @throws \Exception
     */
    public function addPrefetch($url) 
    {
        //check if url of canonical is valid
        if(\HybridCMS\Modules\Url\Url::isValidUrl($url)) 
        {
            array_push($this->arrUrls, $url);
        } 
        else 
        {
            throw new \Exception(
                "Error Processing Request:
                    addPrefetch(), url is not valid.", 1);
        }
    }      
      
    
    /**
     * toString
     *
     * @return String
     */
    public function toString() 
    {        
        $op = '';   
        
        foreach ($this->arrUrls as $url) {
            $op .= '<link rel="prefetch" href="' 
                    . htmlspecialchars($url) . '" />';  
        }
                
        return $op;
    }
}

?>