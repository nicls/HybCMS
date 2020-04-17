<?php

namespace HybridCMS\Plugins\Comments\Database;

/**
 * class Comment
 *
 * @package Comment
 * @version 2.0
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class DBComments
{
    
    /**
     * Select all published comment
     * @param mysqli $db
     * @return \HybridCMS\Plugins\Comments\Model\Comment[]
     */
    public function selectPublishedComments($db)
    {                   
        //statement-Object
        $stmt = null;
        
        //List of Comments
        $arrObjComments = array();
        
        try
        {
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT '
                    . 'hyb_comments.commentId, '
                    . 'hyb_comments.url, ' 
                    . 'hyb_comments.timeCreated, '
                    . 'hyb_comments.published, '
                    . 'hyb_comments.comment, '
                    . 'hyb_user.userId, '
                    . 'hyb_user.type '
                    . 'FROM hyb_comments '
                    . 'JOIN hyb_user USING (userId) '
                    . 'WHERE hyb_comments.published = 1 '
                    . 'ORDER BY hyb_comments.timeCreated DESC';
                 

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                    "Statement is not valid: " . htmlspecialchars($sql) . "\n"
                        . htmlspecialchars($db->error));
            }

            $stmt->execute();
            $stmt->bind_result(
                    $commentId,
                    $url,
                    $commentCreated,
                    $published,
                    $comment,
                    $userId,
                    $type
                    );
            
            //fetch the articles
            while (true === $stmt->fetch()) 
            {         
                //create user-object
                $$userId = \HybridCMS\Plugins\User\Model\UserFactory::
                        create($type);
                
                //set userId
                $$userId->setUserId($userId);
                
                //create comment object
                $$commentId = new \HybridCMS\Plugins\Comments\Model\Comment(
                    $comment, $$userId);
                
                $$commentId->setCommentId($commentId);
                $$commentId->setUrl($url);
                $$commentId->setTimeCreated($commentCreated);  
                $$commentId->setPublished((bool)$published);
                $$commentId->setComment($comment);
                
                //add comment to the list of comments
                $arrObjComments[] = $$commentId;
            }
            
            //close Resources
            $stmt->close();
            
            //select user by userId
            foreach($arrObjComments as &$objComment)
            {
                $objUser = $objComment->getObjUser();
                $userId = $objUser->getUserId();
                $type = $objUser->getType();
                $objDBUser = null;
                
                assert(false === empty($objUser));
                assert(false === empty($userId));
                assert(false === empty($type));
                
                if('openId' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserOpenId();
                }
                else if('registered' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserRegistered();
                }
                else if('unregistered' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserUnregistered();
                }                
                else
                {
                    throw new \Exception(
                        "Type of user is unknown: " . htmlspecialchars($type) 
                            . "\n" . htmlspecialchars($db->error));
                }
                
                $objComment->setObjUser($objDBUser->
                        selectUserByUserId($db, $userId));
            }
            
            //return 
            return $arrObjComments;  
        }
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }
    
    /**
     * Select all published comment for a given url
     * @param mysqli $db
     * @param String $url
     * @return \HybridCMS\Plugins\Comments\Model\Comment[]
     */
    public function selectPublishedCommentsByUrl($db, $url)
    {                   
        //statement-Object
        $stmt = null;
        
        //List of Comments
        $arrObjComments = array();
        
        try
        {
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT '
                    . 'hyb_comments.commentId, '
                    . 'hyb_comments.timeCreated, '
                    . 'hyb_comments.published, '
                    . 'hyb_comments.comment, '
                    . 'hyb_user.userId, '
                    . 'hyb_user.type '
                    . 'FROM hyb_comments '
                    . 'JOIN hyb_user USING (userId) '
                    . 'WHERE hyb_comments.published = 1 '                    
                    . 'AND hyb_comments.url = ?';
                 

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                    "Statement is not valid: " . htmlspecialchars($sql) . "\n"
                        . htmlspecialchars($db->error));
            }

            $stmt->bind_param('s', $url);
            $stmt->execute();
            $stmt->bind_result(
                    $commentId,
                    $commentCreated,
                    $published,
                    $comment,
                    $userId,
                    $type
                    );
            
            //fetch the articles
            while (true === $stmt->fetch()) 
            {         
                //create user-object
                $$userId = \HybridCMS\Plugins\User\Model\UserFactory::
                        create($type);
                
                //set userId
                $$userId->setUserId($userId);
                
                //create comment object
                $$commentId = new \HybridCMS\Plugins\Comments\Model\Comment(
                    $comment, $$userId);
                
                $$commentId->setCommentId($commentId);
                $$commentId->setUrl($url);
                $$commentId->setTimeCreated($commentCreated);  
                $$commentId->setPublished((bool)$published);
                $$commentId->setComment($comment);
                
                //add comment to the list of comments
                $arrObjComments[] = $$commentId;
            }
            
            //close Resources
            $stmt->close();
            
            //select user by userId
            foreach($arrObjComments as &$objComment)
            {
                $objUser = $objComment->getObjUser();
                $userId = $objUser->getUserId();
                $type = $objUser->getType();
                $objDBUser = null;
                
                assert(false === empty($objUser));
                assert(false === empty($userId));
                assert(false === empty($type));
                
                if('openId' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserOpenId();
                }
                else if('registered' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserRegistered();
                }
                else if('unregistered' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserUnregistered();
                }                
                else
                {
                    throw new \Exception(
                        "Type of user is unknown: " . htmlspecialchars($type) 
                            . "\n" . htmlspecialchars($db->error));
                }
                
                $objComment->setObjUser($objDBUser->
                        selectUserByUserId($db, $userId));
            }
            
            //return 
            return $arrObjComments;  
        }
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }
    
    /**
     * Select all unpublished comments
     * @param mysqli $db
     * @return \HybridCMS\Plugins\Comments\Model\Comment[]
     */
    public function selectUnpublishedComments($db)
    {                   
        //statement-Object
        $stmt = null;
        
        //List of Comments
        $arrObjComments = array();
        
        try
        {
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT '
                    . 'hyb_comments.commentId, '
                    . 'hyb_comments.url, ' 
                    . 'hyb_comments.timeCreated, '
                    . 'hyb_comments.published, '
                    . 'hyb_comments.comment, '
                    . 'hyb_user.userId, '
                    . 'hyb_user.type '
                    . 'FROM hyb_comments '
                    . 'JOIN hyb_user USING (userId) '
                    . 'WHERE hyb_comments.published = 0 '
                    . 'ORDER BY hyb_comments.timeCreated DESC';
                 

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                    "Statement is not valid: " . htmlspecialchars($sql) . "\n"
                        . htmlspecialchars($db->error));
            }

            $stmt->execute();
            $stmt->bind_result(
                    $commentId,
                    $url,
                    $commentCreated,
                    $published,
                    $comment,
                    $userId,
                    $type
                    );
            
            //fetch the articles
            while (true === $stmt->fetch()) 
            {         
                //create user-object
                $$userId = \HybridCMS\Plugins\User\Model\UserFactory::
                        create($type);
                
                //set userId
                $$userId->setUserId($userId);
                
                //create comment object
                $$commentId = new \HybridCMS\Plugins\Comments\Model\Comment(
                    $comment, $$userId);
                
                $$commentId->setCommentId($commentId);
                $$commentId->setUrl($url);
                $$commentId->setTimeCreated($commentCreated);  
                $$commentId->setPublished((bool)$published);
                $$commentId->setComment($comment);
                
                //add comment to the list of comments
                $arrObjComments[] = $$commentId;
            }
            
            //close Resources
            $stmt->close();
            
            //select user by userId
            foreach($arrObjComments as &$objComment)
            {
                $objUser = $objComment->getObjUser();
                $userId = $objUser->getUserId();
                $type = $objUser->getType();
                $objDBUser = null;
                
                assert(false === empty($objUser));
                assert(false === empty($userId));
                assert(false === empty($type));
                
                if('openId' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserOpenId();
                }
                else if('registered' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserRegistered();
                }
                else if('unregistered' === $type)
                {
                    $objDBUser = new \HybridCMS\Plugins\User
                            \Database\DBUserUnregistered();
                }                
                else
                {
                    throw new \Exception(
                        "Type of user is unknown: " . htmlspecialchars($type) 
                            . "\n" . htmlspecialchars($db->error));
                }
                
                $objComment->setObjUser($objDBUser->
                        selectUserByUserId($db, $userId));
            }
            
            //return 
            return $arrObjComments;  
        }
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }    
    
    /**
     * inserts a new Comment
     * @param mysqli $db
     * @param Comment $objComment
     * @return mixed[success:Boolean, insertId:Integer] 
     */
    public function insertComment($db, $objComment) {

        //statement-Object
        $stmt = null;

        try 
        {
            $objUser = $objComment->getObjUser();
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertComment(),                    
                        $objUser is not a instance of User.', 1);
            }                                           
            
            //get parameter   
            $userId = $objUser->getUserId();
            $url = $objComment->getUrl();
            $timeCreated = time();
            $published = 0;
            $comment = $objComment->getComment();
            
            //check if userId is valid
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertComment(), $userId is not valid.', 1);
            }     
            
            //check if url is valid
            if(true === empty($url)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertComment(), $url is not valid.', 1);
            }            
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_comments ('
                    . 'userId, '
                    . 'url, '
                    . 'timeCreated, '
                    . 'published, '
                    . 'comment) VALUES (?,?,?,?,?)';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                    "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('isiis', 
                    $userId, 
                    $url,
                    $timeCreated,
                    $published,
                    $comment);

            $success = $stmt->execute();
            
            //store primary-key of this User
            $insertId = $db->insert_id;            

            //close Resources
            $stmt->close();

            //return inside id and verification token
            return array(
                'success' => $success, 
                'insertId' => $insertId
            );
            
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }
    
    /**
     * Sets a unpublished Comment to published state
     * @param mysqli $db
     * @param Integer $commentId
     * @return Integer
     * @throws \Exception
     */
    public function updateCommentToPublishedById($db, $commentId)
    {
        //statement-Object
        $stmt = null;
        
        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comments SET published = 1 '
                    . 'WHERE published = 0 AND commentId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }
            
            $null = null;

            $stmt->bind_param('i', $commentId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

            return $affectedRows;
            
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        } 
    }
    
    /**
     * Sets a published Comment to unpublished state
     * @param mysqli $db
     * @param Integer $commentId
     * @return Integer
     * @throws \Exception
     */
    public function updateCommentToUnpublishedById($db, $commentId)
    {
        //statement-Object
        $stmt = null;
        
        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comments SET published = 0 '
                    . 'WHERE published = 1 AND commentId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }
            
            $null = null;

            $stmt->bind_param('i', $commentId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

            return $affectedRows;
            
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        } 
    }    
    
    /**
     * Deletes a comment by Id
     * @param mysqli $db
     * @param Integer $commentId
     * @return Integer Affected Rows
     * @throws \Exception
     */
    public function deleteCommentById($db, $commentId)
    {
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }            

            $sql = 'DELETE FROM hyb_comments WHERE commentId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('i', $commentId);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        } 
    }
}