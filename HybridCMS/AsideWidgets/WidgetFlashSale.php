<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetFlashSale
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 * @version 1.1
 */
class WidgetFlashSale extends AsideWidget {

    /**
     * attributes
     */
    private $beginTimestamp;
    private $endTimestamp;
    private $url;
    private $imgFileName;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in relation to other 
     * widgets
     * @param arrParams 
     */
    public function __construct(
            $widgetName, 
            $positionName, 
            $priority, 
            $arrParams) {

        /**
         * int mktime (
         *   [  int $hour = date("H") 
         *   [, int $minute = date("i") 
         *   [, int $second = date("s") 
         *   [, int $month = date("n") 
         *   [, int $day = date("j") 
         *   [, int $year = date("Y") 
         *   [, int $is_dst = -1 ]]]]]]] )
         * 
         *   2013-09-14-23-59-59
         *   $d1=new DateTime("2012-07-08 11:14:15.638276");
         */
        try {

            //check if catName is an alphanumeric string
            if (!(isset(
                    $arrParams['headline'], 
                    $arrParams['begin'], 
                    $arrParams['end'], 
                    $arrParams['url'], 
                    $arrParams['imgFileName']))) {
                throw new \Exception(
                "Error Processing Request: __construct(), "
                        . "one or more params are missing.", 1);
            }


            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);

            $this->setBeginTimestamp($arrParams['begin']);
            $this->setEndTimestamp($arrParams['end']);
            $this->setUrl($arrParams['url']);
            $this->setImgFileName($arrParams['imgFileName']);
            $this->setHeadline($arrParams['headline']);
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }
    }

    /**
     * isRelevant - checks if the current Flash Sale is running or not
     * @return boolean
     */
    public function isRelevant() {

        if (!isset($this->endTimestamp, $this->beginTimestamp)) {
            return false;
        }

        //check if widget is expired and return
        if ($this->endTimestamp < time()) {

            return false;
        }

        //check if time is come to show the widget
        if (max($this->beginTimestamp + (60 * 60 * 24),time() + 1) < time()) 
        {
            return false;
        }

        return true;
    }

    /**
     * setUrl
     * @return void
     */
    private function setUrl($url)
    {
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
     * setImgFileName
     * @return void
     */
    private function setImgFileName($imgFileName)
    {
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
     * setBegin
     * @return void
     */
    private function setBeginTimestamp($begin) 
    {
        if (!ctype_digit($begin) || $begin < 1379012845) {
            throw new \Exception(
            "Error Processing Request: setBeginTimestamp(),
                        end is not valid.", 1);
        }

        //set begin
        $this->beginTimestamp = $begin;
    }

    /**
     * setBegin
     * @return void
     */
    private function setEndTimestamp($end) 
    {

        if (!ctype_digit($end) || $end < 1379012845) {
            throw new \Exception(
            "Error Processing Request: setEndTimestamp(),
                        end is not valid.", 1);
        }

        //set begin
        $this->endTimestamp = $end;
    }

    /**
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a 
     * thumb. imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) 
    {
        //check if widget is running
        if (!$this->isRelevant()) {
            return;
        }

        $op = '';

        $op .= '<article class="widget widgetFlashSale">';
        $op .= '<h3 class="remove-bottom">' . htmlspecialchars($this->headline) 
                . '</h3>';

        //check if Sale is in the next 24 h
        if ($this->beginTimestamp - time() > 0) {
            
            $startMin = max(1, abs($this->beginTimestamp - time())) / 60;
            $startHour = $startMin / 60;
            
              if ($startMin <= 120) {
                $op .= '<p class="small remove-top add-bottom-10">(Angebot startet wieder in ' 
                        . (int) $startMin . ' Minuten) <span id="counter"></span></p>';
            } else {
                $op .= '<p class="small remove-top add-bottom-10">(Angebot startet wieder in ' 
                        . (int) $startHour . ' Stunden) <span id="counter"></span></p>';
            } 
        } 
        
        //Sale is running
        else { 

            $restMin = max(1, ($this->endTimestamp - time())) / 60;
            $restHour = $restMin / 60;
            $restDay = $restHour / 24;

            if ($restMin <= 120) {
                $op .= '<p class="small remove-top add-bottom-10">(Angebot gilt noch für ' 
                        . (int) $restMin . ' Minuten) <span id="counter"></span></p>';
            } else if ($restHour < 24) {
                $op .= '<p class="small remove-top add-bottom-10">(Angebot gilt noch für ' 
                        . (int) $restHour . ' Stunden) <span id="counter"></span></p>';
            } else {
                $op .= '<p class="small remove-top add-bottom-10">(Angebot gilt noch für ' 
                        . (int) $restDay . ' Tage) <span id="counter"></span></p>';
            }
        }

        //img-link
        $op .= '<a href="' 
                . htmlentities($this->url) 
                . '" title="' 
                . htmlentities($this->headline) 
                . '" rel="nofollow" target="_blank">';
        $op .= '<img class="img-responsive" src="/images/angebote/' 
                . htmlentities($this->imgFileName) 
                . '" alt="' 
                . htmlspecialchars($this->headline) 
                . '" />';
        $op .= '</a>';

        $op .= '</article>';

        return $op;
    }

}
?>
