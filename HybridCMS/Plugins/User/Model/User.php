<?php

namespace HybridCMS\Plugins\User\Model;

/**
 * class User
 *
 * @package User\Model
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class User {

    /**
     * User Id
     * @var Integer 
     */
    protected $userId;

    /**
     * Type of the user {openId|registered|unregistered}
     * @var String
     */
    protected $type;
    
    /**
     * Unix timestamp of user-registration time
     * @var Integer
     */
    protected $timeCreated;
    
    /**
     * Unix timestamp of the last login
     * @var Integer
     */
    protected $lastLogin;
    
    /**
     * Indicates if the user is currently logged in or not
     * @var Boolean
     */
    protected $isOnline;   
    
    /**
     * Email
     * @var String
     */
    protected $email;    
    
    /**
     * Username
     * @var String
     */
    protected $username;
   
    /**
     * Website of the user
     * @var String
     */
    protected $website;
  

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {}
    
//end __construct

    /**
     * setUserId
     * @param Integer $userId
     * @throws \Exception
     */
    public function setUserId($userId) {

        if (!is_numeric($userId) || $userId <= 0) {
            throw new \Exception(
            'Error Processing Request: 
                    setUserId(),                    
                    $userId is not valid: ' . htmlspecialchars($userId), 1);
        }

        $this->userId = $userId;
    }
    
    /**
     * setType
     * @param String $type
     * @throws \Exception
     */
    public function setType($type) 
    {
        $arrTypes = array('openId', 'registered', 'unregistered');
        
        if (false === in_array($type, $arrTypes)) {
            throw new \Exception(
            'Error Processing Request: 
                    setType(),                    
                    $type is not valid: ' . htmlspecialchars($type), 1);
        }    
        
        $this->type = $type;
    }       

    /**
     * setTimeCreated
     * @param Integer $timeCreated
     * @throws \Exception
     */
    public function setTimeCreated($timeCreated) {

        if (!\HybridCMS\Modules\Validation\DateValidation
                ::isValidTimestamp($timeCreated)) {
            throw new \Exception(
            'Error Processing Request: 
                    setTimeCreated(),                    
                    $timeCreated is not valid: ' . htmlspecialchars($timeCreated), 1);
        }

        $this->timeCreated = $timeCreated;
    }

    /**
     * setLastLogin
     * @param Integer $lastLogin
     * @throws \Exception
     */
    public function setLastLogin($lastLogin) {

        if (!\HybridCMS\Modules\Validation\DateValidation
                ::isValidTimestamp($lastLogin)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                    setLastLogin(),                    
                    $lastLogin is not valid: ' . htmlspecialchars($lastLogin), 1);
        }

        $this->lastLogin = $lastLogin;
    }

    /**
     * setIsOnline
     * @param Boolean $isOnline
     * @throws \Exception
     */
    public function setIsOnline($isOnline) {

        if (!is_bool($isOnline)) {
            throw new \Exception(
            'Error Processing Request: 
                    setIsOnline(),                    
                    $isOnline is not valid: ' . htmlspecialchars($isOnline), 1);
        }

        $this->isOnline = $isOnline;
    }
    
    /**
     * setEmail
     * @param String $email
     * @throws \Exception
     */
    public function setEmail($email) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidEmail($email)) {
            throw new \Exception(
            'Error Processing Request: 
                    setEmail(),                    
                    $email is not valid: ' . htmlspecialchars($email), 1);
        }

        $this->email = $email;
    }
        
    /**
     * setWebsite
     * @param String $website
     * @throws \Exception
     */
    public function setWebsite($website) {

        if (!\HybridCMS\Modules\Validation\URLValidation
                ::isValidUrl($website)) {
            throw new \Exception(
            'Error Processing Request: 
                    setWebsite(),                    
                    $website is not valid: ' . htmlspecialchars($website), 1);
        }

        $this->website = $website;
    }    
     
    /**
     * setUsername
     * @param String $username
     * @throws \Exception
     */
    public abstract function setUsername($username);   
    
    /**
     * Return a Image of the user
     */
    public abstract function getAvatarUrl($size = 80);
       
    /**
     * Getter
     */
    public function getUserId() {
        return $this->userId;
    }
    
    public function getType() {
        return $this->type;
    }    

    public function getUsername() {
        return $this->username;
    }
    
    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getLastLogin() {
        return $this->lastLogin;
    }
    
    public function getIsOnline() {
        return $this->isOnline;
    }
    
    public function getWebsite() {
        return $this->website;
    }
    
    public function getAboutme() {
        return $this->aboutme;
    }    
    
    public function getEmail() {
        return $this->email;
    }    
}

?>