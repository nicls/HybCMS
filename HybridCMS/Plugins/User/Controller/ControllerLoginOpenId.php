<?php
namespace HybridCMS\Plugins\User\Controller;

//includes
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Modules/Hybridauth/Hybrid/Auth.php" );

/**
 * class ControllerLoginOpenId
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerLoginOpenId 
    extends \HybridCMS\Plugins\User\Controller\ControllerLogin 
{    
    /**
     * Object to connect with OpenId
     * @var Hybridauth
     */
    private $objHybridauth;
    
    /**
     * Path to Configuration of Hybridauth
     * @var String
     */
    private $configHybridauth;
    
    /**
     * OpenId Adapter to connect with the openId Provider
     * @var Object 
     */
    private $objAdapter;
    
    /**
     * Profile of the user
     * @var Object
     */
    private $objUserProfile;
    
    /**
     * User that is logged in
     * @var User
     */
    private $objUser;
    
    /**
     * Provider the user wants to connect to
     * @var String
     */
    private $provider;
               
    
    /**
     * __construct
     * @param mixed[] $arrParams
     */    
    public function __construct($arrParams) 
    {
        //call constructor of parent class
        parent::__construct($arrParams);  
        
        //set path to configaration file of Hybridauth
        $this->configHybridauth =
                $_SERVER['DOCUMENT_ROOT'] 
                . '/HybridCMS/Modules/Hybridauth/config.php';
        
        //handle Request sent by the client
        $this->handleRequest();                   
    }
    
    /**
     * handle the request of the client 
     */
    protected function handleRequest() 
    {             
        
        //handle action Logout
        if (true === isset($this->arrParams['hyb_user_logout'])) 
        {
            $this->handleActionLogout();
        }
        else
        {
            
            //attach each InputContent submitted by the 
            //client to an FormElementGroup
            //to print the form later in the view
            $this->createFormElementGroups();

            //handle action Login twitter
            if (true === isset($this->arrParams['hyb_user_login_twitter'])
                || 
                (
                    true === isset($_SESSION['tryLoginOpenId'])
                    &&
                    'Twitter' === $_SESSION['tryLoginOpenId'] 
                )
               )
            {
                $this->provider = 'Twitter';
                $this->handleActionLogin();
            }

            //handle action Login facebook
            else if (true === isset($this->arrParams['hyb_user_login_facebook'])
                     || 
                     (
                         true === isset($_SESSION['tryLoginOpenId'])
                         &&
                         'Facebook' === $_SESSION['tryLoginOpenId'] 
                     )
                    ) 
            {
                $this->provider = 'Facebook';
                $this->handleActionLogin();
            }    

            //handle action Login googleplus
            else if (true === isset($this->arrParams['hyb_user_login_googleplus'])
                     || 
                     (
                         true === isset($_SESSION['tryLoginOpenId'])
                         &&
                         'GooglePlus' === $_SESSION['tryLoginOpenId'] 
                     )                
                    ) 
            {
                $this->provider = 'Google';
                $this->handleActionLogin();
            }                    

            //handle default action and show OpenId LoginButtons
            else 
            {
                $this->handleActionShowOpenIdLoginButtons();
            }
        }
    }
    
    /**
     * Login with an OpenId provider
     */
    protected function handleActionLogin()
    {  
        
        //check if referrer is set and save it 
        $this->getReferrerAndSaveInSession();
        
        assert(false === empty($this->provider));
                
        //login and get identifier of the user
        $identifier = $this->loginWith($this->provider);
        
        if(false === empty($identifier))
        {
            $this->objView = new \HybridCMS\Plugins\User\View
                    \ViewLogedIn($this->arrParams);
            
            try 
            {                     
                //create user-object
                $this->createUser();

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();
            
                //save user into DB
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserOpenId();
                                
                $arrRet = $objDBUser->userExists($db, $this->objUser);
                
                if(true === $arrRet['userExists'])
                {
                    $this->objUser->setUserId($arrRet['userId']);
                    $objDBUser->updateUserOpenId($db, $this->objUser);
                } 
                else
                {
                    $arrRet = $objDBUser->insertUser($db, $this->objUser);
                    
                    assert(true === $arrRet['success']);
                    assert(0 < $arrRet['insertId']);
                    
                    $this->objUser->setUserId($arrRet['insertId']);
                    
                    $success = $objDBUser->insertUserOpenId($db, $this->objUser);
                    assert(true === $success);
                }
                                
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();  
                
                unset($objDBUser);  
                
                //set Session Variables
                $_SESSION['logedIn'] = true;  
                $_SESSION['userId'] = $this->objUser->getUserId();
                $_SESSION['identifier'] = $this->objUser->getIdentifier();
                $_SESSION['issuer'] = $this->objUser->getIssuer();
                $_SESSION['firstname'] = $this->objUser->getFirstname();
                $_SESSION['lastname'] = $this->objUser->getLastname();
                $_SESSION['username'] = $this->objUser->getUsername();                      
                $_SESSION['type'] = $this->objUser->getType();
                
                //delelte try variavle
                unset($_SESSION['tryLoginOpenId']);
                
                //redirect User to the page he came from if the 
                //referrer is set
                $this->redirectUserToPreviousPage();                
                
            } 
            catch (Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();

                throw $e;
            }                        
        }
        else 
        {
            $this->handleActionShowOpenIdLoginButtons();
        }           
    }
    
    /**
     * logs an user out that is connected with openId
     */
    protected function handleActionLogout()
    {
        if(false === empty($_SESSION['issuer']))
        {
    
            // create an instance for Hybridauth with the configuration file path as parameter
            $this->objHybridauth = 
                    new \Hybrid_Auth($this->configHybridauth);

            $this->objHybridauth->logoutAllProviders();
        }   
        
        //set view
        $this->objView = 
                new \HybridCMS\Plugins\User\View\ViewLogedOut(
                        $this->arrParams);     
        
        //redirect User to the homepage
        $this->redirectUserTo(HYB_HOMEPAGE);         
    }    
        
    /**
     * handleActionShowOpenIdLoginButtons
     */
    private function handleActionShowOpenIdLoginButtons()
    {        
        //set view
        $this->objView = new \HybridCMS\Plugins\User\View\ViewLoginOpenId (
                $this->arrParams);

        //Add UIRegister CSSResource
        $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                'socialButtons', 
                '/HybridCMS/Plugins/User/css/social-buttons.css');
        $this->addObjCSSResource($objCSSResource1);
    }
    
    /**
     * Method to login by a given provider
     * @param String $issuer
     * @return String
     */
    private function loginWith($provider)
    {
        $_SESSION['tryLoginOpenId'] = $provider;
        
  	// create an instance for Hybridauth with 
        // the configuration file path as parameter
  	$this->objHybridauth = new \Hybrid_Auth($this->configHybridauth);            
  
  	// try to authenticate the user with the provider, 
  	// user will be redirected to Provider for authentication, 
  	// if he already did, then Hybridauth will ignore this 
        // step and return an instance of the adapter
  	$this->objAdapter = $this->objHybridauth->authenticate($provider);  
 
  	// get the user profile 
  	$this->objUserProfile = $this->objAdapter->getUserProfile();
                
        $identifier = null;
        
        if(false === empty($this->objUserProfile))
        {
            $identifier = $this->objUserProfile->identifier;            
        }
                
        return $identifier;
    }
        
    /**
     * Creates $this->objUser
     */
    private function createUser() 
    {
        $identifier = $this->objUserProfile->identifier;            
        $issuer = $this->objAdapter->id;
        
        assert(false === empty($identifier));
        assert(false === empty($issuer));

        //crete new User and set type
        $this->objUser = \HybridCMS\Plugins\User\Model
                \UserFactory::create('openId'); 
        
        $this->objUser->setIssuer($issuer);
        $this->objUser->setIdentifier($identifier);
        
        //set username
        $username = $this->objUserProfile->displayName;
        if(false === empty($username))
        {                 
            $this->objUser->setUsername($username);
        }   

        //set website
        $website = $this->objUserProfile->webSiteURL;
        if(false === empty($website))
        {                 
            $this->objUser->setWebsite($website);
        } 

        //set profileURL
        $profileURL = $this->objUserProfile->profileURL;
        if(false === empty($profileURL))
        {                 
            $this->objUser->setProfileUrl($profileURL);
        }             

        //set photoURL
        $photoURL = $this->objUserProfile->photoURL;
        if(false === empty($photoURL))
        {                 
            $this->objUser->setPhotoURL($photoURL);
        }              

        //set description
        $aboutme = $this->objUserProfile->description;
        if(false === empty($aboutme))
        {                 
            $this->objUser->setAboutme($aboutme);
        }            

        //set firstName
        $firstname = $this->objUserProfile->firstName;
        if(false === empty($firstname))
        {                 
            $this->objUser->setFirstname($firstname);
        }               

        //set lastName
        $lastname = $this->objUserProfile->lastName;
        if(false === empty($lastname))
        {                 
            $this->objUser->setLastname($lastname);
        } 

        //set gender
        $gender = $this->objUserProfile->gender;
        if(false === empty($gender))
        {                 
            $this->objUser->setGender($gender);
        }             

        //set language
        $language = $this->objUserProfile->language;
        if(false === empty($language))
        {                 
            $this->objUser->setLanguage($language);
        }              

        //set age
        $age = $this->objUserProfile->age;
        if(false === empty($age))
        {                 
            $this->objUser->setAge($age);
        }             

        //set birthDay
        $birthDay = $this->objUserProfile->birthDay;
        if(false === empty($birthDay))
        {                 
            $this->objUser->setBirthDay($birthDay);
        }             

        //set birthMonth
        $birthMonth = $this->objUserProfile->birthMonth;
        if(false === empty($birthMonth))
        {                 
            $this->objUser->setBirthMonth($birthMonth);
        }            

        //set birthYear
        $birthYear = $this->objUserProfile->birthYear;
        if(false === empty($birthYear))
        {                 
            $this->objUser->setBirthMonth($birthYear);
        }            

        //set email
        $email = $this->objUserProfile->email;
        if(false === empty($email))
        {                 
            $this->objUser->setEmail($email);
        }            

        //set emailVerified
        $emailVerified = $this->objUserProfile->emailVerified;
        if(false === empty($emailVerified))
        {                 
            $this->objUser->setEmailVerified($emailVerified);
        }            

        //set phone
        $phone = $this->objUserProfile->phone;
        if(false === empty($phone))
        {                 
            $this->objUser->setPhone($phone);
        }              

        //set address
        $address = $this->objUserProfile->address;
        if(false === empty($address))
        {                 
            $this->objUser->setAddress($address);
        }              

        //set country
        $country = $this->objUserProfile->country;
        if(false === empty($country))
        {                 
            $this->objUser->setCountry($country);
        }            

        //set region
        $region = $this->objUserProfile->region;
        if(false === empty($region))
        {                 
            $this->objUser->setRegion($region);
        }              

        //set city
        $city = $this->objUserProfile->city;
        if(false === empty($city))
        {                 
            $this->objUser->setCity($city);
        }               

        //set zip
        $zip = $this->objUserProfile->zip;
        if(false === empty($zip))
        {                 
            $this->objUser->setZip($zip);
        }                          
    }    
    
    /**
     * redirect User to the page he came from
     */
    protected function redirectUserToPreviousPage()
    {        
        //redirect user to the homepage if he is on the loginpage or
        //if the referrer is not set
        if(false === isset($_SESSION['ref'])
           || HYB_CURRURL === $_SESSION['ref'])
        {
            //redirect user to the startpage
            $_SESSION['ref'] = HYB_HOMEPAGE;
        }        
        
        //redirect user the the page he came from if the referrer is set
        $this->redirectUserTo($_SESSION['ref']);               
    }   
    
    /**
     * check if referrer is set and save it in the session-variable so that 
     * the user can be redirected after he returns from the provider
     */
    private function getReferrerAndSaveInSession()
    {        
        if(true === isset($this->arrParams['ref']))
        {
            if(true === \HybridCMS\Modules\Url\Url
                    ::isValidUrl($this->arrParams['ref']))
            {
                $objRef = new \HybridCMS\Modules\Url
                        \Referrer($this->arrParams['ref']);
                
                if(true === $objRef->refIsInternal())
                {
                    $_SESSION['ref'] = $this->arrParams['ref'];
                }
            }            
        } 
    }

}
