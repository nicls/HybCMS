<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroups;

/**
 * Creates a button to delete a Comment
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ButtonDeleteComment
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
        $this->setIconClass('fa fa-trash-o');
        $this->setFieldName('hyb_comment_delete');      
                
        //set classnames
        $this->addClassName('btn');
        $this->addClassName('btn-xs');
        $this->addClassName('float_right');
        $this->addClassName('add-right-20');
        $this->addClassName('btn-danger');  
    }       
}
