<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'HybridCMS', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/en/'));
    $hcms->setupPage('hreflang', array('url' => 'http://hybcms.de/en/', 'lang' => 'en'));
    $hcms->setupPage('hreflang', array('url' => 'http://hybcms.de/de/', 'lang' => 'de'));

    /* get Articles 
      ==================================================== */
    $objCategory = new \HybridCMS\Content\Section\Category('cateins');
    $arrObjArticles = $objCategory->getArrArticles();

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //Rating-Plugin
    $arrObjArticlePack = array();

    //select Rating for each Article loaded
    foreach ($arrObjArticles as &$objArticle) {
        $objRating = new \HybridCMS\Plugins\Rating\Rating($objArticle->getUrl());
        $objRating->setIsDisabled('true');
        $arrObjArticlePack[] = array('article' => $objArticle, 'rating' => $objRating);
    }

    //load CSS und JS Resources for Plugin Rating
    if (isset($arrObjArticlePack[0]['rating'])) {
        $hcms->loadPlugin($arrObjArticlePack[0]['rating']);
    }
    
    //fontSize Plugin
    $objFontSize = new \HybridCMS\Plugins\FontSize\FontSize();
    if (!empty($objFontSize)) {
        $hcms->loadPlugin($objFontSize);
    }
    
    //CTAButton Plugin
    $arrParams['cta'] = 'Jetzt klicken!';
    $arrParams['url'] = '/login.html';
    $arrParams['action'] = 'showCTAButtonContent';
    $arrParams['classNames'] = 'btn, btn-warning, btn-lg';
    $arrParams['targetBlank'] = false;
    $arrParams['nofollow'] = true;
    $objControllerCTAButton = \HybridCMS\Plugins\CTAButton\Controller\ControllerFactory::create(
            'ControllerCTAButton', $arrParams);
    if (!empty($objControllerCTAButton)) {
        $hcms->loadPlugin($objControllerCTAButton);
    }

    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles('latestArticles', 'sidebar', 1, $arrParams);
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
            
            <?php /** Print FontSize Plugin
            ================================================== */
            if(isset($objFontSize) && !empty($objFontSize)) {
                echo $objFontSize->toString();
            }
            ?>
            
            <h2>Testseite</h2>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>

            <?php /** Print CTAButton Plugin
            ================================================== */
            if(true === isset($objControllerCTAButton)) {
                echo $objControllerCTAButton->toString();
            }
            ?>
            
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

                /** End Print Articles
                  ================================================== */
                //initaiate rating-functionality
                if (isset($arrObjArt[0]['rating'])) {
                    echo $arrObjArt[0]['rating']->toString(array('print' => 'js'));
                }
            }
            ?>
            
            <p class="add-top-30">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. </p>
            <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. </p>
            <p>Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. </p>
            <p>Suspendisse in justo eu magna luctus suscipit. Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui. Praesent blandit dolor. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. Donec lacus nunc, viverra nec, blandit vel, egestas et, augue. Vestibulum tincidunt malesuada tellus. </p>
            <p>Ut ultrices ultrices enim. Curabitur sit amet mauris. Morbi in dui quis est pulvinar ullamcorper. Nulla facilisi. Integer lacinia sollicitudin massa. Cras metus. Sed aliquet risus a tortor. Integer id quam. Morbi mi. Quisque nisl felis, venenatis tristique, dignissim in, ultrices sit amet, augue. Proin sodales libero eget ante. Nulla quam. Aenean laoreet. </p>



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