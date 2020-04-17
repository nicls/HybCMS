<?php

namespace HybridCMS\Content\Article;

/**
 * class ArticleMeta - This class represents Meta-Information of an Article
 *
 * @package Content\Article
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class ArticleMeta {

    /**
     * array of key/value pairs
     * @var String[] 
     */
    private $arrArticleMeta;

    /**
     * url of the current article
     * @var String
     */
    private $url;

    /**
     * __construct
     * 
     * @param String $url
     * @throws \Exception
     */
    public function __construct($url) {
        
        try {
            
            $this->setUrl($url);
            $this->arrArticleMeta = array();
            
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }        
    }

    /**
     * addArticleMeta
     * 
     * @param Atring $key
     * @param String $value
     * @throws \Exception
     */
    public function addArticleMeta($key, $value) {

        //check if key is alphanumeric
        if (false === is_string($key)) {
            throw new \Exception(
            "Error Processing Request: addArticleMeta(),
                        key must be an String.", 1);
        }

        //check if value is a string
        if (false === is_string($value)) {
            throw new \Exception(
            "Error Processing Request: addArticleMeta(),
                        value must be a String.", 1);
        }

        $this->arrArticleMeta[$key] = $value;
    }

    /**
     * getArticleMeta
     * 
     * @param String $key
     * @return String
     * @return null
     * @throws \Exception
     */
    public function getArticleMeta($key) {

        //check if key is alphanumeric
        if (false === is_string($key)) {
            throw new \Exception(
            "Error Processing Request: getArticleMeta(),
                        key must be an String.", 1);
        }

        //check if key exists
        if (isset($this->arrArticleMeta[$key])) 
        {
            return $this->arrArticleMeta[$key];
        } 
        else 
        {
            return null;
        }
    }
    
    /**
     * Return all Article Meta data
     * @return String[]
     */
    public function getArrArticleMeta() 
    {
        return $this->arrArticleMeta;
    }

    /**
     * synchronize
     * @param String $url
     * @throws \Exception
     */
    public function synchronize() {

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get ArticleId of the curernt Article
            $objDBArticle = new \HybridCMS\Database\DBArticle();
            $articleId = $objDBArticle->selectArticleIdByUrl($db, $this->url);

            //check if article of given url exists
            if ($articleId < 1) {
                throw new \Exception(
                'Error Processing Request: synchronize(),
                           $articleId is not valid.', 1);
            }
            
            //create articleMeta Database Object
            $objDBArticleMeta = new \HybridCMS\Database\DBArticleMeta();

            if(0 < count($this->arrArticleMeta))
            {
                //update Article Meta          
                $arrInsertIds = $objDBArticleMeta->insertArticleMeta(
                        $db, $this->arrArticleMeta, $articleId);
            }
            else
            {
                //get Metadata from database
                $this->arrArticleMeta = 
                        $objDBArticleMeta->selectArticleMetaByArticleId(
                                $db, $articleId);
                
            }

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setUrl
     * @param Srting $url
     * @throws \Exception
     */
    public function setUrl($url) {
        
        //check if url is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: synchronize(),
                           url is not valid.", 1);
        }

        //check if url is from the current host
        if (!stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) === 0) {
            throw new \Exception(
            "Error Processing Request: synchronize(),
                           url must be from the current host.", 1);
        }

        $this->url = $url;
    }
    
    /**
     * getUrl
     * @return String
     */
    public function getUrl() {
        return $this->url;
    }

}

?>