<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * class FormElemGroupButtonLoginGoogleplus creates an form button for
 * Twitter Login per OpenId
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupButtonLoginGoogleplus
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroups
        \FormElemGroupButton 
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
        
        $this->setIsOptional(true);       
        
        $this->setFieldName('hyb_user_login_googleplus');
        $this->addClassName('btn-google-plus'); 
        $this->setIconClass('fa fa-google-plus');
    }       
}
