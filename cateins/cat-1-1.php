<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* page setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Title Cat1 Article 1', 'prepend' => ' - hybcmd.vs'));
    $hcms->setupPage('description', array('description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Integer nec odio.'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
    $hcms->setupPage('keywords', array('testKeyword1', 'tetKeyword2'));
    
    /* articleMeta
      ================================================== */
    $objArticleMeta = new \HybridCMS\Content\Article\ArticleMeta(HYB_CURRURL);
    $objArticleMeta->addArticleMeta('price', '7.00');
    $objArticleMeta->addArticleMeta('currency', '€');
    $objArticleMeta->addArticleMeta("scoreCat1", '4.2');
    $objArticleMeta->addArticleMeta("scoreCat2", '6.9');
    $objArticleMeta->addArticleMeta("scoreCat3", '8.3');
    $objArticleMeta->synchronize(); 

    /* load plugins
      ================================================== */
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    //scoring plugin
    $arrCategories = array('scoreCat1', 'scoreCat2', 'scoreCat3');
    $objScore = new \HybridCMS\Plugins\Score\Score($objArticleMeta, $arrCategories);
    $hcms->loadPlugin($objScore);
    
    //rating-plugin
    $objRatingPlugin1 = new \HybridCMS\Plugins\Rating\Rating(HYB_CURRURL);
    #$objRatingPlugin2 = new \HybridCMS\Plugins\Rating\Rating(HYB_CURRURL . '#test');
    if ($objRatingPlugin1) { $hcms->loadPlugin($objRatingPlugin1); }
    
    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles(
            'latestArticles', 'sidebar', 1, $arrParams);
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
    <div class="row">
        <article class="col-xs-12 col-md-7" id="mainContent" itemprop="mainContentOfPage">
            <h1>H1 Category 1 Article 1</h1>
            
            <figure class="add-bottom" id="primaryImageOfPage">
                <a class="lightbox-gallery" href="/images/cateins/raupe-1280x427.jpg" title="Raupe">
                    <img itemprop="primaryImageOfPage" src="<?php echo htmlentities($hcms->scaleImg('/images/cateins/raupe-1280x427.jpg', 660)); ?>" alt="Raupe" height="206" width="660" />
                </a>
                <figcaption class="half-left">Raupe</figcaption>
            </figure>  
            
            <aside>
                <h2>Article Meta</h2>
                <ul>
                <?php
                    $arrArticleMeta = $objArticleMeta->getArrArticleMeta();                                        
                    
                    foreach ($arrArticleMeta as $key => $value) {
                        echo "<li>" . htmlspecialchars($key) . " : " . htmlspecialchars($value) . "</li>";
                    }
                ?>
                </ul>
            </aside>
            
            <h2>Unsere Bewertung:</h2>
            <?php
            
                echo $objScore->toString();
            
            ?>
            
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <aside class="add-bottom add-top">
                <?php
                //print rating-form
                echo $objRatingPlugin1->toString();
                ?>
            </aside>
            
            <aside class="add-bottom add-top">
                <?php
                //print rating-form
                #echo $objRatingPlugin2->toString();
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