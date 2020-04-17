<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerLoginButton
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerLoginButton 
    extends \HybridCMS\Plugins\User\Controller\ControllerUser 
{        
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
        //show login button
        if(true === isset($this->arrParams['hyb_user_showLoginButton']))
        {
            $this->handleActionShowLoginButton();
        }        
    }
    
    /**
     * Show Login-Button if user is loged out and show logout-button if user
     * is loged in
     */
    private function handleActionShowLoginButton() 
    {
        if(true === isset($_SESSION['logedIn'])) 
        {
            //set view logoutButton
            $this->objView = 
                    new \HybridCMS\Plugins\User\View\ViewLogoutButton(
                            $this->arrParams);               
        }
        else 
        {
            //set view loginButton
            $this->objView = 
                    new \HybridCMS\Plugins\User\View\ViewLoginButton(
                            $this->arrParams);  
        }
    }
                    
    /**
     * Return the corresponding view to the user
     * @returns String
     */    
    public function toString($args = array()) 
    {
        return $this->objView->toString();
    }    
}
