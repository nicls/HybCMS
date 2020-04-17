<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetFlattr shows the Flattr Widget Button.
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class WidgetFlattr extends AsideWidget {

    /**
     * Text shown to the user. <BUTTON> gets replaced by the Flattr Button
     * @var String
     */
    private $text;
    
    /**
     * URL to thow the Button on.
     * @var String
     */
    private $url;    
    
    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        try {

            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);
            
            //check if text is given
            if (false === isset($arrParams['text'])) {
                throw new \Exception(
                "Error Processing Request: __construct(), "
                        . "text is missing.", 1);
            }   
            
            //check if url is given
            if (false === isset($arrParams['url'])) {
                throw new \Exception(
                "Error Processing Request: __construct(), "
                        . "url is missing.", 1);
            }    
            
            $this->setText($arrParams['text']);
            $this->setUrl($arrParams['url']);
            
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
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

        //check if $args is of type array
        if (!is_array($args)) {
            throw new \Exception(
            "Error Processing Request: toString(),
                        args must be an array.", 1);
        }
        
        assert(true === isset($this->url));
        assert(true === isset($this->text));


        $op = '';
        
        $op .= '<article id="widgetFlattr" class="widget c6">';
        
        //set headline
        if(isset($this->headline)) {
            $op .= '<h3>'. htmlspecialchars($this->headline) .'</h3>';
        }       
        
        //Build the static Flatttr Button
        $text = str_replace('BUTTON', $this->getStaticButton(), htmlspecialchars($this->text));  
        
        $op .= '<p>' . $text . '</p>';
               
        //close article-tag
        $op .= '</article>';          
             
        return $op;
    }
    
    /**
     * Build the static Flattr Button
     * @return string
     */
    private function getStaticButton()
    {
        $op = '';
        $op .= '<a href="https://flattr.com/submit/auto?fid=w7j1v0&url=' . htmlspecialchars($this->url) . '" '
                . 'title="Kaffeegeld spenden." '
                . 'target="_blank" '
                . 'rel="nofollow" '
                . 'border="0">';
        $op .= '<img src="//button.flattr.com/flattr-badge-large.png" alt="Kaffeegeld spenden" />';
        $op .= '</a>'; 
        
        return $op;
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
     * setText
     * @return void
     */
    private function setText($text)
    {
        //check if text is valid
        if (false === is_string($text)) {
            throw new \Exception(
            "Error Processing Request: setText(),
                        text is not valid.", 1);
        }

        //set text
        $this->text = $text;
    }      

}

?>
