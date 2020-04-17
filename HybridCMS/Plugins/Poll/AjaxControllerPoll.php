<?php

namespace HybridCMS\Plugins\Poll;

/**
 * class AjaxControllerPoll - Handles API-Requests from the client
 * for the Poll-Plugin
 *
 * @package Poll
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class AjaxControllerPoll implements \HybridCMS\Ajax\IAjaxController {

    /**
     * @var Poll
     */
    private $objPoll;

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

            //check wether params are complete
            if (!isset($arrParams['pollName'], $arrParams['action'])) {
                throw new \Exception(
                "Error Processing Ajax-Request: Poll,
                                    Paramter are not valid.", 1);
            }                   

            //assign comptableName
            $this->objPoll = new \HybridCMS\Plugins\Poll\Poll(trim($arrParams['pollName']));           
                   

            //assign action
            $this->setAction(trim($arrParams['action'])); 
            
            ##################################
            ### Actions for User-purpose   ###
            ##################################
            
            //prepare Actions and save necessary parameters
            if ($this->action === 'submitPollAnswers') {
                $this->prepareActionSubmitPollAnswers($arrParams);
            } 
            
            else if($this->action === 'submitPollQuestions') {
                $this->prepareActionSubmitAllPollQuestionsInView($arrParams);
            }
                                  
            ##################################
            ### Actions for Admin-purpose  ###
            ##################################

            //prepare Actions and save necessary parameters
            if ($this->action === 'insertPollQuestion') {
                $this->prepareActionInsertPollQuestion($arrParams);
            } 
            
            else if ($this->action === 'deletePoll') {
                $this->prepareActionDeletePoll($arrParams);
            }                  
            
            else if ($this->action === 'deletePollQuestion') {
                $this->prepareActionDeletePollQuestion($arrParams);
            }     
            
            else if ($this->action === 'updatePollName') {
                $this->prepareActionUpdatePollName($arrParams);
            }      
            
            else if ($this->action === 'updateInfo') {
                $this->prepareActionUpdateInfo($arrParams);
            }              
            
            else if ($this->action === 'updatePollQuestion') {
                $this->prepareActionUpdatePollQuestion($arrParams);
            }   
            
            else if ($this->action === 'updatePollQuestionPriority') {
                $this->prepareActionUpdatePollQuestionPriority($arrParams);
            }             
            
            else if ($this->action === 'updatePollQuestionType') {
                $this->prepareActionUpdatePollQuestionType($arrParams);
            }      
            
            else if ($this->action === 'updatePollQuestionActive') {
                $this->prepareActionUpdatePollQuestionActive($arrParams);
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
            if ($this->action === 'submitPollAnswers') {
                $this->savePollAnswers();
            } 
            
            else if($this->action === 'submitPollQuestions') {
                $this->updateViewsOfPollQuestions();
            }
            
            ##################################
            ### Actions for Admin-purpose  ###
            ##################################            

            //handle requests
            if ($this->action === 'insertPoll') {
                $this->insertPoll();
            } 
                        
            else if($this->action === 'deletePoll') {
                $this->deletePoll();
            }
            
            else if($this->action === 'insertPollQuestion') {
                $this->insertPollQuestion();
            }            
                                    
            else if($this->action === 'deletePollQuestion') {
                $this->deletePollQuestion();
            }  
            
            else if($this->action === 'updatePollName') {
                $this->updatePollName();
            }         
            
            else if($this->action === 'updateInfo') {
                $this->updateInfo();
            }            
            
            else if($this->action === 'updatePollQuestion') {
                $this->updatePollQuestion();
            }  
            
            else if($this->action === 'updatePollQuestionPriority') {
                $this->updatePollQuestionPriority();
            }   
            
            else if($this->action === 'updatePollQuestionType') {
                $this->updatePollQuestionType();
            }    
            
            else if($this->action === 'updatePollQuestionActive') {
                $this->updatePollQuestionActive();
            }                          
            
       
        } catch (Exception $e) {

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
     * prepareActionSubmitAllPollQuestionsInView - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionSubmitAllPollQuestionsInView(&$arrParams) {             

        if (isset($arrParams['allQuestionIds']) 
                   && (!empty($arrParams['allQuestionIds']))) {
            try {
                
                    $allQuestionsIds = $arrParams['allQuestionIds'];
                
                    //remove trailing pipes
                    if(substr($allQuestionsIds, -1) == '|') {
                        $allQuestionsIds = substr($allQuestionsIds, 0, -1);
                    }                

                    //add CheckBoxAnswers
                    $arrAllQuestionIds = explode('|', $allQuestionsIds);
                    foreach ($arrAllQuestionIds as $questionId) {
                        $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion');
                        $objPollQuestion->setQuestionId($questionId);
                        $this->objPoll->addObjPollQuestion($objPollQuestion);
                    }                                                    
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionSubmitAllPollQuestionsInView,
                something went worong: " . print_r($arrParams, true), 1);
        }
    }    
    
    /**
     * prepareActionSubmitPollAnswers - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionSubmitPollAnswers(&$arrParams) {             

        if (isset($arrParams['selectedQuestionIdRadio'], 
                  $arrParams['selectedQuestionIdsCheckbox'], 
                  $arrParams['selectedQuestionIdsCustomText']) 
                   && (!empty($arrParams['selectedQuestionIdRadio'])
                    || !empty($arrParams['selectedQuestionIdsCheckbox'])
                    || !empty($arrParams['selectedQuestionIdsCustomText']))) {
            try {
                
                
                ##################################
                ### Handle Radiobuttons ##########
                ##################################                
                
                //add selectedQuestionIdRadio
                if(!empty($arrParams['selectedQuestionIdRadio']) 
                        && is_numeric($arrParams['selectedQuestionIdRadio'])) {
                    $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion', 'radio');
                    $objPollQuestion->setQuestionId($arrParams['selectedQuestionIdRadio']);
                    $this->objPoll->addObjPollQuestion($objPollQuestion);
                }
                
                $questionIdsCheckbox = $arrParams['selectedQuestionIdsCheckbox'];
                $questionIdsCustomText = $arrParams['selectedQuestionIdsCustomText'];
                                
                ##################################
                ### Handle Checkboxes ############
                ##################################
                
                if(!empty($arrParams['selectedQuestionIdsCheckbox']))  {
                    
                    //remove trailing pipes
                    if(substr($questionIdsCheckbox, -1) == '|') {
                        $questionIdsCheckbox = substr($questionIdsCheckbox, 0, -1);
                    }                

                    //add CheckBoxAnswers
                    $arrCheckboxQuestionIds = explode('|', $questionIdsCheckbox);
                    foreach ($arrCheckboxQuestionIds as $checkboxQuestionId) {
                        $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion', 'checkbox');
                        $objPollQuestion->setQuestionId($checkboxQuestionId);
                        $this->objPoll->addObjPollQuestion($objPollQuestion);
                    }
                }
                
                ##################################
                ### Handle Textareas #############
                ##################################                

                if(!empty($arrParams['selectedQuestionIdsCustomText']))  {

                    if(substr($questionIdsCustomText, -1) == '|') {
                        $questionIdsCustomText = substr($questionIdsCustomText, 0, -1);
                    }   

                    //add CustomTextAnswers
                    $arrQuestionIdsCustomText = explode('|', $questionIdsCustomText);
                    $arrTmp = &$arrQuestionIdsCustomText;                               
                    
                    //check if each id has a coresponding customText
                    if(count($arrTmp) % 2 !== 0) {
                        throw new \Exception(
                        "Error Processing Request: prepareActionSubmitPollAnswers(),
                                   each id must have a coresponding customText.", 1);
                    }
                    
                    for($i=0; $i<count($arrTmp); $i++) {

                        $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion', 'customText');                                                     
                        $objPollQuestion->setQuestionId($arrTmp[$i]);                  
                        $objPollAnswer = new \HybridCMS\Plugins\Poll\PollAnswer($arrTmp[++$i]);                                                
                        $objPollQuestion->addPollAnswer($objPollAnswer);
                        $this->objPoll->addObjPollQuestion($objPollQuestion);
                                                                                                                        
                    }
                }                                      
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionSubmitPollAnswers,
                simething went wrong: " . print_r($arrParams, true), 1);
        }
    }    
    
    
    /**
     * prepareActionUpdatePollQuestionActive - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdatePollQuestionActive(&$arrParams) {

        if (isset($arrParams['pollId'], $arrParams['checkboxState'], $arrParams['pollQuestionId']) 
                && !empty($arrParams['pollId'])
                && !empty($arrParams['pollQuestionId'])) {
            try {

                //create new PollQuestion
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion');
                $objPollQuestion->setQuestionId($arrParams['pollQuestionId']);
                $objPollQuestion->setActive((bool)$arrParams['checkboxState']);
                
                //add id and question
                $this->objPoll->setPollId($arrParams['pollId']);
                $this->objPoll->addObjPollQuestion($objPollQuestion);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdatePollQuestionActive,
                pollId is not given: " . print_r($arrParams, true), 1);
        }
    }    
    

    /**
     * prepareActionUpdatePollQuestionType - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdatePollQuestionType(&$arrParams) {

        if (isset($arrParams['pollId'], $arrParams['optionValue'], $arrParams['pollQuestionId']) 
                && !empty($arrParams['optionValue']) 
                && !empty($arrParams['pollId'])
                && !empty($arrParams['pollQuestionId'])) {
            try {

                //create new PollQuestion
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion');
                $objPollQuestion->setQuestionId($arrParams['pollQuestionId']);
                $objPollQuestion->setType($arrParams['optionValue']);
                
                //add id and question
                $this->objPoll->setPollId($arrParams['pollId']);
                $this->objPoll->addObjPollQuestion($objPollQuestion);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdatePollQuestionType,
                pollId is not given.", 1);
        }
    }    
    
    /**
     * prepareActionUpdatePollQuestionPriority - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdatePollQuestionPriority(&$arrParams) {

        if (isset($arrParams['pollId'], $arrParams['optionValue'], $arrParams['pollQuestionId']) 
                && !empty($arrParams['optionValue']) 
                && !empty($arrParams['pollId'])
                && !empty($arrParams['pollQuestionId'])) {
            try {

                //create new PollQuestion
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion');
                $objPollQuestion->setQuestionId($arrParams['pollQuestionId']);
                $objPollQuestion->setPriority($arrParams['optionValue']);
                
                //add id and question
                $this->objPoll->setPollId($arrParams['pollId']);
                $this->objPoll->addObjPollQuestion($objPollQuestion);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdatePollQuestionPriority,
                pollId is not given.", 1);
        }
    }         
    
    /**
     * prepareActionUpdatePollName - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdatePollName(&$arrParams) {

        //add table
        if (isset($arrParams['pollId'], $arrParams['textValue']) && !empty($arrParams['textValue']) && !empty($arrParams['pollId'])) {
            try {

                //add id and name
                $this->objPoll->setPollName($arrParams['textValue']);
                $this->objPoll->setPollId($arrParams['pollId']);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdatePollName,
                pollId is not given.", 1);
        }
    }     
    
    /**
     * prepareActionUpdateInfo - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdateInfo(&$arrParams) {

        //add table
        if (isset($arrParams['pollId'], $arrParams['textValue']) && !empty($arrParams['textValue']) && !empty($arrParams['pollId'])) {
            try {

                //add id and name
                $this->objPoll->setInfo($arrParams['textValue']);
                $this->objPoll->setPollId($arrParams['pollId']);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionUpdateInfo,
                pollId is not given.", 1);
        }
    }        
    
    /**
     * prepareActionUpdatePollName - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionUpdatePollQuestion(&$arrParams) {

        if (isset($arrParams['pollId'], $arrParams['textValue'], $arrParams['pollQuestionId']) 
                && !empty($arrParams['textValue']) 
                && !empty($arrParams['pollId'])
                && !empty($arrParams['pollQuestionId'])) {
            try {

                //create new PollQuestion
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion($arrParams['textValue']);
                $objPollQuestion->setQuestionId($arrParams['pollQuestionId']);
                
                //add id
                $this->objPoll->setPollId($arrParams['pollId']);
                $this->objPoll->addObjPollQuestion($objPollQuestion);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionDeletePoll,
                pollId is not given.", 1);
        }
    }      
    
    /**
     * prepareActionDeletePoll - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionDeletePoll(&$arrParams) {

        //add table
        if (isset($arrParams['pollId'])) {
            try {

                //add Question to Poll
                $this->objPoll->setPollId($arrParams['pollId']);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionDeletePoll,
                pollId is not given.", 1);
        }
    }      
    
    
    /**
     * prepareActionDeletePollQuestion - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionDeletePollQuestion(&$arrParams) {

        //add table
        if (isset($arrParams['pollQuestionId'])) {
            try {
                //create new PollQuestion
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion('dummyQuestion');
                $objPollQuestion->setQuestionId($arrParams['pollQuestionId']);

                //add Question to Poll
                $this->objPoll->addObjPollQuestion($objPollQuestion);
                
            } catch (Exception $e) {

                //Log Error
                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                $objLogger->logError($e->__toString() . "\n");

                throw $e;
            }
        } else {
            throw new \Exception(
            "Error Processing Ajax-Request: 
                prepareActionDeletePollQuestion,
                pollQuestionId is not given.", 1);
        }
    }    
    
   /**
     * prepareActionDeletePollQuestion - e.g. save submitted GET-values 
     * that are necessary for this action
     */
    private function prepareActionInsertPollQuestion(&$arrParams) {
        
        //add table
        if (isset($arrParams['pollId'],
                  $arrParams['pollQuestion'], 
                  $arrParams['pollQuestionType'], 
                  $arrParams['pollQuestionPriority'], 
                  $arrParams['pollQuestionActive'])) {
            
            try {
                
                $this->objPoll->setPollId($arrParams['pollId']);
                
                //create new PollQuestion
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion($arrParams['pollQuestion']);
                $objPollQuestion->setType($arrParams['pollQuestionType']);
                $objPollQuestion->setPriority($arrParams['pollQuestionPriority']);
                $objPollQuestion->setActive((bool)$arrParams['pollQuestionActive']);

                //add Question to Poll
                $this->objPoll->addObjPollQuestion($objPollQuestion);
                
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
     * requestPoll
     * @returns void
     * @throws Exception
     */
    private function requestPoll() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert Poll
            $objPoll = $objDBPoll->selectPollByPollId($db, $this->objPoll->getPollId());

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
            
            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
            
            //fill poll
            $arrJson['poll']['pollId'] = $objPoll->getPollId();
            $arrJson['poll']['pollName'] = $objPoll->getPollName();
            
            //fill questions
            $arrObjQuestions = $objPoll->getArrObjPollQuestions();
            
            for($i=0; $i>count($arrObjQuestions); $i++) {
                                
                $q &= $arrObjQuestions;
                
                $arrJson['pollQuestions-' . $i]['questionId'] = $q->getQuestionId();
                $arrJson['pollQuestions-' . $i]['question'] = $q->getQuestion();
                $arrJson['pollQuestions-' . $i]['type'] = $q->getType();
                $arrJson['pollQuestions-' . $i]['active'] = (int)$q->getActive();
            }
            
            //info
            $arrJson['info']['action'] = 'select';
            $arrJson['info']['object'] = 'poll';        
                
            //echo response to the user
            if (!empty($objPoll)) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }                          
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * insertPoll
     * @returns void
     * @throws Exception
     */
    private function insertPoll() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert Poll
            $primKey = $objDBPoll->insertPoll($db, $this->objPoll->getPollName());

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
            
            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
            $arrJson['poll']['pollId'] = $primKey;
            $arrJson['poll']['pollName'] = $this->objPoll->getPollName();
            $arrJson['info']['action'] = 'insert';
            $arrJson['info']['object'] = 'poll';        
                
            //echo response to the user
            if ($primKey > 0) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }                          
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * insertPoll
     * @returns void
     * @throws Exception
     */
    private function insertPollQuestion() {
               
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $primKey = $objDBPoll->insertPollQuestion($db, $this->objPoll);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
            
            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
            $arrJson['pollQuestion']['questionId'] = $primKey;
                
            //echo response to the user
            if ($primKey > 0) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * deletePoll
     * @returns void
     * @throws Exception
     */
    private function deletePollQuestion() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();
            
            //get questionId
            $arrObjPollQuestions = $this->objPoll->getArrObjPollQuestions();
            $objPollQuestion = array_shift($arrObjPollQuestions);
            $questionId = $objPollQuestion->getQuestionId();

            //insert PollName by headline
            $affectedRows = $objDBPoll->deletePollQuestionByQuestionId($db, $questionId);                     

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
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
     * deletePoll
     * @returns void
     * @throws Exception
     */
    private function deletePoll() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->deletePoll($db, $this->objPoll->getPollId());

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
            $arrJson['poll']['pollId'] = $this->objPoll->getPollId();
            $arrJson['info']['action'] = 'delete';
            $arrJson['info']['object'] = 'poll';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updatePollName
     * @returns void
     * @throws Exception
     */
    private function updatePollName() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->updatePollName($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
            $arrJson['poll']['pollId'] = $this->objPoll->getPollId();
            $arrJson['poll']['pollName'] = $this->objPoll->getPollName();
            $arrJson['info']['action'] = 'update';
            $arrJson['info']['object'] = 'poll';
            $arrJson['info']['attribut'] = 'pollName';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updateInfo
     * @returns void
     * @throws Exception
     */
    private function updateInfo() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollInfo
            $affectedRows = $objDBPoll->updateInfo($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
                    
            $arrJson = array();
            $arrJson['poll']['pollId'] = $this->objPoll->getPollId();
            $arrJson['poll']['info'] = htmlspecialchars($this->objPoll->getInfo());
            $arrJson['info']['action'] = 'update';
            $arrJson['info']['object'] = 'poll';
            $arrJson['info']['attribut'] = 'info';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updatePollQuestion
     * @returns void
     * @throws Exception
     */
    private function updatePollQuestion() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->updatePollQuestion($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
            
            $arrObjPollQuestions = $this->objPoll->getArrObjPollQuestions();
            $objPollQuestion = array_shift($arrObjPollQuestions);
            
            $arrJson = array();
            $arrJson['pollQuestion']['questionId'] = $objPollQuestion->getQuestionId();
            $arrJson['pollQuestion']['question'] = htmlspecialchars($objPollQuestion->getQuestion());
            $arrJson['info']['action'] = 'update';
            $arrJson['info']['object'] = 'pollQuestion';
            $arrJson['info']['attribut'] = 'question';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updatePollQuestionPriority
     * @returns void
     * @throws Exception
     */
    private function updatePollQuestionPriority() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->updatePollQuestionPriority($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
            
            $arrObjPollQuestions = $this->objPoll->getArrObjPollQuestions();
            $objPollQuestion = array_shift($arrObjPollQuestions);
            
            $arrJson = array();
            $arrJson['pollQuestion']['questionId'] = $objPollQuestion->getQuestionId();
            $arrJson['pollQuestion']['priority'] = $objPollQuestion->getPriority();
            $arrJson['info']['action'] = 'update';
            $arrJson['info']['object'] = 'pollQuestion';
            $arrJson['info']['attribut'] = 'priority';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updatePollQuestionType
     * @returns void
     * @throws Exception
     */
    private function updatePollQuestionType() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->updatePollQuestionType($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
            
            $arrObjPollQuestions = $this->objPoll->getArrObjPollQuestions();
            $objPollQuestion = array_shift($arrObjPollQuestions);
            
            $arrJson = array();
            $arrJson['pollQuestion']['questionId'] = $objPollQuestion->getQuestionId();
            $arrJson['pollQuestion']['type'] = $objPollQuestion->getType();
            $arrJson['info']['action'] = 'update';
            $arrJson['info']['object'] = 'pollQuestion';
            $arrJson['info']['attribut'] = 'type';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updatePollQuestionActive
     * @returns void
     * @throws Exception
     */
    private function updatePollQuestionActive() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->updatePollQuestionActive($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
            
            $arrObjPollQuestions = $this->objPoll->getArrObjPollQuestions();
            $objPollQuestion = array_shift($arrObjPollQuestions);
            
            $arrJson = array();
            $arrJson['pollQuestion']['questionId'] = $objPollQuestion->getQuestionId();
            $arrJson['pollQuestion']['active'] = (int)$objPollQuestion->getActive();
            $arrJson['info']['action'] = 'update';
            $arrJson['info']['object'] = 'pollQuestion';
            $arrJson['info']['attribut'] = 'active';
                
            //echo response to the user
            if ($affectedRows == 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * savePollAnswers
     * @returns void
     * @throws Exception
     */
    private function savePollAnswers() {            
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollAnswers
            $arrInsertIds = $objDBPoll->insertPollAnswers($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
            
            $arrObjPollQuestions = $this->objPoll->getArrObjPollQuestions();
            $objPollQuestion = array_shift($arrObjPollQuestions);
            
            $arrJson = array();
            $arrJson['info']['action'] = 'submitPollAnswers';
            $arrJson['info']['object'] = 'pollAnswer';
                
            //echo response to the user
            if (count($arrInsertIds) > 0) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
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
     * updateViewsOfPollQuestions
     * @returns void
     * @throws Exception
     */
    private function updateViewsOfPollQuestions() {
                    
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();

            //insert PollName by headline
            $affectedRows = $objDBPoll->updateViewsOfPollQuestions($db, $this->objPoll);                  

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //set header Content Type
            header("Content-Type: application/json");	
                        
            $arrJson = array();
            $arrJson['info']['action'] = 'submitPollQuestions';
            $arrJson['info']['object'] = 'pollQuestion';
            $arrJson['info']['attribut'] = 'views';
                
            //echo response to the user
            if ($affectedRows >= 1) {
                $arrJson['info']['status'] = 'successful';
            } else {
                $arrJson['info']['status'] = 'failed';
            }
                          
            echo json_encode($arrJson, JSON_FORCE_OBJECT);
            
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }       
}

?>