<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Confirm Email', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de'));
    $hcms->setupPage('noindex', array()); 

    /* get Articles 
      ==================================================== */


    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    //User-Plugin Controller
    $arrParams = $_GET;  
    $objControllerConfirmNewEmail =
            \HybridCMS\Plugins\User\Controller\ControllerFactory::create(
                    'ControllerConfirmNewEmail', $arrParams);
    assert(false === empty($objControllerConfirmNewEmail));
    $hcms->loadPlugin($objControllerConfirmNewEmail);

    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles
            ('latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php');
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <section class="row" id="mainContent" itemprop="mainContentOfPage">
        <div class="col-xs-12 col-md-7">
                     
            <?php
                echo $objControllerConfirmNewEmail->toString();
            ?>
        </div><!-- end .col-xs-12 .col-md-7 -->

    <?php
    /* include Footer
     * ================================================== */
    require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>  
        
    </section><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>