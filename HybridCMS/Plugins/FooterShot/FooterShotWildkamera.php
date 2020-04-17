<?php

namespace HybridCMS\Plugins\FooterShot;

/**
 * class FooterShotSchriftenKaufen
 *
 * @package FooterShot
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class FooterShotWildkamera extends \HybridCMS\Plugins\FooterShot\FooterShot {

    /**
     * __construct
     * 
     */
    public function __construct() 
    {

        try {

            //call parent constructor
            parent::__construct();

            /**
             * CSS
             *
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             */
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                    'footerShot', //1
                    '/HybridCMS/Plugins/FooterShot/css/f.css', //2
                    4, //3
                    false, //4
                    true //5
                    );
            $this->addObjCSSResource($objCSSResource); 
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * toString
     *
     * @override
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {
        
        //beginn #footerShot
        $op = '<div class="footerShot wildkameraKaufen">';
        
        //woman image
        if(false === empty($this->imgFileName))
        {
            $op .= '<img src="' . HYB_PLUGINPATH 
                    . 'FooterShot/images/' . htmlentities($this->imgFileName) 
                    . '" alt="' . htmlentities($this->text). '" />';   
        }
        
        //text
        if(false === empty($this->text))
        {
            $op .= '<p>'. htmlspecialchars($this->text) .'</p>';
        }       
        
        //CTAButton
        if(false === empty($this->arrObjCTAButton))
        {
            foreach ($this->arrObjCTAButton as &$objCTAButton) 
            {
                $strCTAButton = $objCTAButton->toString();                
                $op .= $strCTAButton;
            }            
        }               
        
        //end #footerShot
        $op .= '</div>';
        
        return $op;
        
        
    }    

}

?>
