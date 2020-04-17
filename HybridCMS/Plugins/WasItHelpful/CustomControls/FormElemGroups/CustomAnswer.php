<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroups;

/**
 * class is the concrete implemenatation for an form 
 * textarea-Field for a new comment
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Comment
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups
        \FormElemGroupTextarea
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     */
    function __construct($objContent, $objFormStateObserver) {
        
        //call constructor of parent class
        parent::__construct();
        
        $this->setIsOptional(false);
        $this->setIconClass('fa fa-envelope');
        $this->setFieldName('hyb_comments_comment'); 
        $this->setRows(6);
        
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
        if(false === ($objContent instanceof
                \HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent
                    \ContentComment))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of FormElemGroupContentAboutme.', 1);
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