<?php

namespace HybridCMS\Plugins\CTAButton\View;

/**
 * class ViewLogedIn
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewCTAButtonContent extends \HybridCMS\Plugins\Plugin\View 
{
    
    /**
     * CTAButton
     * @var CTAButton
     */
    private $objCTAButton;


    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($objCTAButton) {
        
        if(false === ($objCTAButton 
                instanceof \HybridCMS\Plugins\CTAButton\Model\CTAButton))
        {
            throw new \Exception(
            'Error Processing Request: __construct(),
                       $objCTAButton is not an instance of CTAButton.', 1);            
        }
        
        $this->objCTAButton = $objCTAButton;
    }    
    
    /**
     * toString
     * @return string
     */
    public function toString($arrParams = array()) 
    {
        $op = '';
        
        $iconClassNames = $this->objCTAButton->getIconClassNamesAsString();
        $arrDataAttr = $this->objCTAButton->getArrDataAttributes();
        
        $icon = '';
        if(false === empty($iconClassNames))
        {
            $icon = '<i class="' . htmlentities($iconClassNames) . '"></i> ';
        }
        
        $op .= '<a '
                . 'href="'. htmlentities($this->objCTAButton->getUrl()) .'" '
                . 'title="'. htmlentities($this->objCTAButton->getCTA()) .'" '
                . 'rel="'. htmlentities($this->objCTAButton->getNofollowAsString()) .'" '
                . 'target="'. htmlentities($this->objCTAButton->getTargetBlankAsString()) .'" '
                . 'class="' . htmlentities($this->objCTAButton->getClassNamesAsString()) . '" ';
        
        foreach($arrDataAttr as $key => $value)
        {
            $op .= htmlentities($key) . '="' . htmlentities($value) . '" ';
        }
        
        $op .= '>';//close a-tag
        $op .= $icon;
        $op .= htmlspecialchars($this->objCTAButton->getCTA());
        $op .= '</a>';
        
        return $op;
    }    
}
?>