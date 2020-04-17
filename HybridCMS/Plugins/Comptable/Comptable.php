<?php

namespace HybridCMS\Plugins\Comptable;

/**
 * class Comptable
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Comptable extends \HybridCMS\Plugins\Plugin\Plugin {

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
     * __construct
     * @throws \Exception
     */
    public function __construct($comptableName) {

        try {

            //call constructor of parent class
            parent::__construct();

            $this->setComptableName($comptableName);

            //Add JSResources
            $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                    'comptable', '/HybridCMS/Plugins/Comptable/js/f.js', 3, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource1);

            $objJSResource2 = new \HybridCMS\Page\Resources\JSResource(
                    'comptableInit', '/HybridCMS/Plugins/Comptable/js/init.js', 4, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource2);

            $objJSResource3 = new \HybridCMS\Page\Resources\JSResource(
                    'spinner', '/HybridCMS/Plugins/Comptable/js/spin.js', 4, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource3);                     

            //Add CSSResource
            $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                    'comptable', '/HybridCMS/Plugins/Comptable/css/f.css');
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
    private function toStringComptable() {

        $op = '';
        $op .= '<table class="hyb_comptable">';
        if (true === is_array($this->arrObjTables) 
            && 
            count($this->arrObjTables) > 0) 
        {

            $key = key($this->arrObjTables);
            $objTable = $this->arrObjTables[$key];
            $arrObjDatasets = $objTable->getArrObjDatasets();

            //get table head
            $op .= '<thead>';
            $op .= '<tr>';

            //add comtableName
            $op .= '<th>';
            $op .= $this->comptableName;
            $op .= '</th>';

            //add dataset-keys
            foreach ($arrObjDatasets as &$objDataset) {

                $key = $objDataset->getKey();
                $key_className = $this->mkClassName($key);
                
                $op .= '<th class="visibleDataset hyb_ds_' . htmlentities($key_className, ENT_COMPAT, 'UTF-8') . '">';
                $op .= '<span>';
                $op .= $objDataset->getKey();
                $op .= '</span>';

                //add pinning option
                $op .= '<span title="Diese Spalte fixieren/lösen">';
                $op .= '<i class="hyb_lock fa fa-unlock-alt fa-1"'
                        . 'comptableName="' . htmlentities($this->comptableName, ENT_COMPAT, 'UTF-8')
                        . '" key="' . htmlentities($objDataset->getKey(), ENT_COMPAT, 'UTF-8') . '"></i>';
                $op .= '</span>';

                //add sorting option
                $op .= '<i class="hyb_sortAtoZ fa fa-sort-alpha-asc fa-1" title="Diese Spalte sortieren"'
                        . 'comptableName="' . htmlentities($this->comptableName, ENT_COMPAT, 'UTF-8')
                        . '" key="' . htmlentities($objDataset->getKey(), ENT_COMPAT, 'UTF-8') . '"></i>';

                $op .= '</th>';
            }

            $op .= '</tr>';
            $op .= '</thead>';

            $op .= '<tbody id="hyb_tableBody">';

            //get tables
            foreach ($this->arrObjTables as &$objTable) 
            {
                
                //do not print inactive tables
                if(false === $objTable->getIsActive())
                {
                    continue;
                }

                $tableName = $objTable->getTableName();
                $tableName_className = $this->mkClassName($tableName);
                $url = $objTable->getUrl();
                $imgUrl = $objTable->getImgUrl();
                if(!empty($imgUrl)) {
                    $objImage = new \HybridCMS\Modules\Image(substr(HYB_ROOT, 0, -1) . $imgUrl);
                    $imgUrl = $objImage->scale(250);
                }
                $isFavorit = $objTable->getIsFavorit();
                $tableNote = $objTable->getNote();

                $isFavoritClassName = '';
                if($isFavorit) {
                    $isFavoritClassName = 'hyb_isFavorit';
                }
                
                $op .= '<tr class="'
                        . $isFavoritClassName 
                        .'" id="hyb_tbl_' 
                        . htmlentities($tableName_className, ENT_COMPAT, 'UTF-8') 
                        . '" itemscope itemtype="http://schema.org/Product"'
                        . '>';

                //add tableName
                $op .= '<td class="hyb_ds_table_name">';

                //image
                if (!empty($imgUrl)) {
                    
                    global $hcms;
                    if(false === $hcms->clientIsDesktop()) {
                        $imgUrl = str_replace('250x250', '30x30', $imgUrl);
                        $imgUrl = str_replace('250x249', '30x30', $imgUrl); 
                    }
                    $op .= '<span class="img">';
                    $op .= '<img class="borderRadius5 add-right-10" src="' . htmlentities($imgUrl, ENT_COMPAT, 'UTF-8')
                            . '" title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8')
                            . '" height="30" width="30">';
                    $op .= '</span>';
                }

                //name
                $op .= '<span class="hyb_tableName" itemprop="name">';
                if (!empty($url)) {
                    $op .= '<a name="' . htmlentities($this->mkClassName($tableName), ENT_COMPAT, 'UTF-8') 
                            . '" data-tip="Zu ' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') 
                            . ' surfen" href="' . htmlentities($url, ENT_COMPAT, 'UTF-8') 
                            . '" title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') 
                            . ' bei Amazon ansehen" rel="nofollow" target="_blank"'
                            . '>';
                }                
                $op .= htmlspecialchars($tableName);
                if (!empty($url)) {
                    $op .= '</a>'; 
                }
                $op .= '</span>';

                //link
                if (!empty($url)) {
                    $op .= '<a href="' . htmlentities($url, ENT_COMPAT, 'UTF-8') . '" title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') . ' bei Amazon ansehen" rel="nofollow" target="_blank">';
                    $op .= '<i class="fa fa-external-link-square"></i>';
                    $op .= '</a>';
                }

                //internal link
                //$op .= '<a data-tip="Zum Kurzportrait von ' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') . '" href="#' . htmlentities($tableName_className, ENT_COMPAT, 'UTF-8') . '" title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') . '">';
                //$op .= '<i class="fa fa-info-circle"></i>';
                //$op .= '</a>';

                //favorit
                if ($isFavorit) {
                    $op .= '<span title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') . ' favorisieren">';
                    $op .= '<i class="hyb_demarcateAsFavorit fa fa-star"></i>';
                    $op .= '</span>';
                } else {
                    $op .= '<span title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') . ' favorisieren">';
                    $op .= '<i class="hyb_markAsFavorit fa fa-star-o"></i>';
                    $op .= '</span>';
                }

                //trash
                $op .= '<span title="' . htmlentities($tableName, ENT_COMPAT, 'UTF-8') . ' ausblenden">';
                $op .= '<i class="hyb_toTrash fa fa-trash-o"></i>';
                $op .= '</span>';

                $op .= '</td>';

                //add datasets
                $arrObjDatasets = $objTable->getArrObjDatasets();

                foreach ($arrObjDatasets as &$objDataset) {

                    $key = $objDataset->getKey();
                    $key_className = $this->mkClassName($key);
                    
                    $itemType = '';
                    $itempropName = '';
                    if($key == "Hersteller")
                    {
                        $itemType = 'itemscope itemtype="http://schema.org/brand"';
                        $itempropName = 'itemprop="name"';
                    }

                    $op .= '<td class="visibleDataset hyb_ds_' . htmlentities($key_className, ENT_COMPAT, 'UTF-8')
                            . '" id="hyb_ds_'
                            . htmlentities($tableName_className, ENT_COMPAT, 'UTF-8')
                            . '_' . htmlentities($key_className, ENT_COMPAT, 'UTF-8')
                            . '" ' . $itemType . '>';
                    $op .= '<span '. $itempropName .'>';
                    $op .= htmlspecialchars($objDataset->getValue());
                    $op .= '</span>';
                    $op .= '</td>';
                }


                $op .= '</tr>';
            }
        }
        $op .= '</tbody>';
        
            //Table Footer
            //#################################
            $op .= '<tfoot>';
            $op .= '<tr>';

            //add comtableName
            $op .= '<th>';
            $op .= $this->comptableName;
            $op .= '</th>';

            //add dataset-keys
            foreach ($arrObjDatasets as &$objDataset) {

                $key = $objDataset->getKey();
                $key_className = $this->mkClassName($key);

                $op .= '<th class="visibleDataset hyb_ds_' . htmlentities($key_className, ENT_COMPAT, 'UTF-8') . '">';
                $op .= '<span>';
                $op .= $objDataset->getKey();
                $op .= '</span>';

                //add pinning option
                $op .= '<span data-tip="Spalte ' . htmlentities($key, ENT_COMPAT, 'UTF-8') . ' fixieren">';
                $op .= '<i class="hyb_lock fa fa-unlock-alt fa-1"'
                        . 'comptableName="' . htmlentities($this->comptableName, ENT_COMPAT, 'UTF-8')
                        . '" key="' . htmlentities($objDataset->getKey(), ENT_COMPAT, 'UTF-8') . '"></i>';
                $op .= '</span>';

                //add sorting option
                $op .= '<i class="hyb_sortAtoZ fa fa-sort-alpha-asc fa-1"'
                        . 'comptableName="' . htmlentities($this->comptableName, ENT_COMPAT, 'UTF-8')
                        . '" key="' . htmlentities($objDataset->getKey(), ENT_COMPAT, 'UTF-8') . '"></i>';

                $op .= '</th>';
            }

            $op .= '</tr>';
            $op .= '</tfoot>';
        
        $op .= '</table>';

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
