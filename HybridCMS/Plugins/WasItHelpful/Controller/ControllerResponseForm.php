<?php

namespace HybridCMS\Plugins\WasItHelpful\Controller;

/**
 * class handles all requests to add a new UserResponse
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerResponseForm extends 
    \HybridCMS\Plugins\WasItHelpful\Controller\ControllerWasItHelpful
{            
    /**
     * Response submitted by the client
     * @var Response $objResponse
     */
    private $objResponse;  
    
    /**
     * __construct
     * @param mixed[] $arrParams
     */    
    public function __construct($arrParams) 
    {
        //call constructor of parent class
        parent::__construct($arrParams);  
        
        //check if a valid url is passed through
        if(false === isset($arrParams['hyb_response_url']))
        {
            throw new \InvalidArgumentException(
                "Error Processing Request: __construct(),
                    'url is not given.'", 1);
        }
        
        //the comments_url with a fragment is used to differ between differnet 
        //comment forms on the same website
        $this->setObjUrl($arrParams['hyb_response_url']);  
        
        //add objUrl to params
        $this->arrParams['objUrl'] = $this->objUrl;    
                
        //handle Request sent by the client
        $this->handleRequest();                          
    }    

    /**
     * handle Requests sent by the user
     */
    protected function handleRequest()
    {         
        //attach each InputContent submitted by 
        //the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();
            
        //check if this form was submitted. If so, handle action to add 
        //a new comment
        if (
                //check if match-key that identifies this form 
                //was submitted by the client
                true === isset($this->arrParams['formSubmitted'])
                &&
                true === is_string($this->arrParams['formSubmitted'])
                &&
                //check if match-key was submitted by clicking the submit
                //button of the form
                true === isset($this->arrParams[
                    $this->arrParams['formSubmitted']])
           ) 
        {        
            $this->handleActionAddComment();
        }
        else //handle action to show the comment form for unregistered Users
        {   
            $this->handleActionShowCommentsForm();
        }   
    }
    
    /**
     * prints the commentBox for a given url to add a new comment.
     */
    private function handleActionShowCommentsForm()
    {
        
        //add attribute name to the submit button depending on the url fragment
        if(true === isset($this->arrParams['objButtonAddComment']))
        {
            $this->arrParams['objButtonAddComment']->
                    setFieldName('hyb_comments_addComment_#' 
                            . htmlentities($this->objUrl->getFragment()));
        }
        
        //set view
        $this->objView = new \HybridCMS\Plugins\Comments\View
                \ViewCommentFormUnregistered($this->arrParams); 
             
    }    
    
    /**
     * Adds a new comment for a given url.
     */
    private function handleActionAddComment()
    {
        //explicitly call method to validate Formdata
        $this->validateFormData();
            
        if(true === $this->objFormStateObserver->getFormHasError())
        {
            $this->submittedDataIsValid = false;
        } 
        else if(true === $this->objFormStateObserver->getFormHasWarning())
        {
            $this->submittedDataIsValid = false;
        }
        
        //check if all data was valid and submittedDataIsValid is still valid
        if(true === $this->submittedDataIsValid) 
        {                   
            try 
            {                     
                //create User Object out of the data submitted by the user
                $this->createUser();

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();
            
                //save user into DB
                $objDBUser = new \HybridCMS\Plugins\User\Database
                        \DBUserUnregistered();
                                
                $ret = $objDBUser->insertUser($db, $this->objUser);
                
                assert(0 < $ret['insertId']);
                
                $this->objUser->setUserId($ret['insertId']);
                
                $ret = $objDBUser->insertUserUnregistered($db, $this->objUser);
                
                assert(0 < $ret['insertId']);
                
                //create comment aout of the data submitted by the client
                $this->createComment();
                
                //create new DBComments Object 
                $objDBComments = new \HybridCMS\Plugins\Comments
                        \Database\DBComments();
                
                //insert comment into database
                $objDBComments->insertComment($db, $this->objComment);
                
                $this->informAdmins($db);
                
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();      
                
                //pass comment to the view
                $this->arrParams['objComment'] = $this->objComment;
                
                //set view
                $this->objView = new \HybridCMS\Plugins\Comments\View
                       \ViewCommentAdded($this->arrParams); 
                
            } 
            catch (Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();

                throw $e;
            }
        } 
        else //data send by the user was not valid
        {            
            //send user back to registration form
            $this->handleActionShowCommentsForm();
        }               
    }
    
    /**
     * creates a unregistered user out of the submitted data from the client
     */
    private function createUser()
    {
        $email = $this->arrParams['objContentEmailUnique']
                ->getValue();
        
        $username = $this->arrParams['objContentUsernameUnregistered']
                ->getValue();
        
        assert(false === empty($email));
        assert(false === empty($username));  
        
        $this->objUser = \HybridCMS\Plugins\User\Model\UserFactory
                ::create('unregistered');    
        
        $this->objUser->setEmail($email);  
        $this->objUser->setUsername($username);
    }
    
    /**
     * Creates an instance of Comment out of the data submitted by the client
     */
    private function createComment()
    {
        assert(false === empty($this->objUser));
        assert(true === isset($this->arrParams['objContentComment']));
        assert(false === empty($this->objUrl));
        
        //get comment submitted by the user
        $comment = $this->arrParams['objContentComment']->getValue();

        //create new comment-object
        $this->objComment = new \HybridCMS\Plugins\Comments\Model\Comment(
                $comment, $this->objUser);
        $this->objComment->setUrl($this->objUrl->getUrl());
    }    
    
    /**
     * Informs all admins about the new comment to approve the comment.
     * @param myswli $db
     * @throws \HybridCMS\Plugins\Comments\Controller\Exception
     */
    private function informAdmins(&$db)
    {
        try
        {            
            //save user into DB
            $objDBAuth = new \HybridCMS\Database\DBAuth();

            $arrObjAdmin = $objDBAuth->selectUserByRolename($db, 'admin');
            $arrObjAuthor = $objDBAuth->selectUserByRolename($db, 'author');
            $arrObjUser = array_merge($arrObjAdmin, $arrObjAuthor);

            assert(0 < count($arrObjUser));               

            //send email to user for verification
            $objAdminContact = new \HybridCMS\Plugins\Comments\Model
                    \AdminContact($arrObjUser, $this->objComment);
            $objAdminContact->sendEmailNewComment();  
        }
        catch(Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection(); 

            throw $e;
        }                
    }
 }
