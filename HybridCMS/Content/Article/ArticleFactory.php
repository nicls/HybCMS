<?php

namespace HybridCMS\Content\Article;

/**
 * class ArticleFactory - This class is needed to scrape Articles from urls
 *
 * @package Content\Article
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class ArticleFactory {

    private $htmlContent;
    private $htmlArticle;
    private $objDomContent;
    private $url;
    private $title;
    private $description;
    private $arrKeywords;

    /**
     * scrape - scraped the content from a given url
     *
     * @param url:String
     * @return string
     */
    private function scrape($url) {

        try {
                        
            //CURL Handler
            $ch = curl_init($url);            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: ' . HYB_HOST_NAME));
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            //set -k option if tld is .vs
            if(false ==! strpos(HYB_HOST_NAME, ".vs")) 
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }            
  
            $content = curl_exec($ch);
            $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            //check if request was successful
            if ($info == 200 && !empty($content)) {
                return $content;
            } else {
                throw new \Exception(
                    "Error Processing Request: scrape(),
                        curl execution failed and returned HTTP-Staus-Code: " . htmlspecialchars($info), 1);
            }
            
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    
    }

    /**
     * createArticle - created an Article
     *
     * @param url:String
     * @param cssId:String
     * @return Article
     */
    public function createArticle($url, $cssId) {

        try {
            
            //set url
            $this->setUrl($url);        

            //Scrape htmlFile from given URL
            $this->htmlContent = $this->scrape($this->url);       
            
            //remove WhiteSpace
            $this->htmlContent = str_replace(array("\r\n", "\r", "\n", "\t"), null, $this->htmlContent);

            //extract htmlArticle
            $this->htmlArticle = $this->extractHtmlArticle($cssId);            
            
            //extract title
            $this->title = $this->extractTitle();

            //extract description
            $this->description = $this->extractDescription();
            
            //extract keywords
            $this->arrKeywords = $this->extractArrKeywords();         

            //return new Article
            return new \HybridCMS\Content\Article\Article(
                    $this->htmlArticle, 
                    $this->url, 
                    $this->title, 
                    $this->description, 
                    $this->arrKeywords
                    );
            
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * extractHtmlArticle
     *
     * @param cssId
     * @return string
     */
    private function extractHtmlArticle($cssId) {

        //check if cssId is valid
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $cssId)) {
            throw new \Exception(
                    "Error Processing Request: extractHtmlArticle(),
                        cssId is not valid.", 1);
        }

        $this->objDomContent = new \DOMDocument();

        //Document Settings
        $this->objDomContent->preserveWhiteSpace = false;
        $this->objDomContent->strictErrorChecking = true;
        $this->objDomContent->encoding = 'UTF-8';
        @$this->objDomContent->loadHTML('<?xml encoding="UTF-8">' . $this->htmlContent);

        //get element by id
        $domElement = $this->objDomContent->getElementById($cssId);

        if (isset($domElement))
            return $domElement->ownerDocument->saveXML($domElement);
        else {
            throw new \Exception(
                    "Error Processing Request: extractHtmlArticle(),
                        domElement is not set.", 1);
        }
    }

    /**
     * extractTitle
     *
     * @return string
     */
    private function extractTitle() {

        //check if this->objDomContent is set
        if (!isset($this->objDomContent) || !($this->objDomContent instanceof \DOMDocument)) {
            throw new \Exception(
                    "Error Processing Request: extractTitle(),
                        objDomContent is not set.", 1);
        }

        //get element title
        $domElements = $this->objDomContent->getElementsByTagName('title');

        //return title if exists
        if ($domElements->item(0))
            return $domElements->item(0)->nodeValue;

        //else return an empty String
        else
            return '';
    }

    /**
     * extractDescription
     *
     * @return string
     */
    private function extractDescription() {

        //check if this->objDomContent is set
        if (!isset($this->objDomContent) || !($this->objDomContent instanceof \DOMDocument)) {
            throw new \Exception(
                    "Error Processing Request: extractTitle(),
                        objDomContent is not set.", 1);
        }

        //get element meta
        $domElements = $this->objDomContent->getElementsByTagName('meta');

        // find meta element with attribute name and value description
        foreach ($domElements as $domElement) {
            if ($domElement->getAttribute('name') && $domElement->getAttribute('name') == 'description') {
                return $domElement->getAttribute('content');
            }
        }

        //return an empty String if no description is given
        return '';
    }
    
    /**
     * extractKeywords
     * 
     * @return string
     * @throws \Exception
     */
    private function extractArrKeywords() {

        //check if this->objDomContent is set
        if (!isset($this->objDomContent) || !($this->objDomContent instanceof \DOMDocument)) {
            throw new \Exception(
                    "Error Processing Request: extractKeywords(),
                        objDomContent is not set.", 1);
        }
        
        //get element meta
        $domElements = $this->objDomContent->getElementsByTagName('meta');

        // find meta element with attribute name and value keywords
        foreach ($domElements as $domElement) {
            if ($domElement->getAttribute('name') && $domElement->getAttribute('name') == 'keywords') {
                $arrKeywords = explode(',', $domElement->getAttribute('content'));
                
                //trim keywords
                foreach ($arrKeywords as &$keyword) {
                    $keyword = trim($keyword);
                }
                
                return $arrKeywords;
            }
        }

        //return an empty array if no keywords are given
        return array();        
    }

    /**
     * setUrl
     *
     * @param url
     * @return void
     */
    private function setUrl($url) {

        //check if URL is valid
        if(!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
                    "Error Processing Request: setUrl(),
                        url is not valid.", 1);
        }

        //check if url is from current host
        if(stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) !== 0) {
            throw new \Exception(
                    "Error Processing Request: setUrl(),
                        url is not from current host.", 1);
        }

        //set url
        $this->url = $url;
    }

}

//class end
?>




