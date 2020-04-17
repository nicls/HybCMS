<?php

namespace HybridCMS\Content\Article;

/**
 * class Article - This class represents an Artikel of a specific url
 *
 * @package Content\Article
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Article {

    /**
     * Attributes
     */
    protected $htmlArticle; // htmlArticle is the html-formated Article
    protected $url; //url indicates the url of the current Article
    protected $title; //title indicates the value of the title
    protected $description; //indicates the value of the discription-tag of the current Article
    protected $arrKeywords; //keywords from meta tag keywords
    protected $md5; //an md5-hash of the current htmlArticle
    protected $timeCreated; //the timestamp when the Article was stored in the database
    protected $firstHeadline; //the headline of the current Article
    protected $firstParagraph; //the first Paragraph of the current Article
    protected $primaryImageOfPage; //the primary image of the current Article
    protected $objDomArticle; //DomDocument of the current Article
    
    /**
     * ArticleMeta of an Article
     * @var ArticleMeta
     */
    protected $objArticleMeta;

    /**
     * __construct
     *
     * @param htmlArticle:String
     * @param url:String
     * @param title:String
     * @param desciption:String
     * @param arrKeywords:String[]
     * @param md5:String
     * @param firstHeadline:String
     * @param firstParagraph:String
     * @param primaryImageOfpage:Srting
     * @param timeCreated:Integer
     */

    public function __construct(
            $htmlArticle,
            $url,
            $title,
            $description,
            $arrKeywords,
            $md5 = '',
            $firstHeadline = '',
            $firstParagraph = '',
            $primaryImageOfPage = '',
            $timeCreated = 0
    ) {

        try {
            //set the htmlArticle
            $this->setHtmlArticle($htmlArticle);

            //set the title
            $this->setTitle($title);

            //set the description
            $this->setDescription($description);
            
            //set keywords
            $this->setArrKeywords($arrKeywords);                    

            //set the url
            $this->setUrl($url);

            //set the timeCreated
            if($timeCreated !== 0)
            $this->setTimeCreated($timeCreated);

            //DomDocument of Article
            $this->objDomArticle = $this->getDOM($this->htmlArticle);

            //md5-Hash of Article
            if ($md5 == '')
                $this->setMd5(md5($this->htmlArticle));
            else
                $this->setMd5($md5);

            //first Headline
            if ($firstHeadline == '')
                $this->setFirstHeadline($this->extractFirstHeadline());
            else
                $this->setFirstHeadline($firstHeadline);

            //first Paragraph
            if ($firstParagraph == '')
                $this->setFirstParagraph($this->extractFirstParagraph());
            else
                $this->setFirstParagraph($firstParagraph);

            //Primary Image of Page
            if ($primaryImageOfPage == '')
                $this->setPrimaryImageOfPage(
                        $this->extractPrimaryImageOfPage());
            else
                $this->setPrimaryImageOfPage($primaryImageOfPage);

        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                    \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * extractPrimaryImageOfPage - return the src-Attribute of 
     * the primary image of page
     *
     * @return string
     */
    protected function extractPrimaryImageOfPage() {

        if (isset($this->objDomArticle) 
            && 
            ($this->objDomArticle instanceof \DOMDocument)) 
        {

            //get Figure-Element
            $domElementFigure = $this->objDomArticle->getElementById(
                    'primaryImageOfPage');

            //get img-element
            if (isset($domElementFigure) && $domElementFigure)
                $domElementImg = $domElementFigure->firstChild;

            //get src-Attribute
            if (isset($domElementImg) && $domElementImg) {
                
                //get src-Attribute in case of an img-element
                $domElementImgSrc = $domElementImg->getAttribute('src');
                
                //get href-Attribbute in case of an a-Element
                if (!$domElementImgSrc) {
                    $domElementImgSrc = $domElementImg->getAttribute('href');
                }
                
            }                    

            //return img
            if (isset($domElementImgSrc) && $domElementImgSrc) {
                
                //create image-Objecct to check if this is a valid image-file
                $objImage = new \HybridCMS\Modules\Image(substr(
                        HYB_ROOT, 0, -1) . $domElementImgSrc);
                    
                return $domElementImgSrc;
            }
                
        } else
            return '';
    }

    /**
     * extractFirstHeadline
     *
     * @return string
     */
    protected function extractFirstHeadline() {
        if (isset($this->objDomArticle) 
            && 
            ($this->objDomArticle instanceof \DOMDocument)) 
        {

            //get first h1-element
            $domElementHeadline = 
                    $this->objDomArticle->getElementsByTagName('h1');

            //if there is no h1, try to get h2
            if (!($domElementHeadline->item(0)))
                $domElementHeadline = 
                    $this->objDomArticle->getElementsByTagName('h2');

            //if there is no h1 and no h2 return
            if (!($domElementHeadline->item(0)))
                return '';

            //return nodeValue
            return $domElementHeadline->item(0)->nodeValue;
        }
    }

    /**
     * extractFirstParagraph
     *
     * @return string
     */
    protected function extractFirstParagraph() {
        if (isset($this->objDomArticle) 
            && 
            ($this->objDomArticle instanceof \DOMDocument)) 
    {

            //get first p element
            $domElementParagraph = 
                    $this->objDomArticle->getElementsByTagName('p');

            //if there is no p-tag, return
            if (!$domElementParagraph)
                return '';

            //return nodeValue
            return $domElementParagraph->item(0)->nodeValue;
        }
    }

    /**
     * getDOM
     *
     * @param html:String
     * @return DomDocument
     */
    protected function getDom($html) {

        try {

            $objDomDoc = new \DOMDocument();

            //Document Settings
            $objDomDoc->preserveWhiteSpace = false;
            $objDomDoc->strictErrorChecking = true;
            $objDomDoc->encoding = 'UTF-8';
            @$objDomDoc->loadHTML('<?xml encoding="UTF-8">' . $html);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }

        return $objDomDoc;
    }

    /**
     * getThumbOfPrimaryImage
     *
     * @param widthThumb:Integer
     * @param heightThumb:Integer
     * @return string
     */
    
    public function getThumbOfPrimaryImage(
            $widthThumb, $heightThumb = 0, $scale = true) 
    {

        //check if dimensions are valid (height can be 0 when scale is true)
        if (!is_numeric($widthThumb) 
            || 
            !is_numeric($heightThumb) 
            || 
            $widthThumb <= 0 
            || 
            ($heightThumb <= 0 && $scale === false)) 
        {
            throw new \Exception(
                    "Error Processing Request: getThumbOfPrimaryImage(),
                        dimensions of the thumb are not valid.", 1);
        }

        //check if image exists
        if ($this->primaryImageOfPage === '' 
            || 
            !file_exists(substr(HYB_ROOT, 0, -1) . $this->primaryImageOfPage)) 
        {

            //image does not exist
            return '';
        }

        try {
            //set path of the image
            $imagePath = substr(HYB_ROOT, 0, -1) . $this->primaryImageOfPage;

            $objImage = new \HybridCMS\Modules\Image($imagePath);
            
            if($scale) return $objImage->scale($widthThumb);
            else return $objImage->crop($widthThumb, $heightThumb);

        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                    \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setMd5
     *
     * @param md5:String
     * @return void
     */
    public function setMd5($md5) {
        if (!(strlen($md5) <= 32 && ctype_alnum($md5))) {
            throw new \Exception(
                    "Error Processing Request: setMd5(), md5 is not valid.", 1);
        }

        //set md5
        $this->md5 = $md5;
    }

    /**
     * setTimeCreated
     *
     * @param timeCreated:Integer
     *  @return void
     */
    public function setTimeCreated($timeCreated) {
        if (!is_numeric($timeCreated) || $timeCreated < 1361039557) {
            throw new \Exception(
                    "Error Processing Request: setTimeCreated(), "
                    . "timeCreated is not valid.", 1);
        }

        $this->timeCreated = $timeCreated;
    }

    /**
     * setFirstHeadline
     *
     * @param firstHeadline:String
     * @return void
     */
    public function setFirstHeadline($firstHeadline) {
        if (!is_string($firstHeadline)) {
            throw new \Exception(
                    "Error Processing Request: setFirstHeadline(), "
                    . "firstHeadline must be of type String.", 1);
        }

        $this->firstHeadline = $firstHeadline;
    }

    /**
     * setFirstParagraph
     *
     * @param firstParagraph:String
     * @return void
     */
    public function setFirstParagraph($firstParagraph) {
        if (!is_string($firstParagraph)) {
            throw new \Exception(
                    "Error Processing Request: setFirstParagraph(), "
                    . "firstParagraph must be of type String.", 1);
        }

        $this->firstParagraph = $firstParagraph;
    }

    /**
     * setTitle
     *
     * @param title:String
     * @return void
     */
    public function setTitle($title) {
        if (!is_string($title) || strlen($title) >= 180) {
            throw new \Exception(
                    "Error Processing Request: setTitle(), title must be a "
                    . "String with 180 Characters in maximum.", 1);
        }

        $this->title = $title;
    }

    /**
     * setDescription
     *
     * @param description:String
     * @return void
     */
    public function setDescription($description) {
        if (!is_string($description) && strlen($description) >= 200) {
            throw new \Exception(
                    "Error Processing Request: setDescription(), "
                    . "description must be a String with 200 "
                    . "Characters in maximum.", 1);
        }

        $this->description = $description;
    }
    
    /**
     * setArrKeywords - keywords from meta-tag keywords
     * 
     * @param String[] $arrKeywords
     * @throws \Exception
     */
    public function setArrKeywords($arrKeywords) {
        
        //check if arrKeywords is an array
        if(!is_array($arrKeywords)) {
            throw new \Exception(
                    "Error Processing Request: setArrKeywords(), "
                    . "arrKeywords must be an array.", 1);
        }
        
        $this->arrKeywords = $arrKeywords;
    }

    /**
     * setHtmlArticle
     *
     * @param htmlArticle:String
     * @return void
     */
    public function setHtmlArticle($htmlArticle) {
        if (!is_string($htmlArticle)) {
            throw new \Exception(
                    "Error Processing Request: setHtmlArticle(), "
                    . "htmlArticle must be a String.", 1);
        }

        $this->htmlArticle = $htmlArticle;
    }

    /**
     * setUrl
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

    /**
     * setPrimaryImageOfPage
     *
     * @param primaryImageOfPage:String
     * @return void
     */
    public function setPrimaryImageOfPage($primaryImageOfPage) {

        //remove possible url-parameters
        $arrTmp = explode('?', $primaryImageOfPage);
        $primaryImageOfPage = $arrTmp[0];

        //image-url is relative
        if (stripos($primaryImageOfPage, '://') === false) {
            $this->primaryImageOfPage = $primaryImageOfPage;
        }

        //image-url is absolute -> check domain
        else if (stripos($primaryImageOfPage, 
                HYB_PROTOCOL . HYB_HOST_NAME) === 0) 
        {

            //cut of protocol and hostname
            $arrTmp = explode('/', $primaryImageOfPage);
            $arrTmp = array_slice($primaryImageOfPage, 3);
            $this->primaryImageOfPage = implode('/', $primaryImageOfPage);
        } else {
            throw new \Exception(
                    "Error Processing Request: setPrimaryImageOfPage(), "
                    . "primaryImageOfPage is not valid.", 1);
        }
    }
    
    
    /**
     * setObjArticleMeta
     * @param \HybridCMS\Content\Article\ArticleMeta $objArticleMeta
     * @throws \Exception
     */
    public function setObjArticleMeta($objArticleMeta) 
    {
        //check if $objArticleMeta is of type ArticleMeta
        if(false === ($objArticleMeta instanceof 
                \HybridCMS\Content\Article\ArticleMeta)) 
        {
            throw new \Exception(
                    'Error Processing Request: setObjArticleMeta(),
                        $objArticleMeta is not an instance of ArticleMeta.', 1);
        }
        
        $this->objArticleMeta = $objArticleMeta;
    }

    /**
     * getter
     */
    public function getUrl() {
        return $this->url;
    }

    public function getMd5() {
        return $this->md5;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getFirstHeadline() {
        return $this->firstHeadline;
    }

    public function getFirstParagraph() {
        return $this->firstParagraph;
    }

    public function getPrimaryImageOfPAge() {
        return $this->primaryImageOfPage;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHtmlArticle() {
        return $this->htmlArticle;
    }
    
    public function getArrKeywords() {
        return $this->arrKeywords;
    }
    
    public function getObjArticleMeta() {
        return $this->objArticleMeta;
    }

}

//class end
?>