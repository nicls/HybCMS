<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

//check if user is loged in
if(false === isset($_SESSION['logedIn'])) 
{
    header('Location: ' . '/login.html');
}

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'User Dashboard', 'prepend' => ' - hybcms.de'));
    $hcms->setupPage('description', array('description' => 'User Dashboard.'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/dashboard/'));
    $hcms->setupPage('noindex', array()); 

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    /* add asideWidgets
      =================================================== */
    
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
            /* include Navtabs
             * ================================================== */
            require_once($_SERVER['DOCUMENT_ROOT'] . '/user/dashboard/nav-tabs.php'); ?>  
          


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