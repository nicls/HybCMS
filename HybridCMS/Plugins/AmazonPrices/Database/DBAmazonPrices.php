<?php
namespace HybridCMS\Plugins\AmazonPrices\Database;

/**
 * class DBAmazonPrices
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class DBAmazonPrices 
{
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {
        
    }    

    /**
     * insertComptable
     * 
     * @param mysqli $db
     * @param string $comptableName
     * @return boolean
     */
    public function insertNewPrice($db, $prodname, $priceAPI) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_amazon_prices (
		prodname, price) VALUES (?,?)';


            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('sd', $prodname, $priceAPI);

            $success = $stmt->execute();

            //close Resources
            $stmt->close();

            return $success;
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
     * selectPricesByProdname
     * @param mysqli $db
     * @param String $prodname
     * @return array
     * @throws Exception
     */
    public function selectPricesByProdname($db, $prodname) 
    {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }
            

            $arrDatasets = array();

            $sql = 'SELECT created, price
                    FROM hyb_amazon_prices
                        WHERE prodname = ?
                            ORDER BY created ASC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $prodname);
            $stmt->execute();
            $stmt->bind_result($timestamp_created, $price);

            //fetch the articles
            while ($stmt->fetch()) {

                $arrDatasets[] = array(
                    'timestamp_created' => $timestamp_created,
                    'price' => $price
                );
            }
            
            //close Resources
            $stmt->close();

            //close Resources
            $stmt->close();

            //return all prices
            return $arrDatasets;
        } catch (\Exception $e) {

            //close statement
            if ($stmt) { $stmt->close(); }

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }    
}
