<?php

namespace HybridCMS\Plugins\HeroShot;

/**
 * class SchriftKaufenButtons
 *
 * @package SchriftKaufenButtons
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class HeroShot extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * attrubutes
     */
    protected $arrListItems;
    protected $headline;
    protected $url;
    protected $buttonText;
    

    /**
     * __construct
     * 
     */
    public function __construct($headline, $arrListItems, $buttonText, $url) {

        try {

            //call parent constructor
            parent::__construct();

            //set Headline 
            if (isset($headline) && $headline !== '') {
                $this->setHeadline($headline);
            }

            //set ListItems
            $this->setListItems($arrListItems);
            $this->setButtonText($buttonText);
            $this->setUrl($url);
            
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setHeadline
     * @param String $headline
     * @throws \Exception
     */
    protected function setHeadline($headline) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s!\?\.]+$/', $headline)) {
            throw new \Exception(
            "Error Processing Request: setHeadline(),
                            headline is not valid.", 1);
        }

        $this->headline = $headline;
    }

    /**
     * setListItems
     * @param String[] $arrListItems
     * @throws \Exception
     */
    protected function setListItems($arrListItems) {

        foreach ($arrListItems as $item) {
            if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s!\?\.,]+$/', $item)) {
                throw new \Exception(
                "Error Processing Request: setListItems(),
                            ListItem is not valid.", 1);
            }
        }

        $this->arrListItems = $arrListItems;
    }
    
    /**
     * setUrl
     * @return void
     */
    protected function setUrl($url) {
        //check if URL is valid
        if(!\HybridCMS\Helper\Helper::isValidURL($url)) {
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
    protected function setButtonText($buttonText) {
        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s!\?\.]+$/', $buttonText)) {
            throw new \Exception(
            "Error Processing Request: setButtonText(),
                            buttonText is not valid.", 1);
        }

        $this->buttonText = $buttonText;
    }    

}

?>
