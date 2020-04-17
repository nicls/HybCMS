<?php

namespace HybridCMS\Plugins\SMTwoClickButton;

/**
 * class SMTwoClickButton
 *
 * @package SMTwoClickButton
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class SMTwoClickButton extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * Attributes
     */
    private $facebook;
    private $twitter;
    private $googleplus;
    private $text;
    
    /**
     * __construct
     * 
     */
    public function __construct($facebook = true, $twitter = true, $googleplus = true, $text = 'Hier öffnen um Artikel zu teilen.') {

        try {
            
            //call parent constructor
            parent::__construct();
            
            $this->setFacebook($facebook);
            $this->setTwitter($twitter);
            $this->setGoogleplus($googleplus);
            $this->setText($text);
            

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
                            'smTwoClickButton',
                            '/HybridCMS/Plugins/SMTwoClickButton/js/f.js',
                            5,
                            true,
                            true,
                            'footer',
                            false
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
                    'smTwoClickButton', //1
                    '/HybridCMS/Plugins/SMTwoClickButton/css/f.css', //2
                    5, //3
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
     * setFacebook
     * @param Boolean $facebook
     * @throws \Exception
     */
    private function setFacebook($facebook) {
        if (!is_bool($facebook)) {
            throw new \Exception(
            "Error Processing Request: setFacebook(),
                        facebook must be of type Boolean.", 1);
        }
        
        $this->facebook = $facebook;
    }
    
    /**
     * setTwitter
     * @param Boolean $twitter
     * @throws \Exception
     */
    private function setTwitter($twitter) {
        if (!is_bool($twitter)) {
            throw new \Exception(
            "Error Processing Request: setTwitter(),
                        twitter must be of type Boolean.", 1);
        }
        
        $this->twitter = $twitter;
    }    
    
    /**
     * setGoogleplus
     * @param Boolean $googleplus
     * @throws \Exception
     */
    private function setGoogleplus($googleplus) {
        if (!is_bool($googleplus)) {
            throw new \Exception(
            "Error Processing Request: setGoogleplus(),
                        googleplus must be of type Boolean.", 1);
        }
        
        $this->googleplus = $googleplus;
    }  
    
    /**
     * setText
     * @param Boolean $text
     * @throws \Exception
     */
    private function setText($text) {
        if (!preg_match('/^[a-zA-Z0-9\-_,ßöäüÄÖÜ\s\.]+$/', $text)) {
            throw new \Exception(
            "Error Processing Request: setText(),
                        text is not valid.", 1);
        }
        
        $this->text = $text;
    }      
    
    /**
     * Getter
     * @return Boolean
     */
    public function getFacebook() { return $this->facebook; }
    public function getTwitter() { return $this->twitter; }
    public function getGoogleplus() { return $this->googleplus; }
    
    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {

        //output-String
        $op = '';  
                        
        //open unordered List
        $op .= '<ul class="socialButtons add-bottom-30">';
               
        //add Facebook
        if($this->facebook) {
            $op .= '<li class="tcb-facebook"><i class="fa fa-facebook"></i></li>';
        }
        
        //add Twitter
        if($this->twitter) {
            $op .= '<li class="tcb-twitter"><i class="fa fa-twitter"></i></li>';
        }
                
        //add Google Plus
        if($this->facebook) {
            $op .= '<li class="tcb-googleplus"><i class="fa fa-google-plus"></i></li>';
        }
        
        $op .= '<li class="cta"><i class="fa fa-angle-double-left"></i> <span>' . htmlspecialchars($this->text) . '</span></li>';        
                
        //close unordered List
        $op .= '</ul>';
                
        return $op;
       
    }    
}
?>
