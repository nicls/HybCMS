<?php

namespace HybridCMS\Content\Article;

/**
 * class SearchResult - This class represents a search result for a specific serch query
 *
 * @package Content\Article
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class SearchResult extends Article {

    /**
     * Attributes
     */
    private $queryStringsInContent; //indicates the number of query-Strings in content
    private $queryString; //indicates the query-String

    /**
     * __construct
     *
     * @param htmlArticle:String
     * @param url:String
     * @param title:String
     * @param description:String
     * @param md5:String
     * @param firstHeadline:String
     * @param firstParagraph:String
     * @param primaryImageOfPage:String
     * @param timeCreated:Integer
     * @param queryString:String
     */
    public function __construct(
            $htmlArticle,
            $url,
            $title,
            $description,
            $md5,
            $firstHeadline,
            $firstParagraph,
            $primaryImageOfPage,
            $timeCreated,
            $queryString) {

        //call parent constructor
        parent::__construct(
                $htmlArticle,
                $url,
                $title,
                $description,
                $md5,
                $firstHeadline,
                $firstParagraph,
                $primaryImageOfPage,
                $timeCreated);

        try {

            //set Query-Srting
            $this->setQueryString($queryString);

            //set Query-Srting in content
            $this->setQueryStringsInContent($this->calcQueryStringsInContent());
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * calcQueryStringsInContent
     *
     * @return Integer
     */
    private function calcQueryStringsInContent() {

        //count the number of query-Strings in content
        $cnt = substr_count(mb_convert_case($this->htmlArticle, MB_CASE_LOWER, "UTF-8"), $this->queryString);

        //return number of query-Strings in content
        return max(0, $cnt);
    }

    /**
     * setQueryString
     *
     * @param queryString:String
     * @return void
     */
    public function setQueryString($queryString) {
        $this->queryString = $queryString;
    }

    /**
     * setQueryStringsInContent
     *
     * @param cnt:String
     * @throws \Exception
     * @return void
     */
    public function setQueryStringsInContent($cnt) {

        //check if cnt is an integer and greater than 0
        if (is_int($cnt) && $cnt >= 0) {

            $this->queryStringsInContent = $cnt;

        } else {

            throw new \Exception(
                    "Error Processing Request: setQueryStringsInContent(), cnt is not valid.", 1);
        }
    }

    /**
     * getter
     */
    public function getQueryString() { return $this->queryString; }
    public function getQueryStringsInContent() { return $this->queryStringsInContent; }

}

//end class
?>