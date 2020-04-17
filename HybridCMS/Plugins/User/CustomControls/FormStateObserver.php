<?php

namespace HybridCMS\Plugins\User\CustomControls;

/**
 * class HybridCMS\Plugins\User\CustomControls\FormStateObserver 
 * represents the state of a form
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormStateObserver {
    
    /**
     * Inidicates if a forms data has an error
     * @var Boolean
     */
    private $formHasError;
    
    /**
     * Inidicates if a forms data has a warning
     * @var Boolean
     */
    private $formHasWarning;
    
    /**
     * __construct
     */
    public function __construct() {
        $this->formHasError = false;
        $this->formHasWarning = false;
    }


    /**
     * If one ElementGroup of the observed form ha an error,
     * the whole form has an error.
     * @param Boolean $hasError
     */
    public function groupHasError($groupHasError)
    {
        if(false === $this->formHasError
                && $groupHasError === true)
        {
            $this->formHasError = true;
        }
    }
    
    /**
     * If a single Group of the form has a warning, the whole Form 
     * has a warning.
     * @param Boolean $groupHasWarning
     */
    public function groupHasWarning($groupHasWarning)
    {
        if(false === $this->formHasWarning
                && $groupHasWarning === true)
        {
            $this->formHasWarning = true;
        }
    }
    
    /**
     * getFormHasError
     * @return Boolean
     */
    public function getFormHasError() {
        return $this->formHasError;
    }

    /**
     * getFormHasWarning 
     * @return Boolean
     */
    public function getFormHasWarning() {
        return $this->formHasWarning;
    }      
}
