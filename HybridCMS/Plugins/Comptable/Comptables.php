<?php

namespace HybridCMS\Plugins\Comptable;

/**
 * class Comptables
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Comptables {
    
    /**
     * Name of the comptable
     * @var String
     */
    private $arrObjComptables;
       
        
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() { }//end __construct
    
    
    /**
     * addTable
     * @param \HybridCMS\Plugins\Comptable\Table $objTable
     * @throws \Exception
     */
    public function addComptable($objComptable) {
        
        //check if $objComptable is of type Table
        if(!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                
            throw new \Exception(
                "Error Processing Request: addComptable(),
                    'Comptable must be of type Comptable.'", 1);
        }
        
        $this->arrObjComptables[] = $objComptable;
    }
    
    /**
     * fetchComptable - fetches all tables from the Database
     * @returns Integer - number of Comptables fetched
     * @throws \Exception
     */
    public function fetchComptables() {
        try {
            
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();
            
            $this->arrObjComptables = $objDBComptable->selectAllComptables($db);
            
            return count($this->arrObjComptables);
                        
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    public function getArrObjComptables() {
        return $this->arrObjComptables;
    }


}

?>