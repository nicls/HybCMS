<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetGenericTable
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2015 Claroweb.de
 */
class WidgetGenericTable extends AsideWidget 
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
     * attributes
     */
    private $name;
        
    /**
     * Url of the businesswebsite
     * @var String
     */
    private $website;
    
    /**
     * Description of the Business
     * @var String
     */
    private $description;
    
    
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

        //check if name is an alphanumeric string
        if (false === isset($arrParams['name']))
        {
            throw new \Exception(
            "Error Processing Request: __construct(), "
                    . "name is not set.", 1);
        }

        //call the parent's contructor
        parent::__construct($widgetName, $positionName, $priority);

        //assign name
        $this->setName($arrParams['name']);
    }

    /**
     * setName
     * @param String $name
     * @throws \Exception
     */
    private function setName($name) {
        if (false === preg_match('/^[a-zA-Z0-9\-\+_ßöäüÄÖÜ\s\.]+$/', $name)) 
        {
            throw new \Exception(
            "Error Processing Request: setName(),
                        name is not valid", 1);
        }
        $this->name = $name;
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
            
            if(false === is_array($arrAssValue)
               || 
               false === isset($arrAssValue['value'])
               ||
               true === empty($arrAssValue['value'])
               || 
               false === is_string($arrAssValue['value']))
            {
                throw new \InvalidArgumentException(
                    'Error Processing Request:
                        setAssArrKeyAssArrValue(), 
                        $arrAssValue is not valid.', 1); 
            }             
        }
        
        $this->assArrKeyAssArrValue = $assArrKeyAssArrValue;
    }
    
    /**
     * setDescription
     * @param String $description
     * @throws \Exception
     */
    public function setDescription($description) 
    {
        if (false === is_string($description)) 
        {
            throw new \Exception(
            'Error Processing Request: setDescription(),
                        $description is not valid', 1);
        }
        $this->description = $description;
    }    
    
    /**
     * setWebsite
     * @param String $url
     */
    public function setWebsite($url)
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
                    setWebsite(), no valid url given.', 1);
        }
               
        $this->website = $url;
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

        $op .= '<article class="widget">';
        
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

        $op .= '<table'. $itemScope .'>';

        //Name
        $itemprop = '';
        if(false === empty($this->itemType))
        {
           $itemprop = ' itemprop="name"';
        }
        $op .= '<tr class="bold" '. $itemprop .'>';
        $op .= '<th>Name:</th><td>' 
                . htmlspecialchars($this->name) . '</td>';
        $op .= '</tr>';
 
        
        //Generic Key/Values
        foreach ($this->assArrKeyAssArrValue as $key => $assArrValue) 
        {
            $itemprop = '';
            if(false === empty($assArrValue['itemprop']))
            {
                $itemprop = ' itemprop="' . $assArrValue['itemprop'] . '"';
            }
            
            $value = $assArrValue['value'];
                        
            $op .= '<tr' . htmlentities($itemprop) . '">';
            $op .= '<th>' . htmlspecialchars($key) . ':</th><td>' 
                    . htmlspecialchars($value) . '</td>';
            $op .= '</tr>'; 
        }
        
        //Website
        if(true === isset($this->website))
        {
            $itemprop = '';
            if(false === empty($this->itemType))
            {
               $itemprop = ' itemprop="url"';
            }            
            $op .= '<tr'. $itemprop .'>';
            $op .= '<th>Webseite:</th>';
            $op .= '<td>';
            $op .= '<a href="'. htmlentities($this->website) .'" '
                    . 'title="'. htmlentities($this->name) .'" '
                    . 'target="_blank" rel="nofollow">';
            $op .= htmlspecialchars($this->name);
            $op .= ' <i class="fa fa-external-link"></i></a>';                   
            $op .= '</td>';
            $op .= '</tr>';
        }         
        
        //Description
        if(true === isset($this->description))
        {
            $itemprop = '';
            if(false === empty($this->itemType))
            {
               $itemprop = ' itemprop="description"';
            }              
            $op .= '<tr'. $itemprop .'>';
            $op .= '<td colspan="2"><span class="bold">Kurzbeschreibung: </span>' 
                    . htmlspecialchars($this->description) . '</td>';
            $op .= '</tr>';
        }           
             
        
        $op .= '</table>';
        $op .= '</article>';

        return $op;
    }
}

?>
