<?php

namespace HybridCMS\Content\Section;

/**
 * class Search - This class represents a SearchResult
 *
 * @package Content\Section
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Search extends RelevantArticles {
    /*
     * Attributes
     */

    private $truncateLeft;
    private $truncateRight;

    /**
     * __construct
     *
     * @param String $queryString
     * @param Integer $truncateLeft
     * @param Integer $truncateRight
     */
    public function __construct($queryString, $truncateLeft = 100, $truncateRight = 100) {
        
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //open Database-Connection
            $dbSection = new \HybridCMS\Database\DBSection();

            //fetch Articles from Database
            $arrSearchResults = $dbSection->selectArticlesByQueryString($db, trim($queryString));

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            /** Parent Constructor */
            parent::__construct($arrSearchResults, $queryString);
            
            //order Results by queryStringsInContent
            $this->orderBy('queryStringsInContent');

            $this->setTruncateLeft($truncateLeft);
            $this->setTruncateRight($truncateRight);

            //strip and Mark content
            $this->stripContent($this->arrArticles);
            
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * stripContent
     *
     * @param SearchResult[] $arrSearchResults
     * @return void
     */
    public function stripContent(&$arrSearchResults) {

        //ckeck if $arrSearchResults is an array
        if (!is_array($arrSearchResults)) {
            throw new \Exception(
            "Error Processing Request:
                        stripContent(), arrSearchResults must be an array.", 1);
        }

        //check if array is of type SearchResult[]
        foreach ($arrSearchResults as &$objSearchResult) {
            if (!($objSearchResult instanceof \HybridCMS\Content\Article\SearchResult)) {
                throw new \Exception(
                "Error Processing Request:
                        stripContent(), arrSearchResults must be of type SearchResult[].", 1);
            }
        }

        try {
            //handle each SearchResult
            for ($i = 0; $i < count($arrSearchResults); $i++) {

                //remove HTML-Tags
                $contentTmp = strip_tags($arrSearchResults[$i]->getHtmlArticle());
                                           
                //getPosition of first match in content
                $posFirstMatch = strpos(mb_convert_case($contentTmp, MB_CASE_LOWER, "UTF-8"), mb_convert_case($this->queryString, MB_CASE_LOWER, "UTF-8"));
                
                //get the whole word at the beginning
                $j = 0;
                $stop = false;
                while (!$stop && ($posFirstMatch - $this->truncateLeft) > 0) {
                    
                    //check if index is not null
                    if(!isset($contentTmp[($posFirstMatch - $this->truncateLeft - $j)])) {
                        $stop = true;
                        break;
                    }
                    
                    $char = $contentTmp[($posFirstMatch - $this->truncateLeft - $j++)];
                    
                    //check if character at current position is an space-charakter
                    if(' ' == $char) {
                        $this->truncateLeft = ($this->truncateLeft + $j - 1);
                        $stop = true;
                    }
                }
                
                //truncate Content
                $contentTmp = substr(
                        $contentTmp, 
                        max(0, ($posFirstMatch - $this->truncateLeft)), 
                        $this->truncateRight + $this->truncateLeft
                );

                //mark queryStrings in Content
                $regex = "/(.|^)($this->queryString)(.|$)/i";
                $arrSearchResults[$i]->setHtmlArticle(preg_replace($regex, '${1}<span class=\'searchQuery\'>${2}</span>${3}', $contentTmp));
            
            }
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setTruncateLeft
     *
     * @param Integer $truncateLeft
     * @return void
     */
    private function setTruncateLeft($truncateLeft) {
        if (is_int($truncateLeft)) {

            //assign $truncateLeft
            $this->truncateLeft = $truncateLeft;
        } else {

            throw new \Exception(
            "Error Processing Request:
                        setTruncateLeft(), truncateLeft must be an Integer.", 1);
        }
    }

    /**
     * setTruncateRight
     *
     * @param Integer $truncateRight
     */
    private function setTruncateRight($truncateRight) {

        //check if $truncateRight is an Integer
        if (is_int($truncateRight)) {

            //assign $truncateRight
            $this->truncateRight = $truncateRight;
        } else {

            throw new \Exception(
            "Error Processing Request:
                        setTruncateRight(), truncateRight must be an Integer.", 1);
        }
    }

}

//end of class
?>
