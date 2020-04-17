<?php

/** /admin/comptable/index.php */
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/hybCMSLoader.php');

/* Page Setup
  ================================================= */
$pluginNameAndAction = "Plugins";
if (isset($_GET['name'], $_GET['action'])) {
    $pluginNameAndAction = ucfirst(htmlentities($_GET['name']))
            . ' - ' . htmlentities($_GET['action']);
}

$hcms->setupPage('title', array('title' => $pluginNameAndAction . ' - Administration', 'prepend' => ' - hybcms.de'));
$hcms->setupPage('description', array('description' => 'Administrations HybCMS'));
$hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
$hcms->setupPage('noindexNofollow');
?>
<?php

    //build filename of the Plugins view 
    $pluginOptionUrl = "/HybridCMS/Plugins/"
            . ucfirst(htmlentities($_GET['name']))
            . "/Admin/options/"
            . htmlentities($_GET['action']) . ".php";

    //include Plugin-view 
    if (\HybridCMS\Modules\Url\Url::isValidUrl($pluginOptionUrl) 
            && file_exists($_SERVER['DOCUMENT_ROOT'] . $pluginOptionUrl)) {
        
        require_once($_SERVER['DOCUMENT_ROOT'] . $pluginOptionUrl);
        
    } else {
        echo "Plugin or action does not exists: " . $_SERVER['DOCUMENT_ROOT'] . $pluginOptionUrl;
    }
?>
<?php

/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php');
?>