<?php

namespace HybridCMS\Plugins\User\Model;

/**
 * class UserOpenId
 *
 * @package User\Model
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class UserOpenId extends \HybridCMS\Plugins\User\Model\User
{
    /**
     * The Unique user's ID on the connected provider. Can be an interger for 
     * some providers, Email, URL, etc.
     * @var String
     */
    private $identifier;
    
    /**
     * The connected provider
     * @var String
     */
    private $issuer;
    
    /**
     * URL link to profile page on the IDp web site
     * @var String
     */
    private $profileURL;
    
    /**
     * URL link to user photo or avatar
     * @var String
     */
    private $photoURL;
    
    /**
     * User's language
     * @var String
     */
    private $language;
    
    /**
     * User' age, note that we dont calculate it. we return it as is if the 
     * IDp provide it
     * @var Integer
     */
    private $age;
    
    /**
     * The day in the month in which the person was born.
     * @var Integer
     */
    private $birthDay;
    
    /**
     * The month in which the person was born.
     * @var Integer
     */
    private $birthMonth;
    
    /**
     * The year in which the person was born.
     * @var Integer
     */
    private $birthYear;
    
    /**
     * Verified user email. Note: not all of IDp garant access to verified 
     * user email.
     * @var String
     */
    private $emailVerified;
    
    /**
     * User's phone number
     * @var String
     */
    private $phone;
    
    /**
     * User's address
     * @var String
     */
    private $address;
    
    /**
     * User's country
     * @var String
     */
    private $country;
    
    /**
     * User's state or region 
     * @var String
     */
    private $region;
    
    /**
     * User's city
     * @var String
     */
    private $city;
    
    /**
     * Postal code or zipcode.
     * @var Integer
     */
    private $zip;  
    
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
     * setFirstname
     * @param String $firstName
     * @throws \Exception
     */    
    public function setFirstname($firstname) 
    {        
        if(false === is_string($firstname))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setFirstname(),                    
                    $firstname of current User is not valid.', 1);
        }
        
        $this->firstname = $firstname;
    }
    
    /**
     * setGender
     * @param String $gender
     * @throws \Exception
     */
    public function setGender($gender) 
    {        
        if(false === is_string($gender))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setGender(),                    
                    $gender of current User is not valid.', 1);
        }
        
        $this->gender = $gender;        
    }

    /**
     * setLastname
     * @param String $lastName
     * @throws \Exception
     */
    public function setLastname($lastname) 
    {
        if(false === is_string($lastname))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setLastname(),                    
                    $lastname of current User is not valid.', 1);
        }
        
        $this->lastname = $lastname; 
    }

    /**
     * setFirstname
     * @param String $firstName
     * @throws \Exception
     */    
    public function setUsername($username) 
    {
        if(false === is_string($username))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setUsername(),                    
                    $username of current User is not valid.', 1);
        }
        
        $this->username = $username;
    }
    
    /**
     * setIdentifier
     * @param String $identifier
     */
    public function setIdentifier($identifier) 
    {
        if(false === (is_string($identifier) || is_numeric($identifier)))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setIdentifier(),                    
                    $identifier of current User is not valid.', 1);
        }
        
        $this->identifier = (string)$identifier;
    }

    /**
     * setIssuer
     * @param String $issuer
     */    
    public function setIssuer($issuer) 
    {
        if(false === is_string($issuer))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setIssuer(),                    
                    $issuer of current User is not valid.', 1);
        }
        
        $this->issuer = $issuer;
    }

    /**
     * setProfileURL
     * @param String $profileURL
     */       
    public function setProfileURL($profileURL) 
    {
        if(false === is_string($profileURL))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setProfileURL(),                    
                    $profileURL of current User is not valid.', 1);
        }
        
        $this->profileURL = $profileURL;
    }

    /**
     * setPhotoURL
     * @param String $photoURL
     */       
    public function setPhotoURL($photoURL) 
    {
        if(false === is_string($photoURL))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setPhotoURL(),                    
                    $photoURL of current User is not valid.', 1);
        }
        
        $this->photoURL = $photoURL;
    }

    /**
     * setLanguage
     * @param String $language
     */      
    public function setLanguage($language) 
    {
        if(false === is_string($language))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setLanguage(),                    
                    $language of current User is not valid.', 1);
        }
        
        $this->language = $language;
    }

    /**
     * setAge
     * @param Integer $age
     */         
    public function setAge($age) 
    {
        if(false === is_numeric($age))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setAge(),                    
                    $age of current User is not valid.', 1);
        }
        
        $this->age = $age;
    }

    /**
     * setBirthDay
     * @param Integer $birthDay
     */     
    public function setBirthDay($birthDay) 
    {
        if(false === is_numeric($birthDay))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setBirthDay(),                    
                    $birthDay of current User is not valid.', 1);
        }
        
        $this->birthDay = $birthDay;
    }

    /**
     * setBirthMonth
     * @param Integer $birthMonth
     */       
    public function setBirthMonth($birthMonth) 
    {
        if(false === is_numeric($birthMonth))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setBirthMonth(),                    
                    $birthMonth of current User is not valid.', 1);
        }
        
        $this->birthMonth = $birthMonth;
    }

    /**
     * setBirthYear
     * @param Integer $birthYear
     */          
    public function setBirthYear($birthYear) 
    {
        if(false === is_numeric($birthYear))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setBirthYear(),                    
                    $birthYear of current User is not valid.', 1);
        }
        
        $this->birthYear = $birthYear;
    }

    /**
     * setEmailVerified
     * @param String $emailVerified
     */       
    public function setEmailVerified($emailVerified) 
    {
        if(false === is_string($emailVerified))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setEmailVerified(),                    
                    $emailVerified of current User is not valid.', 1);
        }
        
        $this->emailVerified = $emailVerified;
    }

    /**
     * setPhone
     * @param String $phone
     */        
    public function setPhone($phone) 
    {
        if(false === is_string($phone))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setPhone(),                    
                    $phone of current User is not valid.', 1);
        }
        
        $this->phone = $phone;
    }

    /**
     * setAddress
     * @param String $address
     */       
    public function setAddress($address) 
    {
        if(false === is_string($address))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setAddress(),                    
                    $address of current User is not valid.', 1);
        }
        
        $this->address = $address;
    }

    /**
     * setCountry
     * @param String $country
     */        
    public function setCountry($country) 
    {
        if(false === is_string($country))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setCountry(),                    
                    $country of current User is not valid.', 1);
        }
        
        $this->country = $country;
    }

    /**
     * setRegion
     * @param String $region
     */     
    public function setRegion($region) 
    {
        if(false === is_string($region))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setRegion(),                    
                    $region of current User is not valid.', 1);
        }
        
        $this->region = $region;
    }

    /**
     * setCity
     * @param String $city
     */       
    public function setCity($city) 
    {
        if(false === is_string($city))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setCity(),                    
                    $city of current User is not valid.', 1);
        }
        
        $this->city = $city;
    }

    /**
     * setZip
     * @param Integer $zip
     */      
    public function setZip($zip) 
    {
        if(false === is_numeric($zip))
        {
            throw new \Exception(
            'Error Processing Request: 
                    setZip(),                    
                    $zip of current User is not valid.', 1);
        }
        
        $this->zip = $zip;
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
     * Returns the avatar picture url of the current user
     * @return String
     */
    public function getAvatarUrl($size = 80)
    {
        return $this->getPhotoURL();
    }

    /**
     * Getter
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    public function getIssuer() {
        return $this->issuer;
    }

    public function getProfileURL() {
        return $this->profileURL;
    }

    public function getPhotoURL() {
        return $this->photoURL;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getAge() {
        return $this->age;
    }

    public function getBirthDay() {
        return $this->birthDay;
    }

    public function getBirthMonth() {
        return $this->birthMonth;
    }

    public function getBirthYear() {
        return $this->birthYear;
    }

    public function getEmailVerified() {
        return $this->emailVerified;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getRegion() {
        return $this->region;
    }

    public function getCity() {
        return $this->city;
    }

    public function getZip() {
        return $this->zip;
    }
}
?>
