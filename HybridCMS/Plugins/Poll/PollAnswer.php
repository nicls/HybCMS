<?php

namespace HybridCMS\Plugins\Poll;

/**
 * class PollAnswer
 *
 * @package Poll
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class PollAnswer extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * Primary Key of a PollAnswer
     * @var Integer
     */
    private $answerId;
    
    /**
     * Custom answer of the user
     * @var String
     */
    private $customAnswer;
    
    /**
     * Timestamp when the answer was answered
     * @var Integer
     */
    private $timeCreated;

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($customAnswer = null) {

        try {

            //call constructor of parent class
            parent::__construct();

            if(!empty($customAnswer)) {
                $this->setCustomAnswer($customAnswer);
            }

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    } //end __construct
    
    
    /**
     * @param Integer $answerId
     * @throws \Exception
     */
    public function setAnswerId($answerId) {
        
        //check if $questionId is valid.
        if (!is_numeric($answerId)) {

            throw new \Exception(
            'Error Processing Request: setAnswerId(),
                    $answerId has to be numeric and lower than 10.', 1);
        }
        
        $this->answerId = $answerId;
    }
    
    /**
     * @param String $customAnswer
     * @throws \Exception
     */
    public function setCustomAnswer($customAnswer) {
        
        //check if $question is an String width 500 charekters length in maximum
        if (!is_string($customAnswer) || strlen($customAnswer) > 500) {

            throw new \Exception(
            'Error Processing Request: setCustomAnswer(),
                    $customAnswer must be an String width 500 charekters length in maximum.', 1);
        }
        
        $this->customAnswer = $customAnswer;
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
    
    public function getCustomAnswer() {
        return $this->customAnswer;
    }

    public function getAnswerId() {
        return $this->answerId;
    }  
    
    public function getTimeCreated() {
        return $this->timeCreated;
    }


}
?>