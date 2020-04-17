<?php
namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentButtonLoginTwitter represents the content of a 
 * OpenId Twitter Login Button
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentButtonLoginTwitter
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentButton 
{
    /**
     * __construct
     * @param String $value
     */
    function __construct($value = 'Connect with Twitter') 
    {      
        //call constructor of parent class
        parent::__construct($value);
    }  
}
?>
