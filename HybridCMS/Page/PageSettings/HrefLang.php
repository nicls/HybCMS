<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class HrefLang
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class HrefLang implements \HybridCMS\Page\PageSettings\IPageSetting 
{
    /*
     * Attributes
     */
    private $arrHrefLangs;
    
    /**
     * __construct
     * @param String $url
     * @param Boolean $showIndex
     */
    public function __construct() {}

    /**
     * addHrefLang
     * @param String $url
     * @param String $lang
     * @throws \Exception
     */
    public function addHrefLang($url, $lang) 
    {
        //check if url of canonical is valid
        if(\HybridCMS\Modules\Url\Url::isValidUrl($url)) 
        {
            $this->url = $url;
        } 
        else 
        {
            throw new \Exception(
                "Error Processing Request:
                    setUrl(), url is not valid.", 1);
        }
        
        //check if roleName is alphanumeric
        if(false === preg_match('/[a-zA-Z\-]{5}/', $lang)) 
        {
                throw new \Exception(
                    "Error Processing Request:
                        setRoleName(), roleName is not valid.", 1);
        }

        $this->arrHrefLangs[$lang] = $url;
    }      
      
    
    /**
     * toString
     *
     * @return String
     */
    public function toString() {
        $op = '';
        
        if(count($this->arrHrefLangs) > 0)
        {
            foreach ($this->arrHrefLangs as $lang => $url) 
            {
                $op .= '<link rel="alternate" href="'
                        . htmlspecialchars($url)
                        . '" hreflang="'
                        . htmlspecialchars($lang)
                        .'" />';
            }
        }
        
        return $op;
    }
}

?>