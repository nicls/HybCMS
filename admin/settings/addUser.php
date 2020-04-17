<?php

/** addArticle.php ADMIN */
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/hybCMSLoader.php');

/** check if user has the necessary rights to view this page
================================================================= */
if (!\HybridCMS\Helper\Helper::isUser('admin')) {
    header('Location: ' . '/admin/login.php');
}

/* Page Setup
  ================================================= */
$hcms->setupPage('title', array('title' => 'Add User - Settings', 'prepend' => ' - hybcms.de'));
$hcms->setupPage('description', array('description' => 'Administrations HybCMS'));
$hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
$hcms->setupPage('noindexNofollow');

/* Javascripts
  ================================================== */
$hcms->registerJS('user', '/admin/js/User.js', 2, false, true, 'footer', false);
$hcms->registerJS('adduser', '/admin/js/addUser.js', 2, false, true, 'footer', false);

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
            <h1>User hinzufügen und ändern</h1>

            <!-- Add user to DB -->
            <form method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <input class="form-control add-bottom-20" type="text" placeholder="Name" name="username"/>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <select class="form-control" name="rolename">
                            <option value="defaultValue">Rolle</option>
                            <option value="admin">Administrator</option>
                            <option value="author">Author</option>
                            <option value="editor">Editor</option>                    
                        </select>    
                    </div>
                </div>
                                
                <input class="form-control add-bottom-20" type="password" placeholder="Passwort" name="password"/>
                <input class="form-control add-bottom-20" type="email" placeholder="Email" name="email"/>
                
                <label class="add-bottom-20">Weitere optionale Angaben:</label>                
                <input class="form-control add-bottom-20" type="url" placeholder="URL Twitter Account" name="twitter"/>
                <input class="form-control add-bottom-20" type="url" placeholder="URL Facebook Account" name="facebook"/>
                <input class="form-control add-bottom-20" type="url" placeholder="URL Google Plus Account" name="googleplus"/>
                <input class="form-control add-bottom-20" type="url" placeholder="URL Youtube Account" name="youtube"/>
                <input class="form-control add-bottom-20" type="url" placeholder="Webseite" name="website"/>
                <textarea class="form-control add-bottom-20" type="text" placeholder="Über mich..." name="aboutme"></textarea>

                <input class="btn btn-success float_right" type="submit" value="Speichern" name="insertUser">
            </form>
        </article>
    </div><!-- end .row -->
</div><!-- container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php');
?>
