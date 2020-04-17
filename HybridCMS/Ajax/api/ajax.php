<?php define('ROOTDOC', true);

/** Load Global Settings
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/globalSettings.php");

/** include classLoader
  ========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Helper/autoload.php');

//setup Access-Control-Allow-Origin for CORS-Requests
header('Access-Control-Allow-Origin: ' . HYB_PROTOCOL . HYB_HOST_NAME);

//check what service is requested
if (isset($_POST['plugin'])) {
    
    try {
                
        //check if plugin is an alphabetic String
        if (!ctype_alpha($_POST['plugin'])) 
        {
            throw new \Exception(
                    "Error by handling plugin-value on ajax.php: " .
                    htmlspecialchars($_POST['plugin']), 1);
        }

        //get the name of the Plugin
        $pluginName = ucfirst(trim($_POST['plugin']));

        //build the path to the ajaxController in folder 'AjaxController'
        $pathAjaxController = '\\HybridCMS\\Plugins\\' . $pluginName
                . '\\AjaxController\\AjaxController' . $pluginName;

        //try to get the AjaxController of a Plugin in plugin-root directory
        if (false === file_exists($_SERVER['DOCUMENT_ROOT'] 
                . str_replace('\\', '/', $pathAjaxController) . '.php')) 
        {
            $pathAjaxController = '\HybridCMS\\Plugins\\' . $pluginName .
                    '\\AjaxController' . $pluginName;
        }
        
        //check if file exists, otherwise throw exception
        if (false === file_exists($_SERVER['DOCUMENT_ROOT'] 
                . str_replace('\\', '/', $pathAjaxController) . '.php')) 
        {
            throw new \Exception(
                "Error Processing Request: ajax.php,
                        'AjaxController does not exist: '"
                        . htmlspecialchars($_SERVER['DOCUMENT_ROOT'] 
                        . str_replace('\\', '/', $pathAjaxController) 
                        . '.php'), 1);
        }        

        //create ajaxController of the plugin
        $objAjaxController = new $pathAjaxController($_POST);
        $objAjaxController->handleAjaxRequest();

    } catch (\Exception $e) {

        //close Database-Connection
        \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

        //Log Error
        $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
        $objLogger->logError($e->__toString() . "\n");
    }
} else {
       
        //plugin-name is missing
        echo 'Service unavailable.';
}

?>
