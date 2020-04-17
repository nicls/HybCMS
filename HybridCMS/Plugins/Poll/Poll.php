<?php

namespace HybridCMS\Plugins\Poll;

/**
 * class Poll
 *
 * @package Poll
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Poll extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * Primary Key of the poll
     * @var Integer 
     */
    private $pollId;
    
    /**
     * poll-name
     * @var String
     */
    private $pollName;

    /**
     * Timestamp of the time this poll was created
     * @var Integer
     */
    private $timeCreated;
    
    /**
     * Info about the poll to the Poll-Admin
     * @var String
     */
    private $info;
    
    /**
     * Pollquestions of a Poll
     * @var pollQuestion[]
     */
    private $arrObjPollQuestions;
    

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($pollName = '') {

        try {

            //call constructor of parent class
            parent::__construct();
            
            if(!empty($pollName)) {
                $this->setPollName($pollName);
            }

            //Add JSResources
            $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                    'poll', '/HybridCMS/Plugins/Poll/js/f.js', 3, false, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource1);     
            
            //Add JSResources
            $objJSResource2 = new \HybridCMS\Page\Resources\JSResource(
                    'pollInit', '/HybridCMS/Plugins/Poll/js/init.js', 4, false, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource2);                  

            //Add CSSResource
            $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                    'poll', '/HybridCMS/Plugins/Poll/css/f.css');
            $this->addObjCSSResource($objCSSResource1);
                     
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    } //end __construct


    /**
     * Comptable to String
     * @returns String
     */
    public function toString($args = array()) {
        $op = '';
        
        //open Poll container
        $op .='<div id="pollContainer" pollId="'. $this->pollId .'">';
        
        //open header 
        $op .= '<header>';
        
        //add Pollname
        $op .= '<span><i class="fa fa-comments"></i> ';      
        $op .= htmlspecialchars($this->pollName);        
        $op .= '</span>';  
        
        $op .= '<i class="fa fa-plus-circle openPoll"></i>';
        
        //close header 
        $op .= '</header>';

        $op .= '<ul>';        
        
        //add questions
        foreach ($this->arrObjPollQuestions as $objQuestion) {
           
            if($objQuestion->getType() === 'radio' && $objQuestion->getActive()) {
                $op .= $this->toStringRadioQuestion($objQuestion);
            } else if($objQuestion->getType() === 'checkbox' && $objQuestion->getActive()) {
                $op .= $this->toStringCheckboxQuestion($objQuestion);
            } else if($objQuestion->getType() === 'customText' && $objQuestion->getActive()) {
                $op .= $this->toStringCustomTextQuestion($objQuestion);
            }
            
            
        }
        
        $op .= '</ul>';
        
        //send Button
        $op .= '<button id="hyb_savePollAnswer">Speichern</button>';
        
        //close pollContainer
        $op .= '</div><!-- end #pollContainer -->';
        
        return $op;        
    }
        
    /**
     * toStringRadioQuestion
     * @param PollQuestion $objQuestion
     * @return String
     */
    private function toStringRadioQuestion(&$objQuestion) {
        
        $op = '';
        $op .= '<li class="hyb_question_type_checkbox" type="'. htmlentities($objQuestion->getType()) .'" questionId="'. htmlentities($objQuestion->getQuestionId()) .'">';
        $op .= '<i class="fa fa-circle-o"></i>';
        $op .= '<span>';
        $op .= htmlspecialchars($objQuestion->getQuestion());
        $op .= '</span>';
        $op .= '</li>';

        return $op;
    }
    
    /**
     * toStringCheckboxQuestion
     * @param PollQuestion $objQuestion
     * @return String
     */
    private function toStringCheckboxQuestion(&$objQuestion) {
        $op = '';
        $op .= '<li class="hyb_question_type_checkbox" type="'. htmlentities($objQuestion->getType()) .'" questionId="'. htmlentities($objQuestion->getQuestionId()) .'">';
        $op .= '<i class="fa fa-square-o"></i>';
        $op .= '<span>';
        $op .= htmlspecialchars($objQuestion->getQuestion());
        $op .= '</span>';
        $op .= '</li>';

        return $op;
    }    
    
    /**
     * toStringCustomTextQuestion
     * @param PollQuestion $objQuestion
     * @return String
     */
    private function toStringCustomTextQuestion(&$objQuestion) {
        $op = '';
        $op .= '<li class="hyb_question_type_customText" type="'. htmlentities($objQuestion->getType()) .'" questionId="'. htmlentities($objQuestion->getQuestionId()) .'">';
        $op .= '<i class="fa fa-pencil-square"></i>';
        $op .= '<span>';
        $op .= htmlspecialchars($objQuestion->getQuestion());
        $op .= '</span>';
        $op .= '<textarea class="textAreaCustomText"></textarea>';
        $op .= '</li>';        

        return $op;
    }    
    
    /**
     * @param Integer $pollId
     * @throws \Exception
     */
    public function setPollId($pollId) {
        
        //check if $timeCreated is numeric
        if (!is_numeric($pollId) || $pollId < 0) {

            throw new \Exception(
            'Error Processing Request: setPollId(),
                    $pollId is not valid.', 1);
        }
        
        $this->pollId = $pollId;
    }    
    
    /**
     * @param String $pollName
     * @throws \Exception
     */
    public function setPollName($pollName) {
        
        //check if $pollName is an String width 100 charekters length in maximum
        if (!is_string($pollName) || strlen($pollName) > 100) {

            throw new \Exception(
            'Error Processing Request: setPollName(),
                    $pollName must be an String width 100 charekters length in maximum.', 1);
        }
        
        $this->pollName = $pollName;
    }


    /**
     * @param Integer $timeCreated
     * @throws \Exception
     */
    public function setTimeCreated($timeCreated) {
        
        //check if $timeCreated is numeric
        if (!is_numeric($timeCreated) || $timeCreated < 1390153380) {

            throw new \Exception(
            'Error Processing Request: setTimeCreated(),
                    $timeCreated is not valid.', 1);
        }
        
        $this->timeCreated = $timeCreated;
    }
    
    /**
     * @param String $info
     * @throws \Exception
     */
    public function setInfo($info) {
        
        //check if $info is an String width 255 charekters length in maximum
        if (!is_string($info) || strlen($info) > 255) {

            throw new \Exception(
            'Error Processing Request: setInfo(),
                    $info must be an String width 255 charekters length in maximum.', 1);
        }
        
        $this->info = $info;
    }    
    
    /**
     * Adds a PollQuestion
     * @param \HybridCMS\Plugins\Poll\PollQuestion $objPollQuestion
     * @throws \Exception
     */
    public function addObjPollQuestion($objPollQuestion) {
        if (!($objPollQuestion instanceof \HybridCMS\Plugins\Poll\PollQuestion)) {

            throw new \Exception(
            'Error Processing Request: addPollQuestion(),
                    $objPollQuestion is not of type PollQuestion.', 1);
        }
        
        $this->arrObjPollQuestions[] = $objPollQuestion;
    }
    
    /**
     * Fetches all PollQuestions from the Database
     * @returns Integer - number of Polls fetched
     * @throws \Exception
     */
    public function fetchPollQuestions() {
        try {
            
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();
            
            $this->arrObjPollQuestions = $objDBPoll->selectArrObjPollQuestionsByPollId($db, $this->pollId);
                        
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();    
            
            return count($this->arrObjPollQuestions);            
                        
        } catch (Exception $e) {
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();            

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }    
    
    public function getPollName() {
        return $this->pollName;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getArrObjPollQuestions() {
        return $this->arrObjPollQuestions;
    }

    public function getPollId() {
        return $this->pollId;
    }
    
    public function getInfo() {
        return $this->info;
    }

}
?>