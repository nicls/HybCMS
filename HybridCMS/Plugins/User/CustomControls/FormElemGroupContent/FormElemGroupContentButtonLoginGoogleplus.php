<?php
namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentButtonLoginGoogleplus represents the content of a 
 * OpenId Facebook Login Button
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentButtonLoginGoogleplus
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentButton 
{
    /**
     * __construct
     * @param String $value
     */
    function __construct($value = 'Connect with Google+') 
    {      
        //call constructor of parent class
        parent::__construct($value);
    }  
}
?>
