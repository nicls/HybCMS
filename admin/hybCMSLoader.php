<?php /** HYBCMSLOADER ADMIN */

define('ROOTDOC', true);
error_reporting(E_ALL); //jegliche Fehlermeldungen und Warnungen werden angezeigt

/** Load Global Settings
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/globalSettings.php");

/** Load SessionStarter
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/sessionStart.php");
    
    //rolenames allowed
    $arrRolenames = array('admin', 'editor', 'author');

    //check if user is logged in
    if (!isset($_SESSION['username'], $_SESSION['rolename']) 
            || 
            !in_array($_SESSION['rolename'], $arrRolenames)) 
    {
        header('Location: ' . '/admin/login.php');
    }
        
/** include classLoader
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Helper/autoload.php');

/** create HybCMS
========================================= */
$hcms = new \HybridCMS\HybridCMS();
?>