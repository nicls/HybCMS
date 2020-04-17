<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Title Cat1', 'prepend' => ' - hybcmd.vs'));
    $hcms->setupPage('description', array('description' => 'Description Cat1'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));

    /* get Articles 
      ==================================================== */
    $objCategory = new \HybridCMS\Content\Section\Category('cateins', true);
    $arrObjArticles = $objCategory->getArrArticles();
    
    /* load Plugins
      ================================================== */
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles(
            'latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
            \HybridCMS\Helper\KLogger::ERR);
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
        <article class="col-xs-12 col-md-7">
            <h1>H1 Category 1</h1>

            <?php
            /** Begin Print Articles
              ================================================== */
            $cnt = 0;

            foreach ($arrObjArticles as &$arrObjArt) {
                
                $objArticleMeta = $arrObjArt->getObjArticleMeta();
                $price = "";
                $currency = "";
                if(false === empty($objArticleMeta)) {
                    $price = $objArticleMeta->getArticleMeta("price");
                    $currency = $objArticleMeta->getArticleMeta("currency");
                }
                
                //get img in 660px width
                $primImg = null;
                if ($arrObjArt->getPrimaryImageOfPage()) {
                    $primImg = $hcms->scaleImg($arrObjArt->getPrimaryImageOfPage(), 660);
                }

                //build a-tag to the Article
                $articleAHrefBegin = '<a href="' . htmlentities($arrObjArt->getUrl()) . '" title="' . htmlentities($arrObjArt->getFirstHeadline()) . '">';
                $articleAHrefEnd = '</a>';

                //print every 5th article over the whole width
                $articleWidth = 'c6';
                if ($cnt++ % 3 == 0) {
                    $articleWidth = 'add-right-20';
                }

                $op = '';

                //open Article-tag
                $op .= '<article class="' . $articleWidth . ' add-bottom-20">';

                //set headline
                $op .= '<header><h2>' . $articleAHrefBegin . htmlspecialchars($arrObjArt->getFirstHeadline()) . $articleAHrefEnd . '</h2></header>';
                    
                $op .= '<div class="hyb_cat_img_container">';
                
                if (isset($primImg) && !empty($primImg)) {

                    //open figure-tag
                    $op .= '<figure>';

                    //add image
                    $op .= $articleAHrefBegin;
                    
                    if(false === empty($price && $currency)) {
                    $op .= '<span class="price">' 
                            . htmlspecialchars($price) 
                            . " " 
                            . htmlspecialchars($currency)
                            . '</span>';
                    }
                
                    $op .= '<img src="' . htmlentities($primImg) . '" alt="' . htmlentities($arrObjArt->getFirstHeadline()) . '" width="660" />';

                    $op .= $articleAHrefEnd;
                    
                    //close figure tag
                    $op .= '</figure>';
                }
                                
                $op .= '</div>';

                //add description 
                $op .= '<p class="teaserText">' . htmlspecialchars($arrObjArt->getDescription()) . $articleAHrefBegin . ' <i class="icon-angle-right color888"></i></a></p>';

                //close article-tag
                $op .= '</article>';

                //print article
                echo $op;
            }
            ?>

        </article>

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