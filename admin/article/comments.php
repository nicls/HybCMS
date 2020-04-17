<?php
/** INDEX.PHP ADMIN */
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/hybCMSLoader.php');

/* Page Setup
  ================================================= */
$hcms->setupPage('title', array('title' => 'Comments - Administration', 'prepend' => ' - hybcms.de'));
$hcms->setupPage('description', array('description' => 'Administrations HybCMS'));
$hcms->setupPage('canonical', array('canonical' => HYB_CURRURL));
$hcms->setupPage('noindexNofollow');

/* Javascripts
  ================================================== */
$hcms->registerJS('comments', '/admin/js/comments.js', 2, false, true, 'footer', false);

/* load Plugins
  ================================================== */

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');
?>

<?php
/* select comment
  ============================================= */
try {

    //open Database-Connection
    $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

    //fetch Rating-Data by URL from Database
    $objDBComments = new \HybridCMS\Database\DBComments();
    $arrObjComments = $objDBComments->selectAllComments($db);

    //close Database-Connection
    \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
} catch (Exception $e) {

    //close Database-Connection
    \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");

    throw $e;
}
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row" id="mainContent" itemprop="mainContentOfPage">
        <article class="col-xs-12 col-md-7">
            <h1>Comments</h1>

            <?php foreach ($arrObjComments as $objComment): ?>
                <table class="table table-striped comments">
                    <tr class="comment">
                        <td>
                            <figure class="float_left add-right">
                                <img src="<?php echo $objComment['comment']->buildGravatarUrl($objComment['comment']->getEmail(), 80); ?>" height="80" width="80" />
                            </figure>
                        </td>
                        <td colspan="2">                            
                            <?php echo $objComment['comment']->replaceNewLines(trim($objComment['comment']->getComment())); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Autor</th>
                        <td><?php echo htmlspecialchars($objComment['comment']->getCommentatorName()); ?></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($objComment['comment']->getEmail()); ?></td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td><?php echo htmlspecialchars($objComment['comment']->getWebsite()); ?></td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td><?php echo htmlspecialchars($objComment['url']); ?></td>
                    </tr>
                    <tr>
                        <th>Datum</th>
                        <td><?php echo date('h:i:s, j.m.y', $objComment['comment']->getTimeCreated()); ?></td>
                    </tr>
                    <tr class="setup success">
                        <td colspan="2" id="<?php echo $objComment['comment']->getCommendId(); ?>">
                            <span class="add-right f13 event-comment-delete"><i class="fa fa-trash-o"></i> löschen</span>
                            <?php if ($objComment['comment']->getPublished()): ?>
                                <span class="add-right f13 event-comment-block"><i class="fa fa-square-o"></i> <span>sperren</span></span>
                            <?php else: ?>
                                <span class="add-right f13 event-comment-publish"><i class="fa fa-check-square"></i> <span>veröffentlichen</span></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?>


        </article>
    </div><!-- end .row -->
</div><!-- container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php');
?>