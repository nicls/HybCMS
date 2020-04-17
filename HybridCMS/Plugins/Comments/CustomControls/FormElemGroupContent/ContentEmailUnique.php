<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent;

/**
 * This class represents the content of a 
 * NOT unregistered users email-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentEmailUnique 
    extends \HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent
        \ContentEmail
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct($value);
        
        $this->setWarningMsg('Es existiert bereits ein Konto mit dieser Email. '
                . 'Logge dich zunächst ein, um ein Kommentar zu schreiben.');        
    }
    
    /**
     * Checks if a given Username/$this->value does NOT 
     * exists in the Database-Table hyb_user_registered
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
                $ret = !$objDBUser->emailExists($db, $this->value);

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
