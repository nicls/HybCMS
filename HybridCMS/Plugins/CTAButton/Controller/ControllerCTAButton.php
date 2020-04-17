<?php

namespace HybridCMS\Plugins\CTAButton\Controller;

/**
 * class ControllerCTAButton
 *
 * @package CTAButton\Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerCTAButton extends \HybridCMS\Plugins\Plugin\Controller 
{
    
    public function __construct($arrParams) 
    {
        
        //call constructor of parent class
        parent::__construct($arrParams);

        //handle Request sent by the client
        $this->handleRequest();
    }
    
    /**
     * handleRequest handles the request from the client
     */
    protected function handleRequest() {
        
        //show CTA-Button in Content
        if(true === isset($this->arrParams['action']))
        {
            if('showCTAButtonContent' === $this->arrParams['action'])
            {
                $this->handleActionShowCTAButtonContent(); 
            }            
        } 
        else 
        {
            throw new \Exception(
            'Error Processing Request: 
                handleRequest(),
                       action is not set.', 1);   
        }
    }
    
    /**
     * Hanldes action to show CTAButton in Content
     */
    private function handleActionShowCTAButtonContent()
    {
        
        $objCTAButton = $this->createCTAButton();
        
        assert(false === empty($objCTAButton));
        
        //Add CSSResource
        $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                'CTABtnContent', 
                '/HybridCMS/Plugins/CTAButton/css/f.css');
        $this->addObjCSSResource($objCSSResource1);
               
        //create view
        $this->objView = new \HybridCMS\Plugins\CTAButton\View\ViewCTAButtonContent($objCTAButton);
    }
    
    /**
     * createCTAButton
     * @return CTAButton
     * @throws \Exception
     */
    private function createCTAButton()
    {
        
        //create CTAButton
        $objCTAButton = new \HybridCMS\Plugins\CTAButton\Model\CTAButton();        
        
        if(true === isset($this->arrParams['url'])) 
        {
            $objCTAButton->setUrl($this->arrParams['url']);
        }
                
        if(true === isset($this->arrParams['cta'])) 
        {
            $objCTAButton->setCTA($this->arrParams['cta']);
        }
        
        //add classnames
        if(true === isset($this->arrParams['classNames'])) 
        {
            $classNames = str_replace(',', '', $this->arrParams['classNames']);
            $arrClassNames = explode(' ', $classNames);
            
            foreach ($arrClassNames as &$className) {
                $objCTAButton->addClassName($className);
            }
        }
        
        
        //add data attributes
        if(true === isset($this->arrParams['dataAttr']))
        {
            $objCTAButton->setArrDataAttributes($this->arrParams['dataAttr']);
        }
        
        //add nofollow-attribute
        if(true === isset($this->arrParams['nofollow'])) {
            $objCTAButton->setNofollow($this->arrParams['nofollow']);
        }
        
        //add target-attribute
        if(true === isset($this->arrParams['targetBlank'])) {
            $objCTAButton->setTargetBlank($this->arrParams['targetBlank']);
        }
        
        //add icon 
        if(true === isset($this->arrParams['iconClassNames'])) 
        {
            $classNames = str_replace(',', '', $this->arrParams['iconClassNames']);
            $arrClassNames = explode(' ', $classNames);
            
            foreach ($arrClassNames as &$className) {
                $objCTAButton->addIconClassName($className);
            }            
        }
        
        return $objCTAButton;
    }

    
    /**
     * toString
     * @param type $arrParams
     * @throws \Exception
     */
    public function toString() {
        
        if(true === empty($this->objView)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                toString(),
                       objView is not set.', 1);    
        }
        
        return $this->objView->toString();
    }
    
    /**
     * Validates form data sent by the client.
     */
    protected function validateFormData() {}    

}

?>