<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentEmailUniqueOrSessionEmail 
 * represents the content of a NOT existing or euqal to $_SESSION['email'] users 
 * email-input-field for e.g. registration
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentEmailUniqueOrSessionEmail 
   extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
    \FormElemGroupContentEmail
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct($value);
        
        $this->setWarningMsg('Es existiert bereits ein Konto mit dieser Email.');        
    }
    
    /**
     * Checks if a given email/$this->value does NOT exists 
     * in the Database-Table hyb_user
     * or is the same as $_SESSION['email']
     * @return Boolean
     * @throws \HybridCMS\Plugins\User\CustomControls
     *  \FormElemGroupContent\Exception
     */
    public function valueIsUniqueOrSameAsSessionValue()
    {
        //select username from database
        try {

            $ret = false;

            if(false === empty($this->value))
            {
                assert(false === empty($_SESSION['email']));
                
                //check session value
                if($this->value === $_SESSION['email'])
                {
                    $ret = true;
                } 
                else //ckeck if email is unique
                {
                    //open Database-Connection
                    $db = \HybridCMS\Database\DatabaseFactory
                            ::getFactory()->getConnection();

                    //select username from DB
                    $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();                
                    $ret = !$objDBUser->emailExists($db, $this->value);

                    //close Database-Connection
                    \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->closeConnection(); 
                }
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
}
?>
