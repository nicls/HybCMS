<?php

namespace HybridCMS\Plugins\HeroShot;

/**
 * class HeroShotWebfontsKaufen
 *
 * @package HeroShot
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class HeroShotWebfontsKaufen extends \HybridCMS\Plugins\HeroShot\HeroShot {
    
    private $bgImg = 'bg-webfonts-kaufen-heroshot-1200x418.jpg';
    
    /**
     * __construct
     * 
     */
    public function __construct($headline, $arrListItems, $buttonText, $url) {

        try {

            //call parent constructor
            parent::__construct($headline, $arrListItems, $buttonText, $url);
                    
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
                    'heroShot', //1
                    '/HybridCMS/Plugins/HeroShot/css/HSWebfontsKaufen.css', //2
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
        
        //beginn #heroShot
        $op =  '<aside class="c12" id="heroshot-schriften-kaufen">';
        
        //print headline
        if(isset($this->headline)) {
            $op .= '<h1>'. htmlspecialchars($this->headline) .'</h1>';
        }
        
        //background image
        $op .= '<img src="' . HYB_PLUGINPATH . 'HeroShot/images/' . $this->bgImg . '" alt="Verkäuferin von Webfonts" />';
        
        //List begin
        $op .= '<ul>';
        
        foreach ($this->arrListItems as &$item) {
            $op .= '<li><i class="icon-ok"></i> ' . htmlspecialchars($item) . '</li>';
        }
        
            
        //List end
        $op .= '</ul>';
        
        //button
        $op .= '<a href="'. htmlspecialchars($this->url) .'" target="_blank" title="Webfonts kaufen" class="bgLinotypeRed button" rel="nofollow">' . htmlspecialchars($this->buttonText) . '</a>';
        $op .= '<img class="outOfScreen" src="https://www.lduhtrp.net/image-4144791-10979324" width="1" height="1" border="0"/>';
        
        //end #heroShot
        $op .= '</aside>';
        
        return $op;
    }

}

?>
