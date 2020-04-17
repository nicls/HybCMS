<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');
try { //configure page
    

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Title Cat1 Article 2', 'prepend' => ' - hybcmd.vs'));
    $hcms->setupPage('description', array('description' => 'Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Duis sagittis ipsum.'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
    $hcms->setupPage('pagerole', array('roleName' => 'landingpage'));
    $hcms->setupPage('pagerole', array('roleName' => 'commerical'));

    /* load Plugins
      ================================================== */
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    /* load Plugins
      ================================================== */
    //Rating-Plugin
    $objRatingPlugin = new \HybridCMS\Plugins\Rating\Rating(HYB_CURRURL);
    if ($objRatingPlugin)
        $hcms->loadPlugin($objRatingPlugin);

    /* add asideWidgets
     =================================================== */
    $arrParams = array();
    $arrParams['toolname'] = 'Windows Movie Maker';
    $objWidgetSoftwareDetails = new \HybridCMS\AsideWidgets\WidgetSoftwareDetails('softwareDetails', 'sidebar', 1, $arrParams);
    $objWidgetSoftwareDetails->setLicence('Freeware');
    $objWidgetSoftwareDetails->setGerman('ja');
    $objWidgetSoftwareDetails->setDeveloper('Microsoft');
    $objWidgetSoftwareDetails->addOs('Windows');
    $objWidgetSoftwareDetails->setFilesize(136.0);
    $objWidgetSoftwareDetails->setPrice(0.0);
    $objWidgetSoftwareDetails->setDownload('http://windows.microsoft.com/de-de/windows-live/movie-maker');
    $hcms->addAsideWidget($objWidgetSoftwareDetails);

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
    <div class="row">
        <article class="col-xs-12 col-md-7 bookmarkAble" id="mainContent">
            <h1>H1 Category 1 Article 2</h1>
            <?php 
           
            //add only on commercial sites
            if(strrpos($hcms->getPageSetting('pagerole'), 'landingpage') !== 0) {
                echo "<h2>This is a Landingpage</h2>";
            } 
            
            ?>

            <figure class="add-bottom" id="primaryImageOfPage">
                <a class="lightbox-gallery" href="/images/cateins/eule-1134x378.jpg" title="Eule">
                    <img itemprop="primaryImageOfPage" src="<?php echo htmlentities($hcms->scaleImg('/images/cateins/eule-1134x378.jpg', 660)); ?>" alt="Eule" height="206" width="660" />
                </a>
                <figcaption class="half-left">Eule</figcaption>
            </figure>  
            
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>


            <aside class="add-bottom add-top">
                <?php
                //print rating-form
                echo $objRatingPlugin->toString();
                ?>
            </aside>

        </article>

        <?php
        /* include Footer
         * ================================================== */
        require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php');
        ?>     
    </div><!-- end .row -->
</div><!-- end .container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
?>