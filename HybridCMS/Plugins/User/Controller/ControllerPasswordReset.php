<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerPasswordReset
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerPasswordReset 
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
    protected function handleRequest() 
    {        
        //attach each InputContent submitted by the 
        //client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();  
        
        //Step 2: User gets message that he will get an email
        if(true === isset($this->arrParams['hyb_user_resetPassword']))
        {                        
            $this->action = 'resetInitiated';
            $this->handleActionPasswordResetInitiated();
        }

        //Step 1: User enters email to reset password
        // -> default & initial password reset view
        else 
        {                 
            $this->action = 'resetInitiate';
            $this->handleActionPasswordResetInitiate();
        }        
    }
    
    /**
     * Handle Step 2
     * Method to handle Step 2 of the reseting-process
     * User gets message that he will get an email
     */       
    private function handleActionPasswordResetInitiated() 
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
                //create database object
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();
                
                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();                   

                $email = $this->arrParams['objFormElemGroupContentEmailExistingAndRegistered']
                        ->getValue();
                
                assert(false === empty($email));
                
                //get user by Email
                $this->objUser = $objDBUser->selectUserByEmail($db, $email);
                
                assert(false === empty($this->objUser));
                
                //add new verification Token
                $verificationToken = md5(\HybridCMS\Helper\Helper
                        ::generateKey(128));
                $this->objUser->setVerificationToken($verificationToken);
            
                //save verificationToken to database
                $affectedRows = $objDBUser
                        ->updateVerificationToken($db, $this->objUser);
                        
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();                
                                            
                if(1 !== $affectedRows) 
                {
                    throw new \Exception(
                            'Error Processing Request:    
                                handleActionPasswordResetInitiated()
                                    insertion of verificationToken failed.', 1); 
                }
                
                //send email to user for verification
                $objUserContact = new \HybridCMS\Plugins\User\Model
                        \UserContact($this->objUser);
                
                $objUserContact->sendConfirmationEmailPasswordReset();

                //view password reset initiated that tells user that he has e new email
                $this->objView = new \HybridCMS\Plugins\User\View
                        \ViewPasswordResetInitiated($this->arrParams);                 

                
            } 
            catch (\Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
                
                throw $e;
            }                
        }
        else //data send by the user is not valid
        {
            //show PasswordResetInitiate
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewPasswordResetInitiate($this->arrParams); 
        }
        
    }
    
    
    /**
     * Handle Step 1
     * Method to handle Step 1 of the reseting-process
     * User enters email to reset password
     */
    private function handleActionPasswordResetInitiate() 
    {                      
        //Form-Elements if this view:
        //input:  email
        //button: reset password
        $this->objView = 
                new \HybridCMS\Plugins\User\View\ViewPasswordResetInitiate(
                        $this->arrParams); 
    }

    /**
     * Validate Data submitted by the user
     */
    protected function validateFormData() 
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
     * Return the corresponding view to the user
     * @returns String
     */
    public function toString($args = array()) {

        //return formated View to the user
        return $this->objView->toString();
    }

}
