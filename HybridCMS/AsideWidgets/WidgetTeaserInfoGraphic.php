<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetTeaserInfoGraphic
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetTeaserInfoGraphic extends AsideWidget {

    
    /**
     * Attributes
     */
    private $url;
    private $imgFileName;
    
    
    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in relation to other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        try {
            
            //check if catName is an alphanumeric string
            if (!(isset($arrParams['url'], $arrParams['imgFileName'], $arrParams['headline']))) {
                throw new \Exception(
                "Error Processing Request: __construct(), one or more params are missing.", 1);
            }


            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);
            parent::setHeadline($arrParams['headline']);
            
            $this->setUrl($arrParams['url']);
            $this->setImgFileName($arrParams['imgFileName']);

            
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    

    /**
     * setImgFileName
     * @return void
     */
    private function setImgFileName($imgFileName) {
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
     * setUrl
     * @return void
     */
    private function setUrl($url) {
        //check if URL is valid
        if (!\HybridCMS\Modules\Url\Url::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                        url is not valid.", 1);
        }

        //set url
        $this->url = $url;
    }    


    /**
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a thumb.
     * imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) {
        
        $op = '';

        $op .= '<article class="widget c12">';
        $op .= '<h3>' . htmlspecialchars($this->headline) . '</h3>';
        
        //img-link
        $op .= '<a href="'. htmlentities($this->url) .'" title="'. htmlentities($this->headline) .'" rel="nofollow">';
        $op .= '<img src="/images/' . htmlentities($this->imgFileName) .'" alt="' . htmlspecialchars($this->headline) . '" />';
        $op .= '</a>';
                
        $op .= '</article>';
        
        return $op;
                
    }

}

?>
