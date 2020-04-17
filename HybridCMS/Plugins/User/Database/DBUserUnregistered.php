<?php

namespace HybridCMS\Plugins\User\Database;

/**
 * class DBUserUnregistered
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class DBUserUnregistered
{

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {}

    
    /**
     * Select User by his/her id
     * @param mysqli $db
     * @param Integer $userId
     * @return User
     * @throws \Exception
     */
    public function selectUserByUserId($db, $userId) {
                        
        //statement-Object
        $stmt = null;
        
        try {
            
            $objUser = null;

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT 
                        hyb_user.userId, 
                        hyb_user.type, 
                        hyb_user.timeCreated, 
                        hyb_user.lastLogin, 
                        hyb_user.isOnline, 
                        hyb_user_unregistered.email, 
                        hyb_user_unregistered.username, 
                        hyb_user_unregistered.website
                    FROM hyb_user
                    JOIN hyb_user_unregistered
                    USING ( userId ) 
                    WHERE hyb_user.userId = ?';
            
            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                    "Statement is not valid: " . htmlspecialchars($sql) . "\n"
                        . htmlspecialchars($db->error));
            }

            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->bind_result(
                    $userId,
                    $type,
                    $timeCreated,        
                    $lastLogin,
                    $isOnline,  
                    $email,                       
                    $username,                 
                    $website);
            
            $stmt->bind_param('s', $userId);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $objUser = \HybridCMS\Plugins\User\Model\UserFactory::create($type);
                $objUser->setEmail($email);
                $objUser->setUserId($userId);
                $objUser->setTimeCreated($timeCreated);
                $objUser->setIsOnline((bool)$isOnline);
                
                if(false === empty($username))
                {
                    $objUser->setUsername($username);
                }   
                
                if(false === empty($lastLogin))
                {
                    $objUser->setLastLogin($lastLogin);
                }

                if(false === empty($website))
                {
                    $objUser->setWebsite($website);
                }                  
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $objUser;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }
            
            throw $e;
        }
    } 
    
    /**
     * insertUser
     * 
     * @param mysqli $db
     * @param User $objUser
     * @return mixed[success:Boolean, insertId:Integer] 
     */
    public function insertUser($db, $objUser) {

        //statement-Object
        $stmt = null;

        try 
        {
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUser(),                    
                        $objUser is not a instance of User.', 1);
            }                                           
            
            //get parameter
            $timeCreated = time();
            $type = 'unregistered';
            
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_user (type, timeCreated) VALUES (?,?)';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('si', $type, $timeCreated);

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
     * insertUserRegistered
     * 
     * @param mysqli $db
     * @param User $objUser
     * @return mixed[success:Boolean, insertId:Integer] 
     */
    public function insertUserUnregistered($db, $objUser) 
    {
        //statement-Object
        $stmt = null;

        try 
        {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof 
                    \HybridCMS\Plugins\User\Model\UserUnregistered)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserUnregistered(),                    
                        $objUser is not a instance of UserUnregistered.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $email = $objUser->getEmail();
            $username = $objUser->getUsername();
            
            //check if userId is set
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserUnregistered(),                    
                        $userId is not set.', 1);
            }            
            
            //check if email is set
            if(true === empty($email)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserUnregistered(),                    
                        $email is not a set.', 1);
            }
            
            //check if username isset
            if(true === empty($username)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserUnregistered(),                    
                        $username is not a set.', 1);
            }           
            
            //get optional parameter
            $website = $objUser->getWebsite();                        
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_user_unregistered (
		userId,
                username,
                email,
                website) VALUES (?, ?, ? ,?)';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('isss', 
                    $userId,
                    $username,
                    $email,
                    $website);

            $success = $stmt->execute();
            
            //store primary-key of this Poll
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
}

?>
