<?php

namespace HybridCMS\Plugins\User\Database;

/**
 * class DBUserOpenId
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBUserOpenId 
{
    /**
     * Checks if a given user exists in DB or not
     * @param mysqli $db
     * @param String $email
     * @return boolean
     * @throws \Exception
     */
    public function userExists($db, $objUser) {
        
        //statement-Object
        $stmt = null;
        
        //flag email exists
        $arrRet['userExists'] = false;
        $arrRet['userId'] = null;

        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\UserOpenId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUser(),                    
                        $objUser is not a instance of User.', 1);
            }       
            
            $issuer = $objUser->getIssuer();
            $identifier = $objUser->getIdentifier();
            
            //check if issuer is valid
            if(true === empty($issuer)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        userExists(),                    
                        $issuer is not set.', 1);
            }   
            
            //check if identifier is valid
            if(true === empty($identifier)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        userExists(),                    
                        $identifier is not set.', 1);
            }             

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT userId '
                    . 'FROM hyb_user_openId '
                    . 'WHERE identifier = ? '
                    . 'AND issuer = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('ss', $identifier, $issuer);
            $stmt->execute();
            $stmt->bind_result($userId);

            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $arrRet['userExists'] = true;
                $arrRet['userId'] = $userId;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $arrRet;
            
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
     * @return mixed[success:Boolean, insertId:Integer, verificationToken:String] 
     */
    public function insertUser($db, $objUser) {

        //statement-Object
        $stmt = null;

        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\UserOpenId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUser(),                    
                        $objUser is not a instance of UserOpenId.', 1);
            }                                           
            
            //get parameter
            $timeCreated = time();
            $type = $objUser->getType();
            
            //check if type is set
            if(true === empty($type)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUser(),                    
                        $type is not a set.', 1);
            }                         
            
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
     * @return Boolean 
     */
    public function insertUserOpenId($db, $objUser) {

        //statement-Object
        $stmt = null;

        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $identifier = $objUser->getIdentifier();
            $userId = $objUser->getUserId();            
            $issuer = $objUser->getIssuer();
            
            //check if userId is set
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        $userId is not a set.', 1);
            }   
            
            //check if idetifier is set
            if(true === empty($identifier)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        idetifier is not a set.', 1);
            }        
            
            //check if issuer is set
            if(true === empty($issuer)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        issuer is not a set.', 1);
            }            
            
            //get optional parameter
            $profileURL = $objUser->getProfileURL();
            $photoURL = $objUser->getPhotoURL();
            $webSiteURL = $objUser->getWebsite();
            $displayName = $objUser->getUsername();
            $description = $objUser->getAboutme();
            $firstName = $objUser->getFirstname();
            $lastName = $objUser->getLastname();
            $gender = $objUser->getGender();
            $language = $objUser->getLanguage();
            $age = $objUser->getAge();
            $birthDay = $objUser->getBirthDay();
            $birthMonth = $objUser->getBirthMonth();
            $birthYear = $objUser->getBirthYear();
            $email = $objUser->getEmail();
            $emailVerified = $objUser->getEmailVerified();
            $phone = $objUser->getPhone();
            $address = $objUser->getAddress();
            $country = $objUser->getCountry();
            $region = $objUser->getRegion();
            $city = $objUser->getCity();
            $zip = $objUser->getZip();       
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_user_openId (
		identifier,
                issuer,
                userId,
                profileURL,
                webSiteURL,
                photoURL,
                displayName,
                description,
                firstName,
                lastName,
                gender,
                language,
                age,
                birthDay,
                birthMonth,
                birthYear,
                email,
                emailVerified,
                phone,
                address,
                country,
                region,
                city,
                zip) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('ssisssssssssiiiisssssssi', 
                    $identifier,
                    $issuer,
                    $userId,
                    $profileURL,
                    $webSiteURL,
                    $photoURL,
                    $displayName,
                    $description,
                    $firstName,
                    $lastName,
                    $gender,
                    $language,
                    $age,
                    $birthDay,
                    $birthMonth,
                    $birthYear,
                    $email,
                    $emailVerified,
                    $phone,
                    $address,
                    $country,
                    $region,
                    $city,
                    $zip);

            $success = $stmt->execute();                   

            //close Resources
            $stmt->close();

            return $success;
            
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
     * @return Boolean 
     */
    public function updateUserOpenId($db, $objUser) {

        //statement-Object
        $stmt = null;

        try {
            
            //check if $objUser is valid
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $identifier = $objUser->getIdentifier();           
            $issuer = $objUser->getIssuer();
                        
            //check if idetifier is set
            if(true === empty($identifier)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        idetifier is not a set.', 1);
            }        
            
            //check if issuer is set
            if(true === empty($issuer)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertUserOpeId(),                    
                        issuer is not a set.', 1);
            }            
            
            //get optional parameter
            $profileURL = $objUser->getProfileURL();
            $photoURL = $objUser->getPhotoURL();
            $webSiteURL = $objUser->getWebsite();
            $displayName = $objUser->getUsername();
            $description = $objUser->getAboutme();
            $firstName = $objUser->getFirstname();
            $lastName = $objUser->getLastname();
            $gender = $objUser->getGender();
            $language = $objUser->getLanguage();
            $age = $objUser->getAge();
            $birthDay = $objUser->getBirthDay();
            $birthMonth = $objUser->getBirthMonth();
            $birthYear = $objUser->getBirthYear();
            $email = $objUser->getEmail();
            $emailVerified = $objUser->getEmailVerified();
            $phone = $objUser->getPhone();
            $address = $objUser->getAddress();
            $country = $objUser->getCountry();
            $region = $objUser->getRegion();
            $city = $objUser->getCity();
            $zip = $objUser->getZip();       
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_openId SET 
                profileURL = ?,
                webSiteURL = ?,
                photoURL = ?,
                displayName = ?,
                description = ?,
                firstName = ?,
                lastName = ?,
                gender = ?,
                language = ?,
                age = ?,
                birthDay = ?,
                birthMonth = ?,
                birthYear = ?,
                email = ?,
                emailVerified = ?,
                phone = ?,
                address = ?,
                country = ?,
                region = ?,
                city = ?,
                zip = ? 
                WHERE identifier = ? AND issuer = ?';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('sssssssssiiiisssssssiss', 
                    $profileURL,
                    $webSiteURL,
                    $photoURL,
                    $displayName,
                    $description,
                    $firstName,
                    $lastName,
                    $gender,
                    $language,
                    $age,
                    $birthDay,
                    $birthMonth,
                    $birthYear,
                    $email,
                    $emailVerified,
                    $phone,
                    $address,
                    $country,
                    $region,
                    $city,
                    $zip,
                    $identifier,
                    $issuer);

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
            if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\UserOpenId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        deleteUserByUserId(),                    
                        $objUser is not a instance of User.', 1);
            }
            
            //get must have parameter
            $userId = $objUser->getUserId();
              
            //check if userId is set
            if(true === empty($userId)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        deleteUserByUserId(),                    
                        $userId is not a set.', 1);
            }                        

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_user_openId '
                    . 'SET '
                    . 'profileURL = null, '
                    . 'webSiteURL = null, '
                    . 'photoURL = null, '
                    . 'description = null, '
                    . 'firstName = null, '
                    . 'lastName = null, '
                    . 'gender = null, '
                    . 'language = null, '
                    . 'age = null, '
                    . 'birthDay = null, '
                    . 'birthMonth = null, '
                    . 'birthYear = null, '
                    . 'email = null, '
                    . 'emailVerified = null, '
                    . 'phone = null, '
                    . 'address = null, '
                    . 'country = null, '
                    . 'region = null, '
                    . 'city = null, '
                    . 'zip = null '
                    . 'WHERE userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('i', $userId);
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
                    . 'timeCreated, '
                    . 'lastLogin, '
                    . 'isOnline, '                    
                    . 'identifier, '
                    . 'issuer, '
                    . 'profileURL, '
                    . 'webSiteURL, '
                    . 'photoURL, '
                    . 'displayName, '
                    . 'description, '
                    . 'firstName, '
                    . 'lastName, '
                    . 'gender, '
                    . 'language, '
                    . 'age, '
                    . 'birthDay, '
                    . 'birthMonth, '
                    . 'birthYear, '
                    . 'email, '
                    . 'emailVerified, '
                    . 'phone, '
                    . 'address, '
                    . 'country, '
                    . 'region, '
                    . 'city, '
                    . 'zip '
                    . 'FROM hyb_user '
                    . 'JOIN hyb_user_openId '
                    . 'USING (userId) '
                    . 'WHERE userId = ?';

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
                    $timeCreated,
                    $lastLogin,
                    $isOnline,                  
                    $identifier,
                    $issuer,                 
                    $profileURL,
                    $webSiteURL,
                    $photoURL,
                    $displayName,
                    $description,
                    $firstName,
                    $lastName,
                    $gender,
                    $language,
                    $age,
                    $birthDay,
                    $birthMonth,
                    $birthYear,
                    $email,
                    $emailVerified,
                    $phone,
                    $address,
                    $country,
                    $region,
                    $city,
                    $zip);
            
            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $objUser = \HybridCMS\Plugins\User\Model\UserFactory::create($type);
                $objUser->setUserId($userId);
                $objUser->setTimeCreated($timeCreated);
                $objUser->setIsOnline((bool)$isOnline);
                $objUser->setIdentifier($identifier);
                $objUser->setIssuer($issuer);
                
                if(false === empty($profileURL))
                {
                    $objUser->setProfileURL($profileURL);
                }
                
                if(false === empty($webSiteURL))
                {
                    $objUser->setWebsite($webSiteURL);   
                }     
                
                if(false === empty($photoURL))
                {
                    $objUser->setPhotoURL($photoURL);
                }     
                
                if(false === empty($displayName))
                {
                    $objUser->setUsername($displayName);
                }   
                
                if(false === empty($description))
                {
                    $objUser->setAboutme($description);
                }
                
                if(false === empty($firstName))
                {
                    $objUser->setFirstname($firstName);
                }  
                
                if(false === empty($lastName))
                {
                    $objUser->setLastname($lastName);
                }   
                
                if(false === empty($gender))
                {
                    $objUser->setGender($gender);
                }    
                
                if(false === empty($language))
                {
                   $objUser->setLanguage($language);
                }   
                
                if(false === empty($age))
                {
                    $objUser->setAge($age);
                }  
                
                if(false === empty($birthDay))
                {
                    $objUser->setBirthDay($birthDay);
                }   
                
                if(false === empty($birthMonth))
                {
                    $objUser->setBirthMonth($birthMonth);
                } 
                
                if(false === empty($birthYear))
                {
                    $objUser->setBirthYear($birthYear);
                } 
                
                if(false === empty($email))
                {
                    $objUser->setEmail($email);
                } 
                
                if(false === empty($emailVerified))
                {
                    $objUser->setEmailVerified((bool)$emailVerified);
                } 
                
                if(false === empty($phone))
                {
                    $objUser->setPhone($phone);
                } 
                
                if(false === empty($address))
                {
                    $objUser->setAddress($address);
                }     
                
                if(false === empty($country))
                {
                    $objUser->setCountry($country);
                }  
                
                if(false === empty($region))
                {
                    $objUser->setRegion($region);
                }  
                
                if(false === empty($city))
                {
                    $objUser->setCity($city);
                }  
                
                if(false === empty($zip))
                {
                    $objUser->setZip($zip);
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
}
?>

