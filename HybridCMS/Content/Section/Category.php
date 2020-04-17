<?php

namespace HybridCMS\Content\Section;

/**
 * class Category - This class represents a Category
 *
 * @package Content\Section
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Category extends Section {

    private $catName;

    /**
     * __construct
     *
     * @param catName:String
     */
    public function __construct($catName, $withArticleMeta = false) 
    {

        try {
            $this->setCatName($catName);

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->
                    getConnection();

            //open Database-Connection
            $dbSection = new \HybridCMS\Database\DBSection();

            //fetch Articles from Database
            if(false === $withArticleMeta) 
            {
                $arrArticles = $dbSection->selectArticlesByCategory(
                        $db, $this->catName);
            }
            else 
            {
                $arrArticles = 
                        $dbSection->selectArticlesWithArticleMetaByCategory(
                            $db, $this->catName);
            }
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //call the parents constructor
            parent::__construct($arrArticles);

        } 
        catch (\Exception $e) 
        {

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
     * setCatName
     *
     * @param catName:String
     * @return void
     */
    protected function setCatName($catName) {

        if(preg_match('/^[a-zA-Z0-9\-_]+$/', $catName)) {

            //assign catName
            $this->catName = $catName;

        } else {

            throw new \Exception(
                    "Error Processing Request:
                        setCatName(), catName must be alphanumeric.", 1);
        }
    }

    /**
     * getCatName
     * @return string
     */
    public function getCatName() { return $this->catName; }

}

?>