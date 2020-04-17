<?php

/** addArticle.php ADMIN */
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/hybCMSLoader.php');

/** check if user has the necessary rights to view this page
================================================================= */
            
if (!\HybridCMS\Helper\Helper::isUser('admin') && !\HybridCMS\Helper\Helper::isUser('author')) {
    header('Location: ' . '/admin/login.html');
}

/* Page Setup
  ================================================= */
$hcms->setupPage('title', array('title' => 'Add Article - Administration', 'prepend' => ' - hybcms.de'));
$hcms->setupPage('description', array('description' => 'Administrations HybCMS'));
$hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
$hcms->setupPage('noindexNofollow');

/* Javascripts
  ================================================== */
$hcms->registerJS('comments', '/admin/js/addArticle.js', 2, false, true, 'footer', false);

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
            <h1>Article hinzufügen und ändern</h1>

            <!-- Sync Page width DB -->
            <form method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">

                <div class="errorMsg"></div>
                <input class="form-control add-bottom-20" type="url" placeholder="URL eingeben" name="articleUrl"/>

                <div class="errorMsg"></div>
                <input class="form-control add-bottom-20" type="text" placeholder="CSS-Id eingeben" name="cssId" />

                <p class="userResponse"></p>

                <input class="btn btn-success float_right" type="submit" value="Sync" name="insert">
                <input class="btn btn-primary float_right add-right-10" type="submit" value="Update" name="update">
            </form>
        </article>
    </div><!-- end .row -->
</div><!-- container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php');
?>
