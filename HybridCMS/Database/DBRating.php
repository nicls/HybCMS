<?php

namespace HybridCMS\Database;

/**
 * class DBAuth
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBRating {

    /**
     *
     * @param mysqli $db
     * @param String $url
     * @return mixed[]
     * @throws \Exception
     */
    public function selectRatingDataByUrl($db, $url) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'SELECT maxRatingPoints, ratingsTotal, ratingScoreTotal FROM hyb_rating
                        JOIN hyb_article USING (articleId) WHERE url = ?';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            $stmt->bind_param('s', $url);
            $stmt->execute();
            $stmt->bind_result($maxRatingPoints, $ratingsTotal, $ratingScoreTotal);

            $arrRatingData = null;
            if ($stmt->fetch()) {
                $arrRatingData = array(
                    'maxRatingPoints' => $maxRatingPoints,
                    'ratingsTotal' => $ratingsTotal,
                    'ratingScoreTotal' => $ratingScoreTotal);
            }

            //close statement
            $stmt->close();

            return $arrRatingData;

        } catch (\Exception $e) {

            //close statement
            if($stmt) $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError( $e->__toString() . "\n" );

            throw $e;
        }
    }

    /**
     * insertRatingByArticleIdAndMaxRatingPoints
     *
     * @param mysqli $db
     * @param Integer $articleId
     * @param Integer $maxRatingPoints
     * @param float $ratingPoints
     * @return Integer
     * @throws \Exception
     */
    public function insertRatingByArticleIdAndMaxRatingPoints($db, $articleId, $maxRatingPoints, $ratingPoints) {

        //statement-Object
        $stmt = null;

        //set variable for the first rating
        $ratingsTotal = 1;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'INSERT INTO hyb_rating (articleId, maxRatingPoints, ratingsTotal, ratingScoreTotal) VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        ratingScoreTotal = ratingScoreTotal + ?,
                        ratingsTotal = ratingsTotal + 1';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            $stmt->bind_param('iiidd', $articleId, $maxRatingPoints, $ratingsTotal, $ratingPoints, $ratingPoints);

            $stmt->execute();

            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;

        } catch (\Exception $e) {

            //close statement
            if($stmt) $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError( $e->__toString() . "\n" );

            throw $e;
        }
    }
}

?>