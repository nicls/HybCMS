<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetSoftwareDetails
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetSoftwareDetails extends \HybridCMS\AsideWidgets\AsideWidget {

    /**
     * Name of the Software
     * @var String
     */
    private $toolname;
    
    /**
     * Is the UI german?
     * @var String 
     */
    private $german;
    
    /**
     * Name of the developer 
     * @var String
     */
    private $developer;
    
    /**
     * Supported OS
     * @var String[]
     */
    private $arrOs;
    
    /**
     * Memory needed
     * @var String
     */
    private $memory;
    
    /**
     * Processor
     * @var String
     */
    private $processor;
    
    /**
     * Size of the Download File
     * @var float
     */
    private $filesize;
    
    /**
     * Is it Freeware or Shareware?
     * @var String
     */
    private $licence;
    
    /**
     * Price in €
     * @var float
     */
    private $price;
    
    /**
     * Url to the downloadfile
     * @var String 
     */
    private $download;

    /**
     * __construct
     *
     * @param String widgetName - name of the widget
     * @param String positionName - name of the widget-position
     * @param String priority - priority of the current widget in order of other widgets
     * @param String[] arrParams - array with key toolname
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        //check if schriftart is an alphanumeric string
        if (false === isset($arrParams['toolname'])) {
            throw new \Exception(
            "Error Processing Request: __construct(), toolname is not given.", 1);
        }

        //call the parent's contructor
        parent::__construct($widgetName, $positionName, $priority);

        //assign toolname
        $this->setToolname($arrParams['toolname']);
        
        $this->arrOs = array();

    }

    /**
     * setToolname
     * @param String $toolname
     * @throws \Exception
     */
    private function setToolname($toolname) 
    {
        if (!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.]+$/', $toolname)) {
            throw new \Exception(
            'Error Processing Request: 
                setToolname(),
                        $toolname is not valid', 1);
        }
        $this->toolname = $toolname;
    }
    
    /**
     * setGerman
     * @param String $german ja|nein
     * @throws \Exception
     */
    public function setGerman($german) 
    {
        
        if (!preg_match('/^(ja|nein)$/', $german)) {
            throw new \Exception(
            'Error Processing Request: 
                setGerman(),
                        $german is not valid', 1);
        }
        
        $this->german = $german;
    }

    /**
     * setDeveloper
     * @param String $developer
     * @throws \Exception
     */
    public function setDeveloper($developer) 
    {
        if (!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.]+$/', $developer)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setDeveloper(),
                        $developer is not valid', 1);
        }        
        $this->developer = $developer;
    }

    /**
     * addOs
     * @param String $os
     * @throws \Exception
     */
    public function addOs($os) 
    {
        if(!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.]+$/', $os)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                addOs(),
                        $os is not valid', 1);
        }
        $this->arrOs[] = $os;
    }
    
    /**
     * setMemory
     * @param String $memory
     * @throws \Exception
     */
    public function setMemory($memory) 
    {
        if(!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.]+$/', $memory)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setMemory(),
                        $memory is not valid', 1);
        }        
        $this->memory = $memory;
    }

    /**
     * setProcessor
     * @param String $processor
     * @throws \Exception
     */
    public function setProcessor($processor) 
    {
        if(!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.,]+$/', $processor)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setMemory(),
                        $processor is not valid', 1);
        }        
        $this->processor = $processor;
    }

    
    /**
     * setFilesize
     * @param float $filesize
     * @throws \Exception
     */
    public function setFilesize($filesize) 
    {
        if(false === is_float($filesize)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setFilesize(),
                        $filesize is not a float', 1);
        }
        $this->filesize = $filesize;
    }

    /**
     * setLicence
     * @param String $licence
     * @throws \Exception
     */
    public function setLicence($licence) 
    {
        if(!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.]+$/', $licence)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setLicence(),
                        $licence is not valid', 1);
        }        
        $this->licence = $licence;
    }

    /**
     * setPrice
     * @param float $price
     * @throws \Exception
     */
    public function setPrice($price) 
    {
        if(false === is_float($price)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setPrice(),
                        $price is not a float', 1);
        }
        
        $this->price = $price;
    }

    /**
     * setDownload
     * @param String $download 
     */
    public function setDownload($download) 
    {
        if(false === \HybridCMS\Modules\Url\Url::isValidUrl($download)) 
        {
            throw new \Exception(
            'Error Processing Request: 
                setDownload(),
                        $download is not a float', 1);
        }        
        $this->download = $download;
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

        //check wether parameter are passed through
        if (count($args) > 0) {
            
        } //nothing to do..
        //output-String
        $op = '';

        $op .= '<article class="widget c12 add-bottom-20">';
        $op .= '<h3>Softwaredetails</h3>';
        $op .= '<table>';

        //toolaname
        $op .= '<tr>';
        $op .= '<th class="add-inner-right-10">Toolname:</th><td>' . htmlspecialchars($this->toolname) . '</td>';
        $op .= '</tr>';

        //licence
        if (isset($this->licence)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Software-Lizenz:</th><td>' . htmlspecialchars($this->licence) . '</td>';
            $op .= '</tr>';
        }
        
        //german
        if (isset($this->german)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Deusches UI:</th><td>' . htmlspecialchars($this->german) . '</td>';
            $op .= '</tr>';
        }    
        
        //os
        if (0 < count($this->arrOs)) {
            
            $os = implode(', ', $this->arrOs);
            
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Unterstützte OS:</th><td>' . htmlspecialchars($os) . '</td>';
            $op .= '</tr>';
        }  
        
        //memory
        if (isset($this->memory)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Arbeitsspeicher:</th><td>' . htmlspecialchars($this->memory) . '</td>';
            $op .= '</tr>';
        }    
        
        //processor
        if (isset($this->processor)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Prozessor:</th><td>' . htmlspecialchars($this->processor) . '</td>';
            $op .= '</tr>';
        }         
        
        //developer
        if (isset($this->developer)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Entwickler:</th><td>' . htmlspecialchars($this->developer) . '</td>';
            $op .= '</tr>';
        }   

        //filesize
        if (isset($this->filesize)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Dateigröße:</th><td>' . htmlspecialchars($this->filesize) . ' MB</td>';
            $op .= '</tr>';
        }   
        
        //price
        if (isset($this->price)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Preis:</th><td>' . htmlspecialchars($this->price) . ' €</td>';
            $op .= '</tr>';
        }     
        
        //price
        if (isset($this->price)) {
            $op .= '<tr>';
            $op .= '<th class="add-inner-right-10">Download:</th>'
                    . '<td><a href="'. htmlentities($this->download) .'" '
                    . 'title="Download '. htmlentities($this->toolname) .'" '
                    . 'target="_blank" rel="nofollow">Entwicklerwebseite '
                    . '<i class="fa fa-external-link"></i></td>';
            $op .= '</tr>';
        }         

        $op .= '</table>';
        $op .= '</article>';



        return $op;
    }

}

?>
