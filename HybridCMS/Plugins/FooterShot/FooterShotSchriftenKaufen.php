<?php

namespace HybridCMS\Plugins\FooterShot;

/**
 * class FooterShotSchriftenKaufen
 *
 * @package FooterShot
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class FooterShotSchriftenKaufen extends \HybridCMS\Plugins\FooterShot\FooterShot {

    /**
     * attrubutes
     */
    private $img = 'bg-schriften-kaufen-footerShot-654x336.png';

    /**
     * __construct
     * 
     */
    public function __construct($headline, $text, $buttonText, $url) {

        try {

            //call parent constructor
            parent::__construct($headline, $text, $buttonText, $url);

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
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
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
        $op = '<div id="footerShot-schriften-kaufen" class="add-bottom-30">';
        
        //text
        $op .= '<p>'. htmlspecialchars($this->text) .'</p>';
        
        //button
        $op .= '<a href="'. htmlentities($this->url) .'" target="_blank" title="Schriften kaufen" class="bgLinotypeRed button" rel="nofollow">' . htmlspecialchars($this->buttonText) . '</a>';
        $op .= '<img class="outOfScreen" src="https://www.lduhtrp.net/image-4144791-10979324" width="1" height="1" border="0"/>';
        
        //woman image
        $op .= '<img src="' . HYB_PLUGINPATH . 'FooterShot/images/' . $this->img . '" alt="Virtuelle Verkäuferin von Schriften" />';
        
        //end #footerShot
        $op .= '</div>';
        
        return $op;
        
        
    }    

}

?>
