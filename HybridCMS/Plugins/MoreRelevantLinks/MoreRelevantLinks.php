<?php

namespace HybridCMS\Plugins\MoreRelevantLinks;

/**
 * class MoreRelevantLinks - Shows more interesting Articles to the user
 *
 * @package MoreRelevantLinks
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class MoreRelevantLinks extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * Attributes
     */
    private $searchTerm;
    private $numberOfResults;
    private $catName;
    private $objRelevantArticles;

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($searchTerm, $numberOfResults, $catName = null) {
        
        

        try {

            //call parent constructor
            parent::__construct();

            //set Attributes
            $this->setSearchTerm($searchTerm);
            $this->setNumberOfResults($numberOfResults);

            if (!empty($catName)) {
                $this->setCatName($catName);
            }

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get object of DBSection
            $dbSection = new \HybridCMS\Database\DBSection();

            //fetch Articles from Database
            if (isset($this->catName)) {
                $arrSearchResults = $dbSection->selectNArticlesByQueryStringAndCat(
                        $db, $this->searchTerm, $this->numberOfResults, $this->catName);

            } else {
                $arrSearchResults = $dbSection->selectNArticlesByQueryString(
                        $db, $this->searchTerm, $this->numberOfResults);
            }
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            $this->objRelevantArticles = new \HybridCMS\Content\Section\MoreRelevantLinks($arrSearchResults, $this->searchTerm);                            
            $this->objRelevantArticles->orderBy('queryStringsInContent');

            //Add CSSResource
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                    'bookmarks', //1
                    '/HybridCMS/Plugins/MoreRelevantLinks/css/f.css', //2
                    6, //3
                    false, //4
                    true //5
            );
            $this->addObjCSSResource($objCSSResource);
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setSearchTerm
     * @param Sring[] $arrTerms
     * @throws \Exception
     */
    private function setSearchTerm($searchTerm) {

        if (!preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s]+$/', $searchTerm)) {
            throw new \Exception(
            "Error Processing Request: setSearchTerm(),
                        searchTerm is not valid.", 1);
        }

        $this->searchTerm = $searchTerm;
    }

    /**
     * setNumberOfLinks
     * @param Integer $numberOfResults
     * @throws \Exception
     */
    private function setNumberOfResults($numberOfResults) {
        if (!is_numeric($numberOfResults) || $numberOfResults > 10) {
            throw new \Exception(
            "Error Processing Request: setNumberOfResults(),
                        numberOfResults has to be an integer lower than 10.", 1);
        }

        $this->numberOfResults = $numberOfResults;
    }

    /**
     * setCatName
     * @param String $catName
     * @throws \Exception
     */
    private function setCatName($catName) {
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $catName)) {
            throw new \Exception(
            "Error Processing Request: setCatName(),
                        catName is not valid.", 1);
        }

        $this->catName = $catName;
    }

    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {

        $arrObjArticles = $this->objRelevantArticles->getArrArticles();
        
        //return nothing if no articles are available
        if(empty($arrObjArticles)) return '';        

        //set hcms global                
        global $hcms;

        $op = '';
        
        //open div
        $op = '<div id="moreRelevantLinks" class="add-left-20 add-right-20 add-bottom-40 roundBorder">';

        //open aside
        $op .= '<aside>';

        $op .= '<h3 class="add-left-20 add-right-20 add-bottom-30">Das könnte Sie auch interessieren</h3>';

        foreach ($arrObjArticles as &$objArt) {
            
            //continue if this is the current article
            if($objArt->getUrl() === HYB_CURRURL) {
                continue;
            }

            //get img in 360px width
            $primImg = null;
            if ($objArt->getPrimaryImageOfPage()) {
                $primImg = $hcms->scaleImg($objArt->getPrimaryImageOfPage(), 660);
            }

            //build a-tag to the Article
            $articleAHrefBegin = '<a href="' . htmlentities($objArt->getUrl()) . '" title="' . htmlentities($objArt->getFirstHeadline()) . '">';
            $articleAHrefEnd = '</a>';

            //open article
            $op .= '<article class="c6">';

            //set headline
            $op .= '<header><h4>' . $articleAHrefBegin . htmlspecialchars($objArt->getFirstHeadline()) . $articleAHrefEnd . '</h4></header>';

            if (isset($primImg) && !empty($primImg)) {

                //open figure-tag
                $op .= '<figure>';

                //add image
                $op .= $articleAHrefBegin . '<img src="' . htmlentities($primImg) . '" alt="' . htmlentities($objArt->getFirstHeadline()) . ' width="660" />' . $articleAHrefEnd;

                //close figure tag
                $op .= '</figure>';

                //add description 
                $op .= '<p class="teaserText">' . htmlspecialchars($objArt->getDescription()) . $articleAHrefBegin . ' <i class="icon-angle-right color888"></i></a></p>';
            }

            //close article
            $op .= '</article>';
        }//end foreach
        
        //ad clearfix
        $op .= '<hr class="clear noborder">';
        
        //close aside
        $op .= '</aside>';
        
        //close div
        $op .= '</div>';

        return $op;
    }

//end toString
}

?>