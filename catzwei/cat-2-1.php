<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Title Cat2 Article 1', 'prepend' => ' - hybcmd.vs'));
    $hcms->setupPage('description', array('description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Integer nec odio.'));
    $hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));

    /* load Plugins
      ================================================== */
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //Rating-Plugin
    $objRatingPlugin = new \HybridCMS\Plugins\Rating\Rating(HYB_CURRURL);
    if ($objRatingPlugin) {
        $hcms->loadPlugin($objRatingPlugin);
    }
    
    //author plugin
    $objUser = $hcms->fetchAuthor('ZoefyC');
    $objAuthor = null;
    if(!empty($objUser)) { $objAuthor = new \HybridCMS\Plugins\Author\Author($objUser); }
    if ($objAuthor) { $hcms->loadPlugin($objAuthor); }    

    /* add asideWidgets
     =================================================== */
    $arrParams = array('name' => 'Kohlmeise');
    $assArrKeyAssArrValue = array(
        'Ordnung' => array('value' => 'Sperlingsvögel (Passeriformes)'),
        'Unterordnung' => array('value' => 'Singvögel (Passeri)'),
        'Familie' => array('value' => 'Meisen (Paridae)'),
        'Verbreitung' => array('value' => 'Europa, Naher Osten, Asien'),
        'Bestand' => array('value' => 
            '4,6 bis 5,7 Millionen Brutpaare in 2008'),
        'Lebensraum' => array('value' => 'Laub- und Mischwälder'),
        'Nahrung' => array('value' => 'Insekten, Larven, Samen, Nüsse'),
        'Nisten' => array('value' => 'Baumhölen, Nistkästen'),
        'Verhalten' => array('value' => 
            'Wenig ängstlich, hält sich in Baumen und am Bodena auf.')        
    );
    $objWidgetGenTable = new \HybridCMS\AsideWidgets\WidgetGenericTable(
            'genericTable', 'sidebar', 1, $arrParams);
    $objWidgetGenTable->setAssArrKeyAssArrValue($assArrKeyAssArrValue);
    $objWidgetGenTable->setItemType('https://schema.org/Thing');
    $hcms->addAsideWidget($objWidgetGenTable);    
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
            <h1>H1 Category 2 Article 1</h1>
            <pre>
            <?php print_r($objAuthor); ?>
            </pre>
            
            <figure class="add-bottom" id="primaryImageOfPage">
                <a class="lightbox-gallery" href="/images/catzwei/blume-1273x424.jpg" title="Blume">
                    <img itemprop="primaryImageOfPage" src="<?php echo htmlentities($hcms->scaleImg('/images/catzwei/blume-1273x424.jpg', 660)); ?>" alt="Blume" height="206" width="660" />
                </a>
                <figcaption class="half-left">Blume</figcaption>
            </figure>  
            
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <aside class="add-bottom-20">
               <?php if(isset($objAuthor) && !empty($objAuthor)) {
                   echo $objAuthor->toString();
               }
               ?>
            </aside>
            
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