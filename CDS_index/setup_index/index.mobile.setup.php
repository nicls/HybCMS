<?php if(!defined('ROOTDOC')) die();
try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'HybridCMS', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.vs'));
    $hcms->setupPage('ampHtml', array('ampPage' => 'http://hybcms.vs?amp'));
    $hcms->setupPage('prefetch', array('url' => 'http://hybcms.vs/images/frau-1024x768.jpg'));
    $hcms->setupPage('prefetch', array('url' => 'http://hybcms.vs/images/frau-1024x768.png'));

    /* get Articles 
      ==================================================== */
    $objCategory = new \HybridCMS\Content\Section\Category('cateins');
    $arrObjArticles = $objCategory->getArrArticles();

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //Rating-Plugin
    $objRating = new \HybridCMS\Plugins\Rating\Rating(HYB_CURRURL, 5);
    if (false === empty($objRating)) {
        $hcms->loadPlugin($objRating);
    }
    
    //fontSize Plugin
    $objFontSize = new \HybridCMS\Plugins\FontSize\FontSize();
    if (!empty($objFontSize)) {
        $hcms->loadPlugin($objFontSize);
    }
    
    //CTAButton Plugin
    $arrParams['cta'] = 'Jetzt klicken!';
    $arrParams['url'] = '/login.html';
    $arrParams['action'] = 'showCTAButtonContent';
    $arrParams['classNames'] = 'btn, btn-warning, btn-lg';
    $arrParams['targetBlank'] = false;
    $arrParams['nofollow'] = true;
    $arrParams['iconClassNames'] = 'fa fa-amazon';
    $objControllerCTAButton = \HybridCMS\Plugins\CTAButton\Controller\ControllerFactory::create(
            'ControllerCTAButton', $arrParams);
    if (!empty($objControllerCTAButton)) {
        $hcms->loadPlugin($objControllerCTAButton);
    }

    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles(
            'latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
    
    //Widget Siegel
    $arrParams = array('altText' => 'Partner von Bildmania.', 
                       'imgFileName' => 'partner-271x105.png',
                       'url' => "http://google.de",
                       'target' => '_blank',
                       'nofollow' => true);
    $objWidgetSiegel = new \HybridCMS\AsideWidgets
            \WidgetSiegel('siegel', 'sidebar', 2, $arrParams);
    $hcms->addAsideWidget($objWidgetSiegel); 
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(
            LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}
?>