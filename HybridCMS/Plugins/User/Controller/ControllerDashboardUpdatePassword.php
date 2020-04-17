<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class Controller for the Dashboard to Update the Password 

 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerDashboardUpdatePassword 
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
        
        assert(true === isset($_SESSION['email']));
        assert(true === isset($this->arrParams['objFormElemGroupContentPasswordLogin']));
        assert(true === isset($this->arrParams['objFormElemGroupContentPassword']));
        
        //set label of old password-input-field
        $this->arrParams['objFormElemGroupContentPasswordLogin']
                ->setLabel('Altes Passwort eingeben:');
           
        //set label of new password input field
        $this->arrParams['objFormElemGroupContentPassword']
                ->setLabel('Neues Passwort eingeben:');
               
        //create dependent emailContentObject
        $objFormElemGroupContentEmailExistingAndRegistered =
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentEmailExistingAndRegistered
                        ($_SESSION['email']);
        
        //add dependend emailContentObject to $this->arrParams to validate email
        $this->arrParams['objFormElemGroupContentEmailExistingAndRegistered'] =
                $objFormElemGroupContentEmailExistingAndRegistered;
        
        //add dependend emailContentObject to old-passwordContentObject
        $this->arrParams['objFormElemGroupContentPasswordLogin']
                ->setObjDependentFormElemGroupContentEmail
                ($objFormElemGroupContentEmailExistingAndRegistered); 
        
        //attach each InputContent submitted by the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();  
        
        if(true === isset($this->arrParams['hyb_user_updatePassword']))
        {
            $this->handleActionUpdatePassword();
        }
        else
        {
            //take default view and show the update-form
            $this->handleActionShowUpdatePasswordForm();
        }
    }
    
    /**
     * handle Action to Show the Update Form
     */
    private function handleActionShowUpdatePasswordForm()
    {               
                
        $this->objView = new \HybridCMS\Plugins\User\View\Dashboard
                \ViewDashboardUpdatePassword($this->arrParams);  
    }
    
    /**
     * handle Action to update the password
     */
    private function handleActionUpdatePassword()
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
            try //update password
            {   
                $newPassword = $this->arrParams['objFormElemGroupContentPassword']
                        ->getValue();
                
                $email = $_SESSION['email'];
                
                //create user and set new password and verificationToken
                $this->objUser = \HybridCMS\Plugins\User\Model\UserFactory
                        ::create('registered');
                
                $this->objUser->setEmail($email);
                $this->objUser->setPassword($newPassword);

                //create database object
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();    

                $affectedRows = $objDBUser->updateHashByEmail($db, $this->objUser);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();                

                if(1 !== $affectedRows) 
                {
                    throw new \Exception(
                            'Error Processing Request:    
                                handleActionUpdatePassword()
                                    update of password failed.', 1); 
                }       
                
                $this->objView = new \HybridCMS\Plugins\User\View\Dashboard
                        \ViewDashboardUpdatedPassword($this->arrParams);                
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
           $this->handleActionShowUpdatePasswordForm(); 
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
