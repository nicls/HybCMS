<?php define('ROOTDOC', true);
/** Load Global Settings
========================================= */
require_once("/var/www/HybCMS/HybridCMS/Helper/globalSettings.php");

/** Load Classes dynamicly */
function autoload($className) 
{

    $className = ltrim($className, '\\');
    
    $fileName = '';
    $namespace = '';
    
    if ($lastNsPos = strripos($className, '\\')) 
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    //check if file does exist
    if (file_exists('/var/www/HybCMS/' . $fileName)) 
    {
        require_once '/var/www/HybCMS/' . $fileName;
    } 
}

spl_autoload_register("autoload", true, true);

?>