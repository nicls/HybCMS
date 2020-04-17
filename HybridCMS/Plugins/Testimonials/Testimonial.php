<?php

namespace HybridCMS\Plugins\Testimonials;

/**
 * class SchriftKaufenButtons
 *
 * @package SchriftKaufenButtons
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Testimonial extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * Attributes
     */
    private $name;
    private $profession;
    private $text;
    private $imgFileName;
    

    /**
     * __construct
     * 
     */
    public function __construct($name, $text) {

        try {

            //call parent constructor
            parent::__construct();
            
            //set Attributes
            $this->setName($name);
            $this->setText($text);
           

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
                    'testimonial', //1
                    '/HybridCMS/Plugins/Testimonials/css/testimonial.css', //2
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
    
    /**
     * setName
     * @param String $name
     * @throws \Exception
     */
    private function setName($name) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s]+$/', $name)) {
            throw new \Exception(
            "Error Processing Request: setName(),
                        name is not valid.", 1);
        }
        
        $this->name = $name;
    }
    
    /**
     * setText
     * @param String $text
     * @throws \Exception
     */
    protected function setText($text) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\s,\.:\(\)]+$/', $text)) {
            throw new \Exception(
            "Error Processing Request: setText(),
                            text is not valid.", 1);
        }

        $this->text = $text;
    }
    
    /**
     * setProfession
     * @param String $profession
     * @throws \Exception
     */
    public function setProfession($profession) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s]+$/', $profession)) {
            throw new \Exception(
            "Error Processing Request: setProfession(),
                        profession is not valid.", 1);
        }
        
        $this->profession = $profession;
    }    
    
    /**
     * setImgFileName
     * @return void
     */
    public function setImgFileName($imgFileName) {
        //check if fileName is valid
        if (!preg_match('/^[a-zA-Z0-9\-_]+\.(jpg|png)$/', $imgFileName)) {
            throw new \Exception(
            "Error Processing Request: setImgFileName(),
                        imgFileName is not valid.", 1);
        }

        //set $imgFileName
        $this->imgFileName = $imgFileName;
    }   
    
    /**
     * Getter
     */
    public function getName() { return $this->name; }
    public function getText() { return $this->text; }
    public function getProfession() { return $this->profession; }
    public function getImgFileName() { return $this->imgFileName; }
    
    

    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {

    }
    

}

?>
