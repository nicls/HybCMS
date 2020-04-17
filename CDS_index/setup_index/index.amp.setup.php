<?php if(!defined('ROOTDOC')) die();
try { //configure page
    
    /* JSONLD Annotations
      ================================================= */
    $arrJsonLD = array(
        "@context" => "http://schema.org",
        "@type" => "NewsArticle",
        "mainEntityOfPage" => "http://cdn.ampproject.org/article-metadata.html",
        "headline" => "Lorem Ipsum",
        "datePublished" => "1907-05-05T12:02:41Z",
        "author" => array(
            "@type" => "Person",
            "name" => "Jordan M Adler"
        ),
        "publisher" => array(
            "@type" => "Organization",
            "name" => "Google",
            "logo" => array(
                "@type" => "ImageObject",
                "url" => "http://cdn.ampproject.org/logo.jpg",
                "width" => 600,
                "height" => 60
            )
        ),
        "image" => array(
            "@type" => "ImageObject",
            "url" => "http://cdn.ampproject.org/leader.jpg",
            "height" => 2000,
            "width" => 800
        )
    );
    

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'HybridCMS', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.vs'));    
    $hcms->setupPage('prefetch', array('url' => 'http://hybcms.vs/images/frau-1024x768.jpg'));
    $hcms->setupPage('prefetch', array('url' => 'http://hybcms.vs/images/frau-1024x768.png'));

    /* get Articles 
      ==================================================== */
    $objCategory = new \HybridCMS\Content\Section\Category('cateins');
    $arrObjArticles = $objCategory->getArrArticles();

    /* load Plugins
      ================================================== */
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

    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(
            LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}
?>