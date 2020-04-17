<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Plugin Comptable', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de'));
    
    /**
     * CSS
     *
     * 1. $resourceName
     * 2. $resourcePath
     * 3. $priority
     * 4. $minify
     * 5. $autoActivate
     */
    $hcms->registerCSS('jQueryUI', 'https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.min.css', 10, false, true);
    $hcms->registerCSS('slider', '/css/jquery-ui-slider-pips.css', 11, true, true);  
    
    /**
     * JS
     * 
     * 1. $resourceName
     * 2. $resourcePath
     * 3. $priority
     * 4. $minify
     * 5. $autoActivate
     * 6. $position
     * 7. $async
     */
    $hcms->registerJS('jQueryUI', 'https://code.jquery.com/ui/1.11.1/jquery-ui.min.js', 2, false, true, 'footer', false);   
    $hcms->registerJS('slider', '/js/jquery-ui-slider-pips.js', 10, false, true, 'footer', false);
    if(false === $hcms->clientIsDesktop()) 
    {    
        $hcms->registerJS('touchpunch', '/js/jquery.ui.touch-punch.min.js', 13, false, true, 'footer', false);
    }
    if(true === $hcms->clientIsDesktop()) {
        $hcms->registerJS('zoom', '/js/jquery.zoom.min.js', 14, false, true, 'footer', false);
    }   

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);

    //comptable-Plugin
    //comptable-Plugin
    if(true === $hcms->clientIsMobile()) {
        $arrWhitelistKeys = array(
            'Preis ca.',
            'Hersteller',    
            'Bewertung bei Amazon',            
            'Auflösung',
            'Auflösung interpoliert',
            'Auslösezeit',
            'Blitz ist unsichtbar',
            'Blitzreichweite',
            'Display',        
            'Senden an Email (GPRS)',
            'WLAN'
        );
        $arrBlacklistKeys = array(
            'Bedienungsanleitung'
        );
        
        $objComptable = new \HybridCMS\Plugins\Comptable\ComptableMobile('Wildkameras', $arrWhitelistKeys, $arrBlacklistKeys);
        $objComptable->setKeyPrice('Preis ca.');
        
    } else {
        $objComptable = new \HybridCMS\Plugins\Comptable\Comptable('Wildkameras');
    }
    $objComptable->fetchComptable();
    $hcms->loadPlugin($objComptable);

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
<div itemprop="mainContentOfPage">
    <section class="" id="mainContent" itemprop="mainContentOfPage">
        
        <div class="container">
            <div id="hyb_comp_filter">
                <div class="row">
                    <h2>Filter</h2>
                    <div class="hyb_comp_filter hyb_comp_filter_hersteller col-md-2"></div>
                    <div class="col-md-10">
                        <div class="hyb_comp_filter hyb_comp_filter_gprs"></div>
                        <div class="hyb_comp_filter hyb_comp_filter_blitz"></div>
                    </div>
                </div>
                <div class="row add-bottom-30">
                    <div class="col-md-10">
                        <div class="hyb_comp_filter hyb_comp_filter_preis"></div>
                    </div>
                </div>
                <div class="row add-bottom-30">
                    <div class="col-md-10">
                        <div class="hyb_comp_filter hyb_comp_filter_amazon"></div>
                    </div>
                </div>                
            </div>
        </div>
        
        <div>
            <div class="inlineBlock" style="margin-left: 400px; position: relative;">

                <div style="margin-left: -220px; position: absolute;">
                    <?php
                    if (isset($objComptable)) {
                        try {
                            echo $objComptable->toString(array('content' => 'keys'));
                        } catch (Exception $e) {

                            //Log Error
                            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                            $objLogger->logError($e->__toString() . "\n");
                        }
                    }
                    ?>
                </div>   

                <?php
                if (isset($objComptable)) {
                    try {
                        echo $objComptable->toString(array('content' => 'comptable'));
                    } catch (Exception $e) {

                        //Log Error
                        $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                        $objLogger->logError($e->__toString() . "\n");
                    }
                }
                ?>
            </div>                     

        </div><!-- end .col-xs-12 .col-md-12 -->



        <div class="container add-top-60">

            <!-- print Changelogs -->
            <article class="row add-top-30">
                <div class="col-xs-12 col-md-7">
                    
                    <h3>Letzte Aktualisierungen:</h3>
                    <?php
                    if (isset($objComptable)) {
                        try {
                            echo $objComptable->toString(array('content' => 'changelog'));
                        } catch (Exception $e) {

                            //Log Error
                            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                            $objLogger->logError($e->__toString() . "\n");
                        }
                    }
                    ?>
                </div><!-- end .col-xs-12 .col-md-7 -->
            </article><!-- end section.row -->
            
            <!-- print new Tables -->
            <article class="row add-top-30">
                <div class="col-xs-12 col-md-7">
                    
                    <h3>Neue Webfontsanbieter hinzugefügt:</h3>
                    <?php
                    if (isset($objComptable)) {
                        try {
                            echo $objComptable->toString(array('content' => 'newTables'));
                        } catch (Exception $e) {

                            //Log Error
                            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                            $objLogger->logError($e->__toString() . "\n");
                        }
                    }
                    ?>
                </div><!-- end .col-xs-12 .col-md-7 -->
            </article><!-- end section.row -->            

            <!-- print tables -->
            <section id="mainContent">

                <!-- Table Fonts.com -->
                <article class="row add-top-30">
                    <div class="col-xs-12 col-md-7">
                        <?php
                        if (isset($objComptable)) {
                            try {
                                echo $objComptable->toString(array('content' => 'table', 'tableName' => 'GPRS-Cam 2'));
                            } catch (Exception $e) {

                                //Log Error
                                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                                $objLogger->logError($e->__toString() . "\n");
                            }
                        }
                        ?>
                    </div><!-- end .col-xs-12 .col-md-7 -->
                </article><!-- end section.row -->


                <!-- Table Linotype -->
                <article class="row add-top-30" >
                    <div class="col-xs-12 col-md-7">
                        <?php
                        if (isset($objComptable)) {

                            try {
                                echo $objComptable->toString(array('content' => 'table', 'tableName' => 'SnapShot MINI'));
                            } catch (Exception $e) {

                                //Log Error
                                $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
                                $objLogger->logError($e->__toString() . "\n");
                            }
                        }
                        ?>
                    </div><!-- end .col-xs-12 .col-md-7 -->
                </article><!-- end section.row -->  

            </section>
        </div>

    </section><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
?>