<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerDashboardProfile
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerDashboardProfile 
    extends \HybridCMS\Plugins\User\Controller\ControllerUser 
{
    
    /**
     * User that is loged in
     * @var User
     */
    private $objUser;
    
    /**
     * Database Connection
     * @var mysqli
     */
    private $objMySqli;
    
    /**
     * Object to perform Database-Operations
     * @var DBUser
     */
    private $objDBUser;
        
    /**
     * __construct
     * @param mixed[] $arrParams
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
    protected function handleRequest() 
    {      
        
        //attach each InputContent submitted by the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();        
        
        //handle action RegistrationSubmitted
        if (isset($this->arrParams['hyb_user_updateProfile'])) 
        {
            $this->handleActionUpdateProfile();
        }

        //handle default action and show the update form 
        //with all data from the user
        else 
        {
            $this->handleActionShowProfileUpdateForm();
        }        
    }
    
    /**
     * Handle Action to show the update Form with the data of the user
     */
    private function handleActionShowProfileUpdateForm()
    {        
        //create view
        $this->objView = 
            new \HybridCMS\Plugins\User\View\Dashboard\ViewDashboardEditProfile(
                $this->arrParams);
        
            assert(true === isset($_SESSION['userId']));
            assert(false === empty($_SESSION['userId']));

            $userId = $_SESSION['userId'];            
            
            try 
            {
                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();

                //select username from DB
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();                
                $this->objUser = $objDBUser
                        ->selectUserByUserId($db, $userId);

                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection(); 
                
                unset($objDBUser);

            } 
            catch (\Exception $e) 
            {
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory
                ::getFactory()->closeConnection();

                throw $e;
            }        
            
            //Assign userdata to Form Element Groups
            $this->assignUserDataToFormElemGroups();
    }
    
    /**
     * Handle Action to Update the profile data submitted to the user
     */
    private function handleActionUpdateProfile() 
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
        
        //save data to DB if Formstate is not has-error or has-warning
        if(true === $this->submittedDataIsValid) 
        {
            //create user with data submitted by the client
            $this->createUser();
            
            //updateUser
            $this->updateUserData();
        }        
        
        //create view
        $this->objView = new \HybridCMS\Plugins\User\View\Dashboard
                \ViewDashboardEditProfile($this->arrParams);
    }  
    
    private function assignUserDataToFormElemGroups()
    {
        //gender
        if(isset($this->arrParams['objFormElemGroupContentGender']))
        {
            $gender = $this->objUser->getGender();
            if(false === empty($gender))
            {
                $this->arrParams['objFormElemGroupContentGender']
                        ->setValue($gender);
            }
        }
        
        //email
        if(isset($this->arrParams['objFormElemGroupContentEmailUniqueOrSessionEmail']))
        {
            $email = $this->objUser->getEmail();
            if(false === empty($email))
            {
                $this->arrParams['objFormElemGroupContentEmailUniqueOrSessionEmail']
                        ->setValue($email);
            }
        }   
        
        //username
        if(isset($this->arrParams['objFormElemGroupContentUsernameUniqueOrSessionUsername']))
        {
            $username = $this->objUser->getUsername();
            if(false === empty($username))
            {
                $this->arrParams['objFormElemGroupContentUsernameUniqueOrSessionUsername']
                        ->setValue($username);
            }
        }  
        
        //firstname
        if(isset($this->arrParams['objFormElemGroupContentFirstname']))
        {
            $firstname = $this->objUser->getFirstname();
            if(false === empty($firstname))
            {
                $this->arrParams['objFormElemGroupContentFirstname']
                        ->setValue($firstname);
            }
        }   
        
        //lastname
        if(isset($this->arrParams['objFormElemGroupContentLastname']))
        {
            $lastname = $this->objUser->getLastname();
            if(false === empty($lastname))
            {
                $this->arrParams['objFormElemGroupContentLastname']
                        ->setValue($lastname);
            }
        }     
        
        //twittername
        if(isset($this->arrParams['objFormElemGroupContentTwitterName']))
        {
            $twitterName = $this->objUser->getTwitterName();
            if(false === empty($twitterName))
            {
                $this->arrParams['objFormElemGroupContentTwitterName']
                        ->setValue($twitterName);
            }
        }      
        
        //facebookUrl
        if(isset($this->arrParams['objFormElemGroupContentFacebookUrl']))
        {
            $facebookUrl = $this->objUser->getFacebookUrl();
            if(false === empty($facebookUrl))
            {
                $this->arrParams['objFormElemGroupContentFacebookUrl']
                        ->setValue($facebookUrl);
            }
        }     
        
        //googlePlusId
        if(isset($this->arrParams['objFormElemGroupContentGoogleplusId']))
        {
            $googlePlusId = $this->objUser->getGooglePlusId();
            if(false === empty($googlePlusId))
            {
                $this->arrParams['objFormElemGroupContentGoogleplusId']
                        ->setValue($googlePlusId);
            }
        }  
        
        //youtubeChannelName
        if(isset($this->arrParams['objFormElemGroupContentYoutubeChannelName']))
        {
            $youtubeChannelName = $this->objUser->getYoutubeChannelName();
            if(false === empty($youtubeChannelName))
            {
                $this->arrParams['objFormElemGroupContentYoutubeChannelName']
                        ->setValue($youtubeChannelName);
            }
        }    
        
        //website
        if(isset($this->arrParams['objFormElemGroupContentWebsite']))
        {
            $website = $this->objUser->getWebsite();
            if(false === empty($website))
            {
                $this->arrParams['objFormElemGroupContentWebsite']
                        ->setValue($website);
            }
        }  
        
        //aboutme
        if(isset($this->arrParams['objFormElemGroupContentAboutme']))
        {
            $aboutme = $this->objUser->getAboutme();
            if(false === empty($aboutme))
            {
                $this->arrParams['objFormElemGroupContentAboutme']
                        ->setValue($aboutme);
            }
        }        
    }

    /**
     * Creates $this->objUser
     */
    private function createUser() 
    {
            $email = $this->arrParams['objFormElemGroupContentEmailUniqueOrSessionEmail']
                    ->getValue();
            
            $userId = $_SESSION['userId'];
            
            assert(false === empty($userId));
            assert(false === empty($email));
            
            $this->objUser = \HybridCMS\Plugins\User\Model\UserFactory
                    ::create('registered');  
            $this->objUser->setEmail($email);
            $this->objUser->setUserId($userId);
            
            //set username
            if(isset($this->arrParams['objFormElemGroupContentUsernameUniqueOrSessionUsername']))
            {
                $username = $this->arrParams['objFormElemGroupContentUsernameUniqueOrSessionUsername']
                        ->getValue();
                
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
                    $this->objUser->setFirstname($this->arrParams['hyb_user_firstname']);
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
                $twitterName = $this->arrParams['objFormElemGroupContentTwitterName']
                        ->getValue();
                
                if(false === empty($twitterName)) 
                {
                    $this->objUser->setTwitterName($twitterName);
                }
            }     
            
            //set facebookUrl
            if(isset($this->arrParams['objFormElemGroupContentFacebookUrl']))
            {
                $facebookUrl = $this->arrParams['objFormElemGroupContentFacebookUrl']
                        ->getValue();
                
                if(false === empty($facebookUrl)) 
                {                
                    $this->objUser->setfacebookUrl($facebookUrl);
                }
            }     
            
            //set googleplusId
            if(isset($this->arrParams['objFormElemGroupContentGoogleplusId']))
            {
                $googleplusId = $this->arrParams['objFormElemGroupContentGoogleplusId']
                        ->getValue();
                
                if(false === empty($googleplusId)) 
                {                         
                    $this->objUser->setGoogleplusId($googleplusId);
                }
            }     
            
            //set youtubeChannelName
            if(isset($this->arrParams['objFormElemGroupContentYoutubeChannelName']))
            {
                $youtubeChannelName = $this->arrParams['objFormElemGroupContentYoutubeChannelName']
                        ->getValue();
                
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
    
    /**
     * Updates userdata in the database
     * @throws Exception
     */
    private function updateUserData() {
        try 
        {
            //open Database-Connection
            $this->objMySqli = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();
            
            //select username from DB
            $this->objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();                   

            //check if user has updated the email-Adress in the form
            if($this->objUser->getEmail() !== $_SESSION['email'])
            {
                $this->updateEmail();
            }
            
            //update username
            $username = $this->objUser->getUsername();
            if(false === empty($username)
               &&
               $username !== $_SESSION['username'])
            {
                $this->updateUsername();
            }
            
            //update gender
            $gender = $this->objUser->getGender();
            if(false === empty($gender))
            {
                $this->updateGender();
            }      
            
            //update firstname
            $firstname = $this->objUser->getFirstname();
            if(false === empty($firstname))
            {
                $this->updateFirstname();
            }      
            
            //update lastname
            $lastname = $this->objUser->getLastname();
            if(false === empty($lastname))
            {
                $this->updateLastname();
            }      
            
            //update twitterName
            $twitterName = $this->objUser->getTwitterName();
            if(false === empty($twitterName))
            {
                $this->updateTwitterName();
            }      
            
            //update facebookUrl
            $facebookUrl = $this->objUser->getFacebookUrl();
            if(false === empty($facebookUrl))
            {
                $this->updateFacebookUrl();
            }      
            
            //update googleplusId
            $googleplusId = $this->objUser->getGoogleplusId();
            if(false === empty($googleplusId))
            {
                $this->updateGoogleplusId();
            }     
            
            //update youtubeChannelName
            $youtubeChannelName = $this->objUser->getYoutubeChannelName();
            if(false === empty($youtubeChannelName))
            {
                $this->updateYoutubeChannelName();
            }         
            
            //update website
            $website = $this->objUser->getWebsite();
            if(false === empty($website))
            {
                $this->updateWebsite();
            }                 
            
            //update aboutme
            $aboutme = $this->objUser->getAboutme();
            if(false === empty($aboutme))
            {
                $this->updateAboutme();
            }             

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection(); 

            unset($objDBUser);

        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }
    
    /**
     * Updates the email of the user
     */
    private function updateEmail()
    {
        try 
        {
            $affectedRows 
                    = $this->objDBUser
                        ->updateEmailNotConfirmedByUserId(
                            $this->objMySqli, $this->objUser);
            
            assert(1 === $affectedRows);

            //send email to user for verification
            $objUserContact = new \HybridCMS\Plugins\User\Model
                    \UserContact($this->objUser);
            
            $objUserContact->sendConfirmationEmailUpdateEmail();   
            
            //add hint to the form-element
            $hint = 'Sie erhalten in Kürze eine E-Mail an ' 
                    . $this->objUser->getEmail() 
                    . ' um die neue E-Mail Adresse zu bestätigen.';
            
            $this->arrParams['objFormElemGroupContentEmailUniqueOrSessionEmail']
                    ->setHint($hint);
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }
    
    /**
     * Updates the gender of the user
     */    
    private function updateGender()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateGenderByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }   
    }     
    
    /**
     * Updates the username of the user
     */    
    private function updateUsername()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateUsernameByUserId(
                            $this->objMySqli, $this->objUser);
            
            assert(1 === $affectedRows);  
            
            $_SESSION['username'] = $this->objUser->getUsername();
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }
    
    /**
     * Updates the firstname of the user
     */ 
    private function updateFirstname()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateFirstnameByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }
    
    /**
     * Updates the lastname of the user
     */ 
    private function updateLastname()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateLastnameByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }   
    }    
    
    /**
     * Updates the twitterName of the user
     */ 
    private function updateTwitterName()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateTwitterNameByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    } 
    
    /**
     * Updates the facebookUrl of the user
     */ 
    private function updateFacebookUrl()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateFacebookUrlByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }     
    
    /**
     * Updates the googleplusId of the user
     */ 
    private function updateGoogleplusId()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateGoogleplusIdByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }  
    
    /**
     * Updates the youtubeChannelName of the user
     */ 
    private function updateYoutubeChannelName()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateYoutubeChannelNameByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }   
    
    /**
     * Updates the website of the user
     */ 
    private function updateWebsite()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateWebsiteByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }   
    
    /**
     * Updates the aboutme of the user
     */ 
    private function updateAboutme()
    {
        try 
        {
            $affectedRows = $this->objDBUser
                        ->updateAboutmeByUserId(
                            $this->objMySqli, $this->objUser);
            
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            throw $e;
        }  
    }    
}
    ?>