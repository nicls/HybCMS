<?php

namespace HybridCMS\Admin\AjaxController;

/**
 * class AjaxControllerComments - Handles API-Requests from the admins client
 * for handling comments-operations
 *
 * @package AjaxController
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerComments implements \HybridCMS\Ajax\IAjaxController {

    /**
     * indicates what to do
     * @var String
     */
    private $action;

    /**
     * primaryKey of the comment
     * @var Integer
     */
    private $commentId;

    /**
     *
     * @param mixed[] $params
     * @throws \Exception
     */
    public function __construct($params) {

        try {

            //check if commentId is given
            if (isset($params['commentId'])) {

                //assign commenId
                $this->setCommentId($params['commentId']);

            } else {
                throw new \Exception(
                        "Error Processing Request: __construct(),
                       commentId is not given.", 1);
            }

            //assign action
            $this->setAction($params['action']);
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * handleAjaxRequest
     *
     * @throws \Exception
     */
    public function handleAjaxRequest() {
        try {

            //handle request to delete a comment
            if ($this->action === 'delete') {
                $this->deleteComment();
            }

            //handle request to publish a comment
            else if($this->action === 'publish') {
                $this->updatePublished(true);
            }

            //handle request to block a comment
            else if($this->action === 'block') {
                $this->updatePublished(false);
            }

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updatePublished
     *
     * @param Boolean $published
     * @throws \Exception
     */
    private function updatePublished($published) {

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //create object of DBComments
            $objDBComments = new \HybridCMS\Database\DBComments();

            //update status
            $affectedRows = $objDBComments->updatePublished($db, $published, $this->commentId);

            if($affectedRows !== 1) {
                throw new \Exception(
                        "Error Processing Request: updatePublished(),
                       updating published failed. Affected Rows is " . $affectedRows . '.', 1);
            }

            //echo response to the user
            echo "true-" . $this->commentId;

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
     * deleteComment
     *
     * @throws \Exception
     */
    private function deleteComment() {

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //create object of DBComments
            $objDBComments = new \HybridCMS\Database\DBComments();

            //delete comment
            $affectedRows = $objDBComments->deleteComment($db, $this->commentId);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            if($affectedRows !== 1) {
                throw new \Exception(
                        "Error Processing Request: deleteComment(),
                       deleting comment failed. Affected Rows is " . $affectedRows . '.', 1);
            }

            //echo response to the user
            echo "true-" . $this->commentId;

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
     * setCommentId
     *
     * @param Integer $commentId
     * @throws \Exception
     */
    private function setCommentId($commentId) {

        //check if commentId is an Integer
        if (!is_numeric($commentId)) {
            throw new \Exception(
                    "Error Processing Request: setCommentId(),
                       commentId must be an Integer.", 1);
        }

        $this->commentId = $commentId;
    }

    /**
     * setAction
     *
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

}

?>