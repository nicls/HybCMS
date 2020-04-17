<?php

namespace HybridCMS\Plugins\User\Model;

require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Modules/PasswordHash.php');

/**
 * class UserRegistered
 *
 * @package User\Model
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class UserRegistered extends \HybridCMS\Plugins\User\Model\User
{
    /**
     * First name of the user
     * @var String
     */
    private $firstname;

    /**
     * last name of the user
     * @var String
     */
    private $lastname;
    
    /**
     * Gender of the user, t.m. m | w
     * @var String
     */
    private $gender; 
    
    /**
     * Password of the user
     * @var String
     */
    private $password;

    /**
     *  Hash of the password
     * @var String
     */
    private $hash;

    /**
     * Indicates if the user is registered or if verification os pedning
     * @var Boolean
     */
    private $isRegistered;

    /**
     * md5 verification-token 
     * @var String
     */
    private $verificationToken;

    /**
     * Twitter
     * @var String
     */
    private $twitterName;

    /**
     * Facebook
     * @var String
     */
    private $facebookUrl;

    /**
     * Google+
     * @var String
     */
    private $googleplusId;

    /**
     * Youtube
     * @var String
     */
    private $youtubeChannelName;
    
    /**
     * About me
     * @var String
     */
    protected $aboutme;     
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($type) 
    {
        $this->setType($type);    
    }    
    
    /**
     * Checks if a given password euqals the password of this user
     * @param String $password
     */
    public function passwordIsCorrect($password) {
        
        if ( false === is_string($password)) {
            throw new \Exception(
            'Error Processing Request: 
                    passwordIsCorrect(),                    
                    $password is not a String: ' . htmlspecialchars($password), 1);
        }     
        
        if (true === empty($this->hash)) {
            throw new \Exception(
            'Error Processing Request: 
                    passwordIsCorrect(),                    
                    hash of current User is not set.', 1);
        }           
        
        $objPHPPass = new \PasswordHash(8, false);
        $passwordIsCorrect = $objPHPPass->CheckPassword($password, $this->hash); 
        
        return $passwordIsCorrect;
    }
    
    /**
     * hashPassword hashes a given password and saves the hash in $this->hash
     * @throws \Exception
     */
    public function hashPassword() {
        
        //check if password is set
        if(empty($this->password)) {
            throw new \Exception(
            'Error Processing Request: 
                    hashPassword(),                    
                    $password is not set.', 1);
        }

        try {
            //Base-2 logarithm of the iteration count used for password stretching
            $iteration_count_log2 = 8;

            //Do we require the hashes to be portable to older systems (less secure)?
            $portable_hashes = FALSE;                     

            //create phpPass
            $objPHPPass = new \PasswordHash($iteration_count_log2, $portable_hashes);
            
            //get hash
            $hash = $objPHPPass->HashPassword($this->password);                

            $this->setHash($hash);
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                    \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
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
     * setFirstname
     * @param String $firstname
     * @throws \Exception
     */
    public function setFirstname($firstname) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidFirstname($firstname)) {
            throw new \Exception(
            'Error Processing Request: 
                    setFirstname(),                    
                    $firstname is not valid: ' . htmlspecialchars($firstname), 1);
        }

        $this->firstname = $firstname;
    }

    /**
     * setLastname
     * @param String $lastName
     * @throws \Exception
     */
    public function setLastname($lastname) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidLastname($lastname)) {
            throw new \Exception(
            'Error Processing Request: 
                    setLastname(),                    
                    $lastname is not valid: ' . htmlspecialchars($lastname), 1);
        }

        $this->lastname = $lastname;
    }
    
    /**
     * setGender
     * @param String $gender
     * @throws \Exception
     */
    public function setGender($gender) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidGender($gender)) {
            throw new \Exception(
            'Error Processing Request: 
                    setGender(),                    
                    $gender is not valid: ' . htmlspecialchars($gender), 1);
        }

        $this->gender = $gender;
    }    

    /**
     * setPassword sets the password and calculates the passwordHash
     * @param String $password
     * @return String Hashed Password
     * @throws \Exception
     */
    public function setPassword($password) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidPassword($password)) {
            throw new \Exception(
            'Error Processing Request: 
                    setPassword(),                    
                    $password is not valid: ' . htmlspecialchars($password), 1);
        }
        $this->password = $password;
        
        //get hash of the password
        $this->hashPassword();     
        
        return $this->hash;
    }

    /**
     * setHash - Hash of the password hashed with phpPass
     * http://www.openwall.com/phpass/
     * @param String $hash
     * @throws \Exception
     */
    public function setHash($hash) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidPasswordHash($hash)) {
            throw new \Exception(
            'Error Processing Request: 
                    setHash(),                    
                    $hash is not valid: ' . htmlspecialchars($hash), 1);
        }

        $this->hash = $hash;
    }  
    
    /**
     * setIsRegistered
     * @param Boolean $isRegistered
     * @throws \Exception
     */
    public function setIsRegistered($isRegistered) {

        if (!is_bool($isRegistered)) {
            throw new \Exception(
            'Error Processing Request: 
                    setIsRegistered(),                    
                    $isRegistered is not valid: ' 
                    . htmlspecialchars($isRegistered), 1);
        }

        $this->isRegistered = $isRegistered;
    }

    /**
     * setVerificationToken
     * @param type $verificationToken
     * @throws \Exception
     */
    public function setVerificationToken($verificationToken) {

        if (!\HybridCMS\Modules\Validation\StringValidation
                ::isValidMd5Token($verificationToken)) {
            throw new \Exception(
            'Error Processing Request: 
                    setVerificationToken(),                    
                    $verificationToken is not valid: ' 
                    . htmlspecialchars($verificationToken), 1);
        }

        $this->verificationToken = $verificationToken;
    }
    
    /**
     * setTwitterName
     * @param String $twitterName
     * @throws \Exception
     */
    public function setTwitterName($twitterName) {

        if (!\HybridCMS\Modules\Validation\SMValidation
                ::isValidTwitterUsername($twitterName)) {
            throw new \Exception(
            'Error Processing Request: 
                    setTwitterName(),                    
                    $twitterName is not valid: ' 
                    . htmlspecialchars($twitterName), 1);
        }

        $this->twitterName = $twitterName;
    }

    /**
     * setFacebookUrl
     * @param String $facebookUrl
     * @throws \Exception
     */
    public function setFacebookUrl($facebookUrl) {

        if (!\HybridCMS\Modules\Validation\SMValidation
                ::isValidFacebookUrl($facebookUrl)) {
            throw new \Exception(
            'Error Processing Request: 
                    setFacebookUrl(),                    
                    $facebookUrl is not valid: ' 
                    . htmlspecialchars($facebookUrl), 1);
        }

        $this->facebookUrl = $facebookUrl;
    }
    
    /**
     * setGoogleplusId
     * @param Integer $googleplusId
     * @throws \Exception
     */
    public function setGoogleplusId($googleplusId) {

        if (!\HybridCMS\Modules\Validation\SMValidation
                ::isValidGoogleplusId($googleplusId)) {
            throw new \Exception(
            'Error Processing Request: 
                    setGoogleplusId(),                    
                    $googleplusId is not valid: ' 
                    . htmlspecialchars($googleplusId), 1);
        }

        $this->googleplusId = $googleplusId;
    }

    /**
     * setYoutubeChannelName
     * @param String $youtubeChannelName
     * @throws \Exception
     */
    public function setYoutubeChannelName($youtubeChannelName) {

        if (!\HybridCMS\Modules\Validation\SMValidation
                ::isValidYoutubeChannelName($youtubeChannelName)) {
            throw new \Exception(
            'Error Processing Request: 
                    setYoutubeChannelName(),                    
                    $youtubeChannelName is not valid: ' 
                    . htmlspecialchars($youtubeChannelName), 1);
        }

        $this->youtubeChannelName = $youtubeChannelName;
    }
    
    /**
     * setAboutme
     * @param String $aboutme
     * @throws \Exception
     */
    public function setAboutme($aboutme) {

        if (!\HybridCMS\Modules\Validation\UserDataValidation
                ::isValidAboutme($aboutme)) {
            throw new \Exception(
            'Error Processing Request: 
                    setAboutme(),                    
                    $aboutme is not valid: ' . htmlspecialchars($aboutme), 1);
        }

        $this->aboutme = $aboutme;
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
    
    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }
    
    public function getGender() {
        return $this->gender;
    }    
   
    public function getTwitterName() {
        return $this->twitterName;
    }

    public function getFacebookUrl() {
        return $this->facebookUrl;
    }

    public function getGoogleplusId() {
        return $this->googleplusId;
    }

    public function getYoutubeChannelName() {
        return $this->youtubeChannelName;
    }
    
    public function getIsRegistered() {
        return $this->isRegistered;
    }

    public function getVerificationToken() {
        return $this->verificationToken;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getHash() {
        return $this->hash;
    }    
}
?>