<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerConfirmNewEmail
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerConfirmNewEmail
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
                
        if($this->action === 'confirm') 
        {
            $this->handleActionConfirmNewEmail();
        } 
        else 
        {
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewNewEmailConfirmFailed($this->arrParams);
        }
    }
    
    /**
     * handleActionConfirmNewEmail sets the correct view
     */
    private function handleActionConfirmNewEmail() 
    {        
        //check if submitted data is really valid
        $this->validateFormData();
        
        if(true === $this->submittedDataIsValid)
        {
            try {
                
                //get DB-Object to perform Operation on hyb_user-table
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

                //select new email by verification Token
                $emailNotConfirmed = $objDBUser
                        ->selectEmailNotConfirmedByVerificationToken(
                                $db, 
                                $this->arrParams['key']);

                //set new Email as primary email + delete verificationToken and 
                //emailNotConfirmed
                $affectedRows = $objDBUser
                        ->updateEmailByVerificationToken(
                                $db, 
                                $this->arrParams['key'], 
                                $emailNotConfirmed);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection(); 

                if(1 === $affectedRows) 
                {
                    //set session-variable to the new email
                    $_SESSION['email'] = $emailNotConfirmed;

                    $this->objView = new \HybridCMS\Plugins\User\View
                            \ViewNewEmailConfirmed($this->arrParams);
                } 
                else 
                {
                    $this->objView = new \HybridCMS\Plugins\User\View
                            \ViewNewEmailConfirmFailed($this->arrParams);
                }
            }
            catch (\Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection(); 

                throw $e;
            }             
        } 
        else if(false === $this->submittedDataIsValid)
        {
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewNewEmailConfirmFailed($this->arrParams);
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
