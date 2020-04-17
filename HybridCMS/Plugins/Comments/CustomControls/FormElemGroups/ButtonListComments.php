<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroups;

/**
 * class ButtonListComments creates a button to load all comments on click
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ButtonListComments
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
        $this->setButtonType('button'); 
                
        //set classnames
        $this->addClassName('btn');
        //$this->addClassName('btn-block');
        $this->addClassName('btn-danger');    
        $this->addClassName('hyb_comments_buttonShowAll');
        
        $this->setFieldName('hyb_comments_loadComments');
        $this->setIconClass('fa fa-comments');
    }       
}
