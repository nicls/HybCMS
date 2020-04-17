<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetNews - This class holds all AsideWidgets for different Positions
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class WidgetNews extends AsideWidget {

    /**
     * Number of News to show.
     * @var Integer
     */
    private $numberOfResults;
    

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) 
    {
        //call the parent's contructor
        parent::__construct($widgetName, $positionName, $priority);

        $this->numberOfResults = 3; //default value
        if(true === isset($arrParams['numberOfResults'])
           &&
           true === is_int($arrParams['numberOfResults'])
           &&
           0 < $arrParams['numberOfResults']) {

            $this->numberOfResults = $arrParams['numberOfResults'];
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


        //set hcms to global
        global $hcms;
        
        //load TwitterButton Plugin
        $objNews = new \HybridCMS\Plugins\News\News();
        $objNews->fetchNews($this->numberOfResults);
        $arrObjNewsTeaser = $objNews->getArrObjNewsTeaser();
        $hcms->loadPlugin($objNews);

        $op = "<section class='hyb_news_conainer widget'>";
        
        if(true === isset($this->headline))
        {
            $op .= '<h3>';
            
            if(true === isset($this->headlineUrl))
            {
                $op .= '<a href="'. htmlentities($this->headlineUrl) 
                        .'" title="'. htmlentities($this->headline) 
                        .'" name="' . htmlentities($anchorname) .'">';
            }
            
            $op .= htmlspecialchars($this->headline);
            
            if(true === isset($this->headlineUrl))
            {
                $op .= '</a>';
            }
            
            $op .= '</h3>';
        }
        
        if(true === isset($this->anchorname)) 
        {
            $op .= "<input class='refUrl' type='text' value='"
                    . htmlentities(HYB_CURRURL) . "#" 
                    . htmlentities($this->anchorname) ."'>";
        }      
        
        foreach ($arrObjNewsTeaser as &$objNewsTeaser) {
            
            $title = $objNewsTeaser->getTitle();
            $url = $objNewsTeaser->getUrl();
            $text = $objNewsTeaser->gettext();
            $objDate = $objNewsTeaser->getObjDate();
            $strDate = $objDate->format('d.m.Y');
            
            $op .= '<article>';
            $op .= '<header>';
            $op .= '<h4>';
            $op .= "<a href='" . htmlentities($url) 
                    . "' title='". htmlentities($title) 
                    ."' target='_blank' rel='nofollow'>";
            $op .= htmlentities($title) . '</h4>';
            $op .= '</a>';
            $op .= '</header>';
            
            $op .= '<p><span class="hyb_news_date">('. htmlentities($strDate) .')</span> ' 
                    . htmlentities($text) . '</p>';
            
            $op .= '</article>';
            
        }
        
        $op .= "</section>";
        return $op;        
    }

}

?>
