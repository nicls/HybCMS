<?php

namespace HybridCMS\Plugins\Comments\Controller;

/**
 * class ControllerComments handles all requests belonging to comments.
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class ControllerComments extends \HybridCMS\Plugins\Plugin\Controller 
{
    /**
     * FormState Oberver that observes the error, warning, and success
     * State of the form-data submitted by the client.
     * @var FormStateObserver:HybridCMS\Plugins\Plugin\
     *  CustomControls\FormStateObserver
     */
    protected $objFormStateObserver;
        
    /**
     * Url of the comment form
     * @var Url
     */
    protected $objUrl;
    
    /**
     * __construct
     * @param mixed[] $arrParams
     */    
    public function __construct($arrParams) 
    {
        //call constructor of parent class
        parent::__construct($arrParams); 
        
        //create FormObserver that observes the state of 
        //the form submitted by the user
        $this->objFormStateObserver = 
                new \HybridCMS\Plugins\Plugin\CustomControls
                        \FormStateObserver();        

        //initially set flag submittedDataIsValid to true. If a validation-
        //function fails, it sets the flag to false
        $this->submittedDataIsValid = true;                    
               
    }    
    
    /**
     * validate User Form-Data send by the user. 
     */    
    protected function validateFormData() {
       
        foreach ($this->arrParams as &$param) 
        {
            if(true === $param instanceof
                    \HybridCMS\Plugins\Plugin\CustomControls
                    \FormElemGroups\FormElemGroup)
            {
                $param->validateContent();
            }
        }
    }
    
    /**
     * create FormElementGroup for each submitted InputContent-Object
     * submitted by th client and attach the content object and the observer to 
     * the created FormElementGroups
     */
    protected function createFormElementGroups()
    {        
        foreach ($this->arrParams as &$param) 
        {
            if(true === $param instanceof
                    \HybridCMS\Plugins\Plugin\CustomControls
                        \FormElemGroupContent\FormElemGroupContent)
            {                                
                $objFormElemGroupContent = &$param;
                
                $objFormElemGroup = \HybridCMS\Plugins\Comments\CustomControls
                        \FormElemGroups\FormElemGroupFactory::create(                        
                            $objFormElemGroupContent, 
                            $this->objFormStateObserver);                            
                
                //add FormElemGroup to the parameters that are 
                //going to submitted to the view
                $classNamePath = get_class($objFormElemGroup);
                $arrClassNamePathChunks = explode('\\', $classNamePath);
                $index = 'obj' . array_pop($arrClassNamePathChunks);
                $this->arrParams[$index] = $objFormElemGroup;
            }
        }
    } 
    
    /**
     * setUrl
     * @param String $url
     */
    protected function setObjUrl($url)
    {
        //check if a valid url is given
        if(
            true === empty($url)
            ||  
            false === is_string($url)
            ||
            false === \HybridCMS\Modules\Url\Url::isValidUrl($url)
          )
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), no valid url given.', 1);
        }
        
        $objUrl = new \HybridCMS\Modules\Url\Url($url);
        assert(false === empty($objUrl));

        //check if url is internal
        if(false === $objUrl->urlIsInternal())
        {               
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), url is not internal.', 1);  
        }
        
        $this->objUrl = $objUrl;
    }  
    
    /**
     * Return the corresponding view to the user
     * @returns String
     */    
    public function toString($args = array()) 
    {              
        return $this->objView->toString();
    }     
}
