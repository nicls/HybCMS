<?php

/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/hybCMSLoader.php');

//setup Access-Control-Allow-Origin for CORS-Requests
header('Access-Control-Allow-Origin: ' . HYB_PROTOCOL . HYB_HOST_NAME);

//prevent response to be cached by the browser
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

//check if request comes from administration
if (isset($_POST['admin'], $_POST['action'], $_POST['object'])) {

    try {

        //check if object is an alphabetic String
        if (false === ctype_alpha($_POST['object'])) 
        {
            throw new \Exception(
            "Error by handling object-value on admin.php: " .
            htmlspecialchars($_POST['object']), 1);
        }

        //get the name of the action
        $objectName = ucfirst(strtolower($_POST['object']));

        //build the path to the ajaxController of the Admin-Package
        $pathAjaxController = '\\HybridCMS\\Admin\\' .
                'AjaxController\\AjaxController' . $objectName;

        //try to get the AjaxController of a Plugin
        if (false === file_exists($_SERVER['DOCUMENT_ROOT'] 
                . str_replace('\\', '/', $pathAjaxController) . '.php')) 
        {
            $pathAjaxController = '\HybridCMS\\Plugins\\' . $objectName .
                    '\\AjaxController' . $objectName;
        }

        //check if file exists, otherwise throw exception
        if (false === file_exists($_SERVER['DOCUMENT_ROOT'] 
                . str_replace('\\', '/', $pathAjaxController) . '.php')) 
        {
            throw new \Exception(
            "Error Processing Request: admin.php,
                    'AjaxController does not exist: '"
            . htmlspecialchars($_SERVER['DOCUMENT_ROOT'] 
                    . str_replace('\\', '/', $pathAjaxController) . '.php'), 1);
        }

        //create ajaxController of the plugin
        $objAjaxController = new $pathAjaxController($_POST);               
        $objAjaxController->handleAjaxRequest();       
        
    } 
    catch (\Exception $e) 
    {
        //Log Error
        $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                \HybridCMS\Helper\KLogger::ERR);
        $objLogger->logError($e->__toString() . "\n");
    }
} else {

    echo "Request failed. Please try again later...";
}
?>
