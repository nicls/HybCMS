<?php

namespace HybridCMS\Database;

/**
 * class DBArticle
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBArticle {

    /**
     * selectArticleIdByUrl
     *
     * @param mysqli $db
     * @param String $url
     * @return Integer
     * @throws \Exception
     */
    public function selectArticleIdByUrl($db, $url) {
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'SELECT articleId FROM hyb_article WHERE url = ?';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            //bind parameter
            $stmt->bind_param('s', $url);
            $stmt->execute();
            $stmt->bind_result($articleId);

            //fetch result
            $stmt->fetch();

            //close Resources
            $stmt->close();

            return $articleId;

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
     * selectArticleByPageUrl
     *
     * @param msqli $db
     * @param String $url
     * @return Article
     */
    public function selectArticleByUrl($db, $url) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'SELECT
                url,
                htmlArticle,
                title,
                description,
                keywords,
                md5,
                firstHeadline,
                firstParagraph,
                primaryImageOfPage,
                timeCreated FROM hyb_article WHERE url = ?';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            //bind parameter
            $stmt->bind_param('s', $url);
            $stmt->execute();
            $stmt->bind_result(
                $url,
                $htmlArticle,
                $title,
                $description,
                $keywords,
                $md5,
                $firstHeadline,
                $firstParagraph,
                $primaryImageOfPage,
                $timeCreated
            );

            //variable that will be returned
            $objArticle = null;

            if ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);

                //create Article
                $objArticle = new \HybridCMS\Content\Article\Article(
                    $htmlArticle,
                    $url,
                    $title,
                    $description,
                    $arrKeywords,
                    $md5,
                    $firstHeadline,
                    $firstParagraph,
                    $primaryImageOfPage,
                    $timeCreated
                );
            }

           //close Resources
           $stmt->close();

           return $objArticle;

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
     * selectArticleByHeadline
     * 
     * @param mysqli $db
     * @param String $headline
     * @return Article
     * @throws \Exception
     */
    public function selectArticleByHeadline($db, $headline) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'SELECT
                url,
                htmlArticle,
                title,
                description,
                keywords,
                md5,
                firstHeadline,
                firstParagraph,
                primaryImageOfPage,
                timeCreated FROM hyb_article WHERE firstHeadline = ?';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            //bind parameter
            $stmt->bind_param('s', $headline);
            $stmt->execute();
            $stmt->bind_result(
                $url,
                $htmlArticle,
                $title,
                $description,
                $keywords,
                $md5,
                $firstHeadline,
                $firstParagraph,
                $primaryImageOfPage,
                $timeCreated
            );

            //variable that will be returned
            $objArticle = null;

            if ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);

                //create Article
                $objArticle = new \HybridCMS\Content\Article\Article(
                    $htmlArticle,
                    $url,
                    $title,
                    $description,
                    $arrKeywords,
                    $md5,
                    $firstHeadline,
                    $firstParagraph,
                    $primaryImageOfPage,
                    $timeCreated
                );
            }

           //close Resources
           $stmt->close(); 

           return $objArticle;

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
     * insertArticle
     *
     * @param mysqli $db
     * @param Article $objArticle
     * @return Integer - primaryKey
     */
    public function insertArticle($db, $objArticle) {
        
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'INSERT INTO hyb_article (
		url,
		htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid: " . htmlspecialchars($sql)); }

            $url = $objArticle->getUrl();
            $htmlArticle = $objArticle->getHtmlArticle();
            $title = $objArticle->getTitle();
            $description = $objArticle->getDescription();
            $keywords = implode(',', $objArticle->getArrKeywords());
            $md5 = $objArticle->getMd5();
            $firstHeadline = $objArticle->getFirstHeadline();
            $firstParagraph = $objArticle->getFirstParagraph();
            $primaryImageOfPage = $objArticle->getPrimaryImageOfPage();
            $timeCreated = time();

            $stmt->bind_param('sssssssssi',
                    $url,
                    $htmlArticle,
                    $title,
                    $description,
                    $keywords,
                    $md5,
                    $firstHeadline,
                    $firstParagraph,
                    $primaryImageOfPage,
                    $timeCreated
            );

            $stmt->execute();

            //store primary-key of this Article
            $insertId = $db->insert_id;

            //close Resources
            $stmt->close();

            return $insertId;

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
     * updateArticle
     *
     * @param mysqli $objArticle
     * @param Article $objArticle
     * @return Integer - affected Rows
     */
    public function updateArticle($db, $objArticle) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'UPDATE hyb_article SET
		htmlArticle = ?,
		title = ?,
		description = ?,
                keywords = ?,
		md5 = ?,
		firstHeadline = ?,
		firstParagraph = ? ,
		primaryImageOfPage = ? WHERE url = ?';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

                //assign variables
                $htmlArticle = $objArticle->getHtmlArticle();
                $title = $objArticle->getTitle();
                $description = $objArticle->getDescription();
                $keywords = implode(',', $objArticle->getArrKeywords());
                $md5 = $objArticle->getMd5();
                $firstHeadline = $objArticle->getFirstHeadline();
                $firstParagraph = $objArticle->getFirstParagraph();
                $primaryImageOfPage = $objArticle->getPrimaryImageOfPAge();
                $url = $objArticle->getUrl();
                                    
            $stmt->bind_param('sssssssss',
                $htmlArticle,
                $title,
                $description,
                $keywords,
                $md5,
                $firstHeadline,
                $firstParagraph,
                $primaryImageOfPage,
                $url
            );

            $stmt->execute();

            //get number of updated Rows
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

}//class end
?>
