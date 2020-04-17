<?php define('ROOTDOC', true);
error_reporting(E_ALL); //jegliche Fehlermeldungen und Warnungen werden angezeigt

/** Load Global Settings
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/globalSettings.php");

/** Load SessionStarter
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/sessionStart.php");

/** include classLoader
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Helper/autoload.php');

/** create HybCMS
========================================= */
$hcms = new \HybridCMS\HybridCMS();

/** create Content Delivery Service
========================================= */
$objCDS = new \HybridCMS\Modules\CDS\CDS(HYB_CURRELURL_ORIGFILETYPE);

/** load global Aside Widgets
 * ====================================== */
include_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Helper/setupGlobalAsideWidgets.php');

/** load global Plugins
 * ====================================== */
include_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Helper/setupGlobalPlugins.php');
?>