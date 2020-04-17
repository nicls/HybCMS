<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentPasswordLogin represents
 * the content of a password-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentPasswordLogin 
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentPassword 
{
    
    /**
     * EmailContentObject corresponding to the password
     * @var FormElemGroupContentEmail 
     */
    private $objDependentFormElemGroupContentEmail;
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct($value);   
        
        $this->setErrorMsg('Bitte gib einen gültiges Passwort ein.');
        $this->setWarningMsg('Das Passwort ist nicht korrekt.');  
        
        $this->setHint('Dein Passwort hat mindestens einen Groß- und einen '
                . 'Kleinbuchstaben, eine Zahl und ein Sonderzeichen sowie '
                . '8 bis 72 Zeichen.');
    }
    
    /**
     * passwordIsCorrect checks if the passwords sent by the client is correct
     * @return Boolean
     * @throws \Exception
     */
    public function passwordIsCorrect()
    {
        if(true === empty($this->objDependentFormElemGroupContentEmail))
        {
            throw new \Exception(
            'Error Processing Request:
                    passwordIsCorrect(),                    
                    $this->objDependentFormElemGroupContentEmail is not set.', 1); 
        }
        
        $ret = false;
        
        //create alias 
        $objCEmail = $this->objDependentFormElemGroupContentEmail;
        
        //check if email of dependecy object is valid
        $emailIsValid = $objCEmail->valueIsValid();
        
        $emailExistsAndUserIsValid = $objCEmail
                ->emailExistsAndUserIsRegistered();
                
        //check if email are euqal
        if(true === $emailIsValid 
           && 
           true === $emailExistsAndUserIsValid 
           && 
           true === $this->valueIsValid())
        {
            $email = $objCEmail->getValue();
            $password = $this->getValue();
            
            try 
            {

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();

                //select user from DB
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();           
                $objUser = $objDBUser->selectUserByEmail($db, $email);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection(); 

                assert(false === empty($objUser));

                //check if password is valid
                $ret = $objUser->passwordIsCorrect($password);   

            } 
            catch (\Exception $e) 
            {                
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection(); 
                
                throw $e;
            }              
        }
        
        return $ret;
    }
    
    /**
     * setObjDependentFormElemGroupContentEmail
     * @param FormElemGroupContentEmail $objDependentFormElemGroupContentEmail
     * @throws \InvalidArgumentException
     */
    public function setObjDependentFormElemGroupContentEmail(
            $objDependentFormElemGroupContentEmail) 
    {
        if(false === ($objDependentFormElemGroupContentEmail instanceof
                \HybridCMS\Plugins\User\CustomControls
                    \FormElemGroupContent\FormElemGroupContentEmail))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjDependentFormElemGroupContentEmail(),                    
                    $objDependentFormElemGroupContentEmail is not an instance of 
                    FormElemGroupContentEmail.', 1);
        }
        
        $this->objDependentFormElemGroupContentEmail = 
                $objDependentFormElemGroupContentEmail;
    }

    /**
     * getObjDependentFormElemGroupContentEmail
     * @return FormElemGroupContentEmail
     */
    public function getObjDependentFormElemGroupContentEmail() {
        return $this->objDependentFormElemGroupContentEmail;
    }
}
?>
