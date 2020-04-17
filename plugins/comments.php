<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Plugin Comments', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de'));

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    //Plugin Comments                   
    //take POST Params
    $arrParams_Zwei = $arrParams_Drei = $arrParams_Vier = $_POST;
        
    //set different comment-submition keys for each form
    $arrParams_Zwei['formSubmitted'] = 'hyb_comments_addComment_#zwei';
    $arrParams_Drei['formSubmitted'] = 'hyb_comments_addComment_#drei';
    $arrParams_Vier['formSubmitted'] = 'hyb_comments_addComment_#vier';
    
    //set corresponding comment-form urls
    $arrParams_Zwei['hyb_comments_url'] = HYB_CURRURL . '#zwei';
    $arrParams_Drei['hyb_comments_url'] = HYB_CURRURL . '#drei';
    $arrParams_Vier['hyb_comments_url'] = HYB_CURRURL . '#vier';
        
    /**
     * Setup Comments '#zwei'
     * ======================
     */
    //add unique Username input-element in the comments form
    $username = $comment = $email = null;
    
    //ensure that this form was submitted check if form-button was clicked
    if(true === isset($arrParams_Zwei[$arrParams_Zwei['formSubmitted']]))
    {
        //check if username was submitted per form
        $username = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_usernameUnregistered');
        
        //check if comment was submitted per form 
        $comment = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_comment');     
        
        //check if email was submitted per form 
        $email = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_email');         
    } 
    
    //add content object for the username
    $key = 'objContentUsernameUnregistered';
    $arrParams_Zwei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentUsernameUnregistered($username); 
    
    //add content-object for the comment
    $key = 'objContentComment';
    $arrParams_Zwei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentComment($comment);   
    
    //add content-object for email
    $key = 'objContentEmailUnique';
    $arrParams_Zwei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentEmailUnique($email);   
    
    //add content for comment submit button
    $key = 'objContentAddComment';
    $arrParams_Zwei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonAddComment();     
        
    //create controller to add new comments
    $ctrl = 'ControllerCommentAdderUnregisteredUsers';
    $objCtrlCommentAdderZwei = \HybridCMS\Plugins\Comments\Controller
            \ControllerFactory::create($ctrl, $arrParams_Zwei);   
    
    //Comments List
    ###############
    
    $key = 'objContentButtonListComments';
    $arrParams_Zwei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonListComments();    
    
    //create controller to show all comments in list
    $ctrl = 'ControllerCommentList';
    $objCtrlCommentListZwei = \HybridCMS\Plugins\Comments\Controller
            \ControllerFactory::create($ctrl, $arrParams_Zwei);
    
    /**
     * Setup Comments '#drei'
     * =====================
     */
    //add unique Username input-element in the comments form
    $username = $comment = $email = null;
    
    //ensure that this form was submitted check if form-button was clicked
    if(true === isset($arrParams_Drei[$arrParams_Drei['formSubmitted']]))
    {
        //check if username was submitted per form
        $username = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_usernameUnregistered');
        
        //check if comment was submitted per form 
        $comment = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_comment');  
        
        //check if email was submitted per form 
        $email = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_email');            
    } 
    
    //add content object for the username
    $key = 'objContentUsernameUnregistered';
    $arrParams_Drei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentUsernameUnregistered($username);
    
    //add content-object for the comment
    $key = 'objContentComment';
    $arrParams_Drei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentComment($comment);
    
    //add content-object for email
    $key = 'objContentEmailUnique';
    $arrParams_Drei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentEmailUnique($email);   
    
    //add content for comment submit button
    $key = 'objContentAddComment';
    $arrParams_Drei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonAddComment();      
    
    //create controller to add new comments
    $ctrl = 'ControllerCommentAdderUnregisteredUsers';
    $objCtrlCommentAdderDrei = \HybridCMS\Plugins\Comments\Controller
            \ControllerFactory::create($ctrl, $arrParams_Drei);   
    
    //Comments List
    ###############
    
    $key = 'objContentButtonListComments';
    $arrParams_Drei[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonListComments();
    
    //create controller to show all comments in list
    $ctrl = 'ControllerCommentList';
    $objCtrlCommentListDrei = \HybridCMS\Plugins\Comments\Controller
            \ControllerFactory::create($ctrl, $arrParams_Drei);    
    
    /**
     * Setup Comments '#vier'
     * ======================
     */   
    //add unique Username input-element in the comments form
    $username = $comment = $email = null;
    
    //ensure that this form was submitted and check if form-button was clicked
    if(true === isset($arrParams_Vier[$arrParams_Vier['formSubmitted']]))
    {
        //check if username was submitted per form
        $username = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_usernameUnregistered');
        
        //check if comment was submitted per form 
        $comment = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_comment');
        
        //check if email was submitted per form 
        $email = \HybridCMS\Util\HttpArgs::postValOrNull(
                'hyb_comments_email');            
    } 
    
    //add content object for the username
    $key = 'objContentUsernameUnregistered';
    $arrParams_Vier[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentUsernameUnregistered($username);
    
    //add content-object for the comment
    $key = 'objContentComment';
    $arrParams_Vier[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentComment($comment);  
    
    //add content-object for email
    $key = 'objContentEmailUnique';
    $arrParams_Vier[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentEmailUnique($email);      
    
    //add content for comment submit button
    $key = 'objContentAddComment';
    $arrParams_Vier[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonAddComment();      
    
    //create controller to add new comments
    $ctrl = 'ControllerCommentAdderUnregisteredUsers';
    $objCtrlCommentAdderVier = \HybridCMS\Plugins\Comments\Controller
            \ControllerFactory::create($ctrl, $arrParams_Vier); 
    
    //Comments List
    ###############
    
    $key = 'objContentButtonListComments';
    $arrParams_Vier[$key] = new \HybridCMS\Plugins\Comments\CustomControls
            \FormElemGroupContent\ContentButtonListComments();
    
    //create controller to show all comments in list
    $ctrl = 'ControllerCommentList';
    $objCtrlCommentListVier = \HybridCMS\Plugins\Comments\Controller
            \ControllerFactory::create($ctrl, $arrParams_Vier);    
    
    //load CSS and JS for the comment plugin
    assert(false === empty($objCtrlCommentAdderZwei));  
    assert(false === empty($objCtrlCommentListZwei));
    $hcms->loadPlugin($objCtrlCommentAdderZwei);
    $hcms->loadPlugin($objCtrlCommentListZwei);

    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets
            \WidgetLatestArticles('latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
            \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php');
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row">
        <article class="col-xs-12 col-md-7" id="mainContent">
            <h1>Comments Plugin</h1>
            
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </p>
            <p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. </p>
            <p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. </p>

            <h2><a href="#zwei" name="zwei">Zwei</a></h2>
            <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. </p>
            <?php            
                //show comments list
                echo $objCtrlCommentListZwei->toString();
                            
                //Show comments form
                echo $objCtrlCommentAdderZwei->toString();

            ?>
            
            <h2><a href="#drei" name="drei">Drei</a></h2>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
            <?php            
                //show comments list
                echo $objCtrlCommentListDrei->toString();
                
                //Show comments form
                echo $objCtrlCommentAdderDrei->toString();
            ?>
            
            <h2><a href="#vier" name="vier">Vier</a></h2>
            <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. </p>
            <?php
                //show comments list
                echo $objCtrlCommentListVier->toString();
                
                //Show comments form
                echo $objCtrlCommentAdderVier->toString();
            ?>
        </article>

        <?php
        /* include Footer
         * ================================================== */
        require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php');
        ?>  
    </div><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
?>