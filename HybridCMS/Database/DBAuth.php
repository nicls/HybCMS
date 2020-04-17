<?php

namespace HybridCMS\Database;

/**
 * class DBAuth
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBAuth {
    /*
     * Atributes
     */

    private $arrAuth;
    
    /**
     * 
     * @param mysqli $db
     * @param string $username
     * @return \HybridCMS\Admin\Auth\User
     * @throws \Exception
     */
    public function selectAuthorByUsername($db, $username) {
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = "SELECT
		username,
		rolename,
                email,
		twitter,
		facebook,
		googleplus,
		youtube,
                website,
		aboutme FROM hyb_auth WHERE username = ? AND rolename = 'author'";

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result(                    
                    $username,
                    $rolename,
                    $email,
                    $twitter,
                    $facebook,
                    $googleplus,
                    $youtube,
                    $website,
                    $aboutme);
            
            $objUser = null;

            if ($stmt->fetch()) {
                
                $objUser = new \HybridCMS\Admin\Auth\User($username, $rolename, $email);
                
                if(!empty($twitter)) {
                    $objUser->setTwitter($twitter);
                }
                if(!empty($facebook)) {
                    $objUser->setFacebook($facebook);
                }
                if(!empty($googleplus)) {
                    $objUser->setGooglePlus($googleplus);
                }
                if(!empty($youtube)) {
                    $objUser->setYoutube($youtube);                    
                }
                if(!empty($website)) {
                    $objUser->setWebsite($website);
                }
                if(!empty($aboutme)) {
                    $objUser->setAboutme($aboutme);
                }
                
            }

            //close statement
            $stmt->close();

            return $objUser;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * 
     * @param mysqli $db
     * @param string $rolename
     * @return \HybridCMS\Admin\Auth\User[]
     * @throws \Exception
     */
    public function selectUserByRolename($db, $rolename) {
        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = "SELECT
		username,
		rolename,
                email,
		twitter,
		facebook,
		googleplus,
		youtube,
                website,
		aboutme FROM hyb_auth WHERE rolename = ?";

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('s', $rolename);
            $stmt->execute();
            $stmt->bind_result(                    
                    $username,
                    $rolename,
                    $email,
                    $twitter,
                    $facebook,
                    $googleplus,
                    $youtube,
                    $website,
                    $aboutme);
            
            $arrObjUser = array();

            while ($stmt->fetch()) 
            {                
                $objUser = new \HybridCMS\Admin\Auth\User(
                        $username, $rolename, $email);
                
                if(!empty($twitter)) {
                    $objUser->setTwitter($twitter);
                }
                if(!empty($facebook)) {
                    $objUser->setFacebook($facebook);
                }
                if(!empty($googleplus)) {
                    $objUser->setGooglePlus($googleplus);
                }
                if(!empty($youtube)) {
                    $objUser->setYoutube($youtube);                    
                }
                if(!empty($website)) {
                    $objUser->setWebsite($website);
                }
                if(!empty($aboutme)) {
                    $objUser->setAboutme($aboutme);
                }
                
                $arrObjUser[] = $objUser;                
            }

            //close statement
            $stmt->close();

            return $arrObjUser;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    

    /**
     * insertUser
     * @param mysqli $db
     * @param \HybridCMS\Admin\Auth\User $objUser
     * @param String $hash
     * @return Integer
     * @throws \Exception
     */
    public function insertUser($db, $objUser, $hash) {

        //check if $objUser is of type User
        if (!($objUser instanceof \HybridCMS\Admin\Auth\User)) {
            throw new \Exception(
            'Error Processing Request: insertUser(),
                            $objUser is not of type \HybridCMS\Admin\Auth\User.', 1);
        }

        //statement-Object
        $stmt = null;               

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_auth (
		username,
		hash,
		rolename,
                email,
		twitter,
		facebook,
		googleplus,
		youtube,
                website,
		aboutme) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid: " . htmlspecialchars($sql));
            }

            $username = $objUser->getUsername();
            $rolename = $objUser->getRolename();
            $email = $objUser->getEmail();
            $twitter = $objUser->getTwitter();
            $facebook = $objUser->getFacebook();
            $googleplus = $objUser->getGoogleplus();
            $youtube = $objUser->getYoutube();
            $website = $objUser->getWebsite();
            $aboutme = $objUser->getAboutme();


            $stmt->bind_param('ssssssssss', 
                    $username,
                    $hash,
                    $rolename,
                    $email,
                    $twitter,
                    $facebook,
                    $googleplus,
                    $youtube,
                    $website,
                    $aboutme
            );

            $stmt->execute();

            //store primary-key of this Article
            $insertId = $db->insert_id;

            //close Resources
            $stmt->close();

            return $insertId;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     *
     * @param mysqli $db
     * @param String $username
     * @return mixed
     * @throws \Exception
     */
    public function selectUserRolenameAndPassByUsername($db, $username) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT username, rolename, hash FROM hyb_auth WHERE username = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($username, $rolename, $hash);

            if ($stmt->fetch()) {
                $this->arrAuth = array('username' => $username, 'rolename' => $rolename, 'hash' => $hash);
            }

            //close statement
            $stmt->close();

            return $this->arrAuth;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

}

?>
