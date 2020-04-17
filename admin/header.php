<?php
/** HEADER ADMIN */
/* CSS */
$hcms->registerCSS('webfont', 'https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700', 1, false, true);
$hcms->registerCSS('satisfy', 'https://fonts.googleapis.com/css?family=Satisfy', 2, false, true);
$hcms->registerCSS('bootstrap', '/admin/css/bootstrap.css', 3, true, true);
$hcms->registerCSS('bootstraptheme', '/admin/css/bootstrap-theme.css', 4, true, true);
$hcms->registerCSS('styles', '/admin/css/styles.css', 5, true, true);
$hcms->registerCSS('layout', '/admin/css/layout.css', 6, true, true);
$hcms->registerCSS('fontAwesome', '/admin/css/font-awesome.min.css', 7, false, true);

/* JS */
$hcms->registerJS('jQuery', 'https://code.jquery.com/jquery-latest.min.js', 1, false, true, 'head', false);
$hcms->registerJS('bootstrap', '/admin/js/bootstrap.js', 2, true, true, 'footer', false);
$hcms->registerJS('functions', '/admin/js/functions.js', 3, true, true, 'footer', false);
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
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
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png">

    </head>
    <body>

        <!-- Logo -->
        <div class="container  add-top-40">
            <div class="row">
                <header id="logo" class="col-xs-10 col-md-10">                    
                    <a href="/admin/"><img src="/admin/images/logo-hybcms.svg" alt="Logo HybCMS" height="50" width="50" />HybCMS</a>
                </header>

                <!-- Logout -->
                <form class="col-xs-2 col-md-2" id="logout" method="POST" action="/admin/login.php">
                    <input type="hidden" name="logout" value="logout" />
                    <input type="submit" class="btn btn-danger" value="logout" />
                </form>
            </div>

        </div><!-- container -->


        <div id="container_mainnav" class="add-top-30 add-bottom-30">         
            <div class="container">
                <?php
                /* include Nav
                  ================================================== */
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/nav.php');
                ?>

            </div><!-- container -->
        </div>        