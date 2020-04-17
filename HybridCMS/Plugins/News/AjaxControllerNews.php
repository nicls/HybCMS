<?php

namespace HybridCMS\Plugins\News;

/**
 * class AjaxControllerNews - Handles API-Requests from the client
 * for the News-Plugin
 *
 * @package News
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class AjaxControllerNews implements \HybridCMS\Ajax\IAjaxController {

    /**
     * Primary Key 
     * @var Integer
     */
    private $newsId;
    
    /**
     * NewsTeaser
     */
    private $objNewsTeaser;

    /**
     * indicates what to do
     * @var String
     */
    private $action;
   

    /**
     * __construct
     *
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    public function __construct(&$arrParams) {  
        
        try {
            
            //assign action
            $this->setAction(trim($arrParams['action'])); 
            
            ##################################
            ### Actions for User-purpose   ###
            ##################################
                        
            
            
            ##################################
            ### Actions for Admin-purpose  ###
            ##################################

            //prepare Actions and save necessary parameters
            if ($this->action === 'insertNewsTeaser') {
                $this->prepareActionInsertNewsTeaser($arrParams);
            } 
            
            //handle incoming params to delete a NewsTeaser
            else if ($this->action === 'deleteNewsTeaser') {
                $this->prepareActionDeleteNewsTeaser($arrParams);
            } 
                             
                      
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * handleAjaxRequest
     */
    public function handleAjaxRequest() {

        try {
            
            ##################################
            ### Actions for User-purpose   ###
            ##################################
            
            ##################################
            ### Actions for Admin-purpose  ###
            ##################################            

            //handle requests
            if ($this->action === 'insertNewsTeaser') {
                $this->insertNewsTeaser();
            }
            
            else if ($this->action === 'deleteNewsTeaser') {                               
                $this->deleteNewsTeaser();
            }            
        
        } catch (Exception $e) {

            throw $e;
        }
    }
    

    /**
     * prepareActionInsertNewsTeaser - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionInsertNewsTeaser(&$arrParams) {

        //add table
        if (isset($arrParams['url'], 
                  $arrParams['text'], 
                  $arrParams['title'], 
                  $arrParams['date'])) 
        {
            
            try {
                
                $objDate = \DateTime::createFromFormat('d-m-Y', 
                        trim($arrParams['date']));                
                
                //create new Table
                $this->objNewsTeaser = new \HybridCMS\Plugins\News\NewsTeaser(
                        trim($arrParams['title']), 
                        trim($arrParams['url']), 
                        trim($arrParams['text']), 
                        $objDate);

            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(
                        LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
            
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionInsertNewsTeaser,
                Paramter url, text, title, date is not given.", 1);
        }
    }
    
    
    /**
     * prepareActionInsertDataset - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionDeleteNewsTeaser(&$arrParams) {
        
        if (true === isset($arrParams['newsId'])) {
            try {
                $this->setNewsId((int)$arrParams['newsId']);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(
                        LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionDeleteNewsTeaser,
                Paramter newsId is not given.", 1);
        }
    }    


   
    /**
     * insertComptable
     * @returns void
     * @throws Exception
     */
    private function insertNewsTeaser() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->
                    getConnection();

            //database-object to operate on Tables
            $objDBNews = new \HybridCMS\Plugins\News\Database\DBNews();

            //insert ComptableName by headline
            $success = $objDBNews->insertNewsTeaser($db, $this->objNewsTeaser);
            
            $insertId = $db->insert_id;
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();            

            //echo response to the user
            if (true === $success) {                
                
                $this->objNewsTeaser->setNewsId($insertId);
                $arrNewsTeaser = $this->objNewsTeaser->toArray();
                foreach ($arrNewsTeaser as &$value) {
                    $value = htmlspecialchars($value);
                }
                $arrNewsTeaser['success'] = 'true';
                
                echo json_encode($arrNewsTeaser);
                
            } else {
                echo json_encode(array('success' => 'false'));
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    /**
     * deleteNewsTeaser
     * @returns void
     * @throws Exception
     */
    private function deleteNewsTeaser() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->
                    getConnection();

            //database-object to operate on Tables
            $objDBNews = new \HybridCMS\Plugins\News\Database\DBNews();

            //insert ComptableName by headline
            $affectedRows = $objDBNews->deleteNewsTeaser($db, $this->newsId);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo json_encode(array('success' => 'true'));
            } else {
                echo json_encode(array('success' => 'false'));
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    
    /**
     * setAction
     * @param String $action
     * @throws \Exception
     */
    private function setAction($action) {

        //check if action is an alphabetic String
        if (!ctype_alpha($action)) {

            throw new \Exception(
            "Error Processing Request: setAction(),
                       action must be alphanumeric.", 1);
        }

        $this->action = $action;
    }
    
    /**
     * setter newsId
     * @param Integer $newsId
     * @throws \InvalidArgumentException
     */
    public function setNewsId($newsId) 
    {    
        if(false === is_int($newsId)) {
            $msg = "newsId has to be an Integer.";
            throw new \InvalidArgumentException($msg);
        }
        
        if(0 > $newsId) {
            $msg = "newsId must not be greater than 0.";
            throw new \InvalidArgumentException($msg);            
        }
        $this->newsId = $newsId;
    }     
}

?>