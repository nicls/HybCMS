<?php

namespace HybridCMS\Plugins\Comments\Admin\Controller;

/**
 * Handles requests to list all comments.
 *
 * @package Plugins\User\Controller\Admin
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerCommentList extends 
    \HybridCMS\Plugins\Comments\Admin\Controller\ControllerComments 
{   
    /**
     * __construct
     * @param mixed[] $arrParams
     */    
    public function __construct($arrParams) 
    {               
        //call constructor of parent class
        parent::__construct($arrParams);                       
                
        //handle Request sent by the client
        $this->handleRequest();           
               
    }    

    /**
     * handle Requests sent by the user
     */
    protected function handleRequest()
    {             
        //check if admin is logged is
        if(false === \HybridCMS\Helper\Helper::isUser('admin')
           &&
           false === \HybridCMS\Helper\Helper::isUser('author'))
        {
            return;
        }            
        
        if(true === isset($this->arrParams['hyb_comment_publish']))
        {        
            $this->handleActionPublishComment();
        }
        if(true === isset($this->arrParams['hyb_comment_unpublish']))
        {        
            $this->handleActionUnpublishComment();
        }        
        else if(true === isset($this->arrParams['hyb_comment_delete']))
        {        
            $this->handleActionDeleteComment();
        } 
        else if(true === isset($this->arrParams[
            'hyb_comment_listPublishedComments']))
        {
            $this->handleActionListPublishedComments();
        }
        else //default action 
        {   
            $this->handleActionListNewComments();
        }        
    }
    
    /**
     * Handles request to list all published comments
     * @throws \Exception
     */
    private function handleActionListPublishedComments()
    {
        //attach each InputContent submitted by 
        //the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();
        
        try
        {        
            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //get all comments for the current url
            $arrObjPubComments = $objDBComments->selectPublishedComments($db);
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();                    
                        
            //pass comments to the view
            $this->arrParams['arrObjPubComments'] = $arrObjPubComments;

            //set view
            $this->objView = new \HybridCMS\Plugins\Comments\Admin\View
                    \ViewPublishedCommentList($this->arrParams); 
        
        }
        catch(\Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            throw $e;
        }    
    }
    
    /**
     * Handles action to delete a comment.
     * @throws \Exception
     */
    private function handleActionDeleteComment()
    {
        //check if commentId is valid
        if(false === \HybridCMS\Util\VarCheck
               ::issetAndNotEmpty($this->arrParams['commentId'])) 
        {
            throw new \Exception(
                "Error Processing handleActionPublishComments: commentId,
                            Paramter is not valid.", 1);
        } 
        $commentId = $this->arrParams['commentId'];
        
        if(false === is_numeric($commentId)
           ||
           $commentId < 0
           ||
           $commentId > PHP_INT_MAX)
        {
            throw new \Exception(
                "Error Processing handleActionDeleteComment:
                    CommentId is not valid.", 1);
        } 
        
        try
        {        
            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //deleteCommentById
            $affectedRows = $objDBComments->deleteCommentById(
                    $db, $commentId);
            
            //add result to the params
            $this->arrParams['commentDeleted'] = ($affectedRows === 1);
            $this->arrParams['commentId'] = $commentId;
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();                                            
        
        }
        catch(\Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            throw $e;
        }          

        if(true === isset($this->arrParams[
            'hyb_comment_listPublishedComments']))
        {
            $this->handleActionListPublishedComments();
        }
        else //default action 
        {   
            $this->handleActionListNewComments();
        }
    }
    
    /**
     * Sets a unpublished Comment state to 'published'
     */
    private function handleActionPublishComment()
    {
        //check if commentId is valid
        if(false === \HybridCMS\Util\VarCheck
               ::issetAndNotEmpty($this->arrParams['commentId'])) 
        {
            throw new \Exception(
                "Error Processing handleActionPublishComments: commentId,
                            Paramter is not valid.", 1);
        } 
        $commentId = $this->arrParams['commentId'];
        
        if(false === is_numeric($commentId)
           ||
           $commentId < 0
           ||
           $commentId > PHP_INT_MAX)
        {
            throw new \Exception(
                "Error Processing handleActionPublishComments:
                    CommentId is not valid.", 1);
        }        
        
        try
        {        
            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //update comment by id
            $affectedRows = $objDBComments->updateCommentToPublishedById(
                    $db, $commentId);
            
            //add result to the params
            $this->arrParams['commentPublished'] = ($affectedRows === 1);
            $this->arrParams['commentId'] = $commentId;
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();                                            
        
        }
        catch(\Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            throw $e;
        }          

        $this->handleActionListNewComments();      
    }
    
    /**
     * Sets a published Comment state to 'unpublished'
     */
    private function handleActionUnpublishComment()
    {
        //check if commentId is valid
        if(false === \HybridCMS\Util\VarCheck
               ::issetAndNotEmpty($this->arrParams['commentId'])) 
        {
            throw new \Exception(
                "Error Processing handleActionUnpublishComment: commentId,
                            Paramter is not valid.", 1);
        } 
        $commentId = $this->arrParams['commentId'];
        
        if(false === is_numeric($commentId)
           ||
           $commentId < 0
           ||
           $commentId > PHP_INT_MAX)
        {
            throw new \Exception(
                "Error Processing handleActionUnpublishComment:
                    CommentId is not valid.", 1);
        }        
        
        try
        {        
            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //update comment by id
            $affectedRows = $objDBComments->updateCommentToUnpublishedById(
                    $db, $commentId);
            
            //add result to the params
            $this->arrParams['commentUnpublished'] = ($affectedRows === 1);
            $this->arrParams['commentId'] = $commentId;
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();                                            
        
        }
        catch(\Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            throw $e;
        }          

        $this->handleActionListPublishedComments();      
    }    


    /**
     * prints the list of comments for a given url.
     */
    private function handleActionListNewComments()
    {                   
        //attach each InputContent submitted by 
        //the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();
        
        try
        {        
            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //get all comments for the current url
            $arrObjNewComments = $objDBComments->selectUnpublishedComments($db);
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();                    
                        
            //pass comments to the view
            $this->arrParams['arrObjNewComments'] = $arrObjNewComments;

            //set view
            $this->objView = new \HybridCMS\Plugins\Comments\Admin\View
                    \ViewNewCommentList($this->arrParams); 
        
        }
        catch(\Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            throw $e;
        }        
        
        /**
         * JS
         * 
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         * 6. $position
         * 7. $async
         */       
        $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                        'initCommentList',
                        '/HybridCMS/Plugins/Comments/Admin/js/initNewCommentList.js',
                        15,
                        true,
                        true,
                        'footer',
                        true
        );
        $this->addObjJSResource($objJSResource);              
    }

}
