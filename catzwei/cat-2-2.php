<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try 
{ //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Title Cat2 Article 2', 'prepend' => ' - hybcmd.vs'));
    $hcms->setupPage('description', array('description' => 'Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
    $hcms->setupPage('keywords', array('testKeyword1'));

    /* load Plugins
      ================================================== */
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //Rating-Plugin
    $objRatingPlugin = new \HybridCMS\Plugins\Rating\Rating(HYB_CURRURL);
    if ($objRatingPlugin) {
        $hcms->loadPlugin($objRatingPlugin);
    }

} 
catch (\Exception $e) 
{

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(
            LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
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
        <article class="col-xs-12 col-md-7" id="mainContent">
        <h1>H1 Category 2 Article 2</h1>
        
            <figure class="add-bottom" id="primaryImageOfPage">
                <a class="lightbox-gallery" href="/images/catzwei/dragon-1275x425.jpg" title="Drache">
                    <img itemprop="primaryImageOfPage" src="<?php echo htmlentities($hcms->scaleImg('/images/catzwei/dragon-1275x425.jpg', 660)); ?>" alt="Drache" height="206" width="660" />
                </a>
                <figcaption class="half-left">Drache</figcaption>
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
        require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>  
    </div><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>