<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerRegistration
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerRegistration 
    extends \HybridCMS\Plugins\User\Controller\ControllerUser {
 
    /**
     * Userdata submitted by the client
     * @var User $objUser
     */
    private $objUser;

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrParams) 
    {         
        //call constructor of parent class
        parent::__construct($arrParams);        
        
        //handle Request sent by the client
        $this->handleRequest();   
    }

    /**
     * handle the request of the client 
     */
    protected function handleRequest() {
        
        //attach each InputContent submitted by 
        //the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();         

        //handle action RegistrationSubmitted
        if (true === isset($this->arrParams['hyb_user_submitRegistration'])) 
        {
            $this->handleActionRegistrationSubmitted();
        }
        else 
        {   
            //handle default action
            $this->handleActionRegistration();
        }
    }

    /**
     * handleActionRegistration and assign the corresponding view
     */
    private function handleActionRegistration() 
    {   
        //set view
        $this->objView = new \HybridCMS\Plugins\User\View
                \ViewRegistration($this->arrParams); 
         
        //Add UIRegister JSResources
        $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                'userUIRegister', 
                '/HybridCMS/Plugins/User/js/UserUIRegister.js', 
                3, 
                false, 
                true, 
                'footer', 
                true
        );
        $this->addObjJSResource($objJSResource1);

        //Add UIRegister CSSResource
        $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                'userUIRegister', 
                '/HybridCMS/Plugins/User/css/userUIRegister.css');
        $this->addObjCSSResource($objCSSResource1);
    }

    /**
     * handleActionRegistrationSubmitted and assign the corresponding view
     */
    private function handleActionRegistrationSubmitted() 
    {                
        //explicitly call method to validate Formdata
        $this->validateFormData();
        
        if(true === $this->objFormStateObserver->getFormHasError())
        {
            $this->submittedDataIsValid = false;
        } 
        else if(true === $this->objFormStateObserver->getFormHasWarning())
        {
            $this->submittedDataIsValid = false;
        }
        
        //check if all data was valid and submittedDataIsValid is still valid
        if(true === $this->submittedDataIsValid) 
        {                   
            try 
            {                     
                //create User Object out of the data submitted by the user
                $this->createUser();

                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();
            
                //save user into DB
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();
                                
                $ret = $objDBUser->insertUser($db, $this->objUser);
                
                assert(0 < $ret['insertId']);
                
                $this->objUser->setUserId($ret['insertId']);
                
                $ret = $objDBUser->insertUserRegistered($db, $this->objUser);
                
                assert(0 < $ret['insertId']);
                
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();                 

                //send email to user for verification
                $objUserContact = new \HybridCMS\Plugins\User\Model
                        \UserContact($this->objUser);
                $objUserContact->sendConfirmationEmailRegistration();
                
                //set view
                $this->objView = new \HybridCMS\Plugins\User\View
                        \ViewRegistrationSubmitted($this->arrParams);
                
            } 
            catch (Exception $e) 
            {

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();

                throw $e;
            }
        } 
        else //data send by the user was not valid
        {            
            //send user back to registration form
            $this->handleActionRegistration();
        }
    }
    
    /**
     * Creates $this->objUser
     */
    private function createUser() 
    {
        
        $email = $this->arrParams['objFormElemGroupContentEmailUnique']
                ->getValue();

        $password = $this->arrParams['objFormElemGroupContentPassword']
                ->getValue();

        assert(false === empty($email));
        assert(false === empty($password));

        $this->objUser = \HybridCMS\Plugins\User\Model\UserFactory
                ::create('registered'); 

        $this->objUser->setEmail($email);
        $this->objUser->setPassword($password);

        //set username
        if(isset($this->arrParams['objFormElemGroupContentUsernameUnique']))
        {
            $username = $this->arrParams
                    ['objFormElemGroupContentUsernameUnique']->getValue();

            if(false === empty($username)) 
            {                    
                $this->objUser->setUsername($username);
            }
        }

        //set firstname
        if(isset($this->arrParams['objFormElemGroupContentFirstname']))
        {
            $firstname = $this->arrParams['objFormElemGroupContentFirstname']
                    ->getValue();

            if(false === empty($firstname)) 
            {                   
                $this->objUser
                        ->setFirstname
                        ($this->arrParams['hyb_user_firstname']);
            }
        }    

        //set lastname
        if(isset($this->arrParams['objFormElemGroupContentLastname']))
        {
            $lastname = $this->arrParams['objFormElemGroupContentLastname']
                    ->getValue();

            if(false === empty($lastname)) 
            {                     
                $this->objUser->setLastname($lastname);
            }
        }     

        //set gender
        if(isset($this->arrParams['objFormElemGroupContentGender']))
        {
            $gender = $this->arrParams['objFormElemGroupContentGender']
                    ->getValue();

            if(false === empty($gender)) 
            {                
                $this->objUser->setGender($gender);
            }
        }               

        //set twitterName
        if(isset($this->arrParams['objFormElemGroupContentTwitterName']))
        {
            $twitterName = 
                    $this->arrParams['objFormElemGroupContentTwitterName']
                    ->getValue();
            if(false === empty($twitterName)) 
            {
                $this->objUser->setTwitterName($twitterName);
            }
        }     

        //set facebookUrl
        if(isset($this->arrParams['objFormElemGroupContentFacebookUrl']))
        {
            $facebookUrl = 
                    $this->arrParams['objFormElemGroupContentFacebookUrl']
                    ->getValue();

            if(false === empty($facebookUrl)) 
            {                
                $this->objUser->setfacebookUrl($facebookUrl);
            }
        }     

        //set googleplusId
        if(isset($this->arrParams['objFormElemGroupContentGoogleplusId']))
        {
            $googleplusId = 
                    $this->arrParams['objFormElemGroupContentGoogleplusId']
                    ->getValue();

            if(false === empty($googleplusId)) 
            {                         
                $this->objUser->setGoogleplusId($googleplusId);
            }
        }     

        //set youtubeChannelName
        if(isset($this->arrParams['objFormElemGroupContentYoutubeChannelName']))
        {
            $youtubeChannelName = 
                    $this->arrParams
                    ['objFormElemGroupContentYoutubeChannelName']->getValue();

            if(false === empty($youtubeChannelName)) 
            {                         
                $this->objUser->setYoutubeChannelName($youtubeChannelName);
            }
        }     

        //set website
        if(isset($this->arrParams['objFormElemGroupContentWebsite']))
        {
            $website = $this->arrParams['objFormElemGroupContentWebsite']
                    ->getValue();

            if(false === empty($website)) 
            {                         
                $this->objUser->setWebsite($website);
            }
        }     

        //set aboutme
        if(isset($this->arrParams['objFormElemGroupContentAboutme']))
        {
            $aboutme = $this->arrParams['objFormElemGroupContentAboutme']
                    ->getValue();

            if(false === empty($aboutme)) 
            {                         
                $this->objUser->setAboutme($aboutme);
            }
        }     

        //generate new verificationToken
        $verificationToken = md5(time() * mt_rand());
        $this->objUser->setVerificationToken($verificationToken);
    }
}
