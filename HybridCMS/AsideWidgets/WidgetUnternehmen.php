<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetUnternehmen
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetUnternehmen extends AsideWidget 
{
    /**
     * attributes
     */
    private $name;
    
    /**
     * Year of the foundation
     * @var Integer
     */
    private $foundation;
    
    /**
     * legal form of the business
     * @var String
     */
    private $legalForm;
    
    /**
     * Main office
     * @var String
     */
    private $mainOffice;
    
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
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams - array with key name
     */
    public function __construct($widgetName, 
            $positionName, $priority, $arrParams) 
    {

        //check if name is an alphanumeric string
        if (false === isset($arrParams['name']))
        {
            throw new \Exception(
            "Error Processing Request: __construct(), "
                    . "name is not set.", 1);
        }

        try {

            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);

            //assign name
            $this->setName($arrParams['name']);
            
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }
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
     * setLegalForm
     * @param String $legalForm
     * @throws \Exception
     */
    public function setLegalForm($legalForm) {
        if (false === preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜé\s\.]+$/', $legalForm)) 
        {
            throw new \Exception(
            'Error Processing Request: setLegalForm(),
                        $legalForm is not valid', 1);
        }
        $this->legalForm = $legalForm;
    }
    
    /**
     * setMainOffice
     * @param String $legalForm
     * @throws \Exception
     */
    public function setMainOffice($mainOffice) {
        if (false === preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.\,]+$/', $mainOffice)) 
        {
            throw new \Exception(
            'Error Processing Request: setMainOffice(),
                        $mainOffice is not valid', 1);
        }
        $this->mainOffice = $mainOffice;
    }    
    
    /**
     * setFoundation
     * @param Integer $year
     * @throws \Exception
     */
    public function setFoundation($year) {
        if (false === is_numeric($year)
            ||
            $year < 1400 
            ||
            $year > 3000) 
        {
            throw new \Exception(
            "Error Processing Request: setFoundation(),
                        year is not valid", 1);
        }
        $this->foundation = $year;
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
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a thumb.
     * imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) {

        //check if $args is of type array
        if (false === is_array($args)) {
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
        
        $op .= '<table itemscope itemtype="http://schema.org/Organization">';

        //Name
        $op .= '<tr class="bold" itemprop="name">';
        $op .= '<th>Name:</th><td>' 
                . htmlspecialchars($this->name) . '</td>';
        $op .= '</tr>';

        //Gründung
        if(true === isset($this->foundation))
        {
            $op .= '<tr>';
            $op .= '<th>Gründungsjahr:</th><td>' 
                    . htmlspecialchars($this->foundation) . '</td>';
            $op .= '</tr>';
        }
        
        //Legal Form
        if(true === isset($this->legalForm))
        {
            $op .= '<tr>';
            $op .= '<th>Rechtsform:</th><td>' 
                    . htmlspecialchars($this->legalForm) . '</td>';
            $op .= '</tr>';
        }     
        
        //Main Office
        if(true === isset($this->mainOffice))
        {
            $op .= '<tr itemprop="addressLocality">';
            $op .= '<th>Hauptsitz:</th><td>' 
                    . htmlspecialchars($this->mainOffice) . '</td>';
            $op .= '</tr>';
        } 
        
        //Website
        if(true === isset($this->website))
        {
            $op .= '<tr itemprop="url">';
            $op .= '<th>Webseite:</th>';
            $op .= '<td>';
            $op .= '<a class="trk:company" href="'. htmlentities($this->website) .'" '
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
            $op .= '<tr itemprop="description">';
            $op .= '<td colspan="2"><span class="bold">Kurzportrait: </span>' 
                    . htmlspecialchars($this->description) . '</td>';
            $op .= '</tr>';
        }           
             
        
        $op .= '</table>';
        $op .= '</article>';

        return $op;
    }
}

?>
