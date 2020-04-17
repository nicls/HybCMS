<?php

namespace HybridCMS\Plugins\User\Database;

/**
 * class DBUserRegistered
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBUserRegistered
    extends \HybridCMS\Plugins\User\Database\DBUpdateUser
{

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {
        
    }

    /**
     * insertUser
     * 
     * @param mysqli $db
     * @param User $objUser
     * @return mixed[success:Boolean, insertId:Integer, verificationToken:String] 
     */
    public function insertUser($db, $objUser) {

        //statement-Object
        $stmt = null;

        try {
            
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
            $type = 'registered';
            
            
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
            
        } catch (\Exception $e) {

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
     * @return mixed[success:Boolean, insertId:Integer, verificationToken:String] 
     */
    public function insertUserRegistered($db, $objUser) {

        //statement-Object
        $stmt = null;

        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserRegistered(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $email = $objUser->getEmail();
            $hash = $objUser->getHash();
            $verificationToken = $objUser->getVerificationToken();
            
            //check if userId is set
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserRegistered(),                    
                        $userId is not a set.', 1);
            }            
            
            //check if email is set
            if(true === empty($email)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserRegistered(),                    
                        $email is not a set.', 1);
            }
            
            //check if hash isset
            if(true === empty($hash)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserRegistered(),                    
                        $hash is not a set.', 1);
            }
            
            //check if verificationToken isset
            if(true === empty($verificationToken)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserRegistered(),                    
                        $verificationToken is not a set.', 1);
            }            
            
            //get optional parameter
            $username = $objUser->getUsername();
            $firstname = $objUser->getFirstname();
            $lastname = $objUser->getLastname();
            $gender = $objUser->getGender();
            $twitterName = $objUser->getTwitterName();
            $facebookUrl = $objUser->getFacebookUrl();
            $googleplusId = $objUser->getGoogleplusId();
            $youtubeChannelName = $objUser->getYoutubeChannelName();
            $website = $objUser->getWebsite();
            $aboutme = $objUser->getAboutme();
            $isRegistered = 0;
                        
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_user_registered (
		userId,
                username,
                firstname,
                lastname,
                gender,
                hash,
                isRegistered,
                verificationToken,
                email,
                twitterName,
                facebookUrl,
                googleplusId,
                youtubeChannelName,
                website,
                aboutme) VALUES (?, ?, ? ,? ,? ,?, ?, ? ,? ,? ,? ,? ,? ,? ,?)';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('isssssissssssss', 
                    $userId,
                    $username,
                    $firstname,
                    $lastname,
                    $gender,
                    $hash,
                    $isRegistered,
                    $verificationToken,
                    $email,
                    $twitterName,
                    $facebookUrl,
                    $googleplusId,
                    $youtubeChannelName,
                    $website,
                    $aboutme);

            $success = $stmt->execute();
            
            //store primary-key of this Poll
            $insertId = $db->insert_id;            

            //close Resources
            $stmt->close();

            //return inside id and verification token
            return array(
                'success' => $success, 
                'insertId' => $insertId,
                'verificationToken' => $verificationToken
            );
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }
    
    /**
     * Checks if a give username exists in DB or not
     * @param mysqli $db
     * @param String $username
     * @return boolean
     * @throws \Exception
     */
    public function usernameExists($db, $username) {
        
        //statement-Object
        $stmt = null;
        
        //flag username exists
        $usernameExists = false;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT username FROM hyb_user_registered WHERE username = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($username);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $usernameExists = true;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $usernameExists;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }
    
    /**
     * Checks if a give email exists in DB or not
     * @param mysqli $db
     * @param String $email
     * @return boolean
     * @throws \Exception
     */
    public function emailExists($db, $email) {
        
        //statement-Object
        $stmt = null;
        
        //flag email exists
        $emailExists = false;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT email FROM hyb_user_registered WHERE email = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($email);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $emailExists = true;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $emailExists;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }    
    
    /**
     * Checks if a give email exists and the coresponding user is registered
     * @param mysqli $db
     * @param String $email
     * @return boolean
     * @throws \Exception
     */
    public function emailExistsAndUserIsRegistered($db, $email) {
        
        //statement-Object
        $stmt = null;
        
        //flag email exists
        $emailExists = false;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT email FROM hyb_user_registered WHERE email = ? AND isRegistered = 1';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($email);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $emailExists = true;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $emailExists;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }        
    
    /**
     * Checks if a give verificationToken exists in DB or not
     * @param mysqli $db
     * @param String $verificationToken
     * @return boolean
     * @throws \Exception
     */
    public function verificationTokenExists($db, $verificationToken) {
        
        //statement-Object
        $stmt = null;
        
        //flag username exists
        $verificationTokenExists = false;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT verificationToken FROM hyb_user_registered WHERE verificationToken = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $verificationToken);
            $stmt->execute();
            $stmt->bind_result($verificationToken);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $verificationTokenExists = true;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $verificationTokenExists;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }    
    
    /**
     * Select User by his/her Email
     * @param mysqli $db
     * @param String $email
     * @return User
     * @throws \Exception
     */
    public function selectUserByEmail($db, $mail) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            $objUser = null;

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT '
                    . 'userId, '
                    . 'type, '
                    . 'username, '
                    . 'firstname, '
                    . 'lastname, '
                    . 'gender, '
                    . 'hash, '
                    . 'timeCreated, '
                    . 'lastLogin, '
                    . 'isRegistered, '
                    . 'isOnline, '
                    . 'email, '
                    . 'twitterName, '
                    . 'facebookUrl, '
                    . 'googleplusId, '
                    . 'youtubeChannelName, '
                    . 'website, '
                    . 'aboutme '
                    . 'FROM hyb_user '
                    . 'JOIN hyb_user_registered '
                    . 'USING (userId) '
                    . 'WHERE email = ? '
                    . 'AND isRegistered = 1';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $mail);
            $stmt->execute();
            $stmt->bind_result(
                    $userId,
                    $type,
                    $username,
                    $firstname,
                    $lastname,
                    $gender,
                    $hash,
                    $timeCreated,
                    $lastLogin,
                    $isRegistered,
                    $isOnline,
                    $email,
                    $twitterName,
                    $facebookUrl,
                    $googleplusId,
                    $youtubeChannelName,
                    $website,
                    $aboutme);
            
            $stmt->bind_param('s', $email);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $objUser = \HybridCMS\Plugins\User\Model\UserFactory::create($type);
                $objUser->setEmail($email);
                $objUser->setUserId($userId);
                $objUser->setHash($hash);
                $objUser->setTimeCreated($timeCreated);
                $objUser->setIsRegistered((bool)$isRegistered);
                $objUser->setIsOnline((bool)$isOnline);
                
                if(false === empty($username))
                {
                    $objUser->setUsername($username);
                }
                
                if(false === empty($firstname))
                {
                    $objUser->setFirstname($firstname);   
                }     
                
                if(false === empty($lastname))
                {
                    $objUser->setLastname($lastname);
                }     
                
                if(false === empty($gender))
                {
                    $objUser->setGender($gender);
                }   
                
                if(false === empty($lastLogin))
                {
                    $objUser->setLastLogin($lastLogin);
                }
                
                if(false === empty($twitterName))
                {
                    $objUser->setTwitterName($twitterName);
                }  
                
                if(false === empty($facebookUrl))
                {
                    $objUser->setFacebookUrl($facebookUrl);
                }   
                
                if(false === empty($googleplusId))
                {
                    $objUser->setGoogleplusId($googleplusId);
                }    
                
                if(false === empty($youtubeChannelName))
                {
                   $objUser->setYoutubeChannelName($youtubeChannelName);
                }   
                
                if(false === empty($website))
                {
                    $objUser->setWebsite($website);
                }  
                
                if(false === empty($aboutme))
                {
                    $objUser->setAboutme($aboutme);
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

            $sql = 'SELECT '
                    . 'userId, '
                    . 'type, '
                    . 'username, '
                    . 'firstname, '
                    . 'lastname, '
                    . 'gender, '
                    . 'hash, '
                    . 'timeCreated, '
                    . 'lastLogin, '
                    . 'isRegistered, '
                    . 'isOnline, '
                    . 'email, '
                    . 'twitterName, '
                    . 'facebookUrl, '
                    . 'googleplusId, '
                    . 'youtubeChannelName, '
                    . 'website, '
                    . 'aboutme '
                    . 'FROM hyb_user '
                    . 'JOIN hyb_user_registered '
                    . 'USING (userId) '
                    . 'WHERE userId = ? '
                    . 'AND isRegistered = 1';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->bind_result(
                    $userId,
                    $type,
                    $username,
                    $firstname,
                    $lastname,
                    $gender,
                    $hash,
                    $timeCreated,
                    $lastLogin,
                    $isRegistered,
                    $isOnline,
                    $email,
                    $twitterName,
                    $facebookUrl,
                    $googleplusId,
                    $youtubeChannelName,
                    $website,
                    $aboutme);
            
            $stmt->bind_param('s', $email);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $objUser = \HybridCMS\Plugins\User\Model\UserFactory::create($type);
                $objUser->setEmail($email);
                $objUser->setUserId($userId);
                $objUser->setHash($hash);
                $objUser->setTimeCreated($timeCreated);
                $objUser->setIsRegistered((bool)$isRegistered);
                $objUser->setIsOnline((bool)$isOnline);
                
                if(false === empty($username))
                {
                    $objUser->setUsername($username);
                }
                
                if(false === empty($firstname))
                {
                    $objUser->setFirstname($firstname);   
                }     
                
                if(false === empty($lastname))
                {
                    $objUser->setLastname($lastname);
                }     
                
                if(false === empty($gender))
                {
                    $objUser->setGender($gender);
                }   
                
                if(false === empty($lastLogin))
                {
                    $objUser->setLastLogin($lastLogin);
                }
                
                if(false === empty($twitterName))
                {
                    $objUser->setTwitterName($twitterName);
                }  
                
                if(false === empty($facebookUrl))
                {
                    $objUser->setFacebookUrl($facebookUrl);
                }   
                
                if(false === empty($googleplusId))
                {
                    $objUser->setGoogleplusId($googleplusId);
                }    
                
                if(false === empty($youtubeChannelName))
                {
                   $objUser->setYoutubeChannelName($youtubeChannelName);
                }   
                
                if(false === empty($website))
                {
                    $objUser->setWebsite($website);
                }  
                
                if(false === empty($aboutme))
                {
                    $objUser->setAboutme($aboutme);
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
     * 
     * @param mysqli $db
     * @param String $verificationToken
     * @return Integer Affected Rows
     * @throws \Exception
     */
    public function deleteUserByVerificationToken($db, $verificationToken) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }            

            $sql = 'DELETE FROM hyb_user WHERE '
                    . 'userId IN '
                    . '('
                    . 'SELECT userId '
                    . 'FROM hyb_user_registered '                
                    . 'WHERE verificationToken = ? '
                    . 'AND isRegistered = 0'
                    . ')';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('s', $verificationToken);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }  
    
    /**
     * deleteUserByUserId does not delete the user but sets all userdata to null
     * except the username. the email is set to deletedUser-md5hash@HOSTNAME and the 
     * password is set to a random one
     * @param mysqli $db
     * @param String $verificationToken
     * @return Integer Affected Rows
     * @throws \Exception
     */
    public function deleteUserByUserId($db, $objUser) {

        //statement-Object
        $stmt = null;

        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof 
                    \HybridCMS\Plugins\User\Model\UserRegistered)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        deleteUserByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $email = md5(time() * mt_rand()) . '@' . HYB_HOST_NAME;
            $password = \HybridCMS\Helper\Helper::generateRandomPassword();
            $hash = $objUser->setPassword($password);
            
            
            //check if userId is set
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        deleteUserByUserId(),                    
                        $userId is not a set.', 1);
            }  
            
            //check if hash is set
            if(true === empty($hash)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        deleteUserByUserId(),                    
                        $hash is not a set.', 1);
            }             

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered '
                    . 'SET '
                    . 'isRegistered = 0, '
                    . 'firstname = null, '
                    . 'lastname = null, '
                    . 'gender = null, '
                    . 'hash = ?, '
                    . 'email = ?, '
                    . 'twitterName = null, '
                    . 'facebookUrl = null, '
                    . 'googleplusId = null, '
                    . 'youtubeChannelName = null, '
                    . 'website = null, '
                    . 'aboutme = null '
                    . 'WHERE userId = ? '
                    . 'AND isRegistered = 1';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ssi', $hash, $email, $userId);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }  
    
    
    /**
     * selectEmailNotConfirmedByVerificationToken
     * @param mysqli $db
     * @param String $verificationToken
     * @return boolean
     * @throws \Exception
     */
    public function selectEmailNotConfirmedByVerificationToken($db, $verificationToken) {
        
        //statement-Object
        $stmt = null;  
        
        $emailRet = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT emailNotConfirmed FROM hyb_user_registered WHERE verificationToken = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $verificationToken);
            $stmt->execute();
            $stmt->bind_result($emailNotConfirmed);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $emailRet = $emailNotConfirmed;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $emailRet;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }
    }      

}

?>
