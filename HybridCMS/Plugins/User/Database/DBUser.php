<?php

namespace HybridCMS\Plugins\User\Database;

/**
 * class DBUser
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBUser {

    /**
     * Select type by userId
     * @param mysqli $db
     * @param Integer $userId
     * @return String type
     * @throws \Exception
     */
    public function selectTypeByUserId($db, $userId) {
        
        //statement-Object
        $stmt = null;
        $retType = null;
        
        try {
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT type FROM hyb_user WHERE userId = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->bind_result($type);
            
            //fetch the articles
            if (true === $stmt->fetch()) 
            {
                $retType = $type;
            }
            
            //close Resources
            $stmt->close();

            //return 
            return $retType;
            
        } catch (\Exception $e) {

            //close statement
            if ($stmt) {
                $stmt->close();
            }
            
            throw $e;
        }
    }     
}
