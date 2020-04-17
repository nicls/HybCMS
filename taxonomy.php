<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page


    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'HybridCMS', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));

    /* get Articles 
      ==================================================== */
    $objTaxonomy = new \HybridCMS\Content\Section\Taxonomy('testKeyword1');
    $arrObjArticles = $objTaxonomy->getArrArticles();

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //Rating-Plugin
    $arrObjArticlePack = array();

    foreach ($arrObjArticles as &$objArticle) 
    {
        //select Rating for each Article
        $objRating = new \HybridCMS\Plugins\Rating\Rating(
                $objArticle->getUrl());
        $objRating->setIsDisabled('true');
        $arrObjArticlePack[] = array('article' => $objArticle, 
                                     'rating' => $objRating);
    }

    //load CSS und JS Resources for Plugin Rating
    if (isset($arrObjArticlePack[0]['rating'])) {
        $hcms->loadPlugin($arrObjArticlePack[0]['rating']);
    }

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
        <div class="col-xs-12 col-md-7">
            <h2>Taxonomie "testKeyword1"</h2>
            <p class="lead">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>

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
                $articleAHrefBegin = '<a href="' 
                        . htmlentities($arrObjArt['article']->getUrl()) 
                        . '" title="' 
                        . htmlentities($arrObjArt['article']->getFirstHeadline()) . '">';
                
                //close a-tag
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
                $op .= '<header><h2>' . $articleAHrefBegin 
                        . htmlspecialchars($arrObjArt['article']->getFirstHeadline()) 
                        . $articleAHrefEnd . '</h2></header>';

                if (isset($primImg) && !empty($primImg)) {

                    //open figure-tag
                    $op .= '<figure>';

                    //add image
                    $op .= $articleAHrefBegin . '<img src="' 
                            . htmlentities($primImg) . '" alt="' 
                            . htmlentities($arrObjArt['article']->getFirstHeadline()) 
                            . '" width="660" />' . $articleAHrefEnd;

                    //close figure tag
                    $op .= '</figure>';
                }

                //add description 
                $op .= '<p class="teaserText">' 
                        . htmlspecialchars($arrObjArt['article']->getDescription()) 
                        . $articleAHrefBegin 
                        . ' <i class="icon-angle-right color888"></i></a></p>';

                //close article-tag
                $op .= '</article>';

                //print article
                echo $op;

                //initaiate rating-functionality
                if (isset($arrObjArt[0]['rating'])) {
                    echo $arrObjArt[0]['rating']->toString(array('print' => 'js'));
                }
            }
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