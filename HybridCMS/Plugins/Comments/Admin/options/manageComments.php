<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comments', '/HybridCMS/Plugins/Comments/Admin/js/Comments.js', 4, false, true, 'footer', false);
    $hcms->registerJS('manageComments', '/HybridCMS/Plugins/Comptable/Admin/js/manageComments.js', 5, false, true, 'footer', false);
    
    /* CSS
      ================================================== */
    $hcms->registerCSS('comments', '/HybridCMS/Plugins/Comments/Admin/css/comments.css');    

    /* load Plugins
      ================================================== */
    
    /**
     * Load all new Comments
     */
    $arrParamsNewCom = $_POST;
    
    //add content for comment delete button
    $key = 'objContentDeleteComment';
    $arrParamsNewCom[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonDeleteComment();   
    
    //add content for comment publish button
    $key = 'objContentPublishComment';
    $arrParamsNewCom[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonPublishComment();      
    
    //create controller to list all new comments
    $ctrl = 'ControllerCommentList';
    $objCtrlNewCommentList = \HybridCMS\Plugins\Comments\Admin\Controller
            \ControllerFactory::create($ctrl, $arrParamsNewCom);        
} 
catch (\Exception $e) 
{
    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
            \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');
?>


<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row" id="mainContent" itemprop="mainContentOfPage">
        <article class="col-xs-12 col-md-7">
            <h1>Manage Comments</h1>
            <hr class="add-top-30"/> 

            <?php
                echo $objCtrlNewCommentList->toString();
            ?>
            
            <a class="btn btn-success" href="/admin/plugins/index.php?name=comments&action=commentArchive" title="Comemnt Archive">
                Comment Archive -></a>
        </article>
    </div><!-- end .row -->
</div><!-- container -->