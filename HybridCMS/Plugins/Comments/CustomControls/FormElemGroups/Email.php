<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroups;

/**
 * class FormElemGroupUsername is the concrete implemenatation for an form 
 * Input-Field for a TwitterName
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Email
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups
        \FormElemGroupInput
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     * @param FormStateObserver $objFormStateObserver
     */
    function __construct($objContent, $objFormStateObserver) {
        
        //call constructor of parent class
        parent::__construct();
        
        $this->setIsOptional(false);
        $this->setInputIcon('<i class="fa fa-envelope"></i>');
        $this->setFieldName('hyb_comments_email');
        $this->setInputType('text');          
        
        //attach obersver
        $this->registerObserver($objFormStateObserver);
        
        //attach Content
        $this->setObjContent($objContent);         
    }
    
    /**
     * setObjContent
     * @param FormElemGroupInputContent $objFormElemGroupInputContent
     * @throws \InvalidArgumentException
     */
    public function setObjContent($objContent) 
    {
        if(false === ($objContent 
                instanceof \HybridCMS\Plugins\Comments\CustomControls
                \FormElemGroupContent\ContentEmail))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of ContentEmail.', 1);
        }
        
        parent::setObjContent($objContent);                             
    }    

    /**
     * Returns the TwitterInputGroup as String
     * @return String
     */
    public function toString() {
        
        return parent::toString();
    }

}

?>