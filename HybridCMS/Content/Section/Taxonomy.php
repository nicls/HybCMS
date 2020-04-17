<?php

namespace HybridCMS\Content\Section;

/**
 * class Taxonomy - This class represents all Articles for a keyword 
 *
 * @package Content\Section
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Taxonomy extends Section {

    private $keyword;

    /**
     * __construct
     * 
     * @param String $keyword
     * @throws \Exception
     */
    public function __construct($keyword, $withArticleMeta = false) {

        try {
            $this->setKeyword($keyword);

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //open Database-Connection
            $dbSection = new \HybridCMS\Database\DBSection();

            //fetch Articles from Database
            if(false === $withArticleMeta) 
            {
                $arrArticles = $dbSection->selectArticlesByKeyword($db, $this->keyword);
            }
            else 
            {
                $arrArticles = 
                        $dbSection->selectArticlesWithArticleMetaByKeyword(
                            $db, $this->keyword);
            }
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //call the parents constructor
            parent::__construct($arrArticles);
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
     * setKeyword
     *
     * @param keyword:String
     * @return void
     */
    protected function setKeyword($keyword) {
        if (!is_string($keyword) 
                || strlen($keyword) >= 100 
                || !preg_match('/^[a-zA-Z0-9\-_\.!:\?\s]+$/', $keyword)) {

            throw new \Exception(
            'Error Processing Request:
                        setCatName(), $keyword must be alphanumeric.', 1);
        }
        //assign $keyword
        $this->keyword = $keyword;
    }

    /**
     * getKeyword
     * @return string
     */
    public function getKeyword() {
        return $this->keyword;
    }

}

?>