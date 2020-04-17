<?php

namespace HybridCMS\Admin\AjaxController;

require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Admin/Auth/PasswordHash.php');

/**
 * class AjaxControllerUser - Handles API-Requests from the admins client
 * for handling user-operations
 *
 * @package AjaxController
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerUser implements \HybridCMS\Ajax\IAjaxController {

    /**
     * indicates what to do
     * @var String
     */
    private $action;

    /**
     * password of the user
     * @var String
     */
    private $password;

    /**
     * User
     * @var \HybridCMS\Auth\User 
     */
    private $objUser;

    /**
     *
     * @param mixed[] $params
     * @throws \Exception
     */
    public function __construct($params) {

        try {

            //check if username is given
            if (!isset($params['username'])) {

                throw new \Exception(
                'Error Processing Request: __construct(),
                       \$params[\'username\'] is not given.', 1);
            }

            //check if rolename is given
            if (!isset($params['rolename'])) {

                throw new \Exception(
                'Error Processing Request: __construct(),
                       \$params[\'rolename\'] is not given.', 1);
            }

            //check if email is given
            if (!isset($params['email'])) {

                throw new \Exception(
                'Error Processing Request: __construct(),
                       \$params[\'email\'] is not given.', 1);
            }

            //check if action is given
            if (!isset($params['action'])) {

                throw new \Exception(
                'Error Processing Request: __construct(),
                       \$params[\'action\'] is not given.', 1);
            }

            //assign action
            $this->setAction($params['action']);

            //create User
            $this->objUser = new \HybridCMS\Admin\Auth\User(
                    $params['username'], $params['rolename'], $params['email']
            );

            if (isset($params['password']) && !empty($params['password'])) {
                $this->setPassword($params['password']);
            }
            if (isset($params['twitter']) && !empty($params['twitter'])) {
                $this->objUser->setTwitter($params['twitter']);
            }
            if (isset($params['facebook']) && !empty($params['facebook'])) {
                $this->objUser->setFacebook($params['facebook']);
            }
            if (isset($params['googleplus']) && !empty($params['googleplus'])) {
                $this->objUser->setGoogleplus($params['googleplus']);
            }
            if (isset($params['youtube']) && !empty($params['youtube'])) {
                $this->objUser->setYoutube($params['youtube']);
            }
            if (isset($params['website']) && !empty($params['website'])) {
                $this->objUser->setWebsite($params['website']);
            }
            if (isset($params['aboutme']) && !empty($params['aboutme'])) {
                $this->objUser->setAboutme($params['aboutme']);
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
     *
     * @throws \Exception
     */
    public function handleAjaxRequest() {

        try {

            //handle request to delete a user
            if ($this->action === 'delete') {
                $this->deleteUser();
            }

            //handle request to update a user
            else if ($this->action === 'update') {
                $this->updateUser();
            }

            //handle request to insert a user
            else if ($this->action === 'insert') {
                $this->insertUser();
            }
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateUser
     *
     * @throws \Exception
     */
    private function updateUser() {

        try {

            /*
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
             * 
             */
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
     * deleteUser
     *
     * @throws \Exception
     */
    private function deleteUser() {

        try {

            /*

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
             * 
             */
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
     * insertUser
     *
     * @throws \Exception
     */
    private function insertUser() {

        if (!isset($this->password) || empty($this->password)) {
            throw new \Exception(
            'Error Processing Request: insertUser(),
                       Password not given.', 1);
        }

        try {

            //create hash of the password
            $objHasher = new \PasswordHash(6, false);
            $hash = $objHasher->HashPassword($this->password);
            
            //check if hashing was sucessfull
            if (strlen($hash) < 20) {
                unset($objHasher);

                throw new \Exception(
                'Error Processing Request: insertUser(),
                           Failed to hash new password', 1);
            }

            //create Database-Object
            $dbAuth = new \HybridCMS\Database\DBAuth();

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            $primKey = $dbAuth->insertUser($db, $this->objUser, $hash);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //echo response to the user
            if ($primKey > 0) {
                echo "true-user-saved";
            } else {
                echo 'false-user-not-saved';
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
     * setPassword
     *
     * @param String $password
     * @throws \Exception
     */
    private function setPassword($password) {

        // Passwords should never be longer than 72 characters to prevent DoS attacks
        if (!is_string($password) || strlen($password) > 72) {
            throw new \Exception(
            'Error Processing Request: setPassword(),
                       Password must be 72 characters or less.', 1);
        }

        $this->password = $password;
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