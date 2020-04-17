<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetSiegel - Just shows a Siegel something
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 * @version 1.1
 */
class WidgetSiegel extends AsideWidget {
    
    /**
     * Img Filename of the siegel
     * @var String
     */
    private $imgFileName;
    
    /**
     * Alternative Text for the image
     * @var String
     */
    private $altText;
    
    /**
     * Url to link to.
     * @var String
     */
    private $url;
    
    /**
     * Indicates if the URL is follow or nofollow.
     * @var Boolean
     */
    private $nofollow = true;
    
    /**
     * _blank | _self
     * @var String
     */
    private $target = "_blank";

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams 
     */
    public function __construct(
            $widgetName, 
            $positionName, 
            $priority, 
            $arrParams) 
    {

        try 
        {
            
            //check parasms
            if (false === isset(
                    $arrParams['altText'],  
                    $arrParams['imgFileName'])) 
            {
                throw new \Exception(
                "Error Processing Request: __construct(), "
                        . "one or more params are missing.", 1);
            }
            
            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);
            
            $this->setImgFileName($arrParams['imgFileName']);
            $this->setAltText($arrParams['altText']);
            
            //handle optinal settings
            if(true === isset($arrParams['url']))
            {
                $this->setUrl($arrParams['url']);
            }
            if(true === isset($arrParams['nofollow']))
            {
                $this->setNofollow($arrParams['nofollow']);
            }      
            if(true === isset($arrParams['target']))
            {
                $this->setTarget($arrParams['target']);
            }              
            
        } catch (\Exception $e) {

            throw $e;
        }
    }
    
    /**
     * setTarget
     * @param String $target
     * @throws \Exception
     */
    private function setTarget($target)
    {
        $arrTargets = array("_self", "_blank");
        
        //check if target is valid
        if (false === in_array($target, $arrTargets)) {
            throw new \Exception(
            'Error Processing Request: setTarget(),
                        $target is not valid.', 1);
        }
        
        $this->target = $target;
    }
    
    
    /**
     * setNofollow
     * @param Boolean $nofollow
     * @throws \Exception
     */
    private function setNofollow($nofollow)
    {
        //check if nofollow is a boolean
        if (false === is_bool($nofollow)) {
            throw new \Exception(
            'Error Processing Request: setNofollow(),
                        $nofollow is not valid.', 1);
        }
        
        $this->nofollow = $nofollow;
    }
    
    /**
     * setUrl
     * @param String $url
     */
    private function setUrl($url)
    {
        //check if a valid url is given
        if(
            true === empty($url)
            ||  
            false === is_string($url)
            ||
            false === \HybridCMS\Modules\Url\Url::isValidUrl($url)
          )
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), no valid url given.', 1);
        }
               
        $this->url = $url;
    }    
    
    /**
     * setImgFileName
     * @return void
     */
    private function setImgFileName($imgFileName)
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
     * setAltText
     * @param String $altText
     * @throws \Exception
     */
    private function setAltText($altText)
    {
        //check if fileName is valid
        if (false == preg_match('/^[a-zA-Z0-9\-_\s,\.!\?:öüäß%€]+$/', 
                $altText)) 
        {
            throw new \Exception(
            'Error Processing Request: setAltText(),
                        $altText is not valid.', 1);
        }
        
        $this->altText = $altText;
    }

    /**
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a thumb.
     * imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) 
    {
        //check if $args is of type array
        if (false === is_array($args)) 
        {
            throw new \Exception(
            "Error Processing Request: toString(),
                        args must be an array.", 1);
        }
        
        $nofollowText = "nofollow";        
        if(false === $this->nofollow)
        {
            $nofollowText = "follow";
        }

        $op = '';        
        $op .= '<article class="widget widgetSiegel">';
        
        //set headline
        if(isset($this->headline)) {
            $op .= '<h3>'. htmlspecialchars($this->headline) .'</h3>';
        }        
        
        if(false === empty($this->url)) 
        {
            $op .= '<a href="'. htmlentities($this->url) 
                    .'" title="'. htmlentities($this->altText) 
                    .'" rel="'. htmlentities($nofollowText) .'"'
                    .'" target="'. htmlentities($this->target) .'"'
                    . '>';
        }
        
        $op .= '<img class="img-responsive" src="/images/' . htmlentities($this->imgFileName) 
                . '" alt="'. htmlentities($this->altText) .'" />';
        
        if(false === empty($this->url)) 
        {
            $op .= '</a>';
        }        
        
        $op .= '</article>';      
        
        return $op;
    }
}

?>
