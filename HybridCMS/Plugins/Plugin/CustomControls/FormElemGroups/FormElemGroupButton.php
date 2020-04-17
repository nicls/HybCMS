<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups;

/**
 * class FormElemGroupButton creates an form Button
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupButton extends \HybridCMS\Plugins\Plugin\CustomControls\
        FormElemGroups\FormElemGroup 
{        
    /**
     * fontawesome icon to show infron of the button
     * @var String
     */
    protected $iconClass;
    
    /**
     * CSS-ClassNames of the button
     * @var String
     */
    protected $arrClassNames;    

    /**
     * Name of the Field e.g. hy_user_twitterName, ...
     * @var String
     */
    protected $fieldName;
    
    /**
     * Value corresponing to the name field
     * @var String
     */
    protected $fieldValue;
    
    /**
     * submit | reset | button
     * @var String
     */
    protected $buttonType;    
    
    /**
     * __construct
     * @param FormElemGroupInputContent $objContent
     * @param FormStateObserver
     */
    public function __construct($objContent, $objFormStateObserver) 
    {
        //call constructor of parent class
        parent::__construct();  
                
        //initialize arrClassNames
        $this->arrClassNames = array();
                        
        //attach obersver
        $this->registerObserver($objFormStateObserver);
        
        //attach Content
        $this->setObjContent($objContent);                                  
    }

    /**
     * setIconClass
     * @param String $iconClass
     * @throws \InvalidArgumentException
     */
    public function setIconClass($iconClass) 
    {
        
        //Icon must match the http://fortawesome.github.io/ icon format
        $pattern = '/^[a-zA-Z][0-9\w\s\-_]*$/';
        
        if(false === is_string($iconClass)
           ||
           false === preg_match($pattern, $iconClass)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setIconClass(),                    
                    $iconClass is not valid.', 1); 
        }
        
        $this->iconClass = $iconClass;
    }

    
    /**
     * setFieldName
     * @param String $fieldName
     * @throws \InvalidArgumentException
     */
    public function setFieldName($fieldName) {
                
        $pattern = '/^[0-9a-zA-Z\-_]+$/';
        
        if(false === is_string($fieldName)
           ||
           false === preg_match($pattern, $fieldName)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setFieldName(),                    
                    $fieldName is not valid.', 1); 
        }
        
        $this->fieldName = $fieldName;
    }
    
    /**
     * setFieldValue
     * @param String $fieldValue
     * @throws \InvalidArgumentException
     */
    public function setFieldValue($fieldValue) {
                
        $pattern = '/^[0-9a-zA-Z\-_]+$/';
        
        if(false === is_string($fieldValue)
           ||
           false === preg_match($pattern, $fieldValue)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setFieldValue(),                    
                    $fieldValue is not valid.', 1); 
        }
        
        $this->fieldValue = $fieldValue;
    }    

    /**
     * setButtonType 
     * @param String $buttonType
     * @throws \InvalidArgumentException
     */
    public function setButtonType($buttonType) 
    {        
        $arrTypesAllowed = array('submit', 'reset', 'button');
        
        if(false === in_array($buttonType, $arrTypesAllowed)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setButtonType(),                    
                    $buttonType is not valid.', 1);  
        }
        
        $this->buttonType = $buttonType;
    }
    
    /**
     * setObjContent
     * @param FormElemGroupInputContent $objFormElemGroupInputContent
     * @throws \InvalidArgumentException
     */
    public function setObjContent($objContent) 
    {
        if(false === ($objContent instanceof
                \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
                    \FormElemGroupContentButton))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of FormElemGroupContentButton.', 1);
        }
        
        parent::setObjContent($objContent);                             
    }
    
    /**
     * addClassName
     * @param String $className
     * @throws \InvalidArgumentException
     */
    public function addClassName($className) 
    {
        $pattern = '/^[a-zA-Z][0-9\w\s\-_]*$/';
        
        if(false === is_string($className)
           ||
           false === preg_match($pattern, $className)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    addClassNames(),                    
                    $className is not valid.', 1); 
        }
        
        $this->arrClassNames[] = $className;
    }  
    
    public function getArrClassNames() {
        return $this->arrClassNames;
    }      
    
    /**
     * getFieldName
     * @return String
     */
    public function getFieldName() {
        return $this->fieldName;
    }
    
    /**
     * getFieldValue
     * @return String
     */
    public function getFieldValue() {
        return $this->fieldValue;
    }
    
    /**
     * getInputType
     * @return String
     */
    public function getInputType() {
        return $this->inputType;
    }
       
    /**
     * Returns the GrupElement as String
     * @return String     
     */
    public function toString() 
    {
        
        $iconPostFx = '';
        if(false === empty($this->iconClass))
        {
            $iconPostFx = ' | ';
        }
        
        $op = '';
        $op .= '<div>';
        $op .= '<div class="control-group has-feedback ' 
                . htmlentities($this->feedbackState) . '">';

        $op .= '<div class="controls add-bottom-10">';

        $op .= '<button class="'
                . htmlentities(implode(' ', $this->arrClassNames)) .'" '
                . 'name="' . htmlentities($this->fieldName) . '" '
                . 'value="' . htmlentities($this->fieldValue) . '" '
                . 'type="'. htmlentities($this->buttonType) .'">'
                . '<i class="' . htmlentities($this->iconClass) . '"></i> '
                . htmlspecialchars($iconPostFx)
                . htmlspecialchars($this->objContent->getValue())
                . '</button>';
        
        //show error message to the user
        if('has-error' === $this->feedbackState) 
        {
            $op .= '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';
            $op .= '<span class="help-block small add-bottom-20">'
                    . htmlspecialchars($this->objContent->getErrorMsg())
                    . '</span>';            
        } 
        
        //show warning message to the user
        if('has-warning' === $this->feedbackState) 
        {
            $op .= '<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>';
            $op .= '<span class="help-block small add-bottom-20">'
                    . htmlspecialchars($this->objContent->getWarningMsg())
                    . '</span>';            
        } 
        
        //mark as successful
        else if('has-success' === $this->feedbackState)
        {
        }
        
        
        $hint = $this->objContent->getHint();
        if(false === empty($hint))
        {
            $op .= '<div class="alert alert-info fade in add-top-10 add-bottom-30">';
            $op .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
            $op .= '<p><i class="fa fa-info-circle"></i> '
                    . htmlspecialchars($hint) .'</p>';
            $op .= '</div>';
        }
        
        //close div.controls
        $op .= '</div>';

        //close div.control-group
        $op .= '</div>';
        
        //close first div
        $op .= '</div>';

        return $op;
    }
}
