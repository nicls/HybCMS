<?php

namespace HybridCMS\Plugins\Plugin;

/**
 * class Controller
 *
 * @package Plugin
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class Controller extends \HybridCMS\Plugins\Plugin\Plugin 
{

    /**
     * Represents the view
     * @var \HybridCMS\Plugins\Plugin\View
     */
    protected $objView;
    
    /**
     * Action of the current request
     * @var String
     */
    protected $action;

    /**
     * Request Parameter sent from the client
     * @var mixed[] $arrPArams
     */
    protected $arrParams;
    
    /**
     * True if all data submitted by the user is valid, else false.
     * @var Boolean
     */
    protected $submittedDataIsValid;  
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrParams) 
    {
        //call constructor of parent class Plugin
        parent::__construct();

        //set parameter sent by the client
        $this->setArrParams($arrParams);         
    }
    
    /**
     * Handles the Request and performs the nessecary action
     */
    protected abstract function handleRequest();
    
    /**
     * validate Form-Data send by the client
     */
    protected abstract function validateFormData();
    
    /**
     * Set parameter sent by the client
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    protected function setArrParams($arrParams) {
        
        if (!is_array($arrParams)) {
            throw new \Exception(
            'Error Processing Request: setArrParams(),
                               $arrParams has to be an Array.', 1);
        }
        
        //trim all Strings in arrParams
        //array could hold formdata
        foreach ($arrParams as &$value) {
            if(true === is_string($value)) {
                $value = trim($value);
            }
        }
       
        $this->arrParams = $arrParams;
    }    
    
    /**
     * setAction
     * @param String $action
     * @throws \Exception
     */
    protected function setAction($action) {
        
        if (false === is_string($action)
         || false === ctype_alnum($action)) {
            throw new \Exception(
            'Error Processing Request: setAction(),
                               $action is not valid.', 1);
        }
        
        $this->action = $action;
        
    }        
}
?>