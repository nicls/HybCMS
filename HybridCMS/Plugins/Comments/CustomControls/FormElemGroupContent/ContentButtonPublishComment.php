<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent;

/**
 * Represents the content of a Button of type ButtonPublishComment
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentButtonPublishComment
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentButton 
{
    /**
     * __construct
     * @param String $value
     */
    function __construct($value = 'Publish') 
    {      
        //call constructor of parent class
        parent::__construct($value);
    }  
}
?>
