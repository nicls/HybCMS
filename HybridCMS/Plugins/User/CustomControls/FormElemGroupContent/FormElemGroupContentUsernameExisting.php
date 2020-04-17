<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentUsernameExisting represents the content of an 
 * existing users user-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentUsernameExisting 
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentUsername 
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct($value);
        
        $this->setWarningMsg('Es gibt kein Profil mit diesem Usernamen.');     
              
    }
    
    /**
     * Checks if a given Username/$this->value exists in 
     * the Database-Table hyb_user
     * @return Boolean
     * @throws \HybridCMS\Plugins\User\CustomControls
     *  \FormElemGroupContent\Exception
     */
    public function valueExists()
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
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();                
                $ret = $objDBUser->usernameExists($db, $this->value);

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
}
?>
