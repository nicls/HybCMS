<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerConfirmRegistration
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerConfirmRegistration 
    extends \HybridCMS\Plugins\User\Controller\ControllerUser 
{
        
    /**
     * Indicates of the verification token submitted by the user is valid.
     * @var Boolean
     */
    private $verificationTokenIsValid;
    
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
     * handle the request of the user
     */
    protected function handleRequest() {
        
        //get action from data send by the user
        if(true === isset($this->arrParams['action'])) 
        {
            $this->setAction($this->arrParams['action']);
        }
                
        //delete user profile 
        if($this->action === 'delete') 
        {
            $this->handleActionDeleteUser();
        }
        
        //handle action to confirm user profile
        else //$this->action === 'confirm'
        {
            $this->handleActionConfirmRegistration();
        } 
    }
    
    /**
     * handleActionConfirmRegistration sets the correct view
     */
    private function handleActionConfirmRegistration() 
    {        
        //check if submitted data is really valid
        $this->validateFormData();
        
        if(true === $this->submittedDataIsValid)
        {
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            //save user into DB
            $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();

            $affectedRows = $objDBUser->updateIsRegisteredByVerificationToken(
                    $db, $this->arrParams['key']);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection(); 

            if(1 === $affectedRows) 
            {
                $this->objView = new \HybridCMS\Plugins\User\View
                        \ViewRegistrationConfirmed($this->arrParams);
            } 
            else 
            {
                $this->objView = new \HybridCMS\Plugins\User\View
                        \ViewRegistrationConfirmFailed($this->arrParams);
            }
        } 
        else if(false === $this->submittedDataIsValid)
        {
            $this->objView = new \HybridCMS\Plugins\User
                    \View\ViewRegistrationConfirmFailed($this->arrParams);
        }
        
    }
    
    /**
     * handleActionDeleteUser sets the corresponding view
     */
    private function handleActionDeleteUser() {        
        
        //check if submitted data is really valid
        $this->validateFormData();
        
        if(true === $this->submittedDataIsValid)
        {
            try 
            { 
                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();

                //save user into DB
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();

                $affectedRows = $objDBUser->deleteUserByVerificationToken(
                        $db, $this->arrParams['key']);
            } 
            catch (\Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection(); 
                
                throw $e;
            }            

            if(1 === $affectedRows) 
            {
               $this->objView = new \HybridCMS\Plugins\User\View
                       \ViewRegistrationConfirmUserDeleted($this->arrParams);
            } 
            else 
            {
               $this->objView = new \HybridCMS\Plugins\User\View
                       \ViewRegistrationConfirmUserDeleteFailed
                               ($this->arrParams);
            }

        } 
        else if(false === $this->submittedDataIsValid)
        {
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewRegistrationConfirmUserDeleteFailed($this->arrParams);
        }
    }

    /**
     * Validates all data send by the client
     */
    protected function validateFormData() 
    {
        $this->isValidVerificationToken();
    }
    
    /**
     * Verification of verificationToken send by the user
     * @return Boolean
     */
    private function isValidVerificationToken() {
        
        $ret = true === isset($this->arrParams['key'])
               &&
               true === \HybridCMS\Modules\Validation\StringValidation
                ::isValidMd5Token($this->arrParams['key']);  

        //mark submittedDataIsValid as false if data is false
        if(false === $ret) { 
            $this->submittedDataIsValid = false;
        }     
        
        return $ret;
    }
    
    /**
     * Return the corresponding view to the user
     * @returns String
     */
    public function toString($args = array()) 
    {   
        if(true === empty($this->objView)) 
        {
            throw new \Exception(
            'Error Processing Request: toString(),
                               $this->objView is null.', 1);
        }

        return $this->objView->toString();        
    }    
}
