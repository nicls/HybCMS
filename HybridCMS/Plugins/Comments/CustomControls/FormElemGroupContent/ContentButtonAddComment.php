<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent;

/**
 * class ContentButtonAddComment represents the content of a 
 * Button of type ButtonAddComment
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentButtonAddComment
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentButton 
{
    /**
     * __construct
     * @param String $value
     */
    function __construct($value = 'Kommentar speichern') 
    {      
        //call constructor of parent class
        parent::__construct($value);
    }  
}
?>
