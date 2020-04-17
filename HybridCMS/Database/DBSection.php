<?php

namespace HybridCMS\Database;

/**
 * class DBSection
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBSection {

    /**
     * selectArticlesByCategory
     *
     * @param String $catName
     * @return Article[]
     */
    public function selectArticlesByCategory($db, $catName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            //add slashes to catName to get exact matches
            $catName = "%/" . $catName . "/%";

            $sql = "SELECT
                articleId,
		url,
		htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE url LIKE ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            $stmt->bind_param('s', $catName);
            $stmt->execute();
            $stmt->bind_result(
                    $id,
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

            //array to hold the Articles fot this category
            $arrArticles = array();

            //fetch the articles
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);

                //add each Article to $arrArticles
                array_push($arrArticles, new \HybridCMS\Content\Article\Article(
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
                                )
                );
            }

            //close Resources
            $stmt->close();

            //return Articles of a Category
            return $arrArticles;

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
     * selectArticlesByCategory
     *
     * @param String $catName
     * @return Article[]
     */
    public function selectArticlesWithArticleMetaByCategory($db, $catName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            //add slashes to catName to get exact matches
            $catName = "%/" . $catName . "/%";

            $sql = "SELECT
                        hyb_article.articleId,
                        hyb_article.url,
                        hyb_article.htmlArticle,
                        hyb_article.title,
                        hyb_article.description,
                        hyb_article.keywords,
                        hyb_article.md5,
                        hyb_article.firstHeadline,
                        hyb_article.firstParagraph,
                        hyb_article.primaryImageOfPage,
                        hyb_article.timeCreated, 
                        hyb_article_meta.metakey,
                        hyb_article_meta.metaValue
                    FROM hyb_article 
                    LEFT JOIN hyb_article_meta USING(articleId) 
                    WHERE url LIKE ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql)))
            { 
                throw new \Exception("Statement is not valid.");             
            }

            $stmt->bind_param('s', $catName);
            $stmt->execute();
            $stmt->bind_result(
                    $id,
                    $url,
                    $htmlArticle,
                    $title,
                    $description,
                    $keywords,
                    $md5,
                    $firstHeadline,
                    $firstParagraph,
                    $primaryImageOfPage,
                    $timeCreated,
                    $metaKey,
                    $metaValue
            );

            //array to hold the Articles fot this category
            $arrArticles = array();
            $currArticleId = 0;
            $currObjArticleMeta = NULL;
            $currObjArticle = NULL;

            //fetch the articles
            while ($stmt->fetch()) 
            {                     
                if($currArticleId !== $id)
                {
                    $currArticleId = $id;                                       
                    
                    //add the previous Article to $arrArticles
                    if(false === empty($currObjArticle)) {
                        
                        if(false === empty($currObjArticleMeta)) {
                            $currObjArticle->setObjArticleMeta(
                                    $currObjArticleMeta);
                        }
                        
                        $arrArticles[] = $currObjArticle;
                    }
                            
                    //explode keywords to an array
                    $arrKeywords = explode(',', $keywords);
                    $currObjArticle =  
                            new \HybridCMS\Content\Article\Article(
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
                    
                    $currObjArticleMeta = 
                            new \HybridCMS\Content\Article\ArticleMeta($url);                    

                    if(false === empty($metaKey)) {
                        $currObjArticleMeta->addArticleMeta($metaKey, $metaValue);                  
                    }                                        
                } 
                else 
                {
                    //just add the ArticleMeta Data
                    $currObjArticleMeta->addArticleMeta($metaKey, $metaValue);  
                }
            }
            
            //add the last Article to $arrArticles
            if(false === empty($currObjArticle)) {

                if(false === empty($currObjArticleMeta)) {
                    $currObjArticle->setObjArticleMeta(
                            $currObjArticleMeta);
                }

                $arrArticles[] = $currObjArticle;
            }            

            //close Resources
            $stmt->close();

            //return Articles of a Category
            return $arrArticles;

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

    /**
     * selectLatestNArticleByCategory
     * @return array Articles
     */
    public function selectLatestNArticlesByCategory($db, $catName, $n) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            //add slashes to catName to get exact matches
            $catName = "%/" . $catName . "/%";

            $sql = "SELECT
		url,
		htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE url LIKE ? ORDER BY timeCreated DESC LIMIT ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            //bind params
            $stmt->bind_param('si', $catName, $n);
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

            //array to hold the Articles fot this category
            $arrArticles = array();

            //fetch all Articles
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);

                //add each Article to $arrArticles
                array_push($arrArticles, new \HybridCMS\Content\Article\Article(
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
                        )
                );
            }

            //close Resources
            $stmt->close();

            //retrun Articles as Category
            return $arrArticles;

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
     * selectLatestNRandomArticles
     * @return array Articles
     */
    public function selectLatestNRandomArticles($db, $catName, $n) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            //add slashes to catName to get exact matches
            $catName = "%/" . $catName . "/%";

            $sql = "SELECT
		url,
		htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE url LIKE ? ORDER BY RAND() DESC LIMIT ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

            //bind params
            $stmt->bind_param('si', $catName, $n);
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

            //array to hold the Articles fot this category
            $arrArticles = array();

            //fetch all Articles
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);

                //add each Article to $arrArticles
                array_push($arrArticles, new \HybridCMS\Content\Article\Article(
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
                        )
                );
            }

            //close Resources
            $stmt->close();

            //retrun Articles as Category
            return $arrArticles;

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
     * selectArticleByQueryString
     * @return array SearchResult
     */
    public function selectArticlesByQueryString($db, $queryString) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $queryStringLike = '%' . $queryString . '%';
            $sql = "SELECT
		url,
                htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE htmlArticle LIKE ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

                //bind params
                $stmt->bind_param('s', $queryStringLike);
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

            //array to hold the searchResults
            $arrSearchResults = array();

            //fetch all SearchResults
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);
                
                //put each SearchResult to $arrSearchResults
                array_push($arrSearchResults, new \HybridCMS\Content\Article\SearchResult(
                                $htmlArticle,
                                $url,
                                $title,
                                $description,
                                $arrKeywords,
                                $md5,
                                $firstHeadline,
                                $firstParagraph,
                                $primaryImageOfPage,
                                $timeCreated,
                                $queryString
                        )
                );
            }

            //close Resources
            $stmt->close();

            return $arrSearchResults;

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
     * selectNArticlesByQueryString
     * 
     * @param mysqli $db
     * @param String $queryString
     * @param Integer $numberOfResults
     * @return array
     * @throws \Exception
     */
    public function selectNArticlesByQueryString($db, $queryString, $numberOfResults) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $queryStringLike = '%' . $queryString . '%';
            $sql = "SELECT
		url,
                htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE htmlArticle LIKE ? LIMIT ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

                //bind params
                $stmt->bind_param('si', $queryStringLike, $numberOfResults);
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

            //array to hold the searchResults
            $arrSearchResults = array();

            //fetch all SearchResults
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);
                
                //put each SearchResult to $arrSearchResults
                array_push($arrSearchResults, new \HybridCMS\Content\Article\SearchResult(
                                $htmlArticle,
                                $url,
                                $title,
                                $description,
                                $arrKeywords,
                                $md5,
                                $firstHeadline,
                                $firstParagraph,
                                $primaryImageOfPage,
                                $timeCreated,
                                $queryString
                        )
                );
            }

            //close Resources
            $stmt->close();

            return $arrSearchResults;

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
     * selectNArticlesByQueryString
     * 
     * @param mysqli $db
     * @param String $queryString
     * @param Integer $numberOfResults
     * @return array
     * @throws \Exception
     */
    public function selectNArticlesByQueryStringAndCat($db, $queryString, $numberOfResults, $catName) {

        //statement-Object
        $stmt = null;
        
        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }
            
            //add slashes to catName to get exact matches
            $catName = "%/" . $catName . "/%";     
            
            //add slashes to queryString
            $queryStringLike = '%' . $queryString . '%';
            
            $sql = "SELECT
		url,
                htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE htmlArticle LIKE ? AND url LIKE ? LIMIT ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

                //bind params
                $stmt->bind_param('ssi', $queryStringLike, $catName, $numberOfResults);
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

            //array to hold the searchResults
            $arrSearchResults = array();

            //fetch all SearchResults
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);
                
                //put each SearchResult to $arrSearchResults
                array_push($arrSearchResults, new \HybridCMS\Content\Article\SearchResult(
                                $htmlArticle,
                                $url,
                                $title,
                                $description,
                                $arrKeywords,
                                $md5,
                                $firstHeadline,
                                $firstParagraph,
                                $primaryImageOfPage,
                                $timeCreated,
                                $queryString
                        )
                );
            }

            //close Resources
            $stmt->close();

            return $arrSearchResults;

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
     * selectArticlesByKeyword
     * 
     * @param mysqli $db
     * @param String $keyword
     * @return array
     * @throws \Exception
     */
    public function selectArticlesByKeyword($db, $keyword) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $keywordStringLike = '%' . $keyword . '%';
            $sql = "SELECT
		url,
                htmlArticle,
		title,
		description,
                keywords,
		md5,
		firstHeadline,
		firstParagraph,
		primaryImageOfPage,
		timeCreated FROM hyb_article WHERE keywords LIKE ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql))) { throw new \Exception("Statement is not valid."); }

                //bind params
                $stmt->bind_param('s', $keywordStringLike);
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

            //array to hold the searchResults
            $arrArticles = array();

            //fetch all SearchResults
            while ($stmt->fetch()) {
                
                //explode keywords to an array
                $arrKeywords = explode(',', $keywords);
                
                //put each SearchResult to $arrSearchResults
                array_push($arrArticles, new \HybridCMS\Content\Article\Article(
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
                        )
                );
            }

            //close Resources
            $stmt->close();

            return $arrArticles;

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
     * selectArticlesByKeyword
     * 
     * @param mysqli $db
     * @param String $keyword
     * @return array
     * @throws \Exception
     */
    public function selectArticlesWithArticleMetaByKeyword($db, $keyword) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if(!$db) { throw new \Exception("DB-Connection is not established."); }

            $keywordStringLike = '%' . $keyword . '%';
            $sql = "SELECT
                        hyb_article.articleId,
                        hyb_article.url,
                        hyb_article.htmlArticle,
                        hyb_article.title,
                        hyb_article.description,
                        hyb_article.keywords,
                        hyb_article.md5,
                        hyb_article.firstHeadline,
                        hyb_article.firstParagraph,
                        hyb_article.primaryImageOfPage,
                        hyb_article.timeCreated, 
                        hyb_article_meta.metakey,
                        hyb_article_meta.metaValue
                    FROM hyb_article 
                    LEFT JOIN hyb_article_meta USING(articleId) 
                    WHERE keywords LIKE ?";

            //check if statement is valid
            if(!($stmt = $db->prepare($sql)))
            { 
                throw new \Exception("Statement is not valid.");             
            }

            $stmt->bind_param('s', $keywordStringLike);
            $stmt->execute();
            $stmt->bind_result(
                    $id,
                    $url,
                    $htmlArticle,
                    $title,
                    $description,
                    $keywords,
                    $md5,
                    $firstHeadline,
                    $firstParagraph,
                    $primaryImageOfPage,
                    $timeCreated,
                    $metaKey,
                    $metaValue
            );

            //array to hold the Articles fot this category
            $arrArticles = array();
            $currArticleId = 0;
            $currObjArticleMeta = NULL;
            $currObjArticle = NULL;

            //fetch the articles
            while ($stmt->fetch()) 
            {                     
                if($currArticleId !== $id)
                {
                    $currArticleId = $id;                                       
                    
                    //add the previous Article to $arrArticles
                    if(false === empty($currObjArticle)) {
                        
                        if(false === empty($currObjArticleMeta)) {
                            $currObjArticle->setObjArticleMeta(
                                    $currObjArticleMeta);
                        }
                        
                        $arrArticles[] = $currObjArticle;
                    }
                            
                    //explode keywords to an array
                    $arrKeywords = explode(',', $keywords);
                    $currObjArticle =  
                            new \HybridCMS\Content\Article\Article(
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
                    
                    $currObjArticleMeta = 
                            new \HybridCMS\Content\Article\ArticleMeta($url);                    

                    if(false === empty($metaKey)) {
                        $currObjArticleMeta->addArticleMeta($metaKey, $metaValue);                  
                    }                                        
                } 
                else 
                {
                    //just add the ArticleMeta Data
                    $currObjArticleMeta->addArticleMeta($metaKey, $metaValue);  
                }
            }
            
            //add the last Article to $arrArticles
            if(false === empty($currObjArticle)) {

                if(false === empty($currObjArticleMeta)) {
                    $currObjArticle->setObjArticleMeta(
                            $currObjArticleMeta);
                }

                $arrArticles[] = $currObjArticle;
            }   

            //close Resources
            $stmt->close();

            return $arrArticles;

        } catch (\Exception $e) {

            //close statement
            if($stmt) $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError( $e->__toString() . "\n" );

            throw $e;
        }
    }        

}//end class
