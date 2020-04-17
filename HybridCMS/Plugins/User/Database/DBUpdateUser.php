<?php

namespace HybridCMS\Plugins\User\Database;

/**
 * class DBUpdateUser
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBUpdateUser {

    /**
     * updateIsRegisteredByVerificationToken
     * @param mysqli $db
     * @param String $verificationToken
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateIsRegisteredByVerificationToken($db, $verificationToken) 
    {
        //statement-Object
        $stmt = null;
        
        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET isRegistered = 1, verificationToken = ? '
                    . 'WHERE verificationToken = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }
            
            $null = null;

            $stmt->bind_param('ss', $null, $verificationToken);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateVerificationToken
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateVerificationToken($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateVerificationTokenByEmail(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $email = $objUser->getEmail();
            $verificationToken = $objUser->getVerificationToken();
            
            //check if email is set
            if(true === empty($email)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateVerificationTokenByEmail(),                    
                        $email is not a set.', 1);
            }            
            
            //check if verificationToken isset
            if(true === empty($verificationToken)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateVerificationTokenByEmail(),                    
                        $verificationToken is not a set.', 1);
            }                

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET verificationToken = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND email = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ss', $verificationToken, $email);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateHash
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateHashByVerificationToken($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateHash(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $verificationToken = $objUser->getVerificationToken();
            $hash = $objUser->getHash();            
            
            //check if verificationToken isset
            if(true === empty($verificationToken)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateHash(),                    
                        $verificationToken is not a set.', 1);
            }                 
            
            //check if $hash isset
            if(true === empty($hash)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateHash(),                    
                        $hash is not a set.', 1);
            }                

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET hash = ?, verificationToken = NULL '
                    . 'WHERE isRegistered = 1 '
                    . 'AND verificationToken = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ss', $hash, $verificationToken);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateHash
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateHashByEmail($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateHashByEmail(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $email = $objUser->getEmail();
            $hash = $objUser->getHash();            
            
            //check if email isset
            if(true === empty($email)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateHashByEmail(),                    
                        $verificationToken is not a set.', 1);
            }                 
            
            //check if $hash isset
            if(true === empty($hash)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateHashByEmail(),                    
                        $hash is not a set.', 1);
            }                

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET hash = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND email = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ss', $hash, $email);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateEmailNotConfirmedByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateEmailNotConfirmedByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateEmailNotConfirmedByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $verificationToken = $objUser->getVerificationToken();
            $userId = $objUser->getUserId();
            $email = $objUser->getEmail();
            
            //check if verificationToken isset
            if(true === empty($verificationToken)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateEmailNotConfirmedByUserId(),                    
                        $verificationToken is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateEmailNotConfirmedByUserId(),                    
                        $userId is not a set.', 1);
            }   
            
            //check if email isset
            if(true === empty($email)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateEmailNotConfirmedByUserId(),                    
                        $email is not a set.', 1);
            }              

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'emailNotConfirmed = ?, '
                    . 'verificationToken = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ssi', $email, $verificationToken, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateUsernameByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateUsernameByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateUsernameByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $username = $objUser->getUsername();
            
            //check if username isset
            if(true === empty($username)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateUsernameByUserId(),                    
                        $username is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateUsernameByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'username = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $username, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateFirstnameByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateFirstnameByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateFirstnameByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $firstname = $objUser->getFirstname();
            
            //check if firstname isset
            if(true === empty($firstname)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateFirstnameByUserId(),                    
                        $firstname is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateFirstnameByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'firstname = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $firstname, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateLastnameByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateLastnameByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateLastnameByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $lastname = $objUser->getLastname();
            
            //check if lastname isset
            if(true === empty($lastname)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateLastnameByUserId(),                    
                        $lastname is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateLastnameByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'lastname = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $lastname, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateGenderByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateGenderByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateGenderByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $gender = $objUser->getGender();
            
            //check if gender isset
            if(true === empty($gender)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateGenderByUserId(),                    
                        $gender is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateGenderByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'gender = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $gender, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateTwitterNameByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateTwitterNameByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateTwitterNameByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $twitterName = $objUser->getTwitterName();
            
            //check if twitterName isset
            if(true === empty($twitterName)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateTwitterNameByUserId(),                    
                        $twitterName is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateTwitterNameByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'twitterName = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $twitterName, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateFacebookUrlByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateFacebookUrlByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateFacebookUrlByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $facebookUrl = $objUser->getFacebookUrl();
            
            //check if facebookUrl isset
            if(true === empty($facebookUrl)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateFacebookUrlByUserId(),                    
                        $facebookUrl is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateFacebookUrlByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'facebookUrl = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $facebookUrl, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateGoogleplusIdUrlByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateGoogleplusIdByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateGoogleplusIdByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $googleplusId = $objUser->getGoogleplusId();
            
            //check if googleplusId isset
            if(true === empty($googleplusId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateGoogleplusIdByUserId(),                    
                        $googleplusId is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateGoogleplusIdByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'googleplusId = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $googleplusId, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateYoutubeChannelNameByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateYoutubeChannelNameByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateYoutubeChannelNameByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $youtubeChannelName = $objUser->getYoutubeChannelName();
            
            //check if youtubeChannelName isset
            if(true === empty($youtubeChannelName)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateYoutubeChannelNameByUserId(),                    
                        $youtubeChannelName is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateYoutubeChannelNameByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'youtubeChannelName = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $youtubeChannelName, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateWebsiteByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateWebsiteByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateWebsiteByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $website = $objUser->getWebsite();
            
            //check if website isset
            if(true === empty($website)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateWebsiteByUserId(),                    
                        $website is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateWebsiteByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'website = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $website, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateAboutmeByUserId
     * @param mysqli $db
     * @param User $objUser
     * @return Integer Number of affected Rows
     * @throws \Exception
     */
    public function updateAboutmeByUserId($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateAboutmeByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
            $aboutme = $objUser->getAboutme();
            
            //check if aboutme isset
            if(true === empty($aboutme)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateAboutmeByUserId(),                    
                        $aboutme is not a set.', 1);
            }       
            
            //check if userId isset
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        updateAboutmeByUserId(),                    
                        $userId is not a set.', 1);
            }                         

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'aboutme = ? '
                    . 'WHERE isRegistered = 1 '
                    . 'AND userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('si', $aboutme, $userId);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;      
                      
            //close Resources
            $stmt->close();

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
     * updateEmailByVerificationToken
     * @param mysqli $db
     * @param String $verificationToken
     * @param String $emailNotConfirmed
     * @return Integer Affected Rows
     * @throws \Exception
     */
    public function updateEmailByVerificationToken(
            $db, 
            $verificationToken, 
            $emailNotConfirmed)
    {
        //statement-Object
        $stmt = null;
        
        try {
                        
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_registered SET '
                    . 'email = ?, '
                    . 'emailNotConfirmed = null, '
                    . 'verificationToken = null '
                    . 'WHERE isRegistered = 1 '
                    . 'AND verificationToken = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ss', $emailNotConfirmed, $verificationToken);
            
            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;  
                                  
            //close Resources
            $stmt->close();

            return $affectedRows;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }

            throw $e;
        }  
    }
}
