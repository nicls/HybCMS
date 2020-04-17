<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentPasswordRepeat represents the content of a passwordRepeat-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentPasswordRepeat 
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentPassword
{
    
    /**
     * Dependent FormElemGroupContentPassword-Object
     * @var FormElemGroupContentPassword
     */
    private $objDependentFormElemGroupContentPassword;
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct($value);
        
        $this->setLabel('Passwort wiederholen:');
        $this->setWarningMsg('Die eingegeben Passwörter unterscheiden sich.');
        $this->setPlaceholder('Passwort wiederholen.');               
    }
    
    public function passwordRepeatIsEqualToPassword()
    {
        if(true === empty($this->objDependentFormElemGroupContentPassword))
        {
            throw new \Exception(
            'Error Processing Request:
                    passwordRepeatIsEqualToPassword(),                    
                    $this->objDependentFormElemGroupContentPassword is not set.', 1); 
        }
        
        $ret = false;
        
        //create alias fpr depedency object
        $objCPass = &$this->objDependentFormElemGroupContentPassword;
        
        //check if password of dependecy object is valid
        $passwordIsValid = $objCPass->valueIsValid();
                
        //check if passwords are euqal
        if(true === $passwordIsValid && true === $this->valueIsValid())
        {
            $password = $objCPass->getValue();
            
            $ret = $password === $this->value;
        }
        
        return $ret;
    }    
    
    /**
     * setObjDependentFormElemGroupContentEmail
     * @param FormElemGroupContentEmail $objDependentFormElemGroupContentEmail
     * @throws \InvalidArgumentException
     */
    public function setObjDependentFormElemGroupContentPassword(
            $objDependentFormElemGroupContentPassword) 
    {
        if(false === ($objDependentFormElemGroupContentPassword instanceof
                \HybridCMS\Plugins\User\CustomControls
                \FormElemGroupContent\FormElemGroupContentPassword))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjDependentFormElemGroupContentEmail(),                    
                    $objDependentFormElemGroupContentPassword is not an instance of 
                    FormElemGroupContentPassword.', 1);
        }
        
        $this->objDependentFormElemGroupContentPassword 
                = $objDependentFormElemGroupContentPassword;
    } 
    
    /**
     * getObjDependentFormElemGroupContentPassword
     * @return FormElemGroupContentPassword
     */
    public function getObjDependentFormElemGroupContentPassword() {
        return $this->objDependentFormElemGroupContentPassword;
    }
}
?>
