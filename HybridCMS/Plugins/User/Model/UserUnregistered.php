<?php

namespace HybridCMS\Plugins\User\Model;

/**
 * class UserUnegistered
 *
 * @package User\Model
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class UserUnregistered extends \HybridCMS\Plugins\User\Model\User
{
   
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($type) 
    {
        $this->setType($type);    
    }    
        
    /**
     * setUsername
     * @param String $username
     * @throws \Exception
     */
    public function setUsername($username) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidUsername($username)) {
            throw new \Exception(
            'Error Processing Request: 
                    setUsername(),                    
                    $username is not valid: ' . htmlspecialchars($username), 1);
        }

        $this->username = $username;
    }
    
    /**
     * getGravatar imageUrl
     * @param String $size
     * @return String
     * @throws \Exception
     */
    public function getGravatar($size = 80) 
    {
        if (false === is_numeric($size) || $size < 0 || $size > 200) 
        {
            throw new \Exception(
            'Error Processing Request: 
                    getGravatar(),                    
                    $size is not valid: ' 
                    . htmlspecialchars($size), 1);
        }
        
        return 'https://secure.gravatar.com/avatar/'
            . md5($this->email) . '.jpg?s=' . $size;
    }
    
    /**
     * Returns the avatar picture url of the current user
     * @return String
     */
    public function getAvatarUrl($size = 80)
    {
        return $this->getGravatar($size);
    }    
}
?>