<?php if(!defined('ROOTDOC')) die();
/**
 * CSS
 *
 * 1. $resourceName
 * 2. $resourcePath
 * 3. $priority
 * 4. $minify
 * 5. $autoActivate
 */
$hcms->registerCSS('opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800|Open+Sans+Condensed:300,300italic,700', 1, false, true);
$hcms->registerCSS('satisfy', 'https://fonts.googleapis.com/css?family=Satisfy', 2, false, true);
$hcms->registerCSS('bootstrap', '/css/bootstrap.css', 3, true, true);
#$hcms->registerCSS('bootstraptheme', '/css/bootstrap-theme.css', 4, true, true);
$hcms->registerCSS('overrideBootstrap', '/css/override-bootstrap.css', 5, true, true);
#$hcms->registerCSS('overrideBootstrapTheme', '/css/override-bootstrap-theme.css', 5, true, true);
$hcms->registerCSS('styles', '/css/styles.css', 6, true, true);
$hcms->registerCSS('layout', '/css/layout.css', 7, true, true);
$hcms->registerCSS('fontawesome', '/css/font-awesome.min.css', 8, false, true);
$hcms->registerCSS('magnificPopup', '/css/magnific-popup.css', 9, true, true);


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
$hcms->registerJS('jQuery', 'https://code.jquery.com/jquery-latest.min.js', 1, false, true, 'head', false);
$hcms->registerJS('jQueryUI', 'https://code.jquery.com/ui/1.11.1/jquery-ui.js', 2, false, true, 'head', true);
$hcms->registerJS('bootstrap', '/js/bootstrap.js', 2, true, true, 'footer', false);
$hcms->registerJS('functions', '/js/functions.js', 3, true, true, 'footer', false);
$hcms->registerJS('popupWindow', '/js/jquery.popup.js', 4, true, true, 'footer', true);
$hcms->registerJS('viewport', '/js/viewport.js', 5, true, true, 'footer', true);
$hcms->registerJS('magnificPopup', '/js/jquery.magnific-popup.min.js', 6, true, true, 'footer', true);
$hcms->registerJS('init', '/js/init.js', 11, true, true, 'footer', true);

/* global Plugins setup in the setuoGlobalPlugins.php */

//load Ressources of the ToTop-Slider Plugin
if(true === isset($objToTop))
{
    $hcms->loadPlugin($objToTop);
}

if(true === isset($objTCB)) 
{ 
    $hcms->loadPlugin($objTCB); 
}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="de" itemscope="itemscope"  itemtype="http://schema.org/WebPage"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="de" itemscope="itemscope"  itemtype="http://schema.org/WebPage"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="de" itemscope="itemscope"  itemtype="http://schema.org/WebPage"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="de" itemscope="itemscope"  itemtype="http://schema.org/WebPage"> <!--<![endif]-->
    <head>

        <!-- Basic Page Needs
        ================================================== -->
        <meta charset="utf-8">
        <?php
        echo $hcms->getPageSetting('noindex');
        echo $hcms->getPageSetting('noindexnofollow');
        echo $hcms->getPageSetting('title');
        echo $hcms->getPageSetting('description');
        echo $hcms->getPageSetting('canonical');
        echo $hcms->getPageSetting('amphtml');
        echo $hcms->getPageSetting('keywords');
        echo $hcms->getPageSetting('hreflang');
        echo $hcms->getPageSetting('prefetch');
        ?>

        <!-- Mobile Specific Metas
        ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- CSS
        ================================================== -->
        <?php $hcms->printActiveCSSResources(); ?>

        <!-- JS
        ================================================== -->
        <?php $hcms->printActiveJSResources('head'); ?>

        <!-- Favicons
        ================================================== -->
        <link rel="shortcut icon" href="/images/favicon.ico">
        <link rel="apple-touch-icon" href="/images/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png">

    </head>
    <body hyb-device="<?php echo htmlentities($hcms->getDeviceType()); ?>">
        <div class="container add-top-40">
            <div class="row">
                <header id="logo" class="col-xs-12 col-md-7">
                    <a href="/" title="Startseite">
                        <img class="float_left" src="/images/logo-hybcms.svg" alt="Logo HybCMS" height="40" width="40" /> HybCMS
                    </a>
                </header>
                            
                <div class="col-xs-6 col-md-3 form-inline">
                    <form method="GET" action="/suche.html">                        
                        <input data-provide="typeahead" class="form-control" type="search" name="q" placeholder="Suchbegriff eingeben" value="<?php if (isset($_GET['q'])) echo htmlentities(trim($_GET['q'])); ?>" />
                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                    </form>   
                </div>
                
                <!-- Logout -->
                <?php
                    if(true === isset($objControllerLoginButton))
                    {
                        echo $objControllerLoginButton->toString();
                    }
                ?>              
            </div>
        </div><!-- container -->

        <div id="container_mainnav" class="add-top-30 add-bottom-30">         
            <div class="container">
                <?php
                /* include Nav
                  ================================================== */
                require_once($_SERVER['DOCUMENT_ROOT'] . '/nav.php');
                ?>

            </div><!-- container -->
        </div>

        <!-- breadcrumb -->
        <?php if(false === $hcms->clientIsMobile()) : ?>
        <div class="container add-bottom-20">
            <div class="row">
                <div id="breadcrumb" class="col-xs-12 col-md-7">
                    <?php if (isset($objBreadcrumb)) echo $objBreadcrumb->toString(); ?>
                </div>
                <div id="bookmarks_container" class="col-xs-6 col-md-5">
                    <div class="float_right">
                        <span id="bookmarkLabel" class="half-right">Produkt merken:</span>
                        <span class="half-right addBookmark"><i class="fa fa-plus"></i></span>
                        <span class="listBookmarks"><i class="fa fa-bookmark"></i><span></span></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>