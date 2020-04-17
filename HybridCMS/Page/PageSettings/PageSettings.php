<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class PageSettings
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class PageSettings {

    /*
     * Attributes
     */
    private $objNoindex;
    private $objNoindexNofollow;
    private $objTitle;
    private $objDescription;
    private $objCanonical;
    private $objAmpHtml;
    private $objPageRole;
    private $objKeywords;
    private $objHrefLang;
    private $objPrefetch;

    /**
     * addNoindex - add an object of Noindex to the stack of Settings
     *
     * @return void
     */
    public function addNoindex() 
    {
        //delete NoindexNofollow
        if ($this->objNoindexNofollow) 
        {
            $this->objNoindexNofollow = NULL; //delete NoindexNofollow
        }

        //add Noindex
        $this->objNoindex = new \HybridCMS\Page\PageSettings\
                Noindex(); //add Noindex
    }

    /**
     * getNoindex
     *
     * @return NoIndex
     */
    public function getNoindex() 
    {
        return $this->objNoindex;
    }

    /**
     * addNoindexNofollow - add an object of NoindexNofollow to the 
     * stack of Settings
     *
     * @return void
     */
    public function addNoindexNofollow() 
    {
        //delete NoIndex
        if ($this->objNoindex) $this->objNoindex = NULL; //delete Noindex

        //add NoIndexNofollow
        $this->objNoindexNofollow = new \HybridCMS\Page\PageSettings\
                NoindexNofollow(); //add NoindexNofollow
    }

    /**
     * getNoindexNofollow
     *
     * @return NoindexNofollow
     */
    public function getNoindexNofollow() 
    {
        return $this->objNoindexNofollow;
    }

    /**
     * addTitle - add an Object of Title
     *
     * @param String $title
     * @param String $prepend
     * @param Integer $maxLength
     */
    public function addTitle($title, $prepend, $maxLength = 160) 
    {
        $this->objTitle = new \HybridCMS\Page\PageSettings\
                Title($title, $prepend, $maxLength);
    }

    /**
     * getTitle
     *
     * @return Title
     */
    public function getTitle() {
        return $this->objTitle;
    }

    /**
     *  addDescription - add an Object of Description
     *
     * @param String $description
     * @return void
     */
    public function addDescription($description) 
    {
        $this->objDescription = new \HybridCMS\Page\PageSettings\
                Description($description);
    }

    /**
     * getDescription
     *
     * @return Description
     */
    public function getDescription()
    {
        return $this->objDescription;
    }

    
    /**
     * addKeywords
     * 
     * @param String[] $arrKeywords
     * @throws \HybridCMS\Page\PageSettings\Exception
     */
    public function addKeywords($arrKeywords) 
    {
        $this->objKeywords = new \HybridCMS\Page\PageSettings\
                Keywords($arrKeywords);
    }
    
    /**
     * getKeywords
     * 
     * @return Keywords
     */
    public function getKeywords() 
    {
        return $this->objKeywords;
    }
    
    /**
     * addCanonical - ad an Object of Canonical
     *
     * @param String $canonical
     * @return void
     */
    public function addCanonical($canonical) 
    {
        $this->objCanonical = new \HybridCMS\Page\PageSettings\
                Canonical($canonical);
    }

    /**
     * getCanonical
     *
     * @return Canonical
     */
    public function getCanonical() 
    {
        return $this->objCanonical;
    }
    
    /**
     * addAmpHtml - ad an Object of AmpHtml
     *
     * @param String $ampPage
     * @return void
     */
    public function addAmpHtml($ampPage) 
    {
        $this->objAmpHtml = new \HybridCMS\Page\PageSettings\
                AmpHtml($ampPage);
    }

    /**
     * getAmpHtml
     *
     * @return AmpHtml
     */
    public function getAmpHtml() 
    {
        return $this->objAmpHtml;
    }    

    /**
     * addPageRole - ad an Object of PageRole
     *
     * @param String $roleName
     */
    public function addPageRole($roleName) 
    {
        $this->objPageRole = new \HybridCMS\Page\PageSettings\
                PageRole($roleName);
    }

    /**
     * getPageRole
     *
     * @return PageRole
     */
    public function getPageRole() 
    {
        return $this->objPageRole;
    }
    
    /**
     * addHrefLang - add an new HrefLang
     *
     * @param String $url
     * @param String $lang
     */
    public function addHrefLang($url, $lang) 
    {
        if(true === empty($this->objHrefLang))
        {
            $this->objHrefLang = new \HybridCMS\Page\PageSettings\
                HrefLang();
        }
        
        $this->objHrefLang->addHrefLang($url, $lang);
    } 

    /**
     * getHrefLang
     *
     * @return HrefLang
     */
    public function getHrefLang() 
    {
        return $this->objHrefLang;
    }   
    
    /**
     * addPrefetch - add an new url to prefetch
     *
     * @param String $url
     */
    public function addPrefetch($url) 
    {
        if(true === empty($this->objPrefetch))
        {
            $this->objPrefetch = new \HybridCMS\Page\PageSettings\Prefetch();
        }
        
        $this->objPrefetch->addPrefetch($url);
    }  
    
    
    /**
     * getPrefetch
     *
     * @return Prefetch
     */
    public function getPrefetch() 
    {
        return $this->objPrefetch;
    }  
    
}//end class

?>