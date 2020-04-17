<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerLogin
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class ControllerLogin 
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
               
    }
    
    /**
     * Log user out
     */
    protected abstract function handleActionLogout();
        
    /**
     * handle Action Login
     */
    protected abstract function handleActionLogin();

        
    /**
     * redirect User to the page he came from
     */
    protected abstract function redirectUserToPreviousPage();
    
    /**
     * redirect user the the page passed by paramater. Url has to be an url from
     * this domain.
     * @param String $page
     */
    protected function redirectUserTo($page)
    {
        if(false === empty($page))
        {
            if(true === \HybridCMS\Modules\Url\Url::isValidUrl($page))
            {
                $objRef = new \HybridCMS\Modules\Url\Referrer($page);
                if(true === $objRef->refIsInternal())
                {
                    header('Location: ' . $objRef->getUrl());
                }
            }
        }
    }
}
