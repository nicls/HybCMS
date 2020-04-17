<?php

namespace HybridCMS\Plugins\FooterShot;

/**
 * class SchriftKaufenButtons
 *
 * @package SchriftKaufenButtons
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class FooterShot extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * attrubutes
     */
    protected $headline;
    protected $text;
    protected $imgFileName;
    
    /**
     * url and buttonText to create a button
     * @var String
     */
    protected $url;
    protected $buttonText;
    
    /**
     * CTA Buttons as alternative ti url and buttonText
     * @var HybridCMS\Plugins\CTAButton\Controller\ControllerCTAButton
     */
    protected $arrObjCTAButton;

    /**
     * __construct
     * 
     */
    public function __construct() 
    {

        try {

            //call parent constructor
            parent::__construct();

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setHeadline
     * @param String $headline
     * @throws \Exception
     */
    public function setHeadline($headline) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s!\?\.]+$/', $headline)) {
            throw new \Exception(
            "Error Processing Request: setHeadline(),
                            headline is not valid.", 1);
        }

        $this->headline = $headline;
    }

    /**
     * setText
     * @param String[] $text
     * @throws \Exception
     */
    public function setText($text) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s!\?\.,]+$/', $text)) {
            throw new \Exception(
            "Error Processing Request: setText(),
                            text is not valid.", 1);
        }

        $this->text = $text;
    }

    /**
     * setUrl
     * @return void
     */
    public function setUrl($url) {
        //check if URL is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                        url is not valid.", 1);
        }

        //set url
        $this->url = $url;
    }

    /**
     * setButtonText
     * @param String $buttonText
     * @throws \Exception
     */
    public function setButtonText($buttonText) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s!\?\.]+$/', $buttonText)) {
            throw new \Exception(
            "Error Processing Request: setButtonText(),
                            buttonText is not valid.", 1);
        }

        $this->buttonText = $buttonText;
    }
    
    /**
     * setImgFileName
     * @return void
     */
    public function setImgFileName($imgFileName)
    {
        //check if fileName is valid
        if (!preg_match('/^[a-zA-Z0-9\-_\/]+\.(jpg|png)$/', $imgFileName)) {
            throw new \Exception(
            "Error Processing Request: setImgFileName(),
                        imgFileName is not valid.", 1);
        }

        //set $imgFileName
        $this->imgFileName = $imgFileName;
    }    
    
    /**
     * setarrObjCTAButton
     * @param array $arrObjCTAButton
     * @throws \Exception
     */
    public function setarrObjCTAButton($arrObjCTAButton)
    {
        //check if objPlugin is not null
        if (false === isset($arrObjCTAButton)) {
            throw new \Exception(
            "Error Processing Request: __construct(), "
                    . "arrObjCTA is not given", 1);
        }
        
        if(false === is_array($arrObjCTAButton))
        {
            throw new \Exception(
            "Error Processing Request: __construct(), "
                    . "arrObjCTA is not an Array.", 1);
        }

        foreach ($arrObjCTAButton as $objCTA) 
        {
            if (false === ($objCTA instanceof 
                    \HybridCMS\Plugins\CTAButton\Controller\ControllerCTAButton)) 
            {
                throw new \Exception(
                "Error Processing Request: __construct(), "
                        . "Object is not an instance of class CTAButton.", 1);
            }
        }  
        
        $this->arrObjCTAButton = $arrObjCTAButton;
    }

}

?>
