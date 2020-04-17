<?php

namespace HybridCMS\Plugins\FontSize;

/**
 * class FontSize
 *
 * @package Plugins
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FontSize extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * __construct
     * 
     */
    public function __construct() {

        try {

            //call parent constructor
            parent::__construct();


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
                    'fontSize', '/HybridCMS/Plugins/FontSize/js/f.js', 5, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource);

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
                    'fontSize', //1
                    '/HybridCMS/Plugins/FontSize/css/f.css', //2
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
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array('min' => 13, 'max' => 17)) {

        if (!isset($args['min']) || !is_numeric($args['min'])) {
            throw new \Exception(
            "Error Processing Request: toString(),
                            'min is missing or not numeric.'", 1);
        }
        
        if (!isset($args['max']) || !is_numeric($args['max'])) {
            throw new \Exception(
            "Error Processing Request: toString(),
                            'max is missing or not numeric.'", 1);
        }
        
        if ($args['max'] <= $args['min'] ) {
            throw new \Exception(
            "Error Processing Request: toString(),
                            'max has to be larger that min.'", 1);
        }        

        $fsMin = $args['min'];
        $fsMax = $args['max'];

        //output-String
        $op = '';
        $op .= '<span id="hyb_fontSize">';
        $op .= '<input id="hyb_fontSizeSlider" step="1" type="range" min="' . htmlentities($fsMin) . '" max="' . htmlentities($fsMax) . '" />';
        $op .= '</span>';

        return $op;
    }

}

?>
