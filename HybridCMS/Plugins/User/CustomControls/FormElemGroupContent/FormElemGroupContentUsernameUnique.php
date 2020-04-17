<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentUsernameUnique represents the content of a 
 * NOT existing users user-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentUsernameUnique 
    extends \HybridCMS\Plugins\User\CustomControls
        \FormElemGroupContent\FormElemGroupContentUsername 
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct($value);
        
        $this->setWarningMsg('Der Username ist bereits vergeben.');        
    }
    
    /**
     * Checks if a given Username/$this->value does NOT exists 
     * in the Database-Table hyb_user
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
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();                
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
}
?>
