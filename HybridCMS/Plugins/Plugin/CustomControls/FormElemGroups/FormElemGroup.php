<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups;

/**
 * class FormElemGroup creates an form-element
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroup {

    /**
     * Observer that observes the state of the form of this FormElemGroupInput
     * @var FormStateObserver
     */
    protected $objFormStateObserver;
    
    /**
     * Represents the content of this ElemGroupInput
     * @var FormElemGroupInputContent
     */
    protected $objContent;
    
    /**
     * Indicates if the groupElements data has an error
     * @var Boolean
     */
    protected $groupHasError;   
    
    /**
     * Indicates if the groupElements data has a warning
     * @var Boolean
     */
    protected $groupHasWarning;    
    
    /**
     * Indicates if the groupElements data is valid
     * @var Boolean
     */
    protected $groupHasSuccess; 
    
    /**
     * State of the feedback-settings. Error is more important than warning 
     * is more important than success
     * @var String
     */
    protected $feedbackState;
    
    /**
     * Indicates weather the FormGroup is optinal or not
     * @var Boolean
     */
    protected $isOptional;   
    
    /**
     * __construct
     * @param FormElemGroupInputContent $objContent
     * @param FormStateObserver
     */
    public function __construct() 
    {
        $this->groupHasError = false;
        $this->groupHasWarning = false;
        $this->groupHasSuccess = false;                               
    }
    
    /**
     * RegistSetAnder a FormStateObserver that observes the state of a complete form
     * @param FormStateObserver $objFormStateObserver
     */
    protected function registerObserver($objFormStateObserver)
    {
        if(false === ($objFormStateObserver instanceof
                 \HybridCMS\Plugins\Plugin\CustomControls\FormStateObserver))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjFormStateObservfeedbackStateer(),                    
                    $objFormStateObserver is not an 
                    instance of FormStateObserver.', 1);
        }
        
        $this->objFormStateObserver = $objFormStateObserver;
    }
    
    /**
     * Nofifies the Observer about the group state so that the Observer can 
     * set the state of the whole form
     */
    protected function notifyObserver() 
    {
        $this->objFormStateObserver->groupHasError($this->groupHasError);
        $this->objFormStateObserver->groupHasWarning($this->groupHasWarning);
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
                    \FormElemGroupContent))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance 
                    of FormElemGroupContent.', 1);
        }
        
        $this->objContent = $objContent;                             
    }
    
    /**
     * validateContent
     */
    public function validateContent()
    {
        //check if value is set
        $value = $this->objContent->getValue();
        $valueIsSet = (false === empty($value));
                    
        //check if value has an error
        if(true === $valueIsSet || false === $this->isOptional)            
        {                        
            if(false === $this->objContent->valueIsValid())
            {
                $this->setGroupHasError(true); 
            }
            else
            {
                $this->setGroupHasSuccess(true); 
            }
        }        
    }
    
    /**
     * setGroupHasError
     * @param Boolean $groupHasError
     * @throws \InvalidArgumentException
     */
    public function setGroupHasError($groupHasError) 
    {
        if(false === is_bool($groupHasError)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setGroupHasError(),                    
                    $groupHasError is not valid.', 1);  
        }
        
        $this->groupHasError = $groupHasError;
        
        //update feedbackstate
        $this->updateFeedbackState();  
        
        //notify Observer
        $this->notifyObserver();
    }

    /**
     * setGroupHasWarning
     * @param Boolean $groupHasWarning
     * @throws \InvalidArgumentException
     */
    public function setGroupHasWarning($groupHasWarning) 
    {
        if(false === is_bool($groupHasWarning)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setGroupHasWarning(),                    
                    $groupHasWarning is not valid.', 1);  
        }
        
        $this->groupHasWarning = $groupHasWarning;
        
        //update feedbackstate
        $this->updateFeedbackState(); 
        
        //notify Observer
        $this->notifyObserver();        
    }

    /**
     * $groupHasSuccess
     * @param Boolean $groupHasSuccess
     * @throws \InvalidArgumentException
     */
    public function setGroupHasSuccess($groupHasSuccess) 
    {
        if(false === is_bool($groupHasSuccess)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setGroupHasSuccess(),                    
                    $groupHasSuccess is not valid.', 1);  
        }     
        
        $this->groupHasSuccess = $groupHasSuccess;
        
        //update feedbackstate
        $this->updateFeedbackState();
    }
    
    /**
     * updateFeedbackState sets the state of the feedback to client. Error is
     * more important than warning is more important than success.
     */
    protected function updateFeedbackState() 
    {
        if(true === $this->groupHasError)
        {
            $this->feedbackState = 'has-error';
        }
        else if(true === $this->groupHasWarning)
        {
            $this->feedbackState = 'has-warning';
        }
        else if(true === $this->groupHasSuccess)
        {
            $this->feedbackState = 'has-success';
        } 
    }

    /**
     * setIsOptional
     * @param Boolean $isOptional
     * @throws \InvalidArgumentException
     */
    public function setIsOptional($isOptional) 
    {
        if(false === is_bool($isOptional)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setIsOptional(),                    
                    $isOptional is not valid.', 1);  
        }     
        
        $this->isOptional = $isOptional;
    }  
    
    /**
     * getObjFormStateObserver
     * @return FormStateObserver
     */
    public function getObjFormStateObserver() {
        return $this->objFormStateObserver;
    }
    
    /**
     * getObjFormElemGroupInputContent
     * @return FormElemGroupInputContent
     */
    public function getObjFormElemGroupInputContent() {
        return $this->objFormElemGroupInputContent;
    }       
    
    /**
     * getGroupHasError
     * @return Boolean
     */
    public function getGroupHasError() {
        return $this->groupHasError;
    }

    /**
     * getGroupHasWarning
     * @return Boolean
     */
    public function getGroupHasWarning() {
        return $this->groupHasWarning;
    }

    /**
     * getGroupHasSuccess
     * @return Boolean
     */
    public function getGroupHasSuccess() {
        return $this->groupHasSuccess;
    }
}
