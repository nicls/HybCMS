<?php
namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentButtonLoginFacebook represents the content of a 
 * OpenId Facebook Login Button
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentButtonLoginFacebook
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentButton 
{
    /**
     * __construct
     * @param String $value
     */
    function __construct($value = 'Connect with Facebook') 
    {      
        //call constructor of parent class
        parent::__construct($value);
    }  
}
?>
