<?php

namespace HybridCMS\Plugins\User\CustomControls;

/**
 * class ControlGroupInputElement creates an Input-Field for optional userdata 
 * e.g. TwitterName, FacebookUrl and Website
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControlGroupInput{

    /**
     * Error Infomessage to the user
     * @var String 
     */
    private $errorMessages;
    
    /**
     * Infotext for the user
     * @var String
     */
    private $infoText;

    /**
     * Type of the infomessage: has-error, has-warning, has-success
     * @var String
     */
    private $feedbackState;
    
    /**
     * fontawesome icon to show infron of the input field
     * @var String
     */
    private $inputIcon;

    /**
     * Name of the Field e.g. twitterName, facebookUrl ...
     * @var String
     */
    private $fieldName;

    /**
     * Label for the input Element
     * @var String
     */
    private $label;

    /**
     * Value of the input Element
     * @var String
     */
    private $value;

    /**
     * Placeholder for the input element
     * @var String
     */
    private $placeholder;
    
    /**
     * e.g. text, password ...
     * @var String
     */
    private $inputType;
    
    /**
     * Grid Columns e.g. span6, span12, ...
     * @var String
     */
    private $width;
    
    /**
     * Margin left e.g. remove-left, add-left-20, ...
     * @var String
     */
    private $marginLeft;
    
    /**
     * Url to show to the user
     * @var String
     */
    private $urlPasswordReset;

    /**
     * Constructor
     */
    public function __construct(
            $errorMessages,
            $infoText,
            $feedbackState,       
            $inputIcon, 
            $fieldName, 
            $label, 
            $value, 
            $placeholder, 
            $inputType = 'text',  
            $width = 'col-sm-12 col-md-12',
            $marginLeft = 'remove-left',
            $urlPasswordReset = null
    ) {         
        try {
            $this->setErrorMessages($errorMessages);
            $this->setInfoText($infoText);
            $this->setFeedbackState($feedbackState);
            $this->setInputIcon($inputIcon);
            $this->setFieldName($fieldName);
            $this->setLabel($label);
            $this->setValue($value);
            $this->setPlaceholder($placeholder);
            $this->setInputType($inputType);
            $this->setWidth($width);
            $this->setMarginLeft($marginLeft);
            
            if(false === empty($urlPasswordReset)) {
                $this->setUrlPasswordReset($urlPasswordReset);
            }
            
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                    \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    public function setErrorMessages($errorMessages) {
        $this->errorMessages = $errorMessages;
    }

    public function setFeedbackState($feedbackState) {
        $this->feedbackState = $feedbackState;
    }

    public function setInputIcon($inputIcon) {
        $this->inputIcon = $inputIcon;
    }

    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
    }

    public function setInputType($inputType) {
        $this->inputType = $inputType;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function setMarginLeft($marginLeft) {
        $this->marginLeft = $marginLeft;
    }

    public function setInfoText($infoText) {
        $this->infoText = $infoText;
    }
    
    /**
     * setUrlPasswordReset
     * @param String $urlPasswordReset
     * @throws \Exception
     */
    public function setUrlPasswordReset($urlPasswordReset) {
        
        if (false === \HybridCMS\Modules\Validation\URLValidation
                ::isValidUrl($urlPasswordReset)) {
            throw new \Exception(
            'Error Processing Request: 
                    setUrlPasswordReset(),                    
                    $urlPasswordReset is not valid: ' 
                    . htmlspecialchars($urlPasswordReset), 1);
        }
        
        $this->urlPasswordReset = $urlPasswordReset;
    }

            
    public function toString() {

        $op = '';
        $op .= '<div class="'. htmlentities($this->marginLeft) . ' ' 
                . htmlentities($this->width) .'">';
        $op .= '<div class="control-group has-feedback ' 
                . htmlentities($this->feedbackState) . '">';
        $op .= '<label class="control-label" for="hyb_user_' 
                . htmlentities($this->fieldName) . '">'
                . '<i class="fa ' . htmlentities($this->inputIcon) . '"></i> '
                . htmlspecialchars($this->label) . ':</label>';

        $op .= '<div class="controls add-bottom-10">';

        $op .= '<input class="form-control" name="hyb_user_' 
                . htmlentities($this->fieldName) . '" '
                . 'placeholder="' . htmlentities($this->placeholder) . '" '
                . 'value="' . htmlentities($this->value) . '" '
                . 'type="'. htmlentities($this->inputType) .'" />';
        
        //show error message to the user
        if('has-error' === $this->feedbackState) 
        {
            $op .= '<span class="glyphicon glyphicon-remove '
                    . 'form-control-feedback"></span>';
            $op .= '<span class="help-block small add-bottom-20">'
                    . htmlspecialchars($this->errorMessages)
                    . '</span>';
            
            if(false === empty($this->urlPasswordReset))
            {
                $op .= '<span id="forgotPassword">';
                $op .= '<a href="'. $this->urlPasswordReset 
                        .'"><i class="fa fa-lightbulb-o">'
                        . '</i> Passwort vergessen?</a>';
                $op .= '</span>';
            }
        } 
        //mark as successful
        else if('has-success' === $this->feedbackState)
        {
            $op .= '<span class="glyphicon glyphicon-ok '
                    . 'form-control-feedback"></span>';
        }
        
        
        if(false === empty($this->infoText))
        {
            $op .= '<div class="alert alert-info fade in '
                    . 'add-top-10 add-bottom-30">';
            $op .= '<button type="button" class="close" '
                    . 'data-dismiss="alert" aria-hidden="true">×</button>';
            $op .= '<p><i class="fa fa-info-circle"></i> '
                    . htmlspecialchars($this->infoText) .'</p>';
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

?>