<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetDeepLink - Deeplink to an Affiliate Produkt wit image and price
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2015 Claroweb.de
 * @version 1.1
 */
class WidgetDeepLink extends AsideWidget {
    
    /**
     * Img Filename of the produkt image
     * @var String
     */
    private $imgFileName;
    
    /**
     * Alternative Text for the image
     * @var String
     */
    private $altText;
    
    /**
     * Width of the image
     * @var int
     */
    private $imgWidth;
    
    /**
     * Img Quality
     * @var Int
     */
    private $imgQuality = 80;
    
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
     * Price of the product
     * @var type string
     */
    private $price;

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
            if(true === isset($arrParams['imgWidth']))
            {
                $this->setImgWidth($arrParams['imgWidth']);
            }    
            if(true === isset($arrParams['imgQuality']))
            {
                $this->setImgQuality($arrParams['imgQuality']);
            }              
            if(true === isset($arrParams['price']))
            {
                $this->setPrice($arrParams['price']);
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
     * setImgWidth
     * @param String $width
     * @throws \Exception
     */
    private function setImgWidth($width)
    {
        //check if width is valid
        if (false == is_numeric($width)) 
        {
            throw new \Exception(
            'Error Processing Request: setImgWidth(),
                        $width is not valid.', 1);
        }
        
        $this->imgWidth = $width;
    }      
    
    /**
     * setImgQuality
     * @param Int $imgQuality between 0 to 100
     * @throws \Exception
     */
    private function setImgQuality($imgQuality)
    {
        //check if quailty is valid
        if (false == is_numeric($imgQuality)) 
        {
            throw new \Exception(
            'Error Processing Request: setImgQuality(),
                        $imgQuality is not valid.', 1);
        }
        
        $this->imgQuality = $imgQuality;
    }       
    
    /**
     * setAltText
     * @param String $price
     * @throws \Exception
     */
    private function setPrice($price)
    {
        //check if price is valid
        if (false == preg_match('/^[a-zA-Z0-9\-_\s,\.!\?:öüäß%€]+$/', 
                $price)) 
        {
            throw new \Exception(
            'Error Processing Request: setPrice(),
                        $price is not valid.', 1);
        }
        
        $this->price = $price;
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
        $op .= '<article class="widget widgetDeepLink">';                 
        
        if(false === empty($this->url)) 
        {
            $op .= '<a href="'. htmlentities($this->url) 
                    .'" title="'. htmlentities($this->altText) 
                    .'" rel="'. htmlentities($nofollowText) .'"'
                    .'" target="'. htmlentities($this->target) .'"'
                    . '>';
        }
        
        //set headline
        if(isset($this->headline)) {
            $op .= '<h3 class="headline">'. htmlspecialchars($this->headline) .'</h3>';
        }          
        
        //set price
        if(isset($this->price)) {
            $op .= '<p class="price">'. htmlspecialchars($this->price) .'</p>';
        }         
        
        if(true === isset($this->imgWidth)) 
        {
            global $hcms;
            $op .= '<img class="img-responsive" src="' . htmlentities(
                $hcms->scaleImg(
                    '/images/' . $this->imgFileName, 
                    $this->imgWidth, 
                    $this->imgQuality))
                . '" alt="'. htmlentities($this->altText) . '" '
                . 'width="' . htmlentities($this->imgWidth) .'"/>';   
        }
        else 
        {
            $op .= '<img class="img-responsive" src="/images/' . htmlentities($this->imgFileName) 
                . '" alt="'. htmlentities($this->altText) .'" />';
        }

        
        if(false === empty($this->url)) 
        {
            $op .= '</a>';
        }        
        
        $op .= '</article>';      
        
        return $op;
    }
}

?>
