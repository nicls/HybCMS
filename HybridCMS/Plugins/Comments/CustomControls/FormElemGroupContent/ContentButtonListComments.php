<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent;

/**
 * class ContentButtonListComments represents the content of a 
 * Button of type ButtonListComments
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentButtonListComments
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentButton 
{
    /**
     * __construct
     * @param String $value
     */
    function __construct($value = 'Kommentare anzeigen.') 
    {      
        //call constructor of parent class
        parent::__construct($value);
    }  
}
?>
