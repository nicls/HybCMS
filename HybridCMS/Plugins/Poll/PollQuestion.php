<?php

namespace HybridCMS\Plugins\Poll;

/**
 * class PollQuestion
 *
 * @package Poll
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class PollQuestion extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * Primary Key of a PollQuestion
     * @var Integer
     */
    private $questionId;

    /**
     * poll-question
     * @var String
     */
    private $question;

    /**
     * type of the question. Allowed are the types 'customText', 'checkbox', 'radio'.
     * @var String
     */
    private $type;

    /**
     * View indicates how often the question was shown to the user.
     * @var Integer
     */
    private $views;

    /**
     * Indicates how often the Question was selected.
     * @var Integer
     */
    private $selected;

    /**
     * Timestamp of the time this questions was created
     * @var Integer
     */
    private $timeCreated;

    /**
     * The priority indicates the psoition of this question to other wquestions 
     * of the same poll.
     * @var Integer
     */
    private $priority;

    /**
     * Indicates of the question is active or not
     * @var Boolean
     */
    private $active;
    
    /**
     * PollAnswers of a PollQuestion
     * @var PollAnswer[]
     */
    private $arrObjPollAnswers;

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($question, $type = 'radio') {

        try {

            //call constructor of parent class
            parent::__construct();

            $this->setQuestion($question);
            $this->setType($type);
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end __construct

    /**
     * @param Integer $questionId
     * @throws \Exception
     */
    public function setQuestionId($questionId) {

        //check if $questionId is valid.
        if (!is_numeric($questionId)) {

            throw new \Exception(
            'Error Processing Request: setQuestionId(),
                    $questionId has to be numeric and lower than 10.', 1);
        }

        $this->questionId = $questionId;
    }

    /**
     * @param String $question
     * @throws \Exception
     */
    public function setQuestion($question) {

        //check if $question is an String width 255 charekters length in maximum
        if (!is_string($question) || strlen($question) > 255) {

            throw new \Exception(
            'Error Processing Request: setQuestion(),
                    $question must be an String width 255 charekters length in maximum.', 1);
        }

        $this->question = $question;
    }

    /**
     * @param type String
     * @throws \Exception
     */
    public function setType($type) {

        //check if $type is valid. Allowed are the types 'customText', 'checkbox', 'radio'.
        if (!is_string($type) || !in_array($type, array('customText', 'checkbox', 'radio'))) {

            throw new \Exception(
            'Error Processing Request: setType(),
                    $type is not valid.', 1);
        }

        $this->type = $type;
    }

    /**
     * @param Integer $views
     * @throws \Exception
     */
    public function setViews($views) {

        //check if $views is numeric
        if (!is_numeric($views)) {

            throw new \Exception(
            'Error Processing Request: setViews(),
                    $views is not valid.', 1);
        }

        $this->views = $views;
    }

    /**
     * @param Integer $selected
     * @throws \Exception
     */
    public function setSelected($selected) {

        //check if $selected is numeric
        if (!is_numeric($selected)) {

            throw new \Exception(
            'Error Processing Request: setViews(),
                    $selected is not valid.', 1);
        }

        $this->selected = $selected;
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
     * @param Integer $priority
     * @throws \Exception
     */
    public function setPriority($priority) {

        //check if $priority is valid.
        if (!is_numeric($priority) || $priority > 9) {

            throw new \Exception(
            'Error Processing Request: setPriority(),
                    $priority has to be numeric and lower than 10.', 1);
        }

        $this->priority = $priority;
    }

    /**
     * @param Boolean $actibve
     * @throws \Exception
     */
    public function setActive($active) {

        //check if $priority is valid.
        if (!is_bool($active)) {

            throw new \Exception(
            'Error Processing Request: setPriority(),
                    $actibve must be of type boolean.', 1);
        }

        $this->active = $active;
    }
    
    /**
     * addPollAnwer
     * @param \HybridCMS\Plugins\Poll\PollAnswer $objPollAnswer
     * @throws \Exception
     */
    public function addPollAnswer($objPollAnswer) {
        if(!($objPollAnswer instanceof \HybridCMS\Plugins\Poll\PollAnswer)) {
            throw new \Exception(
            'Error Processing Request: setPriority(),
                    $objPollAnswer must be of type PollAnwer.', 1);
        }
        
        $this->arrObjPollAnswers[] = $objPollAnswer;
    }
    
    public function getActive() {
        return $this->active;
    }
    
    public function getQuestionId() {
        return $this->questionId;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function getType() {
        return $this->type;
    }

    public function getViews() {
        return $this->views;
    }

    public function getSelected() {
        return $this->selected;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getPriority() {
        return $this->priority;
    }
    
    public function getArrObjPollAnswers() {
        return $this->arrObjPollAnswers;
    }



}

?>