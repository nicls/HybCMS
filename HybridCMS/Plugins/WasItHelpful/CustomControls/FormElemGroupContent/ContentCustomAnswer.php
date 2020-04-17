<?php

namespace HybridCMS\Plugins\WasItHelpful\CustomControls\FormElemGroupContent;

/**
 * class represents the content of a customAnswer-textarea-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentCustomAnswer
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentTextarea
{
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Möchten Sie uns sonstiges Feedback zu diesem Artikel geben?');
        $this->setPlaceholder('Dein Kommentar');    
        $this->setErrorMsg('Das Kommentarfeld darf nicht leer sein.');                     
        
        if(false === empty($value))
        {
            $this->setValue($value);
        }
    }    
        
    public function valueIsValid() 
    {
        //comment was submitted
        $ret = false === empty($this->value);
                  
        return $ret;
    }

}
