<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewLogin
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewLogin extends \HybridCMS\Plugins\User\View\ViewUser
{
    /**
     * Refferer that indicates the page the user clicked the login-button
     * @var String
     */
    private $referrer;
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) 
    {        
        //call constructor of parent class
        parent::__construct($arrSettings);                 
    }     
            
    /**
     * toString
     * @return string
     */
    public function toString() 
    {
        $op = '';

        //open form
        $op .= '<form role="form" action="/login.html" method="POST">';
        
        //FormElemGroupEmailExistingAndRegistered
        $op .= $this->toStringFormElemGroup('objFormElemGroupEmailExistingAndRegistered'); 

        //Password Login
        $op .= $this->toStringFormElemGroup('objFormElemGroupPasswordLogin'); 
        
        if(true === $this->referrerIsSet())
        {
            $op .= '<input type="hidden" name="ref" value="'
                    . htmlentities($this->referrer) .'" />';
        }
        
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_login" type="submit" value="Einloggen" />';
        
        //close from
        $op .= '</form>';
        
        return $op;
    }
    
    /**
     * Check if a referrer is set and valid
     * @return boolean
     */
    private function referrerIsSet()
    {
        $ret = false;
        
        if(true === isset($this->arrSettings['ref']))
        {
            if(true === \HybridCMS\Modules\Url\Url
                    ::isValidUrl($this->arrSettings['ref']))
            {
                $objRef = new \HybridCMS\Modules\Url
                        \Referrer($this->arrSettings['ref']);
                
                if(true === $objRef->refIsInternal())
                {
                    $this->referrer = $this->arrSettings['ref'];
                    $ret = true;
                }
            }
        }
        
        return $ret;
    }
}

?>