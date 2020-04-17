<?php

namespace HybridCMS\Plugins\Rating;

/**
 * class AjaxControllerRating - Handles API-Requests from the client
 * for the Rating-Plugin
 *
 * @package Rating
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerRating implements \HybridCMS\Ajax\IAjaxController {
    
    /**
     * indicates what to do
     * @var String
     */
    private $action;    

    /**
     * url - URL of the submitted rating
     * @var String
     */
    private $url;

    /**
     * maxRatingPoints - the maxiumum of rating point for the current url
     * @var type
     */
    private $maxRatingPoints;

    /**
     * ratingPoints - the rating points of the current submitted rating
     * @var type
     */
    private $ratingPoints;

    /**
     * __construct
     *
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    public function __construct($arrParams) {

        try {

            //check wether params are complete
            if (false === isset($arrParams['idBox'], 
                                $arrParams['rate'], 
                                $arrParams['maxRatingPoints'])) 
            {
                throw new \Exception(
                        "Error Processing Ajax-Request: Rating,
                                    Paramter are not valid.", 1);
            }

            $this->setUrl($arrParams['idBox']);
            $this->setMaxRatingPoints($arrParams['maxRatingPoints']);
            $this->setRatingPoints(floatval($arrParams['rate']));
            $this->setAction($arrParams['action']);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }

    }

    /**
     * handleAjaxRequest - inserts/updates Rating into Database
     *
     * @param mixed[] $arrParams
     * @return void
     * @throws \Exception
     */
    public function handleAjaxRequest() {

        try {
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $dbArticle = new \HybridCMS\Database\DBArticle();

            //select articleId of the current Rating
            $articleId = $dbArticle->selectArticleIdByUrl($db, $this->url);

            //check articleId
            if (false === isset($articleId) || $articleId <= 0) {
                throw new \Exception(
                        "Error Processing Ajax-Request " . htmlspecialchars($this->action) .
                        ", articleId of the current url is not valid: " . htmlspecialchars($this->url), 1);
            }

            //get object fpr db-Operations on table hyb_rating
            $dbRating = new \HybridCMS\Database\DBRating();

            //get maxRatingPoints from Database
            $arrRatingData = $dbRating->selectRatingDataByUrl($db, $this->url);

            //compare value of maxRatingPoints if rating exists in database
            if(isset($arrRatingData)) {
                if ($arrRatingData['maxRatingPoints'] != $this->maxRatingPoints) {
                    throw new \Exception(
                            "Error Processing Ajax-Request " . htmlspecialchars($this->action) .
                            ", maxRatingPoints is not equal to maxRatingPoints from Database.", 1);
                }
            }

            //insert on dublicate key update rating
            $affectedRows = $dbRating->insertRatingByArticleIdAndMaxRatingPoints(
                    $db,
                    $articleId,
                    $this->maxRatingPoints,
                    $this->ratingPoints);

            //check affected Rows
            if (false === isset($affectedRows) 
                || 
                (
                 $affectedRows != 1 
                 && 
                 $affectedRows != 2
                )
               ) 
            {
                throw new \Exception(
                        "Error Processing Ajax-Request " 
                        . htmlspecialchars($this->action) .
                        ", Updating RatingPoints failed.", 1);
            }

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
                        
        } catch (Exception $e) {

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
     *
     * @param String $url
     * @return void
     */
    private function setUrl($url) {

        //check if url is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
                "Error Processing Ajax-Request " . htmlspecialchars($this->action) .
                ", url is not valid.", 1);
        }

        //check if url is from the current host
        if (!stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) === 0) {
            throw new \Exception(
                "Error Processing Ajax-Request " . htmlspecialchars($this->action) .
                ", url is not from current host.", 1);
        }

        $this->url = $url;
    }

    /**
     * setMaxRatingPoints
     *
     * @param Integer $maxRatingPoints
     * @throws \Exception
     * @return void
     */
    private function setMaxRatingPoints($maxRatingPoints) {

        //check if maxRatingPoints is an Integer grater than 0
        if (!is_numeric($maxRatingPoints) && $maxRatingPoints > 0) {
            throw new \Exception(
                "Error Processing Ajax-Request " . htmlspecialchars($this->action) .
                ", maxRatingPoints must be an Integer greater than 0.", 1);
        }

        $this->maxRatingPoints = $maxRatingPoints;
    }

    /**
     * setRatingsTotal
     *
     * @param Integer $ratingsTotal
     * @throws \Exception
     * @return void
     */
    public function setRatingPoints($ratingPoints) {

        //validate ratingPoints
        if (!is_numeric($ratingPoints) || $ratingPoints > $this->maxRatingPoints) {
            throw new \Exception(
                    "Error Processing Ajax-Request " . htmlspecialchars($this->action) .
                    ", ratingPoints must be an Integer lower than maxRaringPoints.", 1);
        }

        $this->ratingPoints = $ratingPoints;
    }
    
    /**
     * setAction
     * @param String $action
     * @throws \Exception
     */
    private function setAction($action) {

        //check if action is an alphabetic String
        if (!ctype_alpha($action)) {

            throw new \Exception(
            "Error Processing Request: setAction(),
                       action must be alphanumeric.", 1);
        }

        $this->action = $action;
    }    

}//end class

?>
