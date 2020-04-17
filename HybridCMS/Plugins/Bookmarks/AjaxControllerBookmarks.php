<?php

namespace HybridCMS\Plugins\Bookmarks;

/**
 * class AjaxControllerBookmarks - Handles API-Requests from the client
 * for the Bookmarks-Plugin
 *
 * @package Bookmarks
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerBookmarks implements \HybridCMS\Ajax\IAjaxController {

    /**
     * headlien of an article
     * @var String
     */
    private $headline;

    /**
     * width of the thumb of the primaryImage of page
     * @var Integer
     */
    private $widthThumb;

    /**
     * height of the thumb of the primaryImageOfPage
     * @var Integer
     */
    private $heightThumb;

    /**
     * __construct
     *
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    public function __construct($arrParams) {

        try {

            //check if headline was submitted by the client
            if (!isset($arrParams['headline'])) 
            {
                throw new \Exception(
                "Error Processing Request: handleAjaxRequest(),
                            'headline is missing.'", 1);
            }
            //assign headline
            $this->setHeadline($arrParams['headline']);

            //check if width of thumb was submitted by the client
            if (isset($arrParams['widthThumb'])) 
            {
                //assign width of thumb
                $this->setWidthThumb($arrParams['widthThumb']);

                //assign height of thumb
                if (isset($arrParams['heightThumb'])) 
                {
                    $this->setHeightThumb($arrParams['heightThumb']);
                }
            } 
            else 
            {
                throw new \Exception(
                "Error Processing Request: handleAjaxRequest(),
                            'widthThumb is missing.'", 1);
            }
        } 
        catch (Exception $e) 
        {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * handleAjaxRequest
     */
    public function handleAjaxRequest() {

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBArticle = new \HybridCMS\Database\DBArticle();

            //selectArticle by headline
            $objArticle = $objDBArticle->selectArticleByHeadline($db, $this->headline);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            $json = '{}';

            //check if Article was in Database
            if (isset($objArticle)) {
                //serialise Article as json-String
                $json = $this->buildBookmark($objArticle);
            }

            //set header to json
            header('Content-Type: text/javascript; charset=utf8');

            echo $json;

            //return Article as json to the client
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setHeadline
     * 
     * @param String $headline
     * @throws \Exception
     */
    private function setHeadline($headline) {

        //check if headline is valid
        if (strlen($headline) > 160) {
            throw new \Exception(
            "Error Processing Request: setHeadline(),
                        'headline must be a String with 160 characters 
                        in maximum.'", 1);
        }

        $this->headline = $headline;
    }

    /**
     * setWidthThumb
     * 
     * @param Integer $widthThumb
     * @throws \Exception
     */
    private function setWidthThumb($widthThumb) {

        //check if $widthThumb is an valid integer
        if (!is_numeric($widthThumb) || $widthThumb < 1 || $widthThumb > 3000) {
            throw new \Exception(
            "Error Processing Request: setWidthThumb(),
                        '$widthThumb must be an Integer between 1 and 3000.'", 1);
        }

        $this->widthThumb = $widthThumb;
    }

    /**
     * setHeightThumb
     * 
     * @param Integer $heightThumb
     * @throws \Exception
     */
    private function setHeightThumb($heightThumb) {

        //check if $widthThumb is an valid integer
        if (!is_numeric($heightThumb) || $heightThumb < 0 || $heightThumb > 3000) {
            throw new \Exception(
            "Error Processing Request: setHeightThumb(),
                        '$heightThumb must be an Integer between 0 and 3000.'", 1);
        }

        $this->heightThumb = $heightThumb;
    }

    /**
     * buildBookmark
     * @param Article $objArticle
     * @return String
     */
    private function buildBookmark(&$objArticle) {

        try {
            $arrBookmark = array();

            //assign description
            $arrBookmark['url'] = $objArticle->getDescription();

            //assign headline
            $arrBookmark['firstHeadline'] = $objArticle->getFirstHeadline();

            //assign firstParagraph
            $arrBookmark['firstParagraph'] = $objArticle->getFirstParagraph();

            //check if there is an primaryIamgeOfPage
            if ($objArticle->getPrimaryImageOfPage()) {

                //assign primaryImageOfPage
                if (isset($this->heightThumb) && $this->heightThumb > 0) {

                    //get scropped thumb
                    $arrBookmark['thumbOfPrimaryImage'] = $objArticle->getThumbOfPrimaryImage($this->widthThumb, $this->heightThumb, false);
                } else {

                    //get scaled thumb
                    $arrBookmark['thumbOfPrimaryImage'] = $objArticle->getThumbOfPrimaryImage($this->widthThumb);
                }
            } else {

                //use the default image if there is no primaryImageOfPage
                $arrBookmark['thumbOfPrimaryImage'] = HYB_PLUGINPATH . 'Bookmarks/images/default-thumb.jpg';
            }

            //assign url of the article
            $arrBookmark['url'] = $objArticle->getUrl();

            return json_encode($arrBookmark);
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

}

?>