<?php

namespace HybridCMS\Plugins\Poll\Database;

/**
 * class DBPoll
 *
 * @package Poll
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBPoll {

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {
        
    }

    /**
     * insertPoll
     * 
     * @param mysqli $db
     * @param string $pollName
     * @return Integer Primary Key
     */
    public function insertPoll($db, $pollName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_polls (
		pollname, timeCreated) VALUES (?, ?)';


            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $timeCreated = time();

            $stmt->bind_param('si', $pollName, $timeCreated);

            $success = $stmt->execute();

            //store primary-key of this Poll
            $insertId = $db->insert_id;

            //close Resources
            $stmt->close();

            return $insertId;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end insertPoll

    /**
     * insertPollQuestion
     * 
     * @param mysqli $db
     * @param PollQuestion $objPollQuestion
     * @return Integer Primary Key
     */
    public function insertPollQuestion($db, &$objPoll) {

        //statement-Object
        $stmt = null;                      

        try {

            //check if $objPoll is an instance of Poll
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing insertPollQuestion:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $pollId = $objPoll->getPollId();
            
            //check if $pollId is not null
            if (empty($pollId) || $pollId < 0) {
                throw new \Exception(
                'Error Processing insertPollQuestion:                 
                    Paramter $pollId must be greater than 0.', 1);
            }            

            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //check if a Question is missing
            if (empty($arrObjPollQuestions) || count($arrObjPollQuestions) === 0) {
                throw new \Exception(
                'Error Processing insertPollQuestion:                 
                    PollQuestion is missing.', 1);
            }

            $objPollQuestion = array_shift($arrObjPollQuestions);

            //get values   
            $question = $objPollQuestion->getQuestion();
            $views = 0;
            $timeCreated = time();
            $priority = $objPollQuestion->getPriority();
            $type = $objPollQuestion->getType();
            $active = (int) $objPollQuestion->getActive();

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_pollQuestions (
                pollId,
                question,
                views,
                timeCreated,
                priority,
                type,
                active
                ) VALUES (?,?,?,?,?,?,?)';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('isiiisi', $pollId, $question, $views, $timeCreated, $priority, $type, $active);

            //Execute Insert
            $success = $stmt->execute();

            //store primary-key of this Article
            $insertId = $db->insert_id;

            //close Resources
            $stmt->close();

            return $insertId;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end insertPollQuestion

    /**
     * insertPollAnswer
     * 
     * @param mysqli $db
     * @param String $customAnswer
     * @param Integer $questionId
     * @return Integer[]
     */
    public function insertPollAnswers($db, $objPoll) {              

        //statement-Object
        $stmt = null;
        
        $arrInsertIds = array();

        try {
            
            //check if $objPoll is an instance of Poll
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing insertPollQuestion:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //get current timestamp
            $timeCreated = time();

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_pollAnswers (
                questionId,
                customAnswer,
                timeCreated
                ) VALUES (?,?,?)';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('isi', $questionId, $customAnswer, $timeCreated);

            foreach ($arrObjPollQuestions as &$pollQuestion) {                                      
                
                $questionId = $pollQuestion->getQuestionId();
                $customAnswer = null;                       
                                
                //get PollAnswer
                $arrObjPollAnswers = $pollQuestion->getArrObjPollAnswers();
                if(count($arrObjPollAnswers) > 0) {
                    $objPollAnswer = $arrObjPollAnswers[0];
                    $customAnswer = $objPollAnswer->getCustomAnswer();
                }
                               
                
                $success = $stmt->execute();
                
                if(!$success) {
                    throw new \Exception("Execute failed!");
                }
                
                //store primary-key of this Answer
                $arrInsertIds[] = $db->insert_id;
            }
            

            //close Resources
            $stmt->close();

            return $arrInsertIds;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end insertPollAnswer

    /**
     * selectAllPolls
     * @param mysqli $db
     * @return Poll[]
     * @throws Exception
     */
    public function selectAllPolls($db) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT 
                hyb_polls.pollId,
                hyb_polls.pollName,
                hyb_polls.timeCreated,
                hyb_polls.info,
                hyb_pollQuestions.questionId, 
                hyb_pollQuestions.question, 
                hyb_pollQuestions.views,                
                hyb_pollQuestions.timeCreated, 
                hyb_pollQuestions.priority, 
                hyb_pollQuestions.type,
                hyb_pollQuestions.active,
                COUNT( hyb_pollAnswers.questionId ) AS selected
                    FROM hyb_polls 
                    LEFT OUTER JOIN hyb_pollQuestions USING ( pollId )
                    LEFT OUTER JOIN hyb_pollAnswers USING ( questionId ) 
                    GROUP BY pollId, questionId
                    ORDER BY priority ASC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->execute();
            $stmt->bind_result(
                    $pollId,
                    $pollName,
                    $timeCreatedPoll,
                    $info,
                    $questionId, 
                    $question, 
                    $views, 
                    $timeCreatedQuestion, 
                    $priority, 
                    $type, 
                    $active, 
                    $selected
            );

            //array to hold the Polls 
            $arrObjPolls = array();

            //fetch the data
            while ($stmt->fetch()) {

                //add polls
                if(empty($arrObjPolls['pollId-' . $pollId])) {
                   
                    //add each Poll
                    $objPoll = new \HybridCMS\Plugins\Poll\Poll($pollName);
                    $objPoll->setTimeCreated($timeCreatedPoll);
                    $objPoll->setPollId($pollId);   
                    
                    if(!empty($info)) {
                        $objPoll->setInfo($info);
                    }

                    $arrObjPolls['pollId-' . $pollId] = $objPoll;
                }     
                
                //add Questions
                if(!empty($question) && !empty($type)) {

                    //create new PollQuestions
                    $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion($question, $type);

                    $objPollQuestion->setQuestionId($questionId);
                    $objPollQuestion->setViews($views);
                    $objPollQuestion->setTimeCreated($timeCreatedQuestion);
                    $objPollQuestion->setPriority($priority);
                    $objPollQuestion->setActive((bool) $active);
                    $objPollQuestion->setSelected((int) $selected);

                    //add pollQuestion to the the poll
                    $arrObjPolls['pollId-' . $pollId]->addObjPollQuestion($objPollQuestion);
                }
            }

            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrObjPolls;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }
            
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end selectAllPolls

    /**
     * selectPollByPollId
     * @param mysqli $db
     * @return Poll
     * @throws Exception
     */
    public function selectPollByPollId($db, $pollId) {

        //statement-Object
        $stmt = null;
        
        $objPoll = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT 
                hyb_polls.pollId,
                hyb_polls.pollName,
                hyb_polls.timeCreated,
                hyb_polls.info,
                hyb_pollQuestions.questionId, 
                hyb_pollQuestions.question, 
                hyb_pollQuestions.views,                
                hyb_pollQuestions.timeCreated, 
                hyb_pollQuestions.priority, 
                hyb_pollQuestions.type,
                hyb_pollQuestions.active,
                COUNT( hyb_pollAnswers.questionId ) AS selected
                    FROM hyb_polls 
                    LEFT OUTER JOIN hyb_pollQuestions USING ( pollId )
                    LEFT OUTER JOIN hyb_pollAnswers USING ( questionId ) 
                    WHERE pollid = ?
                    GROUP BY questionId
                    ORDER BY priority ASC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('i', $pollId);
            $stmt->execute();
            $stmt->bind_result(
                    $pollId,
                    $pollName,
                    $timeCreatedPoll,
                    $info,
                    $questionId, 
                    $question, 
                    $views, 
                    $timeCreatedQuestion, 
                    $priority, 
                    $type, 
                    $active, 
                    $selected
            );
            
            if($stmt->fetch()) {
                
                $arrObjPollQuestions = array();
                
                $objPoll = new \HybridCMS\Plugins\Poll\Poll($pollName);
                $objPoll->setPollId($pollId);
                $objPoll->setTimeCreated($timeCreatedPoll);
                
                if(!empty($info)) {
                    $objPoll->setInfo($info);
                }                

                //fetch the questions of this poll
                do {
                    
                    if(!empty($question) && !empty($type)) {

                        //create new PollQuestions
                        $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion($question, $type);

                        $objPollQuestion->setQuestionId($questionId);
                        $objPollQuestion->setViews($views);
                        $objPollQuestion->setTimeCreated($timeCreatedQuestion);
                        $objPollQuestion->setPriority($priority);
                        $objPollQuestion->setActive((bool) $active);
                        $objPollQuestion->setSelected((int) $selected);

                        //add pollQuestion to the the poll
                        $objPoll->addObjPollQuestion($objPollQuestion);
                    }
                    
                } while($stmt->fetch());
         
            }

            //close Resources
            $stmt->close();

            //return Poll
            return $objPoll;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end selectPollByPollName    

    /**
     * selectArrObjPollQuestionsByPollId
     * @param mysqli $db
     * @return PollQuestion[]
     * @throws Exception
     */
    public function selectArrObjPollQuestionsByPollId($db, $pollId) {

        //statement-Object
        $stmt = null;

        try {

            $arrObjPollQuestions = array();

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT 
                questionId, 
                pollId, 
                question, 
                views,                
                hyb_pollQuestions.timeCreated, 
                priority, 
                hyb_pollQuestions.type,
                active,
                COUNT( hyb_pollAnswers.questionId ) AS selected
                    FROM hyb_pollQuestions
                    LEFT OUTER JOIN hyb_pollAnswers
                    USING ( questionId ) 
                    WHERE pollid = ?
                    GROUP BY questionId
                    ORDER BY priority ASC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('i', $pollId);
            $stmt->execute();
            $stmt->bind_result(
                    $questionId, $pollId, $question, $views, $timeCreated, $priority, $type, $active, $selected
            );

            //fetch the articles
            while ($stmt->fetch()) {

                //create new PollQuestions
                $objPollQuestion = new \HybridCMS\Plugins\Poll\PollQuestion($question, $type);

                $objPollQuestion->setQuestionId($questionId);
                $objPollQuestion->setViews($views);
                $objPollQuestion->setTimeCreated($timeCreated);
                $objPollQuestion->setPriority($priority);
                $objPollQuestion->setActive((bool) $active);
                $objPollQuestion->setSelected((int) $selected);

                //add pollQuestion to the array
                $arrObjPollQuestions[] = $objPollQuestion;
            }//end while
            //close Resources
            $stmt->close();

            //return all PollQuestion
            return $arrObjPollQuestions;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end selectArrObjPollQuestionsByPollName     

    /**
     * deletePoll
     *
     * @param mysqli $db
     * @param Integer $pollId
     * @return Integer - affected Rows
     * @throws \Exception
     */
    public function deletePoll($db, $pollId) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'DELETE FROM hyb_polls WHERE pollId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('i', $pollId);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end deletePoll

    /**
     * deletePollQuestionByQuestionId
     *
     * @param mysqli $db
     * @param Integer $questionId
     * @return Integer - affected Rows
     * @throws \Exception
     */
    public function deletePollQuestionByQuestionId($db, $questionId) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'DELETE FROM hyb_pollQuestions WHERE questionId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('i', $questionId);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end deletePollQuestionByQuestionId

    /**
     * updatePollQuestionPriority
     *
     * @param mysqli $db
     * @param Poll $objPoll
     * @return Integer - affected Rows
     */
    public function updatePollQuestionPriority($db, $objPoll) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objPoll is an instance of Poll
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updatePollQuestionPriority:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //check if a Question is missing
            if (empty($arrObjPollQuestions) || count($arrObjPollQuestions) === 0) {
                throw new \Exception(
                'Error Processing updatePollQuestionPriority:                 
                    PollQuestion is missing.', 1);
            }

            $objPollQuestion = array_shift($arrObjPollQuestions);
            $questionId = $objPollQuestion->getQuestionId();

            if (empty($questionId)) {
                throw new \Exception(
                'Error Processing updatePollQuestionPriority:                 
                    $questionId is not.', 1);
            }

            $priority = $objPollQuestion->getPriority();

            if (empty($priority)) {
                throw new \Exception(
                'Error Processing updatePollQuestionPriority:                 
                    $priority is not.', 1);
            }

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_pollQuestions SET priority = ? '
                    . 'WHERE questionId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ii', $priority, $questionId);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    } //end updatePriorityOfPollQuestion
    
    
    /**
     * updatePollQuestionType
     *
     * @param mysqli $db
     * @param Poll $objPoll
     * @return Integer - affected Rows
     */
    public function updatePollQuestionType($db, $objPoll) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objPoll is an instance of Poll
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updatePollQuestionType:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //check if a Question is missing
            if (empty($arrObjPollQuestions) || count($arrObjPollQuestions) === 0) {
                throw new \Exception(
                'Error Processing updatePollQuestionType:                 
                    PollQuestion is missing.', 1);
            }

            $objPollQuestion = array_shift($arrObjPollQuestions);
            $questionId = $objPollQuestion->getQuestionId();

            if (empty($questionId)) {
                throw new \Exception(
                'Error Processing updatePollQuestionType:                 
                    $questionId is not given.', 1);
            }

            $type = $objPollQuestion->getType();

            if (empty($type)) {
                throw new \Exception(
                'Error Processing updatePollQuestionType:                 
                    $type is not given.', 1);
            }

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_pollQuestions SET type = ? '
                    . 'WHERE questionId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $type, $questionId);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    
    /**
     * updatePollQuestionActive
     *
     * @param mysqli $db
     * @param Poll $objPoll
     * @return Integer - affected Rows
     */
    public function updatePollQuestionActive($db, $objPoll) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objPoll is an instance of Poll
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updatePollQuestionActive:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //check if a Question is missing
            if (empty($arrObjPollQuestions) || count($arrObjPollQuestions) === 0) {
                throw new \Exception(
                'Error Processing updatePollQuestionActive:                 
                    PollQuestion is missing.', 1);
            }

            $objPollQuestion = array_shift($arrObjPollQuestions);
            $questionId = $objPollQuestion->getQuestionId();

            if (empty($questionId)) {
                throw new \Exception(
                'Error Processing updatePollQuestionActive:                 
                    $questionId is not given.', 1);
            }

            $active = (int)$objPollQuestion->getActive();

            if ($active !== 1 && $active !== 0) {
                throw new \Exception(
                'Error Processing updatePollQuestionActive:                 
                    $active is not given.', 1);
            }

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_pollQuestions SET active = ? '
                    . 'WHERE questionId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ii', $active, $questionId);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * updatePollName
     *
     * @param mysqli $db
     * @param Poll $objPoll
     * @return Integer - affected Rows
     */
    public function updatePollName($db, $objPoll) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objPoll is an instance of PollQuestion
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updatePollName:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }

            $pollId = $objPoll->getPollId();

            if (empty($pollId)) {
                throw new \Exception(
                'Error Processing updatePollName:                 
                    $pollId is not defined.', 1);
            }

            $pollName = $objPoll->getPollName();                    

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_polls SET pollName = ? '
                    . 'WHERE pollId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $pollName, $pollId);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    } //end updatePollName
    
    /**
     * updateInfo
     *
     * @param mysqli $db
     * @param PollQuestion $objPoll
     * @return Integer - affected Rows
     */
    public function updateInfo($db, $objPoll) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objPoll is an instance of PollQuestion
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updatePollName:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }

            $pollId = $objPoll->getPollId();

            if (empty($pollId)) {
                throw new \Exception(
                'Error Processing updatePollName:                 
                    $pollId is not defined.', 1);
            }

            $info = $objPoll->getInfo();                    

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_polls SET info = ? '
                    . 'WHERE pollId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $info, $pollId);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }    
    
    /**
     * updatePollQuestion
     *
     * @param mysqli $db
     * @param PollQuestion $objPollQuestion
     * @return Integer - affected Rows
     */
    public function updatePollQuestion($db, $objPoll) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objPoll is an instance of PollQuestion
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updatePollName:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //check if a Question is missing
            if (empty($arrObjPollQuestions) || count($arrObjPollQuestions) === 0) {
                throw new \Exception(
                'Error Processing insertPollQuestion:                 
                    PollQuestion is missing.', 1);
            }

            $objPollQuestion = array_shift($arrObjPollQuestions);

            //get values   
            $question = $objPollQuestion->getQuestion(); 
            $questionId = $objPollQuestion->getQuestionId();

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_pollQuestions SET question = ? '
                    . 'WHERE questionId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $question, $questionId);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    } //end updatePollName    
    
    
    /**
     * updateViewsOfPollQuestions
     *
     * @param mysqli $db
     * @param Poll $objPoll
     * @return Integer - affected Rows
     */
    public function updateViewsOfPollQuestions($db, $objPoll) {

        //statement-Object
        $stmt = null;
        
        $affectedRows = 0;

        try {

            //check if $objPoll is an instance of Poll
            if (!($objPoll instanceof \HybridCMS\Plugins\Poll\Poll)) {
                throw new \Exception(
                'Error Processing updateViewsOfPollQuestions:                 
                    Paramter $objPoll must be an instance of Poll.', 1);
            }
            
            $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();

            //check if a Question is missing
            if (empty($arrObjPollQuestions) || count($arrObjPollQuestions) === 0) {
                throw new \Exception(
                'Error Processing updateViewsOfPollQuestions:                 
                    PollQuestion is missing.', 1);
            }

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_pollQuestions SET views = views + 1 '
                    . 'WHERE questionId = ? ';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('i', $questionId);

            foreach ($arrObjPollQuestions as $pollQuestion) {
                
                $questionId = $pollQuestion->getQuestionId();
                
                if(empty($questionId)) {
                    throw new \Exception(
                        'Error Processing updateViewsOfPollQuestions:                 
                            $questionId is missing.', 1);
                }
                
                $stmt->execute();
                
                //get number of updated Rows
                $affectedRows += $db->affected_rows;                
            }
           

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
}

?>
