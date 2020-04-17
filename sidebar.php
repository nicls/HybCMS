<?php if(!defined('ROOTDOC')) die();?>
<?php
$arrSidebarWidgetNames = array();
$arrSidebarWidgetNames[] = 'latestArticles';
$arrSidebarWidgetNames[] = 'softwareDetails';
$arrSidebarWidgetNames[] = 'siegel';
$arrSidebarWidgetNames[] = 'genericTable';
$arrSidebarWidgetNames[] = 'flattr';
$arrSidebarWidgetNames[] = 'news';

//get all aside widgets
$arrAsideWidgets = $hcms->getAsideWidgets('sidebar');

//open aside-tag
echo '<aside id="sidebar" class="col-xs-12 col-md-5">';

//print all asideWidgets
foreach ($arrAsideWidgets as $asideWidget) {
    if (in_array($asideWidget->getWidgetName(), $arrSidebarWidgetNames)) {

        //handle AsdeWidgets with params
        if ($asideWidget->getWidgetName() == 'latestArticles') {
            
            echo $asideWidget->toString(array('imgWidth' => 470, 'imgHeight' => 200, 'imgScale' => true));
            
        } 
        
        //handle all Aside Widgets without params
        else {
            echo $asideWidget->toString();
        }
    }
}

//close aside-tag
echo "</aside>";
?>

