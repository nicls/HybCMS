<?php

namespace HybridCMS\Plugins\Comptable;

/**
 * class AjaxControllerBookmarks - Handles API-Requests from the client
 * for the Bookmarks-Plugin
 *
 * @package Bookmarks
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerComptable implements \HybridCMS\Ajax\IAjaxController {

    private $objComptable;

    /**
     * indicates what to do
     * @var String
     */
    private $action;
    
    /**
     * orderBy attribute to order a table
     * @var String
     */
    private $orderBy;
    
    /**
     * direction is either asc or desc to sort a table
     * @var String
     */
    private $direction;
    
    /**
     * indicates wheather to use ABS in key-sorting or not
     * @var Boolean
     */
    private $useAbs;

    /**
     * __construct
     *
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    public function __construct(&$arrParams) {  
        
        try {

            //check wether params are complete
            if (!isset($arrParams['comptableName'])) {
                throw new \Exception(
                "Error Processing Ajax-Request: Comptable,
                                    Paramter are not valid.", 1);
            }

            //assign comptableName
            $this->objComptable = new \HybridCMS\Plugins\Comptable\Comptable(trim($arrParams['comptableName']));                         

            //assign action
            $this->setAction(trim($arrParams['action'])); 
            
            ##################################
            ### Actions for User-purpose   ###
            ##################################
            
            //prepare Actions and save necessary parameters
            if ($this->action === 'requestSortedTableNames') {
                $this->prepareActionRequestSortedTableNames($arrParams);
            } 
            
            
            
            ##################################
            ### Actions for Admin-purpose  ###
            ##################################

            //prepare Actions and save necessary parameters
            if ($this->action === 'insertTable') {
                $this->prepareActionInsertTable($arrParams);
            } 
            
            //handle incoming params to delete a Table
            else if ($this->action === 'deleteTable') {
                $this->prepareActionDeleteTable($arrParams);
            } 
            
            //handle incoming params to insert a Dataset
            else if ($this->action === 'insertDataset') {
                $this->prepareActionInsertDataset($arrParams);
            }
            
            //handle incoming params to delete a Dataset
            else if ($this->action === 'deleteDataset') {
                $this->prepareActionDeleteDataset($arrParams);
            }    
            
            //handle incoming params to update 'private' of a Dataset
            else if ($this->action === 'updatePrivateOnDataset') {
                $this->prepareActionUpdatePrivateOnDataset($arrParams);
            }   
            
            else if ($this->action === 'updateNoteOnDataset') {
                $this->prepareActionUpdateNoteOnDataset($arrParams);
            }             
            
            //handle incoming params to update 'note' of a table
            else if ($this->action === 'updateValueOnDataset') {            
                $this->prepareActionUpdateValueOnDataset($arrParams);
            }    
            
            //handle incoming params to update 'is_active' of a table
            else if ($this->action === 'updateIsActiveOnTable') {
                $this->prepareActionUpdateIsActiveOnTable($arrParams);
            }              
            
            //handle incoming params to update 'is_favorit' of a table
            else if ($this->action === 'updateIsFavoritOnTable') {
                $this->prepareActionUpdateIsFavoritOnTable($arrParams);
            }  

            //handle incoming params to update 'url' of a table
            else if ($this->action === 'updateUrlOnTable') {            
                $this->prepareActionUpdateUrlOnTable($arrParams);
            }      
            
            //handle incoming params to update 'imgUrl' of a table
            else if ($this->action === 'updateImgUrlOnTable') {            
                $this->prepareActionUpdateImgUrlOnTable($arrParams);
            } 
            
            //handle incoming params to update 'note' of a table
            else if ($this->action === 'updateNoteOnTable') {            
                $this->prepareActionUpdateNoteOnTable($arrParams);
            }   
            
            //handle incoming params to bulk insert datasets
            else if ($this->action === 'bulkInsertDatasets') {            
                $this->prepareActionBulkInsertDatasets($arrParams);
            }                    
                      
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
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
            
            //prepare Actions and save necessary parameters
            if ($this->action === 'requestSortedTableNames') {
                $this->selectTablesOrderedBy();
            } 
            
            
            
            ##################################
            ### Actions for Admin-purpose  ###
            ##################################            

            //handle requests
            if ($this->action === 'insertComptable') {
                $this->insertComptable();
            }
            
            else if ($this->action === 'deleteComptable') {                               
                $this->deleteComptable();
            }            

            else if ($this->action === 'insertTable') {
                $this->insertTable();
            }

            else if ($this->action === 'deleteTable') {
                $this->deleteTable();
            }
            
            else if ($this->action === 'insertDataset') {
                $this->insertDataset();
            }   
            
            else if ($this->action === 'deleteDataset') {
                $this->deleteDataset();
            }   
            
            else if ($this->action === 'updatePrivateOnDataset') {
                $this->updatePrivateOnDataset();
            } 
            
            else if ($this->action === 'updateValueOnDataset') {
                $this->updateValueOnDataset();
            }     
            
            else if ($this->action === 'updateNoteOnDataset') {
                $this->updateNoteOnDataset();
            } 
            
            else if ($this->action === 'updateIsActiveOnTable') {
                $this->updateIsActiveOnTable();                         
            }                
            
            else if ($this->action === 'updateIsFavoritOnTable') {
                $this->updateIsFavoritOnTable();                         
            }     
            
            else if ($this->action === 'updateUrlOnTable') {                                
                $this->updateUrlOnTable();                         
            }      
            
            else if ($this->action === 'updateImgUrlOnTable') {                                
                $this->updateImgUrlOnTable();                         
            }  
            
            else if ($this->action === 'updateNoteOnTable') {                                
                $this->updateNoteOnTable();                         
            }     
            
            
            else if ($this->action === 'bulkInsertDatasets') {                                
                $this->bulkInsertDatasets();                         
            }              
        } catch (Exception $e) {

            throw $e;
        }
    }
    
    /**
     * prepareActionBulkInsertDatasets
     * @param String[] $arrParams
     * @throws \Exception
     */
    private function prepareActionBulkInsertDatasets(&$arrParams) {
               
            
        //add table
        if (isset($arrParams['tableName'], $arrParams['datasetKeyVals'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));
                
                //parse key-value-pairs
                $arrKeyVals = explode("\n", (trim($arrParams['datasetKeyVals'])));
                $arrKeyVals = array_filter($arrKeyVals, 'trim');
                                
                foreach ($arrKeyVals as $keyVal) {
                    
                    $arrKeyVal = explode(';', $keyVal);
                                       
                    
                    if(count($arrKeyVal) === 2) {
                    
                        //create new Dataset
                        $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                                trim($arrKeyVal['0']), trim($arrKeyVal['1']));

                        $objTable->addDataset($objDataset);
                    }
                
                }
                
                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionInsertDataset,
                Paramter tableName, datasetKeyVals is not given.", 1);
        }
    }

    /**
     * prepareActionInsertDataset - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionInsertDataset(&$arrParams) {

        //add table
        if (isset($arrParams['tableName'], $arrParams['datasetKey'], $arrParams['datasetValue'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));

                //create new Dataset
                $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                        trim($arrParams['datasetKey']), trim($arrParams['datasetValue']));

                if (isset($arrParams['private'])) {
                    $objDataset->setPrivate((bool) $arrParams['private']);
                }

                if (isset($arrParams['datasetNote'])) {
                    $objDataset->setNote(trim($arrParams['datasetNote']));
                }

                $objTable->addDataset($objDataset);
                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionInsertDataset,
                Paramter tableName, datasetKey, datasetValue is not given.", 1);
        }
    }
    
    
    /**
     * prepareActionInsertDataset - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionDeleteDataset(&$arrParams) {
        
        //add table
        if (isset($arrParams['tableName'], $arrParams['datasetKey'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));

                //create new Dataset
                $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                        trim($arrParams['datasetKey']), 'dummy');

                $objTable->addDataset($objDataset);
                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionDeleteDataset,
                Paramter tableName, datasetKey is not given.", 1);
        }
    }    

    /**
     * prepareActionUpdatePrivateOnDataset - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdatePrivateOnDataset(&$arrParams) {               

        //add table
        if (isset($arrParams['tableName'], $arrParams['datasetKey'], $arrParams['private'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));

                //create new Dataset
                $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                        trim($arrParams['datasetKey']), 'dummy');
                $objDataset->setPrivate((bool) $arrParams['private']);

                $objTable->addDataset($objDataset);
                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdatePrivateOnDataset,
                Paramter tableName, datasetKey, private is not given.", 1);
        }
    }   
    
    /**
     * prepareActionUpdateNoteOnDataset - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateNoteOnDataset(&$arrParams) {               

        //add table
        if (isset($arrParams['tableName'], $arrParams['textValue'], $arrParams['datasetKey'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));

                //create new Dataset
                $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                        trim($arrParams['datasetKey']), 'dummy');
                $objDataset->setNote($arrParams['textValue']);

                $objTable->addDataset($objDataset);
                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateNoteOnDataset,
                Paramter tableName, datasetKey, textValue is not given.", 1);
        }
    }      
    
    /**
     * prepareActionUpdateValueOnDataset - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateValueOnDataset(&$arrParams) {               

        //add table
        if (isset($arrParams['tableName'], $arrParams['textValue'], $arrParams['datasetKey'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));

                //create new Dataset
                $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                        trim($arrParams['datasetKey']), $arrParams['textValue']);

                $objTable->addDataset($objDataset);
                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateValueOnDataset,
                Paramter tableName, datasetKey, textValue is not given.", 1);
        }
    }      
    
    
    /**
     * prepareActionUpdateIsActiveOnTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateIsActiveOnTable(&$arrParams) {                   

        //add table
        if (true === isset($arrParams['tableName'], $arrParams['isActive'])) 
        {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim(
                        $arrParams['tableName']));
                $objTable->setIsActive((bool) $arrParams['isActive']);                             

                $this->objComptable->addTable($objTable);
            } 
            catch (Exception $e) 
            {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(
                        LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateIsActiveOnTable,
                Paramter tableName, isActive is not given.", 1);
        }
    }   
    
    
    /**
     * prepareActionUpdateIsFavoritOnTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateIsFavoritOnTable(&$arrParams) {                   

        //add table
        if (isset($arrParams['tableName'], $arrParams['isFavorit'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));
                $objTable->setIsFavorit((bool) $arrParams['isFavorit']);                             

                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateIsFavoritOnTable,
                Paramter tableName, isFavorit is not given.", 1);
        }
    }   
    
    /**
     * prepareActionUpdateUrlOnTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateUrlOnTable(&$arrParams) {                   

        //add table
        if (isset($arrParams['tableName'], $arrParams['textValue'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));
                $objTable->setUrl($arrParams['textValue']);                             

                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateUrlOnTable,
                Paramter tableName, textValue is not given.", 1);
        }
    }   
    
    
    /**
     * prepareActionUpdateImgUrlOnTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateImgUrlOnTable(&$arrParams) {                   

        //add table
        if (isset($arrParams['tableName'], $arrParams['textValue'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));
                $objTable->setImgUrl($arrParams['textValue']);                             

                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateImgUrlOnTable,
                Paramter tableName, textValue is not given.", 1);
        }
    }  
    
    /**
     * prepareActionUpdateNoteOnTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateNoteOnTable(&$arrParams) {                   

        //add table
        if (isset($arrParams['tableName'], $arrParams['textValue'])) {
            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));
                $objTable->setNote($arrParams['textValue']);                             

                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateNoteOnTable,
                Paramter tableName, textValue is not given.", 1);
        }
    }     
    
    
    /**
     * prepareActionInsertTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionInsertTable(&$arrParams) {

        //add table
        if (isset($arrParams['tableName'])) {

            try {
                //create new Table
                $objTable = new \HybridCMS\Plugins\Comptable\Table(trim($arrParams['tableName']));

                if (isset($arrParams['isFavorit'])) {
                    $objTable->setIsFavorit((bool) $arrParams['isFavorit']);
                }

                if (isset($arrParams['tableNote'])) {
                    $objTable->setNote(trim($arrParams['tableNote']));
                }

                if (isset($arrParams['url'])) {
                    $objTable->setUrl(trim($arrParams['url']));
                }

                if (isset($arrParams['imgUrl'])) {
                    $objTable->setImgUrl(trim($arrParams['imgUrl']));
                }

                $this->objComptable->addTable($objTable);
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionInsertTable,
                Paramter tableName is not given.", 1);
        }
    }
    
    /**
     * prepareActionIRequestSortedTableNames - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionRequestSortedTableNames(&$arrParams) {               

        //add table
        if (isset($arrParams['sortBy'], $arrParams['direction'])) {

            try {
                                 
                $this->setOrderBy(trim($arrParams['sortBy']));                
                $this->setDirection(trim(strtolower($arrParams['direction'])));
                $this->setUseAbs(trim(strtolower($arrParams['useAbs'])));
                                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionInsertTable,
                Paramter tableName is not given.", 1);
        }
    }    

    /**
     * prepareActionDeleteTable - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionDeleteTable(&$arrParams) {

        //add table
        if (isset($arrParams['tableName'])) {

            $objTable = new \HybridCMS\Plugins\Comptable\Table($arrParams['tableName']);
            $this->objComptable->addTable($objTable);
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionDeleteTable,
                Paramter tableName is not given.", 1);
        }
    }
    
    /**
     * selectTablesOrderedBy
     * @throws Exception
     */
    private function selectTablesOrderedBy() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //insert ComptableName by headline
            $arrTableNames = $objDBComptable->selectTablesOrderedBy(
                    $db, 
                    $this->objComptable->getComptableName(),
                    $this->orderBy,
                    $this->direction,
                    $this->useAbs);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header to json
            header('Content-Type: text/javascript; charset=utf8');

            echo json_encode($arrTableNames);
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        } 
    }  

    /**
     * insertComptable
     * @returns void
     * @throws Exception
     */
    private function insertComptable() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //insert ComptableName by headline
            $success = $objDBComptable->insertComptable($db, $this->objComptable->getComptableName());

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($success) {
                echo "true-insertComptable-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-insertComptable-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * insertTable
     * @returns void
     * @throws Exception
     */
    private function insertTable() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //insert ComptableName by headline
            $success = $objDBComptable->insertTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($success) {
                echo "true-insertTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-insertTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * bulkInsertDatasets
     * @throws \Exception
     */
    private function bulkInsertDatasets() { 
            
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();            

            //insert dataset
            $success = $objDBComptable->bulkInsertDatasets($db, $this->objComptable);                    

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($success) {
                echo "true-bulkInsertDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-bulkInsertDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * insertDataset
     * @returns void
     * @throws Exception
     */
    private function insertDataset() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();            

            //insert dataset
            $success = $objDBComptable->insertDataset($db, $this->objComptable);                    

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($success) {
                echo "true-insertDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-insertDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * deleteTable
     * @returns void
     * @throws Exception
     */
    private function deleteTable() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //insert ComptableName by headline
            $affectedRows = $objDBComptable->deleteTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-deleteTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-deleteTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

        /**
     * deleteComptable
     * @returns void
     * @throws Exception
     */
    private function deleteComptable() {
           
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //delete Comptable
            $affectedRows = $objDBComptable->deleteComptable($db, $this->objComptable->getComptableName());                       

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-deleteComptable-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-deleteComptable-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * deleteDataset
     * @returns void
     * @throws Exception
     */
    private function deleteDataset() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //delete Dataset
            $success = $objDBComptable->deleteDataset($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($success) {
                echo "true-deleteDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-deleteDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * updateDatasetPrivate
     * @returns void
     * @throws Exception
     */
    private function updatePrivateOnDataset() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //delete Dataset
            $affectedRows = $objDBComptable->updatePrivateOnDataset($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-updatePrivateOnDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-updatePrivateOnDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }    
    
    /**
     * updateNoteOnDataset
     * @returns void
     * @throws Exception
     */
    private function updateNoteOnDataset() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //delete Dataset
            $affectedRows = $objDBComptable->updateNoteOnDataset($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-updateNoteOnDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-updateNoteOnDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }      
    
    
    /**
     * updateValueOnDataset
     * @returns void
     * @throws Exception
     */
    private function updateValueOnDataset() {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //delete Dataset
            $affectedRows = $objDBComptable->updateValueOnDataset($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-updateValueOnDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-updateValueOnDataset-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }      
    
    
    /**
     * updateIsActiveOnTable
     * @returns void
     * @throws Exception
     */
    private function updateIsActiveOnTable() 
    {
        try 
        {           
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::
                    getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\
                    Comptable\Database\DBComptable();

            //update table
            $affectedRows = $objDBComptable->
                    updateIsActiveOnTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) 
            {
                echo "true-updateIsActiveOnTable-of-" 
                . htmlspecialchars($this->objComptable->getComptableName());
            } 
            else 
            {
                echo "false-updateIsActiveOnTable-of-" 
                . htmlspecialchars($this->objComptable->getComptableName());
            }
        } 
        catch (Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }     
    
    
    /**
     * updateIsFavoritOnTable
     * @returns void
     * @throws Exception
     */
    private function updateIsFavoritOnTable() 
    {
        try 
        {           
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::
                    getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\
                    Comptable\Database\DBComptable();

            //update table
            $affectedRows = $objDBComptable->
                    updateIsFavoritOnTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) 
            {
                echo "true-updateIsFavoritOnTable-of-" 
                . htmlspecialchars($this->objComptable->getComptableName());
            } 
            else 
            {
                echo "false-updateIsFavoritOnTable-of-" 
                . htmlspecialchars($this->objComptable->getComptableName());
            }
        } 
        catch (Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }       
    
    
    /**
     * updateUrlOnTable
     * @returns void
     * @throws Exception
     */
    private function updateUrlOnTable() {
        try {           

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //update table
            $affectedRows = $objDBComptable->updateUrlOnTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-updateUrlOnTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-updateUrlOnTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }    
    
    /**
     * updateImgUrlOnTable
     * @returns void
     * @throws Exception
     */
    private function updateImgUrlOnTable() {
        try {           

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //update table
            $affectedRows = $objDBComptable->updateImgUrlOnTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-updateImgUrlOnTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-updateImgUrlOnTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }   
    
    /**
     * updateNoteOnTable
     * @returns void
     * @throws Exception
     */
    private function updateNoteOnTable() {
        try {           

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBComptable = new \HybridCMS\Plugins\Comptable\Database\DBComptable();

            //update table
            $affectedRows = $objDBComptable->updateNoteOnTable($db, $this->objComptable);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($affectedRows === 1) {
                echo "true-updateNoteOnTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            } else {
                echo "false-updateNoteOnTable-of-" . htmlspecialchars($this->objComptable->getComptableName());
            }
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
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
     * setOrderBy
     * @param String $orderBy
     * @throws \Exception
     */
    private function setOrderBy($orderBy) {             

        //check if action is an alphabetic String
        if (!is_string($orderBy) || strlen($orderBy) > 255) {

            throw new \Exception(
            "Error Processing Request: setOrderBy(),
                       orderBy must be alphanumeric.", 1);
        }  
                 
        $this->orderBy = $orderBy;
    } 
    
    /**
     * setDirection
     * @param String $direction
     * @throws \Exception
     */
    private function setDirection($direction) {              
        
        //check if action is an alphabetic String
        if (!in_array($direction, array('asc', 'desc'))) {

            throw new \Exception(
            "Error Processing Request: setDirection(),
                       direction must be alphanumeric.", 1);
        }
        $this->direction = $direction;
    }      
    
    /**
     * setUseAbs
     * @param String $direction
     * @throws \Exception
     */
    private function setUseAbs($useAbs) {              
        
        //check if action is valid
        if ('true' === $useAbs) {
            $this->useAbs = true;
            
        } else if ('false' === $useAbs){
            $this->useAbs = false;
        } else {
            throw new \Exception(
            "Error Processing Request: setUseAbs(),
                       useAbs must be 'true' or 'false'.", 1);
        }
    }      

}

?>