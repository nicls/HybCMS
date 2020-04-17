<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent;

/**
 * class Content represents the content of a 
 * e.g. TwitterName, FacebookUrl or Website
 *
 * @package Content
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class FormElemGroupContent {
   
    /**
     * Error Message to the client if something went wrong
     * @var String
     */
    protected $errorMsg;
    
    /**
     * Warning Message to the client if something is strange
     * @var String
     */
    protected $warningMsg;

    /**
     * Hint to the client about the value-format
     * @var String
     */
    protected $hint;
    

    /**
     * setErrorMsg
     * @param String $errorMsg
     * @throws \InvalidArgumentException
     */
    public function setErrorMsg($errorMsg) 
    {
        $pattern = '/^[0-9a-zA-Z\-_\.,:\(\)\!\?äöüÄÖÜß\s]$/';
        
        if(false === is_string($errorMsg)
           ||
           false === preg_match($pattern, $errorMsg)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setErrorMsg(),                    
                    $errorMsg is not valid.', 1); 
        }
        
        $this->errorMsg = $errorMsg;
    }
    
    /**
     * setWarningMsg
     * @param String $errorMsg
     * @throws \InvalidArgumentException
     */
    public function setWarningMsg($warningMsg) 
    {
        $pattern = '/^[0-9a-zA-Z\-_\.,:\(\)\!\?äöüÄÖÜß\s]$/';
        
        if(false === is_string($warningMsg)
           ||
           false === preg_match($pattern, $warningMsg)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setWarningMsg(),                    
                    $warningMsg is not valid.', 1); 
        }
        
        $this->warningMsg = $warningMsg;
    }    

    /**
     * setHint
     * @param String $hint
     * @throws \InvalidArgumentException
     */
    public function setHint($hint) 
    {
        $pattern = '/^[0-9a-zA-Z\-_\.,:\(\)\!\?äöüÄÖÜß\s]$/';
        
        if(false === is_string($hint)
           ||
           false === preg_match($pattern, $hint)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setHint(),                    
                    $hint is not valid.', 1); 
        }
        
        $this->hint = $hint;
    }
    
    /**
     * getErrorMsg
     * @return String
     */
    public function getErrorMsg() {
        return $this->errorMsg;
    }

    /**
     * getWarningMsg
     * @return String
     */
    public function getWarningMsg() {
        return $this->warningMsg;
    }

    /**
     * getHint
     * @return String
     */
    public function getHint() {
        return $this->hint;
    }      
}
