<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPasswordReset that shows the following Form-Elements to the user 
 * to reset his password: 
 * 
 * input:  password
 * input:  password repeat
 * button: save password
 * 
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPasswordReset extends \HybridCMS\Plugins\User\View\ViewUser
{
    /**
     * md5 verification token sent by the client
     * @var String
     */
    private $verificationToken;
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) 
    {
        //call constructor of parent class
        parent::__construct($arrSettings);        
        
        if (isset($arrSettings['key'])) 
        {
            $this->verificationToken = $arrSettings['key'];
        }        
    }    
                
    /**
     * toString
     * @return string
     */
    public function toString() 
    {
        $op = '';

        //open form
        $op .= '<form role="form" action="/set-new-password.html" method="POST">';
                       
        //Add password Inputfield                   
        $op .= $this->toStringFormElemGroup('objFormElemGroupPassword');
        
        //Add passwordRepeat Inputfield               
        $op .= $this->toStringFormElemGroup('objFormElemGroupPasswordRepeat');                   
           
        $op .= '<div>';
        $op .= '<input type="hidden" name="key" value="'
                . htmlentities($this->verificationToken) .'" />';
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_savePassword" type="submit" '
                . 'value="Passwort speichern" />';
        $op .= '<div />';

        //close from
        $op .= '</form>';
        
        return $op;
    } 
}

?>