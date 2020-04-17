<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerLogin
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerLoginRegistered 
    extends \HybridCMS\Plugins\User\Controller\ControllerLogin 
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
        //handle action Login and handle submitted form data
        if (true === isset($this->arrParams['hyb_user_login'])) 
        {
            //attach each InputContent submitted by the client 
            //to an FormElementGroup
            //to print the form later in the view
            $this->createFormElementGroups();
        
            $this->handleActionLogin();
        }
        
        //handle action Login
        else if (true === isset($this->arrParams['hyb_user_logout'])) 
        {
            $this->handleActionLogout();
        }     

        //handle default action and show LoginForm
        else 
        {
            //attach each InputContent submitted by the client 
            //to an FormElementGroup to print the form later in the view
            $this->createFormElementGroups();
        
            $this->handleActionShowLoginForm();
        }
    }    
    
    /**
     * handle default Action 'ShowLoginForm'
     */
    private function handleActionShowLoginForm() 
    {        
        //set view
        $this->objView = new \HybridCMS\Plugins\User\View
                \ViewLogin($this->arrParams);

        //Add UIRegister JSResources
        $objJSResource1 = new \HybridCMS\Page\Resources
                \JSResource(
                    'userUIRegister', 
                    '/HybridCMS/Plugins/User/js/UserUILogin.js', 
                    3, 
                    false, 
                    true, 
                    'footer', 
                    true);
        
        $this->addObjJSResource($objJSResource1);

        //Add UIRegister CSSResource
        $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                'userUIRegister', 
                '/HybridCMS/Plugins/User/css/userUILogin.css');
        $this->addObjCSSResource($objCSSResource1);
        
    }
    
    /**
     * Log user out
     */
    protected function handleActionLogout()
    {                
        // delete all session variables
        $_SESSION = array();

        //delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                    session_name()
                    , ''
                    , time() - 42000
                    , $params["path"]
                    , $params["domain"]
                    , $params["secure"]
                    , $params["httponly"]
            );
        }

        //finally delete session
        session_unset();
        session_destroy();
        
        //set view
        $this->objView = 
                new \HybridCMS\Plugins\User\View\ViewLogedOut(
                        $this->arrParams);  
        
        //redirect User to the homepage
        $this->redirectUserTo(HYB_HOMEPAGE);        
    }
        
    /**
     * handle Action Login
     */
    protected function handleActionLogin() 
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
                         
        if(true === $this->submittedDataIsValid) 
        {
            //set view LogedIn
            $this->objView = 
                    new \HybridCMS\Plugins\User\View\ViewLogedIn(
                            $this->arrParams);    
            
            //get userId, email, firstname, lastname and username
            $email = $this->arrParams['objFormElemGroupContentEmailExistingAndRegistered']
                    ->getValue();
            
            assert(false === empty($email));
            
            try 
            {
                //open Database-Connection
                $db = \HybridCMS\Database\DatabaseFactory
                        ::getFactory()->getConnection();

                //select username from DB
                $objDBUser = new \HybridCMS\Plugins\User\Database\DBUserRegistered();                
                $objUser = $objDBUser->selectUserByEmail($db, $email);

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

            //set Session Variables
            $_SESSION['logedIn'] = true;    
            $_SESSION['userId'] = $objUser->getUserId();
            $_SESSION['email'] = $objUser->getEmail();
            $_SESSION['firstname'] = $objUser->getFirstname();
            $_SESSION['lastname'] = $objUser->getLastname();
            $_SESSION['username'] = $objUser->getUsername();                      
            $_SESSION['type'] = $objUser->getType();
            
            unset($objUser);  
            
            //redirect User to the page he came from if the 
            //referrer is set
            $this->redirectUserToPreviousPage();
        }
        else 
        {                                        
            //password is not valid so set view Login
            $this->handleActionShowLoginForm(); 
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
    
    /**
     * redirect User to the page he came from
     */
    protected function redirectUserToPreviousPage()
    {
        //redirect user to the page he came from if the referrer is set
        //and is not the login-page, otherwise redirect him to the startpage
        if(false === isset($this->arrParams['ref'])
           ||
           HYB_CURRURL === $this->arrParams['ref'])
        {
            $this->arrParams['ref'] = HYB_HOMEPAGE;
        }
        
        $this->redirectUserTo($this->arrParams['ref']);
    }
}
