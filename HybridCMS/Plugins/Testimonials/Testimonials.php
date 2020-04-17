<?php

namespace HybridCMS\Plugins\Testimonials;

/**
 * class SchriftKaufenButtons
 *
 * @package SchriftKaufenButtons
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Testimonials extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * Attributes
     */
    private $arrObjTestimonials = array();

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
                    'testimonials', //1
                    '/HybridCMS/Plugins/Testimonials/css/f.css', //2
                    6, //3
                    true, //4
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
    
    public function addTestimonial($objTestimonial) {
        
        if(!($objTestimonial instanceof \HybridCMS\Plugins\Testimonials\Testimonial)) {
            throw new \Exception(
            "Error Processing Request: addTestimonial(),
                        objTestimonia is not of type Testimonial.", 1);
        }
        
        $this->arrObjTestimonials[] = $objTestimonial;
        
    }

    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {
        
        if(!count($this->arrObjTestimonials) > 0) {
            return;
        }
        
        //initialize output-String
        $op = '';
        
        //print each testimonial
        foreach ($this->arrObjTestimonials as &$tm) {
            
            //begin .testimonial
            $op .= '<div class="testimonial">';
            
            //add image
            if($tm->getImgFileName()) {
               $op .= '<img src="' . HYB_PLUGINPATH . 'Testimonials/images/' . htmlentities($tm->getImgFileName()) . '" alt="Testimonial" />';
            }
            
            //open p-tag for name
            $op .= '<p class="testimonial-name">';
            
            //add name
            $op .= htmlspecialchars($tm->getName());

            //add profession
            if($tm->getProfession()) {
                $op .= ', <span class="testimonial-profession">' . htmlspecialchars($tm->getProfession()) . '</span>';
            }
            
            //close p-tag for name
            $op .= '</p>';
            
            //add text
            $op .= '<p class="testimonial-text">' . htmlspecialchars($tm->getText()) . '</p>';
            
            //end .testimonial
            $op .= '</div>';
        }
        
        return $op;

    }
    

}

?>
