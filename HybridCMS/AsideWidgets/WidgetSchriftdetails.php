<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetSchriftdetails
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetSchriftdetails extends AsideWidget {

    /**
     * attributes
     */
    private $schriftart;
    private $charakter;
    private $verwendung;
    private $merkmale;
    private $schriftgiesserei;
    private $arrEntwickler;
    private $veroeffentlicht;
    private $arrVarianten;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams - array with key Schriftart
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        //check if schriftart is an alphanumeric string
        if (!(isset($arrParams['schriftart']) || !preg_match('/^[a-zA-Z0-9-_ßäöü\s]+$/', $arrParams['schriftart']))) {
            throw new \Exception(
            "Error Processing Request: __construct(), schriftart must be an alphanumeric String.", 1);
        }

        try {

            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);

            //assign schriftart
            $this->setSchriftart($arrParams['schriftart']);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setSchriftart
     * @param String $schriftart
     * @throws \Exception
     */
    private function setSchriftart($schriftart) {
        if (!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.]+$/', $schriftart)) {
            throw new \Exception(
            "Error Processing Request: setSchriftart(),
                        schriftart is not valid", 1);
        }
        $this->schriftart = $schriftart;
    }

    /**
     * setCharakter - Comma-seperated List of Charakters
     * @param String $charakter
     * @throws \Exception
     */
    public function setCharakter($charakter) {
        if (!preg_match('/^[a-zA-Z0-9\-_,ßöäüÄÖÜ\s\.]+$/', $charakter)) {
            throw new \Exception(
            "Error Processing Request: setCharakter(),
                        charakter is not valid", 1);
        }

        $this->charakter = $charakter;
    }

    /**
     * setVerwendung - Comma-seperated List of Verwendung
     * @param String $verwendung
     * @throws \Exception
     */
    public function setVerwendung($verwendung) {
        if (!preg_match('/^[a-zA-Z0-9\-_,ßöäüäöüÄÖÜ\s\.\(\)]+$/', $verwendung)) {
            throw new \Exception(
            "Error Processing Request: setVerwendung(),
                        verwendung is not valid", 1);
        }
        $this->verwendung = $verwendung;
    }

    /**
     * setMerkmale - Comma-seperated List of Merkmale
     * @param String $merkmale
     * @throws \Exception
     */
    public function setMerkmale($merkmale) {
        if (!preg_match('/^[a-zA-Z0-9\-_,ßöäüÄÖÜ\s\.\(\)]+$/', $merkmale)) {
            throw new \Exception(
            "Error Processing Request: setMerkmale(),
                        merkmale is not valid", 1);
        }

        $this->merkmale = $merkmale;
    }

    /**
     * setSchriftgiesserei
     * @param String $schriftgiesserei
     * @throws \Exception
     */
    public function setSchriftgiesserei($schriftgiesserei) {
        if (!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.\+,\&\']+$/', $schriftgiesserei)) {
            throw new \Exception(
            "Error Processing Request: setSchriftgiesserei(),
                        schriftgiesserei is not valid", 1);
        }

        $this->schriftgiesserei = $schriftgiesserei;
    }

    /**
     * setEntwickler
     * @param String $entwickler
     * @throws \Exception
     */
    public function addEntwickler($entwickler) {
        if (!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.,\(\)éç]+$/', $entwickler)) {
            throw new \Exception(
            "Error Processing Request: setEntwickler(),
                        entwickler is not valid", 1);
        }
        $this->arrEntwickler[] = $entwickler;
    }

    /**
     * setVeroeffentlicht - year published
     * @param Integer $veroeffentlicht
     * @throws \Exception
     */
    public function setVeroeffentlicht($veroeffentlicht) {
        if (!is_numeric($veroeffentlicht) || $veroeffentlicht < 1400 || $veroeffentlicht > 2100) {
            throw new \Exception(
            "Error Processing Request: setVeroeffentlicht(),
                        veroeffentlicht is not valid", 1);
        }

        $this->veroeffentlicht = $veroeffentlicht;
    }

    /**
     * addVariant
     * @param String $variantName
     * @throws \Exception
     */
    public function addVariant($variantName) {

        //check name
        if (!preg_match('/^[a-zA-Z0-9\-_ßöäüÄÖÜ\s\.\(\)#]+$/', $variantName)) {
            throw new \Exception(
            "Error Processing Request: addVariant(),
                        variantName is not valid", 1);
        }

        $this->arrVarianten[] = $variantName;
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
        $output = '';

        $output .= '<article class="widget c12">';
        $output .= '<h3>Schriftdetails</h3>';
        $output .= '<table>';

        //Schriftart
        $output .= '<tr>';
        $output .= '<th class="add-inner-right-10">Schriftart:</th><td>' . htmlspecialchars($this->schriftart) . '</td>';
        $output .= '</tr>';

        //charakter
        if (isset($this->charakter)) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Schrift-Charakter:</th><td>' . htmlspecialchars($this->charakter) . '</td>';
            $output .= '</tr>';
        }

        //verwendung
        if (isset($this->verwendung)) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Bevorzugte Verwendung:</th><td>' . htmlspecialchars($this->verwendung) . '</td>';
            $output .= '</tr>';
        }

        //merkmale
        if (isset($this->merkmale)) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Schrift-Merkmale:</th><td>' . htmlspecialchars($this->merkmale) . '</td>';
            $output .= '</tr>';
        }

        //schriftgiesserei
        if (isset($this->schriftgiesserei)) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Schriftgießerei:</th><td>' . htmlspecialchars($this->schriftgiesserei) . '</td>';
            $output .= '</tr>';
        }

        //entwickler
        if (isset($this->arrEntwickler) && count($this->arrEntwickler > 0)) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Entwickler:</th>';
            $output .= '<td>'; 
            
            foreach ($this->arrEntwickler as $entwickler) {
                $output .= htmlspecialchars($entwickler);

                if ($entwickler !== end($this->arrEntwickler)) {
                    $output .= ', ';
                }
            }
            
            $output .= '</td>';
            $output .= '</tr>';
        }

        //veroeffentlicht
        if (isset($this->veroeffentlicht)) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Erste Veröffentlichung:</th><td>' . htmlspecialchars($this->veroeffentlicht) . '</td>';
            $output .= '</tr>';
        }

        //arrVarianten
        if (isset($this->arrVarianten) && count($this->arrVarianten) > 0) {
            $output .= '<tr>';
            $output .= '<th class="add-inner-right-10">Varianten:</th>';
            $output .= '<td>';

            foreach ($this->arrVarianten as $varient) {
                $output .= htmlspecialchars($varient);

                if ($varient !== end($this->arrVarianten)) {
                    $output .= ', ';
                }
            }

            $output .= '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';
        $output .= '</article>';



        return $output;
    }

}

?>
