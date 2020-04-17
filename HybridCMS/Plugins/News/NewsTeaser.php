<?php

namespace HybridCMS\Plugins\News;

/**
 * class NewsTeaser
 *
 * @package NewsTeaser
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class NewsTeaser
{

    /**
     * Primary Key
     * @var Integer
     */
    private $newsId;
    
    /**
     * Title of the News
     * @var String
     */
    private $title;

    /**
     * Url of the Original News-Article
     * @var String
     */
    private $url;
    
    /**
     * Teasertext of the News-Article
     * @var String 
     */
    private $text;
    
    /**
     * Publishing date of the original Article.
     * @var Datetime dd-mm-YYYY
     */
    private $objDate;

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($title, $url, $text, $objDate, $newsId = null) 
    {
        if(false === empty($newsId)) {
            $this->setNewsId($newsId);
        }
        $this->setTitle($title);
        $this->setUrl($url);
        $this->setText($text);
        $this->setObjDate($objDate);
    }
    
    public function getNewsId() { return $this->newsId; }
    public function getTitle() { return $this->title; }
    public function getUrl() { return $this->url; }
    public function getText() { return $this->text; }    
    public function getObjDate() { return $this->objDate; }
    
    /**
     * setter newsId
     * @param Integer $newsId
     * @throws \InvalidArgumentException
     */
    public function setNewsId($newsId) 
    {    
        if(false === is_int($newsId)) {
            $msg = "newsId has to be an Integer.";
            throw new \InvalidArgumentException($msg);
        }
        
        if(0 > $newsId) {
            $msg = "newsId must not be greater than 0.";
            throw new \InvalidArgumentException($msg);            
        }
        $this->newsId = $newsId;
    }          

    /**
     * setter Title
     * @param String $title
     * @throws \InvalidArgumentException
     */
    public function setTitle($title) 
    {    
        if(false === is_string($title)) {
            $msg = "Title has to be a String.";
            throw new \InvalidArgumentException($msg);
        }
        
        if(1000 < strlen($title)) {
            $msg = "Title must not be longer than 1000 Characters.";
            throw new \InvalidArgumentException($msg);            
        }
        $this->title = $title;
    }

    /**
     * Setter Url
     * @param String $url
     * @throws \InvalidArgumentException
     */
    public function setUrl($url) 
    {        
        $isValid = \HybridCMS\Modules\Url\Url::isValidUrl($url);
        if(false === $isValid) {
            $msg = "Url is not valid: ". htmlspecialchars($url);
            throw new \InvalidArgumentException($msg);
        }      
        
        $this->url = $url;
    }

    /**
     * Setter TeaserText
     * @param String $text
     * @throws \InvalidArgumentException
     */
    public function setText($text) 
    {
        if(false === is_string($text)) {
            $msg = "Text has to be a String.";
            throw new \InvalidArgumentException($msg);
        }
        
        if(1001 <= strlen($text)) {
            $msg = "Text must not be longer than 1000 Characters.";
            throw new \InvalidArgumentException($msg);            
        }        
        $this->text = $text;
    }
    
    /**
     * Setter Date of publishing
     * @param Datetime $objDate
     * @throws \InvalidArgumentException
     */
    public function setObjDate($objDate) 
    {
        if(false === ($objDate instanceof \DateTime)) {
            $msg = "Date has to be of type Datetime.";
            throw new \InvalidArgumentException($msg);
        }
               
        $this->objDate = $objDate;
    }        
    
    /**
     * Returns this as Array
     * @return array
     */
    public function toArray() {
        
        return [
            'newsId' => $this->newsId,
            'title' => $this->title,
            'url' => $this->url,
            'text' => $this->text,
            'date' => $this->objDate->format('d-m-Y')
        ];
    }
}

?>
