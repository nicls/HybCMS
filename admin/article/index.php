<?php
/** INDEX.PHP ADMIN */
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/hybCMSLoader.php');

/* Page Setup
  ================================================= */
$hcms->setupPage('title', array('title' => 'Article - Administration', 'prepend' => ' - hybcms.de'));
$hcms->setupPage('description', array('description' => 'Administrations HybCMS'));
$hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
$hcms->setupPage('noindexNofollow');

/* load Plugins
  ================================================== */

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');
?>


<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row" id="mainContent" itemprop="mainContentOfPage">
        <article class="col-xs-12 col-md-7">
            <h1>Article</h1>




        </article>
    </div><!-- end .row -->
</div><!-- container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php');
?>