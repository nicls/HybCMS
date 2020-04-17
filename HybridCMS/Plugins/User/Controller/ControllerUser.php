<?php
namespace HybridCMS\Plugins\User\Controller;

/**
 * class ControllerDashboardProfile
 *
 * @package Plugins/User/Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class ControllerUser 
    extends \HybridCMS\Plugins\Plugin\Controller 
{
    
    /**
     * FormState Oberver that observes the error, warning, and success
     * State of the form-data submitted by the client.
     * @var FormStateObserver
     */
    protected $objFormStateObserver;    
    
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
                new \HybridCMS\Plugins\User\CustomControls\FormStateObserver();        

        //initially set flag submittedDataIsValid to true. Id a validation-
        //function fails, it sets the flag to false
        $this->submittedDataIsValid = true;                                           
    }    

    /**
     * validate User Form-Data send by the user
     */    
    protected function validateFormData() {
       
        foreach ($this->arrParams as &$param) 
        {
            if(true === $param instanceof
                    \HybridCMS\Plugins\User\CustomControls
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
                    \HybridCMS\Plugins\User\CustomControls
                        \FormElemGroupContent\FormElemGroupContent)
            {                                
                $objFormElemGroupContent = &$param;
                
                $objFormElemGroup = \HybridCMS\Plugins\User\CustomControls
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
     * Return the corresponding view to the user
     * @returns String
     */    
    public function toString($args = array()) 
    {
        return $this->objView->toString();
    }  
}
