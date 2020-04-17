<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerPasswordResetSetNew
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerPasswordResetSetNew 
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
     * Step 1: User enters email to reset password
     * Step 2: User gets message that he will get an email with a link to follow
     * Step 3: User has followed the link in the email to enter a new password
     * Step 4: User gets an confirmation that his password is reseted
     */
    protected function handleRequest() {
        
        //attach each InputContent submitted by the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();          

        //Step 4: User gets an confirmation that his password is reseted
        // -> view that shows message to uset that 
        // his password was successfuly reseted
        if(true === isset($this->arrParams['hyb_user_savePassword']))
        {            
            $this->action = 'passwordReseted';
            $this->handleActionPasswordReseted();
        } 

        //Step 3: User has followed the link in the email to enter a new password
        // -> show inputfield to the user to enter a new password
        else if (isset($this->arrParams['action']) 
                && 
                $this->arrParams['action'] === 'resetPassword')
        {           
            $this->action = 'resetPassword';
            $this->handleActionPasswordReset();
        }        
    }
    
        
    /**
     * Handle Step 4
     * Method to handle Step 4 of the reseting-process
     * User gets an confirmation that his password is reseted
     */    
    private function handleActionPasswordReseted() 
    {                        
        //validate userdata send by the user
        $this->validateFormData();
        
        //check if verification token is valid 
        if(true === $this->submittedDataIsValid)
        {
            //ckeck if form-data was valid
            if(true === $this->objFormStateObserver->getFormHasError())
            {
                $this->submittedDataIsValid = false;
            } 
            else if(true === $this->objFormStateObserver->getFormHasWarning())
            {
                $this->submittedDataIsValid = false;
            }            
        }
        
        //check if all data was valid and submittedDataIsValid is still valid
        if(true === $this->submittedDataIsValid) 
        {
          try 
            {            
                $passwordNew = 
                        $this->arrParams['objFormElemGroupContentPassword']
                        ->getValue();
                
                //create user and set new password and verificationToken
                $this->objUser = \HybridCMS\Plugins\User\Model\UserFactory
                        ::create('registered');
                
                $this->objUser->setPassword($passwordNew);
                $this->objUser->setVerificationToken($this->arrParams['key']);

                //create database object
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();    

                $affectedRows = $objDBUser
                        ->updateHashByVerificationToken($db, $this->objUser);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();                

                if(1 !== $affectedRows) 
                {
                    throw new \Exception(
                            'Error Processing Request:    
                                handleActionPasswordResetInitiated()
                                    insertion of password failed.', 1); 
                } 
                
                //view password resetted that shows message to the user 
                $this->objView = new \HybridCMS\Plugins\User\View
                        \ViewPasswordReseted($this->arrParams);                
            }    
            catch (\Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();
                
                throw $e;
            }                 
        }
        else //passwords submitted by the user where not valid
        {
            //Form-Elements of this view:        
            //input:  password
            //input:  password repeat
            //button: save password
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewPasswordReset($this->arrParams); 
        }
    }
    
    /**
     * Hanle Step 3
     * Method to handle Step 3 of the reseting-process
     * User has followed the link in the email to enter a new password
     */        
    private function handleActionPasswordReset() 
    {        
        //validate userdata send by the user
        $this->validateFormData();           
        
        //check if all data was valid and submittedDataIsValid is still valid
        if(true === $this->submittedDataIsValid) 
        {
                                                          
            //Form-Elements of this view:        
            //input:  password
            //input:  password repeat
            //button: save password
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewPasswordReset($this->arrParams);               

        }
        else //link invoked by the user is not valid
        {
            //show ViewPasswordResetInvalidConfirmationLink
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewPasswordResetInvalidConfirmationLink($this->arrParams); 
        }        
    }    

    /**
     * Validate Data submitted by the user
     */
    protected function validateFormData() 
    {
        //validate verificationToken
        $this->verificationTokenIsValid();
         
        if($this->action === 'passwordReseted')
        {
            $this->validateFormDataPasswordReseted();
        }        
    }        
    
    /**
     * Validate Form-Data submitted by passwordReset-form (Step 3)
     */
    private function validateFormDataPasswordReseted() 
    {        
        foreach ($this->arrParams as &$param) 
        {
            if(true === $param instanceof
                    \HybridCMS\Plugins\User\CustomControls
                    \FormElemGroups\FormElemGroup)
            {
                $param->validateContent();
            }
        }
    }
        
    /**
     * Verification of verificationToken send by the user
     * @return Boolean
     */
    private function verificationTokenIsValid() {
        
        $ret = true === isset($this->arrParams['key'])
               &&
               true === \HybridCMS\Modules\Validation\StringValidation
                ::isValidMd5Token($this->arrParams['key']);  
        
        if(true === $ret)
        {
            try 
            {
                //create database object
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();
                
                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();                   

                //get user by Email
                $ret = $objDBUser->verificationTokenExists
                        ($db, $this->arrParams['key']);
                
                $this->arrParams['verificationTokenExists'] = $ret;
                                                     
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();   
            }
            catch (\Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();
                
                throw $e;
            }
        }

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
    public function toString($args = array()) {

        //return formated View to the user
        return $this->objView->toString();
    }

}
