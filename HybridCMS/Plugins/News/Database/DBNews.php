<?php

namespace HybridCMS\Plugins\News\Database;

/**
 * class DBNews<
 *
 * @package News
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class DBNews {

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {}

    /**
     * insert a new NewsTeaser
     * 
     * @param mysqli $db
     * @param string $comptableName
     * @return boolean
     */
    public function insertNewsTeaser($db, $objNewsTeaser) 
    {
        
        if(false === ($objNewsTeaser instanceof 
                \HybridCMS\Plugins\News\NewsTeaser)) {
            $msg = '$objNewsTeaser' . " has to be of type NewsTeaser.";
            throw new \InvalidArgumentException($msg);
        }
        
        $title = $objNewsTeaser->getTitle();
        $url = $objNewsTeaser->getUrl();
        $text = $objNewsTeaser->getText();
        $objDate = $objNewsTeaser->getObjDate();
        $strDate = $objDate->format("Y-m-d");

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            $msg = "DB-Connection is not established.";
            if (!$db) { throw new \Exception($msg); }

            $sql = 'INSERT INTO hyb_news (
		title, url, text, date) VALUES (?,?,?,?)';


            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('ssss', $title, $url, $text, $strDate);

            $success = $stmt->execute();

            //close Resources
            $stmt->close();

            return $success;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) { $stmt->close(); }

            throw $e;
        }
    }

    /**
     * selectNArrObjNewsTeaserOrderedByDateDesc
     * @param mysqli $db
     * @param Integer $n
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function selectNArrObjNewsTeaserOrderedByDateDesc($db, $n) {

        //statement-Object
        $stmt = null;
        
        if(false === is_int($n)) {
            $msg = '$n has to be an Integer Value: ' . htmlentities($n);
            throw new \InvalidArgumentException($msg);
        }

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT newsId, title, url, text, date'
                    . ' FROM hyb_news ORDER BY date DESC LIMIT ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('i', $n);
            $stmt->execute();
            $stmt->bind_result($newsId, $title, $url, $text, $date);

            //array to hold the News Teaser
            $arrObjNewsTeaser = array();

            //fetch the articles
            while ($stmt->fetch()) {

                //add each Teaser
                array_push($arrObjNewsTeaser, 
                        new \HybridCMS\Plugins\News\NewsTeaser(                                
                                $title,
                                $url,
                                $text,
                                \DateTime::createFromFormat("Y-m-d H:i:s", $date),
                                $newsId)
                );
            }

            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrObjNewsTeaser;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) { $stmt->close(); }
            
            throw $e;
        }
    }

    /**
     * Delete NewsTeaser from DB by Primary Key
     *
     * @param mysqli $db
     * @param Integer $newsId
     * @return Integer - affected Rows
     * @throws \Exception
     */
    public function deleteNewsTeaser($db, $newsId) 
    {

        if(false === is_int($newsId)) {
            $msg = '$newsId has to be an Integer Value: ' . htmlentities($newsId);
            throw new \InvalidArgumentException($msg);
        }        
        
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'DELETE FROM hyb_news WHERE newsId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('i', $newsId);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) { $stmt->close(); }

            throw $e;
        }
    }

}

?>
