<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups;

/**
 * class FormElemGroupInput creates an form Input-Field for
 * e.g. TwitterName, FacebookUrl and Website
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class FormElemGroupInput 
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups
        \FormElemGroup 
{
        
    /**
     * fontawesome icon to show infron of the input field
     * @var String
     */
    protected $inputIcon;

    /**
     * Name of the Field e.g. hy_user_twitterName, ...
     * @var String
     */
    protected $fieldName;
    
    /**
     * e.g. text, password ...
     * @var String
     */
    protected $inputType;
    
    /**
     * __construct
     * @param FormElemGroupInputContent $objContent
     * @param FormStateObserver
     */
    public function __construct() 
    {
        //call constructor of parent class
        parent::__construct();     
    }

    /**$objFormElemGroupTwitter
     * setInputIcon
     * @param String $inputIcon
     * @throws \InvalidArgumentException
     */
    public function setInputIcon($inputIcon) 
    {
        
        //Icon must match the http://fortawesome.github.io/ icon format
        $pattern = '/^<i\s?(class\=\"[a-z][0-9\w\s\-]+\")?><\/i>$/';
        
        if(false === is_string($inputIcon)
           ||
           false === preg_match($pattern, $inputIcon)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setInputIcon(),                    
                    $inputIcon is not valid.', 1); 
        }
        
        $this->inputIcon = $inputIcon;
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
     * setInputType 
     * @param String $inputType
     * @throws \InvalidArgumentException
     */
    public function setInputType($inputType) 
    {
        
        $arrTypesAllowed = array('password', 'text', 'email', 'hidden', 'url');
        
        if(false === in_array($inputType, $arrTypesAllowed)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setInputType(),                    
                    $inputType is not valid.', 1);  
        }
        
        $this->inputType = $inputType;
    }
    
    /**
     * setObjContent
     * @param FormElemGroupInputContent $objFormElemGroupInputContent
     * @throws \InvalidArgumentException
     */
    public function setObjContent($objContent) 
    {
        if(false === ($objContent instanceof
                \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent\FormElemGroupContentInput))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of 
                    FormElemGroupContentInput.', 1);
        }
        
        parent::setObjContent($objContent);                             
    }
    
    /**
     * getInputIcon
     * @return String
     */
    public function getInputIcon() {
        return $this->inputIcon;
    }

    /**
     * getFieldName
     * @return String
     */
    public function getFieldName() {
        return $this->fieldName;
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
        
        $op = '';
        $op .= '<div>';
        $op .= '<div class="control-group has-feedback ' 
                . htmlentities($this->feedbackState) . '">';
        $op .= '<label class="control-label" for="' 
                . htmlentities($this->fieldName) . '">'
                . $this->inputIcon . ' '
                . htmlspecialchars($this->objContent->getLabel()) . '</label>';

        $op .= '<div class="controls add-bottom-10">';

        $op .= '<input class="form-control" name="' 
                . htmlentities($this->fieldName) . '" '
                . 'placeholder="' 
                . htmlentities($this->objContent->getPlaceholder()) . '" '
                . 'value="' . htmlentities($this->objContent->getValue()) . '" '
                . 'type="'. htmlentities($this->inputType) .'" />';
        
        //show error message to the user
        if('has-error' === $this->feedbackState) 
        {
            $op .= '<span class="glyphicon glyphicon-remove '
                    . 'form-control-feedback"></span>';
            $op .= '<span class="help-block small add-bottom-20">'
                    . htmlspecialchars($this->objContent->getErrorMsg())
                    . '</span>';            
        } 
        
        //show warning message to the user
        if('has-warning' === $this->feedbackState) 
        {
            $op .= '<span class="glyphicon glyphicon-warning-sign '
                    . 'form-control-feedback"></span>';
            $op .= '<span class="help-block small add-bottom-20">'
                    . htmlspecialchars($this->objContent->getWarningMsg())
                    . '</span>';            
        } 
        
        //mark as successful
        else if('has-success' === $this->feedbackState)
        {
            $op .= '<span class="glyphicon glyphicon-ok form-control-feedback"></span>';
        }
        
        $hint = $this->objContent->getHint();
        if(false === empty($hint))
        {
            $op .= '<div class="alert alert-info fade in add-top-10 add-bottom-30">';
            $op .= '<button type="button" class="close" '
                    . 'data-dismiss="alert" aria-hidden="true">×</button>';
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
