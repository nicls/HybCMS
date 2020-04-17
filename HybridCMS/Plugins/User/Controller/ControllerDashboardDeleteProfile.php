<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class Controller for the Dashboard to Delete the Profile of the logged in user 

 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerDashboardDeleteProfile 
    extends \HybridCMS\Plugins\User\Controller\ControllerUser 
{
    
    /**
     * Userdata submitted by the client
     * @var User $objUser
     */
    private $objUser;
        
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrParams) 
    {
        //call constructor of parent class
        parent::__construct($arrParams);

        //handle Request sent by the client
        $this->handleRequest();           
    }    
    
    /**
     * handle reuqst sent by the user
     */
    protected function handleRequest() 
    {                                
        
        if(true === isset($this->arrParams['hyb_user_deleteProfile']))
        {
            $this->handleActionDeleteProfile();
        }
        else
        {
            //take default view and show the update-form
            $this->handleActionShowDeleteProfileForm();
        }
    }
    
    /**
     * handle Action to Show the Delete Form
     */
    private function handleActionShowDeleteProfileForm()
    {                    
        $this->objView = 
                new \HybridCMS\Plugins\User\View\Dashboard
                \ViewDashboardDeleteProfile($this->arrParams);  
    }
    
    /**
     * handle Action to delete the profile
     */
    private function handleActionDeleteProfile()
    {           
        if(true) //no validation required because userId is saved in the session
        {  
            try
            {   
                
                assert(true === isset($_SESSION['userId']));
                assert(true === isset($_SESSION['type']));
                
                $userId =  $_SESSION['userId'];
                $type = $_SESSION['type'];                               
                                
                //create user and set userId and password
                $this->objUser = \HybridCMS\Plugins\User\Model\UserFactory
                        ::create($type);
                $this->objUser->setUserId($userId);

                //create database object
                $objDBUser = null;
                if($type === 'registered')
                {
                    $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();
                }
                else if($type === 'openId')
                {
                    $objDBUser = new \HybridCMS\Plugins\User\Database
                            \DBUserOpenId();
                }
                    
                assert(false === empty($objDBUser));

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();    

                $affectedRows = $objDBUser
                        ->deleteUserByUserId($db, $this->objUser);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();                

                if(1 < $affectedRows) 
                {
                    throw new \Exception(
                            'Error Processing Request:    
                                handleActionDeleteProfile()
                                    delete of user failed.', 1); 
                }      
                
                // delete all session variables
                $_SESSION = array();

                //delete session cookie
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(
                            session_name()
                            , ''
                            , time() - 42000
                            , $params["path"]
                            , $params["domain"]
                            , $params["secure"]
                            , $params["httponly"]
                    );
                }

                //finally delete session
                session_unset();
                session_destroy();
                
                $this->objView = new \HybridCMS\Plugins\User\View\Dashboard
                        \ViewDashboardDeletedProfile($this->arrParams);                
            } 
            catch (Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();
                
                throw $e; 
            }             
        } 
        else 
        {
            //show default form
           $this->handleActionShowDeleteProfileForm(); 
        }        
    }                          
    
    /**
     * Return the corresponding view to the user
     * @returns String
     */
    public function toString($args = array()) {

        //return formated View to the user
        return $this->objView->toString();
    }    
}
