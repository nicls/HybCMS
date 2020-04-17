<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Suche', 'prepend' => ' - hybcms.vs'));
    $hcms->setupPage('description', array('description' => 'Suche'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.vs/suche.html'));

    /* get Articles 
      ==================================================== */
    $arrObjArticles = array();
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $objSearch = new \HybridCMS\Content\Section\Search($_GET['q']);
        $arrObjArticles = $objSearch->getArrArticles();
    }

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //AccNameGenerator-Plugin
    $onlyForSidebar = true;
    $objAccNameGen = new \HybridCMS\Plugins\AccNameGenerator\AccNameGenerator($onlyForSidebar);
    if ($objAccNameGen) {
        $hcms->loadPlugin($objAccNameGen);
    }

    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles('latestArticles1', 'aside1', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
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


            <header>
                <h1>Suchergebnisse (<?php echo count($arrObjArticles); ?>)</h1>
                <p class="lead">Ihre Eingabe: <?php if (isset($_GET['q']) && !empty($_GET['q'])) echo htmlspecialchars($_GET['q']); ?></p>
                <hr class="add-bottom-20" />
            </header>

            <?php
            /** Begin Print Schriftanatomie Article <-------------
              ================================================== */
            $cnt = 0;


            foreach ($arrObjArticles as &$objArt) {

                //continue if no article is available
                if (!$objArt) {
                    continue;
                }

                //get img in 660px width
                $primImg = null;
                if ($objArt->getPrimaryImageOfPage()) {
                    $primImg = $hcms->scaleImg($objArt->getPrimaryImageOfPage(), 660);
                }

                //build a-tag to the Article
                $articleAHrefBegin = '<a href="' . htmlentities($objArt->getUrl()) . '" name="' . htmlentities($objArt->getFirstHeadline()) . '" title="' . htmlentities($objArt->getFirstHeadline()) . '">';
                $articleAHrefEnd = '</a>';

                //print every 5th article over the whole width
                $articleWidth = 'c6';


                $op = '';

                //open Article-tag
                $op .= '<article class="' . $articleWidth . ' add-bottom-30">';

                //set headline
                $op .= '<header><h3>' . $articleAHrefBegin . htmlspecialchars($objArt->getFirstHeadline()) . $articleAHrefEnd . '</h3></header>';

                if (isset($primImg) && !empty($primImg)) {

                    //open figure-tag
                    $op .= '<figure>';

                    //add image
                    $op .= $articleAHrefBegin . '<img src="' . htmlentities($primImg) . '" alt="' . htmlentities($objArt->getFirstHeadline()) . ' width="660" />' . $articleAHrefEnd;

                    //close figure tag
                    $op .= '</figure>';
                }

                //add description 
                $op .= '<p class="teaserText">' . htmlspecialchars($objArt->getDescription()) . $articleAHrefBegin . ' <i class="icon-angle-right color888"></i></a></p>';

                //close article-tag
                $op .= '</article>';

                //print article
                echo $op;
            }

            /** End Print Schriftanatomie Article <-------------
              ================================================== */
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