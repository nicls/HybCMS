<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent;

/**
 * This class represents the content of a 
 * user-input-field. The username is checked against uniqueness on registered
 * users.
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentUsernameUnregistered
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentInput
{
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Username:');
        $this->setErrorMsg('Bitte gib einen gültigen Usernamen ein.');               
        $this->setWarningMsg('Der Username wird leider bereits von einem '
                . 'registrierten Nutzer verwendet. Wählen Sie einen '
                . 'anderen Usernamen.');   
        $this->setPlaceholder('Username eingeben.');
        
        if(false === empty($value))
        {
            $this->setValue($value);
        }            
    }
    
    /**
     * Checks if a given Username/$this->value does NOT exists 
     * in the Database-Table hyb_user_registered
     * @return Boolean
     * @throws \HybridCMS\Plugins\User\CustomControls
     *  \FormElemGroupContent\Exception
     */
    public function valueIsUnique()
    {
        //select username from database
        try {

            $ret = false;

            if(false === empty($this->value))
            {

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();

                //select username from DB
                $objDBUser = new \HybridCMS\Plugins\User\Database
                        \DBUserRegistered();                
                $ret = !$objDBUser->usernameExists($db, $this->value);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection(); 
            }
            
            return $ret;
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();

            throw $e;
        }
    }

    /**
     * validates the value sent by the client
     */
    public function valueIsValid() 
    {
        //twitterName was submitted
        $ret = false === empty($this->value)
               &&
               true === \HybridCMS\Modules\Validation\UserDataValidation
                ::isValidUsername($this->value);
                  
        return $ret;
    }

}