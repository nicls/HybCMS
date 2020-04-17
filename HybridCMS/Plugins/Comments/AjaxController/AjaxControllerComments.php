<?php

namespace HybridCMS\Plugins\Comments\AjaxController;

/**
 * class AjaxControllerComments - Handles API-Requests from the client
 * for the Comments-Plugin
 *
 * @package Comments
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 * @version 0.0.2
 */
class AjaxControllerComments implements \HybridCMS\Ajax\IAjaxController 
{
    /**
     * Comment to store the submitted comment-data
     * @var Comment
     */
    private $objComment;
    
    /**
     * Colelction of Comments
     * @var Comment[]
     */
    private $arrObjComments;
    
    /**
     *
     * @var String
     */
    private $action;

    /**
     * url to handle
     * @var String
     */
    private $url;

    /**
     * __construct
     *
     * @param String[] $arrParams
     * @throws \Exception
     */
    public function __construct($arrParams) {
        try {

            //check whether params are valid or not
            if (false === \HybridCMS\Util\VarCheck::
                    issetAndNotEmpty($arrParams['url'])) 
            {
                throw new \Exception(
                        "Error Processing Ajax-Request: url,
                                    Paramter is not valid.", 1);
            }
            
            if (false === \HybridCMS\Util\VarCheck::
                    issetAndNotEmpty($arrParams['action'])) 
            {
                throw new \Exception(
                        "Error Processing Ajax-Request: action,
                                    Paramter is not valid.", 1);
            }            
            
            //assign url
            $this->setUrl(trim($arrParams['url']));
            
            //assign action
            $this->setAction(trim($arrParams['action']));            
        } 
        catch (Exception $e) 
        {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * handle Ajax Request
     */
    public function handleAjaxRequest() 
    {
        //prepare Actions and save necessary parameters
        if ($this->action === 'requestComments') 
        {
            $this->selectCommentsByUrl();
        } 
    }
    
    /**
     * Select requested Comments from Database an return them to the client
     */
    private function selectCommentsByUrl()
    {
        assert(true === isset($this->url));
        
        try {

            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //get all comments for the current url
            $arrObjComments = $objDBComments->selectPublishedCommentsByUrl(
                    $db, $this->url);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()
                    ->closeConnection();
            
            //return comments to the client as json
            $arrJsonData = array();
            
            foreach($arrObjComments as &$objComment)
            {
                assert($objComment instanceof 
                        \HybridCMS\Plugins\Comments\Model\Comment);
                
                $objUser = $objComment->getObjUser();
                
                $arrJsonData[] = array(
                    'commentId' => htmlentities($objComment->getCommentId(), 
                            ENT_QUOTES, 'UTF-8'),
                    'url' => htmlentities($objComment->getUrl(), 
                            ENT_QUOTES, 'UTF-8'),
                    'comment' => $objComment->getCommentFormatted(),
                    'timeCreated' => htmlentities($objComment->getTimeCreated(), 
                            ENT_QUOTES, 'UTF-8'),
                    'userId' => htmlentities($objUser->getUserId(), 
                            ENT_QUOTES, 'UTF-8'),
                    'username' => htmlentities($objUser->getUsername(), 
                            ENT_QUOTES, 'UTF-8'),
                    'email' => htmlentities($objUser->getEmail(), 
                            ENT_QUOTES, 'UTF-8'),
                    'avatarUrl' => htmlentities($objUser->getAvatarUrl(), 
                            ENT_QUOTES, 'UTF-8'),
                );               
            } 
            
            //set header to json
            header('Content-Type: text/javascript; charset=utf8');                

            echo json_encode($arrJsonData);            
        } 
        catch (Exception $e) 
        {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()
                    ->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setUrl
     * @param String $url
     */
    private function setUrl($url)
    {
        //check if a valid url is given
        if(
            true === empty($url)
            ||  
            false === is_string($url)
            ||
            false === \HybridCMS\Modules\Url\Url::isValidUrl($url)
          )
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), no valid url given.', 1);
        }
        
        $objUrl = new \HybridCMS\Modules\Url\Url($url);
        assert(false === empty($objUrl));

        //check if url is internal
        if(false === $objUrl->urlIsInternal())
        {               
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), url is not internal.', 1);  
        }
        
        $this->url = $objUrl->getUrl();
    }  
    
    /**
     * setAction
     * @param String $action
     * @throws \Exception
     */
    private function setAction($action) 
    {
        //check if action is an alphabetic String
        if (!ctype_alpha($action)) {

            throw new \Exception(
            "Error Processing Request: setAction(),
                       action must be alphanumeric.", 1);
        }

        $this->action = $action;
    }    
}