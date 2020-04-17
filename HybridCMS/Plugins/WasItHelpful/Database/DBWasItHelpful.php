<?php

namespace HybridCMS\Plugins\WasItHelpful\Database;

/**
 * class DBWasItHelpful
 *
 * @package WasItHelpful/Database
 * @version 0.0.1
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class DBWasItHelpful
{
    /**
     * inserts a new Response
     * @param mysqli $db
     * @param Response $objResponse
     * @return mixed[success:Boolean, insertId:Integer] 
     */
    public function insertResponse($db, $objResponse) {

        //statement-Object
        $stmt = null;

        try 
        {            
            //check if $objUser is valid
            if(false === ($objResponse instanceof 
                    \HybridCMS\Plugins\WasItHelpful\Model\Response)) 
            {
                throw new \Exception(
                'Error Processing Request:
                        insertResponse(),                    
                        $objResponse is not a instance of Response.', 1);
            }                                           
            
            //get parameter   
            $wasItHelpful = (int)$objResponse->getWasItHelpful();
            $objUrl = $objResponse->getUrl();
            $url = $objUrl->getUrl();
            $customAnswer = $objResponse->getCustomAnswer();
            $timeCreated = time();                               
            
            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_wasItHelpful ('
                    . 'wasItHelpful, '
                    . 'url, '
                    . 'customAnswer, '             
                    . 'timeCreated) VALUES (?,?,?,?)';


            //check if statement is valid
            if (false === ($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                    "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('issi', 
                    $wasItHelpful, 
                    $url,
                    $customAnswer,
                    $timeCreated);

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
}