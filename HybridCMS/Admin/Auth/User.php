<?php

namespace HybridCMS\Admin\Auth;

/**
 * class User representing a Admin
 *
 * @package Auth
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class User {

    /**
     * name of the user
     * @var String
     */
    private $username;

    /**
     * rolename of the user
     * @var String
     */
    private $rolename;

    /**
     * email of the user
     * @var String
     */
    private $email;

    /**
     * url of the twitter account of the user
     * @var String
     */
    private $twitter;

    /**
     * url of the facebook account of the user
     * @var String
     */
    private $facebook;

    /**
     * url of the googlePlus Account of the user
     * @var String
     */
    private $googlePlus;

    /**
     * youtube account of the user
     * @var String 
     */
    private $youtube;

    /**
     * website of the user
     * @var String
     */
    private $website;

    /**
     * Text about the user
     * @var String
     */
    private $aboutme;

    /**
     * __construct
     * 
     */
    public function __construct($username, $rolename, $email) {

        try {

            $this->setUsername($username);
            $this->setRolename($rolename);
            $this->setEmail($email);
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setUsername
     * @param String $username
     * @throws \Exception
     */
    private function setUsername($username) 
    {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s]+$/', $username) 
            || 
            strlen($username) > 45) {
            throw new \Exception(
            'Error Processing Request: setUsername(),
                        $username is not valid.', 1);
        }

        $this->username = $username;
    }

    /**
     * setRolename
     * @param String $rolename
     * @throws \Exception     
     */
    private function setRolename($rolename) {
        
        //allowed rolenames
        $arrRoleNames = array('admin', 'editor', 'author');
        
        if (!in_array($rolename, $arrRoleNames)) {
            throw new \Exception(
            'Error Processing Request: setRolename(),
                        $rolename is not valid.', 1);
        }
        
        $this->rolename = $rolename;
    }

    /**
     * setEmail
     * @param String $email
     * @throws \Exception     
     */
    private function setEmail($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(
            'Error Processing Request: setEmail(),
                        $email is not valid.', 1);
        }
        
        $this->email = $email;
    }

    /**
     * setTwitter
     * @param String $twitter
     * @throws \Exception     
     */
    public function setTwitter($twitter) {
        if(!\HybridCMS\Modules\Url\Url::isValidUrl($twitter)) {
            throw new \Exception(
            'Error Processing Request: setTwitter(),
                        $twitter is not a valid url.', 1); 
        }
        $this->twitter = $twitter;
    }

    /**
     * setFacebook
     * @param String $facebook
     * @throws \Exception
     */
    public function setFacebook($facebook) {
        if(!\HybridCMS\Modules\Url\Url::isValidUrl($facebook)) {
            throw new \Exception(
            'Error Processing Request: setFacebook(),
                        $facebook is not a valid url.', 1); 
        }        
        $this->facebook = $facebook;
    }

    /**
     * setGooglePlus
     * @param String $googlePlus
     * @throws \Exception
     */
    public function setGooglePlus($googlePlus) {
        
        if(!\HybridCMS\Modules\Url\Url::isValidUrl($googlePlus)) {
            throw new \Exception(
            'Error Processing Request: setGooglePlus(),
                        $googlePlus is not a valid url.', 1); 
        }           
        $this->googlePlus = $googlePlus;
    }

    /**
     * setYoutube
     * @param type $youtube
     * @throws \Exception      
     */
    public function setYoutube($youtube) {
        if(!\HybridCMS\Modules\Url\Url::isValidUrl($youtube)) {
            throw new \Exception(
            'Error Processing Request: setYoutube(),
                        $youtube is not a valid url.', 1); 
        }             
        $this->youtube = $youtube;
    }

    /**
     * setWebsite
     * @param String $website
     * @throws \Exception
     */
    public function setWebsite($website) {
        if(!\HybridCMS\Modules\Url\Url::isValidUrl($website)) {
            throw new \Exception(
            'Error Processing Request: setWebsite(),
                        $website is not a valid url.', 1); 
        }                
        $this->website = $website;
    }

    /**
     * setAboutme
     * @param type $aboutme
     * @throws \Exception
     */
    public function setAboutme($aboutme) {
        if(!is_string($aboutme) || strlen($aboutme) > 500) {
            throw new \Exception(
            'Error Processing Request: setAboutme(),
                        $aboutme is not a valid url.', 1); 
        }  
        $this->aboutme = $aboutme;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getRolename() {
        return $this->rolename;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTwitter() {
        return $this->twitter;
    }

    public function getFacebook() {
        return $this->facebook;
    }

    public function getGooglePlus() {
        return $this->googlePlus;
    }

    public function getYoutube() {
        return $this->youtube;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function getAboutme() {
        return $this->aboutme;
    }
}
?>
