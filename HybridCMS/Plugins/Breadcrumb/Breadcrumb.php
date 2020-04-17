<?php

namespace HybridCMS\Plugins\Breadcrumb;

/**
 * class Breadcrumb
 *
 * @package Breadcrumb
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Breadcrumb extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * current url
     * @var String
     */
    private $url;

    /**
     * html, php, ...
     * @var String
     */
    private $filetype;

    /**
     * array of breadcrumbs with crumb, url and title
     * @var String[]
     */
    private $arrBreadcrumbs;

    /**
     * prepend indicates a prepending String like "Sie befinden sich hier: "
     * @var String
     */
    private $prepend;

    /**
     * seperates each level width an String like '›' or '»'
     * @var String
     */
    private $seperator;

    /**
     * indicates weather the Homepage should be shown
     * @var Boolean
     */
    private $showHome;

    /**
     * indicates weather index.php or index.html will be included
     * in the breadcrumb
     * @var Boolean
     */
    private $showIndexFile;

    /**
     * stopwords that will be printed in lowercase
     * @var String[]
     */
    private $stopwords = array('und', "aber", "alle", "allem", "allen", "aller", "alles", "als", "also", "am", "an", "ander", "andere", "anderem", "anderen", "anderer", "anderes", "anderm", "andern", "anderr", "anders", "auch", "auf", "aus", "bei", "bin", "bis", "bist", "da", "damit", "dann", "der", "den", "des", "dem", "die", "das", "daß", "derselbe", "derselben", "denselben", "desselben", "demselben", "dieselbe", "dieselben", "dasselbe", "dazu", "dein", "deine", "deinem", "deinen", "deiner", "deines", "denn", "derer", "dessen", "dich", "dir", "du", "dies", "diese", "diesem", "diesen", "dieser", "dieses", "doch", "dort", "durch", "ein", "eine", "einem", "einen", "einer", "eines", "einig", "einige", "einigem", "einigen", "einiger", "einiges", "einmal", "er", "ihn", "ihm", "es", "etwas", "euer", "eure", "eurem", "euren", "eurer", "eures", "für", "gegen", "gewesen", "hab", "habe", "haben", "hat", "hatte", "hatten", "hier", "hin", "hinter", "ich", "mich", "mir", "ihr", "ihre", "ihrem", "ihren", "ihrer", "ihres", "euch", "im", "in", "indem", "ins", "ist", "jede", "jedem", "jeden", "jeder", "jedes", "jene", "jenem", "jenen", "jener", "jenes", "jetzt", "kann", "kein", "keine", "keinem", "keinen", "keiner", "keines", "können", "könnte", "machen", "man", "manche", "manchem", "manchen", "mancher", "manches", "mein", "meine", "meinem", "meinen", "meiner", "meines", "mit", "muss", "musste", "nach", "nicht", "nichts", "noch", "nun", "nur", "ob", "oder", "ohne", "sehr", "sein", "seine", "seinem", "seinen", "seiner", "seines", "selbst", "sich", "sie", "ihnen", "sind", "so", "solche", "solchem", "solchen", "solcher", "solches", "soll", "sollte", "sondern", "sonst", "über", "um", "und", "uns", "unse", "unsem", "unsen", "unser", "unses", "unter", "viel", "vom", "von", "vor", "während", "war", "waren", "warst", "was", "weg", "weil", "weiter", "welche", "welchem", "welchen", "welcher", "welches", "wenn", "werde", "werden", "wie", "wieder", "will", "wir", "wird", "wirst", "wo", "wollen", "wollte", "würde", "würden", "zu", "zum", "zur", "zwar", "zwischen");

    /**
     * __construct
     * 
     * @param String $url
     * @param String $make
     * @throws \HybridCMS\Plugins\Breadcrumb\Exception
     */
    public function __construct($url, $make = true) 
    {

        try {
            
            //assign url
            $this->setUrl($url);

            //setup default states
            $this->setPrepend('» ');
            $this->setSeperator($seperator = ' › ');
            $this->setShowHome(true);
            $this->setFiletype('html');
            $this->arrBreadcrumbs = array();
            
            //create Breadcrumbs
            if($make === true) {
                $this->make();
            }
           
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * make - creates the breadcrumbs
     * @throws \HybridCMS\Plugins\Breadcrumb\Exception
     */
    public function make() {
        
        try {

            //process Url
            $arrUrlChunks = $this->processUrl();

            //create the breadcrumbs
            $this->crumble($arrUrlChunks);
        
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }        
    }

    /**
     * processUrl - remove host, protocol and set correct filetype
     * @return String
     */
    private function processUrl() {

        assert(isset($this->url));
        assert(isset($this->filetype));
        assert(defined('HYB_PROTOCOL'));
        assert(defined('HYB_HOST_NAME'));

        //remove protocol and hostname
        $tmpUrl = str_replace(HYB_PROTOCOL . HYB_HOST_NAME 
                . '/', '', $this->url);

        //split url
        $arrChunks = explode('/', $tmpUrl);

        $topLevel = end($arrChunks);

        //check if top level is an filename
        if (strpos($topLevel, '.') !== false) {

            //discard index-file
            if (strpos($topLevel, 'index') !== false) {

                //remove index-filename from array
                array_pop($arrChunks);
                
            } else {

                //process filetype 
                if (strpos($topLevel, '.' . $this->filetype) === false) 
                {
                    //replace filetype with expected filetype
                    $arrChunks[count($arrChunks)-1] = 
                            preg_replace('/\.\w+/', '.' 
                                    . $this->filetype, $topLevel);
                }
            }
        }

        return $arrChunks;
    }

    /**
     * crumble - creates the breadcrumbs
     * @param String $arrUrlChunks
     */
    private function crumble($arrUrlChunks) {

        if (count($arrUrlChunks) === 0)
            return;

        assert(isset($this->arrBreadcrumbs));

        //create crumb
        $arrCrumb['crumb'] = end($arrUrlChunks);

        //create url of the current breadcrumb
        $arrCrumb['url'] = '/' . implode('/', $arrUrlChunks);

        //add a trailing slash if url points to an folder
        if (strpos($arrCrumb['url'], '.') === false) {
            $arrCrumb['url'] .= '/';
        }

        //create title respectively ankortext 
        $arrCrumb['title'] = $this->makeTitle($arrCrumb['crumb']);


        //add current breadcrumb to $this->arrBreadcrumbs
        array_unshift($this->arrBreadcrumbs, $arrCrumb);
        unset($arrCrumb);

        //remove chunk of the highest level to step one 
        //level deeper 
        array_pop($arrUrlChunks);

        //invoke method rekursivly
        if (count($arrUrlChunks) > 0) {
            $this->crumble($arrUrlChunks);
        }
    }

    /**
     * Build a title respectively an ankortext 
     * @param String $title
     * @return String
     */
    private function makeTitle($chunk) {

        //replace underscores with dashes
        $chunk = str_replace('_', '-', $chunk);

        //remove filetype
        $chunk = preg_replace('/\..+$/', '', $chunk);

        //explode String
        $arrTmp = explode('-', $chunk);

        //initialise variable to hold the title
        $title = '';

        //implode chunks to get the title
        for ($i = 0; $i < count($arrTmp); $i++) {

            if (!in_array($arrTmp[$i], $this->stopwords)) {
                $arrTmp[$i] = ucfirst($arrTmp[$i]);
            }

            $title .= $arrTmp[$i];

            if ($i < count($arrTmp) - 1) {
                $title .= " ";
            }
        }

        return $title;
    }

    /**
     * setUrl
     *
     * @param String $url
     * @return void
     */
    public function setUrl($url) {

        //check if url is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                       url is not valid.", 1);
        }

        //check if url is from the current host
        if (!stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) === 0) {
            throw new \Exception(
            "Error Processing Request: setUrl(),
                       url must be from the current host.", 1);
        }

        $this->url = $url;
    }

    /**
     * setPrepend
     *
     * @param String $prepend
     * @throws \Exception
     */
    public function setPrepend($prepend) {

        //check if $prepend is a String with 60 Characters max length
        if (!is_string($prepend) || strlen($prepend) > 60) {
            throw new \Exception(
            "Error Processing Request: setPrepend(),
                       prepend must be a String with 
                       60 characters in maximum.", 1);
        }

        $this->prepend = $prepend;
    }

    /**
     * setSeperator
     *
     * @param String $seperator
     * @throws \Exception
     */
    public function setSeperator($seperator) {

        //check if seperator is a String with 6 Characters max length
        if (!is_string($seperator) || strlen($seperator) > 6) {
            throw new \Exception(
            "Error Processing Request: setSeperator(),
                       seperator must be a String with 
                       6 characters in maximum.", 1);
        }

        $this->seperator = $seperator;
    }

    /**
     * setShowHome
     *
     * @param Boolean $showHome
     * @throws \Exception
     */
    public function setShowHome($showHome) {

        //check weather showHome is an Boolean
        if (!is_bool($showHome)) {
            throw new \Exception(
            "Error Processing Request: setShowHome(),
                       showHome must be a Boolean.", 1);
        }

        $this->showHome = $showHome;
    }

    /**
     * setShowIndexFile
     * 
     * @param Boolean $showIndexFile
     * @throws \Exception
     */
    public function setShowIndexFile($showIndexFile) {

        if (!is_bool($showIndexFile)) {
            throw new \Exception(
            "Error Processing Request: setShowIndexFile(),
                       showIndexFile must be a Boolean.", 1);
        }

        $this->showIndexFile = $showIndexFile;
    }

    /**
     * setFiletype
     * 
     * @param String $filetype
     * @throws \Exception
     */
    public function setFiletype($filetype) {

        if (!ctype_alpha($filetype) && strlen($filetype) > 10) {
            throw new \Exception(
            "Error Processing Request: setFiletype(),
                       filetype must be alphabetic and 
                       10 characters in maximum.", 1);
        }

        $this->filetype = $filetype;
    }

    /**
     * toString - returns the bradcrumb
     *
     * @param mixed[] $args
     */
    public function toString($args = array()) {

        assert(isset($this->arrBreadcrumbs));
        assert(isset($this->seperator));

        //declare outputString
        $string = '<div itemprop="breadcrumb">';

        //add prepending String to the breadcrumb
        $string .= "<span>" . $this->prepend . "</span>";

        //add homepage to the breadcrumb
        if ($this->showHome) 
        {
            $string .= '<a href="/" title="' 
                    . HYB_HOST_NAME . '">' 
                    . 'Startseite' . '</a>';
            $string .= $this->seperator;
        }

        //print breadcrumbs
        for ($i = 0; $i < count($this->arrBreadcrumbs); $i++) {

            if(false === empty($this->arrBreadcrumbs[$i]['title']))
            {
                $string .= "<a href='" 
                        . htmlentities($this->arrBreadcrumbs[$i]['url']) 
                        . "'>" . htmlentities($this->arrBreadcrumbs[$i]['title']) 
                        . "</a>";
            }

            if ($i < count($this->arrBreadcrumbs) - 1) {
                $string .= $this->seperator;
            }
        }

        //end div-tag
        $string .= '</div>';

        return $string;
    }

}

?>
