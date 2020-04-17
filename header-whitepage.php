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
$hcms->registerCSS('bootstraptheme', '/css/bootstrap-theme.css', 4, true, true);
$hcms->registerCSS('overrideBootstrap', '/css/override-bootstrap.css', 5, true, true);
$hcms->registerCSS('overrideBootstrapTheme', '/css/override-bootstrap-theme.css', 5, true, true);
$hcms->registerCSS('styles', '/css/styles.css', 6, true, true);
$hcms->registerCSS('layoutWhitepage', '/css/layout-whitepage.css', 7, true, true);
$hcms->registerCSS('fontawesome', '/css/font-awesome.min.css', 8, false, true);

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
$hcms->registerJS('bootstrap', '/js/bootstrap.js', 2, true, true, 'footer', false);
$hcms->registerJS('functions', '/js/functions.js', 3, true, true, 'footer', false);

/* global Plugins setup in the setuoGlobalPlugins.php */
//load Ressources of the ToTop-Slider Plugin
if (isset($objToTop)){
    $hcms->loadPlugin($objToTop);
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
        echo $hcms->getPageSetting('keywords');
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
        <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png">

    </head>
    <body hyb-device="<?php echo htmlentities($hcms->getDeviceType()); ?>">