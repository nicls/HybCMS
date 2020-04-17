<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetGlosar
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2015 Claroweb.de
 */
class WidgetGlossar extends AsideWidget 
{
    /**
     * Assoziative Array of key value paris.
     * Keys = String
     * Values = array('value'    => 'xyz', 
     *                'itemprop' => 'addressLocality') //schema.org
     * @var String[]
     */
    private $assArrKeyAssArrValue;     
    
    /**
     * Url of a schema.org itemtype e.g. http://schema.org/Organization
     * @var String
     */
    private $itemType;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams - array with key name
     */
    public function __construct(
            $widgetName, 
            $positionName, 
            $priority, 
            $arrParams) 
    {
        //call the parent's contructor
        parent::__construct($widgetName, $positionName, $priority);
    }
    
    /**
     * setAssArrKeyAssArrValue
     * @param String[] $assArrKeyAssArrValue
     * @throws \InvalidArgumentException
     */
    public function setAssArrKeyAssArrValue($assArrKeyAssArrValue)
    {
        if(false === is_array($assArrKeyAssArrValue))
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setAssArrKeyAssArrValue(), no array given.', 1);
        }
        
        foreach ($assArrKeyAssArrValue as $key => $arrAssValue) 
        {
            if(false === is_string($key))
            {
                throw new \InvalidArgumentException(
                    'Error Processing Request:
                        setAssArrKeyAssArrValue(), 
                        key has to be a string.', 1);
            }
            
            if(false === isset($arrAssValue)
               ||
               true === empty($arrAssValue)
               || 
               false === is_string($arrAssValue['value']))
            {
                throw new \InvalidArgumentException(
                    'Error Processing Request:
                        setAssArrKeyAssArrValue(), 
                        $arrAssValue is not valid: ' .
                        htmlspecialchars($arrAssValue), 1); 
            }             
        }
        
        $this->assArrKeyAssArrValue = $assArrKeyAssArrValue;
    }
    
    
    /**
     * setItemType
     * @param String $itemType e.g. http://schema.org/Organization
     * @throws \InvalidArgumentException
     */
    public function setItemType($itemType)
    {
        //check if a valid url is given
        if(
            true === empty($itemType)
            ||  
            false === is_string($itemType)
            ||
            false === \HybridCMS\Modules\Url\Url::isValidUrl($itemType)
          )
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setItemType(), no valid url given.', 1);
        }
        
        $this->itemType = $itemType;
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
        if (false === is_array($args)) 
        {
            throw new \Exception(
            "Error Processing Request: toString(),
                        args must be an array.", 1);
        }
        
        $anchorname = "";
        if(true === isset($this->anchorname))
        {
            $anchorname = $this->anchorname;
        }        

        //output-String
        $op = '';

        $op .= '<section class="widget widgetGlossar">';
        
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
        
        $itemScope = '';
        if(false === empty($this->itemType))
        {
            $itemScope = ' itemscope itemtype="'
                    . htmlentities($this->itemType) .'"';
        }
        
        $firstLetter = "";
        
        //Generic Key/Values
        foreach ($this->assArrKeyAssArrValue as $key => $assArrValue) 
        {            
            
            $op .= '<article'. $itemScope .'>';
            
            //Print first Letter as Headline
            $currFirstLetter = substr($key, 0, 1);
            if($currFirstLetter !== $firstLetter) 
            {
                $firstLetter = $currFirstLetter;
                $op .= "<h4 class='borderBottom'>" . htmlspecialchars($firstLetter) . '</h4>';
            }
                                               
            $op .= '<div>';
            $op .= '<header>';
            
            //open value anchorlink
            if(true === isset($assArrValue['keyAnchorName']))
            {
                $valAnchor = 'glossar-' . $assArrValue['keyAnchorName'];
                $op .= '<a '
                        .'name="'. htmlentities($valAnchor) . '" '
                        .'href="#'. htmlentities($valAnchor) . '" '
                        .'title="'. htmlspecialchars($key) 
                        .'">';
            }
                        
            $op .= htmlspecialchars($key);
            
            //close value anchorlink
            if(true === isset($assArrValue['keyAnchorName']))
            {
                $op .= '</a>';
            }            
            $op .= ':</header>';
            $op .= '<p>' . htmlspecialchars($assArrValue['value']) . '</p>';
            $op .= '</div>'; 
            
            $op .= '</article>';
        }
            
               
        $op .= '</section>';

        return $op;
    }
}

?>
