<?php

namespace HybridCMS\Database;

/**
 * class DBArticleMeta
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBArticleMeta {
    
    
    /**
     * selectArticleMetaByArticleId
     * @param mysqli $db
     * @param Integer $articleId
     * @return array
     * @throws \Exception
     */
    public function selectArticleMetaByArticleId($db, $articleId) 
    {
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT metaKey, metaValue FROM hyb_article_meta WHERE articleId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('i', $articleId);
            $stmt->execute();
            $stmt->bind_result($key, $value);

            $arrArticleMeta = array();

            //fetch the articleMeta data
            while ($stmt->fetch()) {

                //add each ArticleMeta data
                $arrArticleMeta[$key] = $value;
            }

            //close Resources
            $stmt->close();
           
            //return all Comptables
            return $arrArticleMeta;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * insertArticleMeta
     * @param mysqli $db
     * @param String[] $arrArticleMeta
     * @param Integer $articleId
     * @return Integer[]
     * @throws \Exception
     */
    public function insertArticleMeta($db, $arrArticleMeta, $articleId) {

        //statement-Object
        $stmt = null;

        try {
            
            //hold the primarykeys of the articleMeta-Values
            $arrInsertIds = array();

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $sql = 'INSERT INTO hyb_article_meta ('
                    . 'articleId, '
                    . 'metaKey, '
                    . 'metaValue) '
                    . 'VALUES (?, ?, ?) '
                    . 'ON DUPLICATE KEY UPDATE metaValue = ?;';

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }
            
            //bind params
            $stmt->bind_param('isss', $articleId, $key, $value, $value);

            //insert all meta-values
            foreach ($arrArticleMeta as $key => $value) {
                $stmt->execute();
                
                //store primary-key of this ArticleMeta
                $arrInsertIds[] = $db->insert_id;                
            }
            
            //close Resources
            $stmt->close();

            return $arrInsertIds;

        } catch (\Exception $e) {

            //close statement
            if($stmt) $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError( $e->__toString() . "\n" );

            throw $e;
        }
    }
}

?>