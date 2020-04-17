<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

//check if user is loged in
if(false === isset($_SESSION['logedIn'])) 
{
    header('Location: ' . '/login.html');
}

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Profil löschen', 'prepend' => ' - hybcms.de'));
    $hcms->setupPage('description', array('description' => 'Profileinstellungen.'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/dashboard/password.html'));
    $hcms->setupPage('noindex', array()); 

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    //Plugin-User Delete Profile
    if(true === isset($_SESSION['type']))
    {
        $arrParams = $_POST;

        $objContrDashDelProfile 
                = \HybridCMS\Plugins\User\Controller\ControllerFactory
                ::create('ControllerDashboardDeleteProfile', $arrParams);

        assert(false === empty($objContrDashDelProfile));
        
        $hcms->loadPlugin($objContrDashDelProfile); 
    }

    /* add asideWidgets
      =================================================== */
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php');
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <section class="row" id="mainContent" itemprop="mainContentOfPage">
        <div class="col-xs-12 col-md-7">
            
            <?php
            /* include Navtabs
             * ================================================== */
            require_once($_SERVER['DOCUMENT_ROOT'] . '/user/dashboard/nav-tabs.php'); ?>                      
            
            <?php
                //Show delete view of current user   
                if(false === empty($objContrDashDelProfile))
                {
                    echo $objContrDashDelProfile->toString();
                }
            ?>            

        </div><!-- end .col-xs-12 .col-md-7 -->

    <?php
    /* include Footer
     * ================================================== */
    require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>  
        
    </section><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>