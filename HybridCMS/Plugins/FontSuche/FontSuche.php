<?php

namespace HybridCMS\Plugins\FontSuche;

/**
 * class FontSuche
 *
 * @package SchriftKaufenButtons
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class FontSuche extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * __construct
     * 
     */
    public function __construct() {

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
                    'fontSuche', //1
                    '/HybridCMS/Plugins/FontSuche/css/f.css', //2
                    6, //3
                    true, //4
                    true //5
                    );
            $this->addObjCSSResource($objCSSResource);
          
                     
            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                            'fontSuche',
                            '/HybridCMS/Plugins/FontSuche/js/f.js',
                            5,
                            true,
                            true,
                            'footer',
                            true
            );            
            $this->addObjJSResource($objJSResource);
            
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
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {
        $op = '';
        
        $op .= '<div id="fontSuche">';
        $op .= '<div>';
                
        $op .= '<input id="fontSucheInput" class="float_left" type="search" name="q" placeholder="Schriftart eingeben"/>'; 
        $op .= '<a id="fontSuchen" rel="nofollow" target="_blank" class="searchButton float_left"><i class="icon-search"></i></a>';
        $op .= '<img class="outOfScreen" src="https://www.awltovhc.com/image-4144791-10979324" width="1" height="1" border="0"/>';
        
        //close tags
        $op .= '</div>';
        $op .= '</div>';
        
        return $op;
    } 


}

?>
