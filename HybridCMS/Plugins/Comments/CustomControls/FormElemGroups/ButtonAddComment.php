<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroups;

/**
 * class ButtonAddComment creates a button to add a new Comment
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ButtonAddComment
    extends \HybridCMS\Plugins\Plugin\CustomControls
        \FormElemGroups\FormElemGroupButton 
{      
    /**
     * __construct
     * @param FormElemGroupInputContent $objContent
     * @param FormStateObserver
     */
    public function __construct($objContent, $objFormStateObserver) 
    {
        //call constructor of parent class
        parent::__construct($objContent, $objFormStateObserver);  
        
        $this->setIsOptional(false);    
        $this->setButtonType('submit'); 
                
        //set classnames
        $this->addClassName('btn');
        //$this->addClassName('btn-block');
        $this->addClassName('btn-success');  
        $this->addClassName('float_right');
    }       
}
