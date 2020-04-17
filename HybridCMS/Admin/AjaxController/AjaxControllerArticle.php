<?php

namespace HybridCMS\Admin\AjaxController;

/**
 * class AjaxControllerArticle - Handles API-Requests from the admins client
 * for handling article-operations
 *
 * @package AjaxController
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerArticle implements \HybridCMS\Ajax\IAjaxController {

    /**
     * indicates what to do
     * @var String
     */
    private $action;

    /**
     * cssID of the main content
     * @var Integer
     */
    private $cssId;

    /**
     * url of the article
     * @var String
     */
    private $url;

    /**
     *
     * @param mixed[] $params
     * @throws \Exception
     */
    public function __construct($params) {

        try {

            //check if cssIs is given
            if (isset($params['cssId'])) {

                //assign cssId
                $this->setCssId($params['cssId']);
            } else {
                throw new \Exception(
                "Error Processing Request: __construct(),
                       cssId is not given.", 1);
            }

            //check if url is given
            if (isset($params['articleUrl'])) {

                //assign url
                $this->setUrl($params['articleUrl']);
            } else {
                throw new \Exception(
                "Error Processing Request: __construct(),
                       url is not given.", 1);
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
                $this->deleteArticle();
            }

            //handle request to publish a comment
            else if ($this->action === 'update') {
                $this->updateArticle();
            }

            //handle request to block a comment
            else if ($this->action === 'insert') {
                $this->insertArticle();
            }
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateArticle
     *
     * @throws \Exception
     */
    private function updateArticle() {

        try {

            //create ArticleFactory
            $articleFactory = new \HybridCMS\Content\Article\ArticleFactory();

            //fetch Article
            $objArticle = $articleFactory->createArticle($this->url, $this->cssId);

            //create Database-Object
            $dbArticle = new \HybridCMS\Database\DBArticle();

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            $affectedRows = $dbArticle->updateArticle($db, $objArticle);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
            
            //echo response to the user
            if($affectedRows == 1) echo "true-" . $this->url;
            else echo 'false-' . $this->url;
             
            
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
     * deleteArticle
     *
     * @throws \Exception
     */
    private function deleteArticle() {

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //create object of DBComments
            $objDBComments = new \HybridCMS\Database\DBComments();

            //delete comment
            $affectedRows = $objDBComments->deleteComment($db, $this->commentId);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            if ($affectedRows !== 1) {
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
     * insertArticle
     *
     * @throws \Exception
     */
    private function insertArticle() {

        try {          

            //create ArticleFactory
            $articleFactory = new \HybridCMS\Content\Article\ArticleFactory();
           
            //fetch Article
            $objArticle = $articleFactory->createArticle($this->url, $this->cssId);
                      
            //create Database-Object
            $dbArticle = new \HybridCMS\Database\DBArticle();

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection(); 

            $primKey = $dbArticle->insertArticle($db, $objArticle);           

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
            
            //echo response to the user
            if($primKey > 0) echo "true-" . $this->url;
            else echo 'false-' . $this->url;
                 
            
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
     * setCssId
     *
     * @param String $cssId
     * @throws \Exception
     */
    private function setCssId($cssId) {

        //check if $cssId is an String with letters
        if (!ctype_alpha($cssId)) {
            throw new \Exception(
            'Error Processing Request: setCssId(),
                       $cssId must be a String.', 1);
        }

        $this->cssId = $cssId;
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
            'Error Processing Request: setAction(),
                       action must be alphanumeric.', 1);
        }

        $this->action = $action;
    }

    /**
     * setUrl
     * @return void
     */
    private function setUrl($url) {
        //check if URL is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                        url is not valid.", 1);
        }

        //check if url is from current host
        if (stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) !== 0) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                        url is not from current host: " . htmlspecialchars($url), 1);
        }

        //set url
        $this->url = $url;
    }

}

?>