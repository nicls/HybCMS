<?php if(!defined('ROOTDOC')) die();

//setup widget Siegel Klima-Hosting
$objWidgetKlima = new \HybridCMS\AsideWidgets\WidgetSiegelKlimaHosting('klimaHosting', 'sidebar', 10, array());
//INFO: Widget is added in header on all pages

//setup widget Flash Sale Linotype
$arrParamsWidgetFlashSale['headline'] = 'Linotype: Angebot der Woche';
$arrParamsWidgetFlashSale['url'] = 'https://schriftgestaltung.com/linotype/angebot';
$arrParamsWidgetFlashSale['imgFileName'] = 'platinum-660x526.jpg';
$arrParamsWidgetFlashSale['begin'] = date_timestamp_get(new DateTime('2013-11-04 10:00:00.00000'));
$arrParamsWidgetFlashSale['end'] = date_timestamp_get(new DateTime('2013-11-05 10:00:00.00000')); 
$objWidgetFlashSale = new \HybridCMS\AsideWidgets\WidgetFlashSale('flashSale', 'sidebar', 2, $arrParamsWidgetFlashSale);
//INFO: Widget is added in the header ifcurrent pagerole is 'commercial'

//setup widget Flash Sale Fonts.com
//$arrParamsWidgetFlashSale['headline'] = 'Fonts.com: Aktuell im Angebot';
//$arrParamsWidgetFlashSale['url'] = 'https://schriftgestaltung.com/fontshop/angebot';
//$arrParamsWidgetFlashSale['imgFileName'] = 'laura-worthington-families-700x850.png';
//$arrParamsWidgetFlashSale['begin'] = date_timestamp_get(new DateTime('2013-10-08 22:00:00.00000'));
//$arrParamsWidgetFlashSale['end'] = date_timestamp_get(new DateTime('2013-10-09 10:00:00.00000')); 
//$objWidgetFlashSale = new \HybridCMS\AsideWidgets\WidgetFlashSale('flashSale', 'sidebar', 2, $arrParamsWidgetFlashSale);
//INFO: Widget is added in the header ifcurrent pagerole is 'commercial'

?>
