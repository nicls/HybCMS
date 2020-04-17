<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerDashboardProfile
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerPublicUserProfile 
    extends \HybridCMS\Plugins\User\Controller\ControllerUser 
{
    
    /**
     * UserId of the requested user
     * @var User
     */
    private $userId;
        
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
     * Handle Request from the client
     */
    protected function handleRequest() 
    {        
        
        //Add UIRegister CSSResource
        $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                'userPublicProfile', 
                '/HybridCMS/Plugins/User/css/userPublicProfile.css');
        $this->addObjCSSResource($objCSSResource1);
        
        try 
        {
            if(true === isset($this->arrParams['id']))
            {        
                //open Database-Connection
                $this->objMySqli = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();
                    
                $this->userId = $this->arrParams['id'];
                $this->objDBUser = new \HybridCMS\Plugins\User\Database\DBUser();
                $type = $this->objDBUser
                        ->selectTypeByUserId($this->objMySqli, $this->userId);

                unset($this->objDBUser);

                //open Database Connection if user exists
                if(false === empty($type))
                {
                    //handle registered user
                    if($type === 'registered')
                    {    
                        $this->handleActionShowRegisteredUser();
                    }   

                    //handle openId user
                    else if($type === 'openId')
                    {
                        $this->handleActionShowOpenIdUser();
                    }                    
                }
                else
                {
                    //user does not exist so set view for userDoesNotExists
                    $this->objView = new \HybridCMS\Plugins\User\View
                            \ViewPublicUserProfileDoesNotExist($this->arrParams);
                }   
                
                //close Database-Connection
                \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();                 
            } 
            else 
            {
                //userId is missing
                $this->objView = new \HybridCMS\Plugins\User\View
                       \ViewPublicUserProfileDoesNotExist($this->arrParams);
            }   
        } 
        catch (\Exception $e) 
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection(); 

            throw $e;
        }            
    }
    
    /**
     * handleActionShowRegisteredUser
     */
    private function handleActionShowRegisteredUser()
    {
        $this->objDBUser = new \HybridCMS\Plugins\User\Database
                \DBUserRegistered();   
        
        $this->arrParams['objUser'] = $this->objDBUser
                ->selectUserByUserId($this->objMySqli, $this->userId);

        //set view for registered Users
        $this->objView = new \HybridCMS\Plugins\User\View
                \ViewPublicUserProfileRegistered($this->arrParams);
        
        unset($this->objDBUser);
    }
    
    /**
     * handleActionShowOpenIdUser
     */
    private function handleActionShowOpenIdUser()
    {
        $this->objDBUser = new \HybridCMS\Plugins\User\Database
                \DBUserOpenId();
        
        $this->arrParams['objUser'] = $this->objDBUser
                ->selectUserByUserId($this->objMySqli, $this->userId);

        //set view for openId Users
        $this->objView = new \HybridCMS\Plugins\User\View
                \ViewPublicUserProfileOpenId($this->arrParams); 
        
        unset($this->objDBUser);
    }
}
?>
