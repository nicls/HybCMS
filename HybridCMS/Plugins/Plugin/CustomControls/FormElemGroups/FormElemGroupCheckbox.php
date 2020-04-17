<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * Creates an form checkbox
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class FormElemGroupCheckbox
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups\FormElemGroup 
{
        
    /**
     * fontawesome icon to show infron of the input field
     * @var String
     */
    protected $iconClass;

    /**
     * Name of the Field e.g. hy_user_twitterName, ...
     * @var String
     */
    protected $fieldName;    
            
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
     * setObjContent
     * @param FormElemGroupInputContent $objFormElemGroupInputContent
     * @throws \InvalidArgumentException
     */
    public function setObjContent($objContent) 
    {
        if(false === ($objContent instanceof
                 \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                    \FormElemGroupContentRadioGroup))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of FormElemGroupContentRadioGroup.', 1);
        }
        
        parent::setObjContent($objContent);                             
    }      
    
    /**
     * getInputIcon
     * @return String
     */
    public function getIconClass() {
        return $this->iconClass;
    }

    /**
     * getFieldName
     * @return String
     */
    public function getFieldName() {
        return $this->fieldName;
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
                . '<i class' . htmlentities($this->iconClass) . '></i> '
                . htmlspecialchars($this->objContent->getLabel()) . '</label>';

        $op .= '<div class="controls add-bottom-10">';
        
        //get all RadioButtons as String
        $op .= $this->toStringRadioButtons();
        
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
            $op .= '<div class="alert alert-info fade in add-top-10 '
                    . 'add-bottom-30">';
            $op .= '<button type="button" class="close" '
                    . 'data-dismiss="alert" aria-hidden="true">×</button>';
            $op .= '<p><i class="fa fa-info-circle"></i> '
                    . htmlspecialchars($this->objContent->getHint()) .'</p>';
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
    
    /**
     * toStringRadioButtons returns all radio Buttons as String
     * @return String
     */
    private function toStringRadioButtons() 
    {
        $ret = '';       
        
        $arrObjRadioButtons = 
                $this->objContent->getArrObjFormElemGroupContentRadioButton();
        
        foreach ($arrObjRadioButtons as $objRadioButton)
        {
           $ret .=  $this->toStringRadioButton($objRadioButton);
        }
        
        return $ret;
    }
    
    /**
     * toStringRadioButton
     * @param FormElemGroupContentRadioButton $radioButton
     * @return string
     */
    private function toStringRadioButton($objRadioButton)
    {
        $checked = '';
        $selectedValue = $this->objContent->getValue();
        
        //check if user has submitted a selection
        if(false === empty($selectedValue))
        {
          if($objRadioButton->getValue() === $selectedValue)  
          {
            $checked = 'checked';
          }
        }
        //use predefined checked-state
        else if(true === $objRadioButton->getChecked())
        {
            $checked = 'checked';
        }
        
        $ret = '';
        
        $ret .= '<label class="radio-inline">';
        $ret .= '<input '
                . 'type="radio" '
                . 'name="'. htmlentities($this->fieldName) .'" '
                . 'value="'. htmlentities($objRadioButton->getValue()) .'" '
                . htmlentities($checked) 
                .'>';
        $ret .= '<i class="'
                . htmlentities($objRadioButton->getIconClass()) .'"></i> '
                . htmlspecialchars($objRadioButton->getLabel());
        $ret .= '</label>';
        
        return $ret;
    }
}
