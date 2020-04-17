<?php

namespace HybridCMS\Plugins\AmazonPrices;

/**
 * class AjaxControllerAmazonPrices - Handles API-Requests from the client
 * for the AmazonPrices Plugin
 *
 * @package AmazonPrices
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class AjaxControllerAmazonPrices implements \HybridCMS\Ajax\IAjaxController 
{

    /**
     * indicates what to do
     * @var String
     */
    private $action;  
    
    /**
     * Productname
     * @var String
     */
    private $prodname;
    
    /**
     * __construct
     *
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    public function __construct($arrParams) {

        try 
        {            
            //check weather action is set
            if (false === isset($arrParams['action'])) 
            {
                throw new \Exception(
                        "Error Processing Ajax-Request: "
                        . "action is missing.", 1);
            }     
            
            //assign action
            $this->setAction(trim($arrParams['action']));            

            //check wether params are complete
            if (false === isset($arrParams['prodname'])) 
            {
                throw new \Exception(
                        "Error Processing Ajax-Request: "
                        . "productName is missing.", 1);
            }

            $this->setProdname($arrParams['prodname']);                                  

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * handleAjaxRequest - inserts/updates Rating into Database
     *
     * @param mixed[] $arrParams
     * @return void
     * @throws \Exception
     */
    public function handleAjaxRequest() {

        try 
        {
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //database-object to operate on Tables
            $objDBAmazonPrices = new \HybridCMS\Plugins\AmazonPrices\Database\DBAmazonPrices();

            //select articleId of the current Rating
            $arrPrices = $objDBAmazonPrices->selectPricesByProdname($db, $this->prodname);                        

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
            
            //remove Keys from assosicative array
            for($i=0; $i<count($arrPrices);$i++)
            {
                $arrPrices[$i] = array_values($arrPrices[$i]);
                //remove hours from datetime
                $arrPrices[$i][0] = substr($arrPrices[$i][0], 0, 10);
            }
            
            //add header
            array_unshift($arrPrices, array("Datum", "Preis"));
            \HybridCMS\Util\LogDebug::logDebug(print_r($arrPrices, true));
            
            //return as json string
            echo json_encode($arrPrices);
                        
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }    
    
    /**
     * setAction
     * @param String $action
     * @throws \Exception
     */
    private function setAction($action) {

        //check if action is an alphabetic String
        if ( false === ctype_alpha($action)) {

            throw new \Exception(
            "Error Processing Request: setAction(),
                       action must be alphanumeric.", 1);
        }

        $this->action = $action;
    }    
    
    /**
     * setProdname
     * @param String $action
     * @throws \Exception
     */
    private function setProdname($prodname) {

        //check if $prodname is an alphabetic String
        if (false === ctype_alnum($prodname)) {

            throw new \Exception(
            "Error Processing Request: setProdname(),
                       prodname must be alphanumeric.", 1);
        }

        $this->prodname = $prodname;
    }    
    
}
