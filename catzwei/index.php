<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Title Cat2', 'prepend' => ' - hybcmd.vs'));
    $hcms->setupPage('description', array('description' => 'Description Cat2'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));

    /* get Articles 
      ==================================================== */
    $objCategory = new \HybridCMS\Content\Section\Category('catzwei');
    $arrObjArticles = $objCategory->getArrArticles();

    /* load Plugins
      ================================================== */
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //Rating-Plugin
    $arrObjArticlePack = array();

    //select Rating for each Article
    foreach ($arrObjArticles as &$objArticle) {
        $objRating = new \HybridCMS\Plugins\Rating\Rating($objArticle->getUrl());
        $objRating->setIsDisabled('true');
        $arrObjArticlePack[] = array('article' => $objArticle, 'rating' => $objRating);
    }

    //load CSS und JS Resources for Plugin Rating
    if (isset($arrObjArticlePack[0]['rating'])) {
        $hcms->loadPlugin($arrObjArticlePack[0]['rating']);
    }
    
    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'cateins');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles(
            'latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);   
    
} catch (\Exception $e) {

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
    <section class="row" id="mainContent" itemprop="mainContentOfPage">
        <article class="col-xs-12 col-md-7">
            <h1>H1 Category 1</h1>

            <?php
            /** Begin Print Articles
              ================================================== */
            $cnt = 0;

            foreach ($arrObjArticlePack as &$arrObjArt) {

                //continue if no article is available
                if (!$arrObjArt['article']) {
                    continue;
                }

                //get img in 660px width
                $primImg = null;
                if ($arrObjArt['article']->getPrimaryImageOfPage()) {
                    $primImg = $hcms->scaleImg($arrObjArt['article']->getPrimaryImageOfPage(), 660);
                }

                //build a-tag to the Article
                $articleAHrefBegin = '<a href="' . htmlentities($arrObjArt['article']->getUrl()) . '" title="' . htmlentities($arrObjArt['article']->getFirstHeadline()) . '">';
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
                $op .= '<header><h2>' . $articleAHrefBegin . htmlspecialchars($arrObjArt['article']->getFirstHeadline()) . $articleAHrefEnd . '</h2></header>';

                if (isset($primImg) && !empty($primImg)) {

                    //open figure-tag
                    $op .= '<figure>';

                    //add image
                    $op .= $articleAHrefBegin . '<img src="' . htmlentities($primImg) . '" alt="' . htmlentities($arrObjArt['article']->getFirstHeadline()) . '" width="660" />' . $articleAHrefEnd;

                    //close figure tag
                    $op .= '</figure>';
                }

                //add description 
                $op .= '<p class="teaserText">' . htmlspecialchars($arrObjArt['article']->getDescription()) . $articleAHrefBegin . ' <i class="icon-angle-right color888"></i></a></p>';

                //close article-tag
                $op .= '</article>';

                //print article
                echo $op;

                /** End Print Articles
                  ================================================== */
                //initaiate rating-functionality
                if (isset($arrObjArt[0]['rating'])) {
                    echo $arrObjArt[0]['rating']->toString(array('print' => 'js'));
                }
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