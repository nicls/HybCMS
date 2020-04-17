<?php

namespace HybridCMS\Plugins\Smb;

/**
 * Handles API-Requests from the client
 * for the Social Media Buttons Plugin
 *
 * @package Smb
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AjaxControllerSmb implements \HybridCMS\Ajax\IAjaxController {

    /**
     * Url to share
     * @var String
     */
    private $shareUrl;
    
    /**
     * __construct
     *
     * @param mixed[] $arrParams
     * @throws \Exception
     */
    public function __construct($arrParams) {

        try {
            
            //check if headline was submitted by the client
            if (false === isset($arrParams['shareUrl'])) 
            {
                throw new \Exception(
                "Error Processing Request: handleAjaxRequest(),
                            'shareUrl is missing.'", 1);
            }
            //assign headline
            $this->setShareUrl($arrParams['shareUrl']);

        } 
        catch (Exception $e) 
        {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * handleAjaxRequest
     */
    public function handleAjaxRequest() {

        try {

            $json = $this->getShares();

            //set header to json
            header('Content-Type: text/javascript; charset=utf8');

            echo $json;
        } 
        catch (Exception $e) 
        {
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * Return number of shares on Googleplus
     * @return Integer
     * @throws \Exception
     */
    private function getGooglePlusShares() 
    {
        $curl = null;
        
        try
        {
            assert(false === empty($this->shareUrl));

            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, 
                    '[{"method":"pos.plusones.get",'
                    . '"id":"p",'
                    . '"params":{"nolog":true,"id":"' . $this->shareUrl . '",'
                    . '"source":"widget",'
                    . '"userId":"@viewer",'
                    . '"groupId":"@self"},'
                    . '"jsonrpc":"2.0",'
                    . '"key":"p",'
                    . '"apiVersion":"v1"}]');

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, 
                    array('Content-type: application/json'));

            //execute curl
            $curl_results = curl_exec($curl);

            //close curl
            curl_close($curl);

            $json = json_decode($curl_results, true);

            return intval($json[0]['result']['metadata']['globalCounts']['count']);
        } 
        catch (Exception $e) 
        {
            //close curl
            if($curl) 
            {
                curl_close($curl);
            }
            
            throw $e;
        }
    }   
    
    /**
     * getShares
     * @return String
     */
    private function getShares() {

        try 
        {
            $arrShares = array();
            
            //get googleplus shares
            $arrShares['googleplusShares'] = $this->getGooglePlusShares();

            return json_encode($arrShares);
        } 
        catch (Exception $e) 
        {
            throw $e;
        }
    }    

    /** 
     * Sets the url to share
     * @param String $url
     */
    private function setShareUrl($url)
    {
        if (true === empty($url)) 
        {
            throw new \Exception(
            'Error Processing Request: setShareUrl,
                        $url is not set.', 1);
        }
        
        if(false === \HybridCMS\Modules\Url\Url::isValidUrl($url))
        {
            throw new \Exception(
            'Error Processing Request: setShareUrl,
                        $url is not valid.', 1);
        }
        
        $objUrl = new \HybridCMS\Modules\Url\Url($url);
        
        if(false === $objUrl->urlIsInternal())
        {
            throw new \Exception(
            'Error Processing Request: setShareUrl,
                        $url is not internal.', 1);
        }   
        
        $this->shareUrl = $url;
    }     

}

?>