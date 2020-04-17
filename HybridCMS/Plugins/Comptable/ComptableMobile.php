<?php

namespace HybridCMS\Plugins\Comptable;

/**
 * class Comptable for Mobile Devices
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class ComptableMobile extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * Name of the comptable
     * @var String
     */
    private $comptableName;

    /**
     * Tables of this Comptable
     * @var Table[]
     */
    private $arrObjTables;
    
    /**
     * Changelog
     * @var String[]
     */
    private $arrChanges;
    
    /**
     * Tables ordered by timeCreated
     * @var Table[]
     */
    private $arrObjTablesLatest;
        
    /**
     * Keys that should be shown in the comptable. Order matters.
     * @var String[]
     */
    private $arrWhiteListKeys;
    
    /**
     * Keys that should not be shown in the comptable.
     * @var String[]
     */
    private $arrBlackListKeys;
    
    /**
     * Key that holds the price value of an Table
     * @var String
     */
    private $keyPrice;

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct(
            $comptableName, 
            $arrWhiteListKeys = array(), 
            $arrBlackListKeys = array()) 
    {
        try 
        {
            //call constructor of parent class
            parent::__construct();

            $this->setComptableName($comptableName);
            $this->setArrWhitelistKeys($arrWhiteListKeys);
            $this->setArrBlacklistKeys($arrBlackListKeys);
            
            //Add JSResources
            $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                    'comptable', '/HybridCMS/Plugins/Comptable/js/m.js', 3, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource1);            
            
            //Add CSSResource
            $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                    'comptable', '/HybridCMS/Plugins/Comptable/css/m.css');
            $this->addObjCSSResource($objCSSResource1);             
                   
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end __construct

    /**
     * addTable
     * @param \HybridCMS\Plugins\Comptable\Table $objTable
     * @throws \Exception
     */
    public function addTable($objTable) {

        //check if $objTable is of type Table
        if (!($objTable instanceof \HybridCMS\Plugins\Comptable\Table)) {

            throw new \Exception(
            "Error Processing Request: addTable(),
                    'table must be of type Table.'", 1);
        }

        $this->arrObjTables[$objTable->getTableName()] = $objTable;
    }

    /**
     * fetchComptable - fetches all tables from the Database
     * @returns Table[]
     * @throws \Exception
     */
    public function fetchComptable() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::
                    getFactory()->getConnection();

            //get Database object
            $objDBComptable = new \HybridCMS\Plugins\Comptable\
                    Database\DBComptable();

            $arrObjTables = $objDBComptable->
                    selectArrObjTablesByComptableName(
                        $db, $this->comptableName);

            foreach ($arrObjTables as &$objTable) 
            {
                $this->addTable($objTable);
            }
            
            //get changelog
            $this->arrChanges = $objDBComptable->
                    selectChangesOrderedByLastChangedByComptableName(
                            $db, $this->comptableName);
            
            //get latest tables
            $this->arrObjTablesLatest = $objDBComptable->
                    selectArrObjTablesOrderedByCreatedByComptableName(
                            $db, $this->comptableName);
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();         
                        
            return $this->arrObjTables;
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

    /**
     * setComptableName
     * @param String $comptableName
     * @returns void
     * @throws \Exception
     */
    private function setComptableName($comptableName) 
    {
        //check if $comptableName is an String width 45 charekters length in maximum
        if (false === is_string($comptableName) 
            || 
            0 === preg_match('/^[a-zA-Z0-9\-_]+$/', $comptableName) 
            || 
            strlen($comptableName) > 45) 
        {

            throw new \Exception(
            "Error Processing Request: setComtableName(),
                    'comptableName must be an String width 45 
                        charekters length in maximum.'", 1);
        }
        $this->comptableName = $comptableName;
    }
    
    /**
     * setKeyPrice
     * @param String $keyPrice
     * @throws \Exception
     */
    public function setKeyPrice($keyPrice) {
        //check if $key is an String width 45 charekters length in maximum
        if(!is_string($keyPrice) 
                || !preg_match('/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/', $keyPrice) 
                ||  strlen($keyPrice) > 45) {

            throw new \Exception(
                "Error Processing Request: setKeyPrice(),
                    '$keyPrice must be an String width 45 
                    charekters length in maximum.'", 1);
        }
        $this->keyPrice = $keyPrice;
    }
    
    /**
     * setArrWhitelistKeys
     * @param String[] $arrWhitelistKeys
     * @throws \Exception
     */
    private function setArrWhitelistKeys($arrWhiteListKeys) {
        
        if(false === is_array($arrWhiteListKeys)) {
            throw new \Exception(
            "Error Processing Request: setArrWhitelistKeys(),
                    '$arrWhiteListKeys must be an array.'", 1); 
        }
        
        foreach ($arrWhiteListKeys as $key) {
            //check if $key is an String width 45 charekters length in maximum
            if(!is_string($key) 
                    || !preg_match('/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/', $key) 
                    ||  strlen($key) > 45) {

                throw new \Exception(
                    "Error Processing Request: setArrWhitelistKeys(),
                        'key must be an String width 45 charekters length in maximum.'", 1);
            }
        }
        
        $this->arrWhiteListKeys = $arrWhiteListKeys;
    }
    
    /**
     * setArrBlacklistKeys
     * @param String[] $arrBlacklistKeys
     * @throws \Exception
     */
    private function setArrBlacklistKeys($arrBlacklistKeys) {
        
        if(false === is_array($arrBlacklistKeys)) {
            throw new \Exception(
            "Error Processing Request: setArrWhitelistKeys(),
                    '$arrBlacklistKeys must be an array.'", 1); 
        }
        
        foreach ($arrBlacklistKeys as $key) {
            //check if $key is an String width 45 charekters length in maximum
            if(!is_string($key) 
                    || !preg_match('/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/', $key) 
                    ||  strlen($key) > 45) {

                throw new \Exception(
                    "Error Processing Request: setArrBlacklistKeys(),
                        'key must be an String width 45 charekters length in maximum.'", 1);
            }
        }
        
        $this->arrBlackListKeys = $arrBlacklistKeys;
    }    

    /**
     * hasObjTable - checks if a given Table exists
     * @param $tableName
     * @return Boolean
     * @throws \Exception
     */
    public function hasObjTable($tableName) {
        //check if $comptableName is an String width 45 charekters length in maximum
        if (!is_string($tableName) || strlen($tableName) > 45) {

            throw new \Exception(
            "Error Processing Request: setComtableName(),
                    'comptableName must be an String width 45 charekters length in maximum.'", 1);
        }

        return isset($this->arrObjTables[$tableName]);
    }

    /**
     * getObjTable - returns a table by name
     * @param String $tableName
     * @return type
     * @throws \Exception
     */
    public function getObjTable($tableName) {
        //check if $comptableName is an String width 45 charekters length in maximum
        if (!is_string($tableName) || strlen($tableName) > 45) {

            throw new \Exception(
            "Error Processing Request: setComtableName(),
                    'comptableName must be an String width 45 charekters length in maximum.'", 1);
        }

        $objTable = null;
        if (isset($this->arrObjTables[$tableName])) {
            $objTable = $this->arrObjTables[$tableName];
        }

        return $objTable;
    }

    public function getComptableName() {
        return $this->comptableName;
    }

    public function getArrObjTables() {
        return $this->arrObjTables;
    }
    
    /**
     * Returns an array of tables ordered by created timestamp
     * @param int $limit number of tables to get
     */
    public function getArrObjTablesOrderedByCreated($limit)
    {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get Database object
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();
            $arrObjTables = $objDBComptable->
                    selectArrObjTablesOrderedByCreatedByComptableName(
                            $db, $this->comptableName);

            $lastIndex = min($limit, count($arrObjTables));
            $arrObjTables = array_slice($arrObjTables, 0, $limit);
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            return $arrObjTables;

        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();

            throw $e;
        }        
    }
    
    /**
     * Comptable to String
     * @returns String
     */
    public function toString($args = array()) {
        if (isset($args['content'])) {

            //build comptable
            if ($args['content'] === 'comptable') {
                return $this->toStringComptable();
            }

            //build table of keys
            else if ($args['content'] === 'keys') {
                return $this->toStringKeys();
            }

            //build comptable_table
            else if ($args['content'] === 'table' 
                     && 
                     true === isset($args['tableName'])) 
            {

                $tableName = $args['tableName'];
                return $this->toStringTable($tableName);
            }

            //build table of changelogs
            else if ($args['content'] === 'changelog') {

                return $this->toStringChangelog();
            }
            
            //build table of new tableNames
            else if ($args['content'] === 'newTables') 
            {
                $limit = 15; #default vaule
                if(true === isset($args['limit'])
                   && 
                   true === is_numeric($args['limit']))
                {
                    $limit = $args['limit'];
                }
                
                $urlComptable = ""; #default vaule
                if(true === isset($args['urlComptable'])
                   && 
                   true === \HybridCMS\Modules\Url\Url::isValidUrl(
                           $args['urlComptable']))
                {
                    $urlComptable = $args['urlComptable'];
                }
                
                return $this->toStringNewTables($limit, $urlComptable);
            }            
        }
    }
    
    /**
     * toStringNewTables
     * @return string
     * @throws Exception
     */
    private function toStringNewTables($limit, $urlComptable) {

        try {
            
            $lastIndex = min($limit, count($this->arrObjTablesLatest));
            $arrObjTables = array_slice($this->arrObjTablesLatest, 0, $limit);            
            
            $op = '';
            $op .= '<table class="hyb_comptable_newTables">';

            foreach ($arrObjTables as &$objTable) {

                $tableName = $objTable->getTableName();
                $created = $objTable->getCreated();                

                $op .= '<tr>';

                $op .= '<td class="bold"><i class="fa fa-plus-square"></i> ' 
                        . htmlspecialchars(date('d.m.Y', $created)) . ': </td>';
                $op .= '<td>';
                $op .= '<i class="fa fa-arrow-right"></i> ';
                $op .= '<a href="'. htmlentities($urlComptable) .'#' 
                        . htmlentities($this->mkClassName($tableName), ENT_COMPAT, 'UTF-8') 
                        . '" title="'. htmlentities($tableName, ENT_COMPAT, 'UTF-8') .'">';
                $op .= htmlspecialchars($tableName);
                $op .= '</a>';
                $op .='</td>';

                $op .= '</tr>';
            }

            $op .= '</table>';

            return $op;
        } catch (Exception $e) {

            throw $e;
        }
    }    

    /**
     * toStringChangelog
     * @return string
     * @throws Exception
     */
    private function toStringChangelog() {

        try {

            $op = '';
            $op .= '<table class="hyb_comptable_changelog">';

            foreach ($this->arrChanges as &$change) {

                $tableName = $change['tableName'];
                $key = $change['key'];
                $value = $change['value'];
                $lastChanged = $change['lastChanged'];

                $op .= '<tr>';

                $op .= '<td class="bold"><i class="fa fa-pencil-square"></i> ' . htmlspecialchars(date('d.m.Y', $lastChanged)) . '</td>';
                $op .= '<td class="bold">' . htmlspecialchars($tableName) . '</td>';
                $op .= '<td><i class="fa fa-arrow-right"></i> ' . htmlspecialchars($key) . ': ';
                $op .= htmlspecialchars($value) . '</td>';

                $op .= '</tr>';
            }

            $op .= '</table>';

            return $op;
        }             
        catch (\Exception $e) 
        {
            throw $e;
        }
    }

    /**
     * toStringTable
     * @param String $tableName
     * @returns String
     */
    private function toStringTable($tableName) {

        if (!isset($this->arrObjTables[$tableName])) {
            throw new \Exception(
            "Error Processing Request: toStringTable(),
                    'tableName does not exist.'", 1);
        }

        $objTable = $this->arrObjTables[$tableName];
        $imgUrl = $objTable->getImgUrl();
        $arrObjDatasets = $objTable->getArrObjDatasets();

        $op = '';
        $op .= '<table class="hyb_comptable_table">';

        //add table header
        $op .= '<thead><tr><th colspan="2">';

        //add image
        if (!empty($imgUrl)) {
            $op .= '<img class="borderRadius5 add-right-10" src="' . htmlentities($imgUrl, ENT_COMPAT, 'UTF-8')
                    . '" title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8')
                    . '" height="50" width="50">';
        }

        $op .= '<a name="' . htmlentities($this->mkClassName($tableName), ENT_COMPAT, 'UTF-8') . '">';
        $op .= htmlspecialchars($tableName);
        $op .= '</a>';
        $op .= '</th></tr></thead>';

        //add table body
        $op .= '<tbody id="hyb_tableBody">';

        foreach ($arrObjDatasets as &$objDataset) {

            $key = $objDataset->getKey();
            $value = $objDataset->getValue();


            $op .= '<tr>';

            $op .= '<th>';
            $op .= htmlspecialchars($key);
            $op .= '</th>';

            $op .= '<td>';
            $op .= htmlspecialchars($value);
            $op .= '</td>';

            $op .= '</tr>';
        }


        //close table body
        $op .= '</tbody>';

        //close table
        $op .= '</table>';

        return $op;
    }

    /**
     * toStringKeys
     * @returns String
     */
    private function toStringKeys() {
        $op = '';
        $op .= '<table class="hyb_comptable_keys">';
        $op .= '<thead><tr><th data-tip="Vergleichskriterium auswählen"><i class="fa fa-magic add-right-10"></i> Vergleichskriterium auswählen</th></tr></thead>';

        if (is_array($this->arrObjTables) && count($this->arrObjTables) > 0) {

            $key = key($this->arrObjTables);
            $objTable = $this->arrObjTables[$key];
            $arrObjDatasets = $objTable->getArrObjDatasets();

            foreach ($arrObjDatasets as &$objDataset) {

                $key = $objDataset->getKey();

                $op .= '<tr title="Vergleichen nach &quot;'. htmlentities($key, ENT_COMPAT, 'UTF-8') .'&quot;">';
                $op .= '<td class="keySelector"' . ' key="' . htmlentities($key, ENT_COMPAT, 'UTF-8') . '" id="hyb_keySelector_' . $this->mkClassName(htmlentities($key, ENT_COMPAT, 'UTF-8')) . '">';
                $op .= '<span class="hyb_key">' . htmlspecialchars($key) . '</span>';

                //add pinning option
                $op .= '<span data-tip="Spalte ' . htmlentities($key, ENT_COMPAT, 'UTF-8') . ' fixieren">';
                $op .= '<i class="hyb_lock fa fa-unlock-alt childOfKeySelector"'
                        . 'comptableName="' . htmlentities($this->comptableName, ENT_COMPAT, 'UTF-8')
                        . '" key="' . htmlentities($key, ENT_COMPAT, 'UTF-8') . '"></i>';
                $op .= '</span>';

                $op .= '</tr>';
            }
        }

        $op .= '</table>';

        return $op;
    }

    /**
     * toStringComptable
     * @returns String
     */
    private function toStringComptable() 
    {    
        $op = '';
        $op .= '<div class="hyb_comptableMobile">';           
        
        foreach ($this->arrObjTables as &$objTable)
        {                        
            //do not print inactive tables
            if(false === $objTable->getIsActive()) { continue; } 
            
            //get table data
            $url = $objTable->getUrl(); 
            $imgUrl = $objTable->getImgUrl();
            $tableName = $objTable->getTableName();
            $tablenameClass = $this->mkClassName($tableName);  
            $arrObjDatasets = $objTable->getArrObjDatasets();
            
            $op .= '<div class="hyb_table" id="hyb_tablename_' . $tablenameClass  . '">';
            $op .= '<div class="row">';
            $op .= '<div class="hyb_table_header col-lg-12 col-md-12 col-xs-12">';
                    
            //add image            
            if(false === empty($imgUrl))
            {
                $objImage = new \HybridCMS\Modules\Image(substr(HYB_ROOT, 0, -1) . $imgUrl);
                $imgUrl = $objImage->scale(250);
                
                if(false === empty($url)) {  
                    $op .= '<a name="' . htmlentities($this->mkClassName($tableName))                             
                            . '" href="' . htmlentities($url) 
                            . '" title="' . htmlentities($tableName) 
                            . '" bei Amazon ansehen" rel="nofollow" target="_blank"'
                            . '>';
                }
                                
                $op .= '<img src="' . htmlentities($imgUrl)
                        . '" title="' . htmlentities($tableName)
                        . '" height="250" width="250" />'; 
                
                if(false === empty($url)) {  
                    $op .= '</a>';
                }

            }            
            
            $op .= '<div class="hyb_table_info_container">';
            $op .= '<ul class="hyb_table_info">';
            
            $op .= '<li class="hyb_table_name">';
            $op .= htmlspecialchars($tableName);
            $op .= '</li>';
             
            
            //add link
            if(false === empty($url)) {  
                $op .= '<li>';
                $op .= '<a name="' . htmlentities($this->mkClassName($objTable->getTableName()))                             
                        . '" href="' . htmlentities($url) 
                        . '" title="' . htmlentities($objTable->getTableName()) 
                        . '" bei Amazon ansehen" rel="nofollow" target="_blank"'
                        . '>';
                $op .= '<i class="fa fa-3x fa-amazon"></i>';
                
                if(false === empty($this->keyPrice)) 
                {
                    $op .=  '<span>zu Amazon (' 
                        . htmlspecialchars($objTable->getValueOfDateset($this->keyPrice))
                        .')</span>';
                } 
                else 
                {
                    $op .= '<span>Produktseite</span>';
                }              

                $op .= '</a>';  
                $op .= '</li>';
            }             
            
            //add isFavorit
            if (true === $objTable->getIsFavorit()) {
                $op .= '<li class="hyb_demarcateAsFavorit">';
                $op .= '<i class="fa fa-3x fa-star"></i> ';
                $op .= '<span>favorisieren</span>';
                $op .= '</li>';
            } else {
                $op .= '<li class="hyb_markAsFavorit">';
                $op .= '<i class="fa fa-3x fa-star-o"></i>';
                $op .= '<span>favorisieren</span>';
                $op .= '</li>';
            }    

            //add trash
            $op .= '<li class="hyb_toTrash">';
            $op .= '<i class="fa fa-3x fa-trash-o"></i> ';
            $op .= '<span>ausblenden</span>';
            $op .= '</li>';              
            
            $op .= '</ul>';
            $op .= '</div>';
            
            $op .= '</div><!-- <div class="hyb_table_header col-lg-12 col-md-12 col-xs-12"> -->';
            $op .= '</div><!-- <div class="row"> -->';
            
            $op .= '<div class="row">';
            $op .= '<div class="col-lg-12 col-md-12 col-xs-12">';            
                 
            $op .= '<table class="table striped">';
            foreach ($this->arrWhiteListKeys as $key) 
            {
                $value = $objTable->getValueOfDateset($key);
                $elemClass_datasets = $this->mkClassName($key);
                $op .= '<tr>';
                $op .= '<th>' . htmlspecialchars($key) . '</th>';
                $op .= '<td class="hyb_ds_' . htmlspecialchars($elemClass_datasets) 
                        . '">' . htmlspecialchars($value) . '</td>';
                $op .= '</tr>';
            } 
            $op .= '</table>';
            
            //add other key values pares
            $op .= '<button '
                    . 'class="hyb_table_more" '
                    . 'data-toggle="collapse" '
                    . 'data-target="#collapse_' . htmlentities($tablenameClass) . '"'
                    . '>alle Daten anzeigen  <i class="fa fa-chevron-down"></i></button>';
            
            $op .= '<div id="collapse_' . htmlentities($tablenameClass) . '" class="collapse">';                
            $op .= '<table class="table striped">';
                        
            foreach($arrObjDatasets as &$objDataset) 
            {
                $key = $objDataset->getKey();
                if(true === in_array($key, $this->arrWhiteListKeys)
                   ||
                   true === in_array($key, $this->arrBlackListKeys)) 
                {                    
                    continue;
                }
                
                $value = $objDataset->getValue();
                $op .= '<tr>';
                $op .= '<th>' . htmlspecialchars($key) . '</th>';
                $op .= '<td>' . htmlspecialchars($value) . '</td>';
                $op .= '</tr>';                                
            }
            $op .= '</table>';
            $op .= '</div><!-- id="collapse_..."-->';
            
            $op .= '</div><!-- <div class="col-lg-12 col-md-12 col-xs-12"> -->';
            $op .= '</div><!-- <div class="row"> -->';            
            
            $op .= '</div><!-- <div class="hyb_tablename_ ... -->';
        }                         
        
        $op .= '</div><!-- <div class="hyb_comptableMobile"> -->';

        return $op;
    }

    /**
     * Converts a string into a css class-name
     * @param String $string
     * @return String
     * @throws \Exception
     */
    private function mkClassName($string) {
        if (false === is_string($string)) 
        {
            throw new \Exception(
            "Error Processing Request: mkClassName(),
                    'string must be a String.'", 1);
        }
        
        //use mb_strtolower to also convert Ä,Ö,Ü Character independent from 
        //the locale-settings
        $string = mb_strtolower($string,'UTF-8'); 
        $a = array('ä', 'ö', 'ü', 'ß', ' ', '-', '.', '(', ')');
        $b = array('ae', 'oe', 'ue', 'ss', '_', '_', '', '', '');
        $string = str_replace($a, $b, $string);

        return $string;
    }

}

?>
