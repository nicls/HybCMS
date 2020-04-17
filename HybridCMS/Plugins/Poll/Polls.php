<?php

namespace HybridCMS\Plugins\Poll;

/**
 * class Polls
 *
 * @package Poll
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Polls {
    
    /**
     * List of Polls
     * @var Poll[]
     */
    private $arrObjPolls;
       
        
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() { }//end __construct
    
    
    /**
     * addPoll
     * @param \HybridCMS\Plugins\Poll\Poll $objPoll
     * @throws \Exception
     */
    public function addPoll($objPoll) {
        
        //check if $objPoll is of type Poll
        if(!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                
            throw new \Exception(
                'Error Processing Request: addPoll(),
                    $objPoll must be of type Poll.', 1);
        }
        
        $this->arrObjPolls[] = $objPoll;
    }
    
    /**
     * Fetches all Polls from the Database
     * @returns Integer - number of Polls fetched
     * @throws \Exception
     */
    public function fetchPolls() {
        try {
            
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();
            
            $this->arrObjPolls = $objDBPoll->selectAllPolls($db);
            
            return count($this->arrObjPolls);
                        
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    public function getArrObjPolls() {
        return $this->arrObjPolls;
    }


}

?>