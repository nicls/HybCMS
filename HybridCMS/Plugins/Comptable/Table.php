<?php

namespace HybridCMS\Plugins\Comptable;

/**
 * class Table
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Table {

    /**
     * Name of this table
     * @var String
     */
    private $tableName;

    /**
     * Datasets of this table
     * @var Dataset[]
     */
    private $arrObjDatasets;
    
    
    /**
     * Indicates if the table is active
     * @var Boolean
     */
    private $isActive;

    /**
     * Indicates if this Table is marked as Favorit
     * @var Boolean
     */
    private $isFavorit;

    /**
     * A note belonging to this table
     * @var String
     */
    private $note;

    /**
     * url belonging to the Table
     * @var String
     */
    private $url;

    /**
     * Url to an image belonging to this table
     * @var String
     */
    private $imgUrl;

    /**
     * Unix Timestamp that indicates the creation time of this Table
     * @var Integer
     */
    private $created;

    /**
     * __construct
     * @param String $name
     * @throws \Exception
     */
    public function __construct($tableName) {
        try 
        {
            $this->setTableName($tableName);
        } 
        catch (Exception $e) 
        {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end __construct

    /**
     * addDataset
     * @param \HybridCMS\Plugins\Comptable\Dataset $objDataset
     * @throws \Exception
     */
    public function addDataset($objDataset) {

        //check if $objTable is of type Table
        if (!($objDataset instanceof \HybridCMS\Plugins\Comptable\Dataset)) {

            throw new \Exception(
            "Error Processing Request: addDataset(),
                    'objDataset must be of type Dataset.'", 1);
        }

        $this->arrObjDatasets[] = $objDataset;
    }

    /**
     * setTableName
     * @param String $tableName
     * @return void
     * @throws \Exception
     */
    public function setTableName($tableName) 
    {
        //check if $tableName is an String width 45 charekters length in maximum
        if (false === is_string($tableName) 
            || 
            0 === preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\+_\.\s]+$/', $tableName) 
            || 
            strlen($tableName) > 255) 
        {

            throw new \Exception(
            "Error Processing Request: setTableName(),
                    tableName must be an String width 255 
                    charekters length in maximum.", 1);
        }

        $this->tableName = $tableName;
    }
    
    /**
     * setIsActive
     * @param Boolean $isActive
     * @return void 
     * @throws \Exception
     */
    public function setIsActive($isActive) {

        //check if $isActive is an boolean
        if (false === is_bool($isActive)) 
        {
            throw new \Exception(
            "Error Processing Request: setIsActive(),
                    isActive must be a Boolean.", 1);
        }

        $this->isActive = $isActive;
    }
    

    /**
     * setIsFavorit
     * @param Boolean $isFavorit
     * @return void 
     * @throws \Exception
     */
    public function setIsFavorit($isFavorit) {

        //check if $isFavorit is an boolean
        if (!is_bool($isFavorit)) {
            throw new \Exception(
            "Error Processing Request: setIsFavorit(),
                    'isFavorit must be a Boolean.'", 1);
        }

        $this->isFavorit = $isFavorit;
    }

    /**
     * setNote
     * @param String $note
     * @return void 
     * @throws \Exception
     */
    public function setNote($note) {

        //check if $note is an String width 255 charekters length in maximum
        if (!is_string($note) || strlen($note) > 255) {

            throw new \Exception(
            "Error Processing Request: setNote(),
                    'note must be an String width 255 charekters 
                    length in maximum.'", 1);
        }

        $this->note = $note;
    }

    /**
     * setUrl
     * @param String $url
     * @throws \Exception
     */
    public function setUrl($url) {

        //check if URL is valid
        if (!\HybridCMS\Modules\Url\Url::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                        url is not valid.", 1);
        }

        //set url
        $this->url = $url;
    }

    /**
     * setImgUrl
     * @param String $imgUrl
     * @throws \Exception
     */
    public function setImgUrl($imgUrl) {

        //check if $imgUrl is valid
        if (!\HybridCMS\Modules\Url\Url::isValidURL($imgUrl)) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                        imgUrl is not valid.", 1);
        }

        $this->imgUrl = $imgUrl;
    }

    /**
     * setCreated
     * @param Integer $created
     * @throws \Exception
     */
    public function setCreated($created) {
        
        //check if $imgUrl is valid
        if (!is_numeric($created) || $created < 1386457427) {
            throw new \Exception(
            "Error Processing Request: setCreated(),
                        created is not valid.", 1);
        }
        
        $this->created = $created;
    }
    
    public function getCreated() {
        return $this->created;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function getArrObjDatasets() {
        return $this->arrObjDatasets;
    }
    
    /**
     * Returns the value of a dataset with a spezific key
     * @param string $key
     * @return string value of the dataset
     * @throws \Exception
     */
    public function getValueOfDateset($key)
    {
        //check if $key is an String width 45 charekters length in maximum
        if(false === is_string($key) 
           || 
           0 === preg_match('/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/', $key) 
           ||  
           strlen($key) > 45) 
        {                
            throw new \Exception(
                "Error Processing Request: setKey(),
                    'key must be an String width 45 charekters length in maximum.'", 1);
        }
        
        if(true === empty($this->arrObjDatasets))
        {
            throw new \Exception(
                "Error Processing Request: getValueOfDateset(),
                    No datasets given.", 1);
        }
                
        foreach ($this->arrObjDatasets as &$objDataset) 
        {
            if($objDataset->getKey() === $key)
            {
                return $objDataset->getValue();
            }
        }
        return '';
    }
    
    public function getIsActive() {
        return $this->isActive;
    }    

    public function getIsFavorit() {
        return $this->isFavorit;
    }

    public function getNote() {
        return $this->note;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getImgUrl() {
        return $this->imgUrl;
    }

    /**
     * Table to String
     * @returns String
     */
    public function toString($args = array()) {
        return '';
    }

}

?>