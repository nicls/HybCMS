<?php

namespace HybridCMS\Content\Section;

/**
 * class RelevantArticles - This class represents a RelevantArticles
 *
 * @package Content\Section
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class RelevantArticles extends Section {

    /*
     * Attributes
     */
    protected $queryString;

    /**
     * __construct
     *
     * @param Article[] $arrArticles
     * @param String $queryString
     */
    public function __construct($arrArticles, $queryString) {
        try {

            parent::__construct($arrArticles);
            $this->setQueryString($queryString);

        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setQueryString
     *
     * @param String $queryString
     * @return void
     */
    public function setQueryString($queryString) {
        if(is_string($queryString)) {
            $this->queryString = trim($queryString);
        } else {

            throw new \Exception(
                    "Error Processing Request:
                        setQueryString(), queryString must be a String.", 1);
        }
    }

    /**
     * getQueryString
     *
     * @return String
     */
    public function getQueryString() { return $this->queryString; }

}

?>
