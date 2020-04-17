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
#$hcms->registerCSS('opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800|Open+Sans+Condensed:300,300italic,700', 1, false, true);


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
$hcms->registerJS('amp', 'https://cdn.ampproject.org/v0.js', 1, false, true, 'head', true);

/* global Plugins setup in the setuoGlobalPlugins.php */
?>
<!doctype html>
<html amp lang="de" itemscope="itemscope"  itemtype="http://schema.org/WebPage">
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
        echo $hcms->getPageSetting('hreflang');
        echo $hcms->getPageSetting('prefetch');
        ?>

        <!-- Mobile Specific Metas
        ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- CSS
        ================================================== -->
        <!-- only one style tag is allowed, and it must have an "amp-custom" attribute -->
        <style amp-custom>
          body {
            background-color: lightblue;
          }
          amp-img {
            background-color: gray;
          }
        </style>
        <style>body {opacity: 0}</style><noscript><style>body {opacity: 1}</style></noscript>
        <?php $hcms->printActiveCSSResources(); ?>

        <!-- JS
        ================================================== -->
        <?php $hcms->printActiveJSResources('head'); ?>
        
        <!-- JSONLD
        ================================================== -->
        <?php if(true === isset($arrJsonLD)) { echo $hcms->arrayToJsonLD($arrJsonLD); } ?>

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
            </div>
        </div><!-- container -->