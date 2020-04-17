<?php

namespace HybridCMS\Modules\Url;

/**
 * class SERefferer - Class to handle Referrers from Search Engines
 *
 * @package Modules
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class SEReferrer extends \HybridCMS\Modules\Url\Url {

    /**
     * Name of the Searchengine the user came from
     * @var String
     */
    private $searchEngine;

    /**
     * the Query the user tipped into the searchengine
     * @var String
     */
    private $query;
        
    /**
     * List of supported Searchengines
     * @var String[]
     */
    private $arrSearchengines = array(
        'google.de',
        'google.com',
        'google.at',
        'google.ch',
        'bing.de',
        'duckduckgo.com'
    );



    /**
     * __construct
     */
    public function __construct($referrer) {
        
        try {

            /** Parent Constructor */
            parent::__construct($referrer);
            
            //check if referrer is from search engine
            if($this->refIsFromSEngine()) {
                
                //set name of the actual search engine
                $this->setSearchEngine(parent::getHostName());
            
                //set Query
                $arrKeyValues = parent::getArrKeyValues();
                if(isset($arrKeyValues['q'])) {
                    $this->setQuery($arrKeyValues['q']);
                }
            
            }            

            
        } catch (\Exception $e) {
            
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    /**
     * refIsFromSEngine - returns true if the referrer is from a search engine
     * @return boolean
     */
    public function refIsFromSEngine() {
        
        assert(isset($this->arrSearchengines));
        
        //check if search engine is set and not empty
        if(!parent::getHostName()) {
            return false;
        }
                
        //check if search engine is in the list of search engines
        foreach ($this->arrSearchengines as &$se) {
            
            if(strpos(parent::getHostName(), $se) !== false) {
               return true;
            }
        }
        
        return false;
    }
    
    /**
     * setSearchEngine
     * 
     * @param String $searchEngine
     * @throws \Exception
     */
    private function setSearchEngine($searchEngine) {
        
        $validHostname = "/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/";
        
        if (!preg_match($validHostname, $searchEngine)) {
            throw new \Exception(
            "Error Processing Request: __construct(),
                            searchEngine is not valid.", 1);
        }
        
        $this->searchEngine = $searchEngine;
    }
    
    /**
     * setQuery
     * @param String $query
     */
    private function setQuery($query) {
        
        if (!is_string($query)) {
            throw new \Exception(
            "Error Processing Request: setQuery(),
                            query is not valid.", 1);
        }
        
        $this->query = $query;
    }
    
    /**
     * Checks if the Referrer is from Adwords
     */
    public function refIsFromAdwords() {
        
        $isAdwordsUser = false;
        if(true === array_key_exists('gclid', $this->arrKeyValues)) {
            $isAdwordsUser = true;
        }
        
        return $isAdwordsUser;
    }

    /**
     * setArrSearchengines
     * @param String $arrSearchengines
     */
    private function setArrSearchengines($arrSearchengines) {
        
        if (!is_string($arrSearchengines)) {
            throw new \Exception(
            "Error Processing Request: setArrSearchengines(),
                            arrSearchengines is not valid.", 1);
        }
        
        $this->arrSearchengines = $arrSearchengines;
    }
    
    /**
     * getSearchEngine
     * @return String
     */
    public function getSearchEngine() {
        return $this->searchEngine;
    }

    /**
     * getQuery
     * @return String
     */
    public function getQuery() {
        return $this->query;
    }





}

?>