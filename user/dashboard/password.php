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
    $hcms->setupPage('title', array('title' => 'Passwort ändern', 'prepend' => ' - hybcms.de'));
    $hcms->setupPage('description', array('description' => 'Profileinstellungen.'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/dashboard/password.html'));
    $hcms->setupPage('noindex', array());     

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    //Plugin-User Password-Reset
    if(true === isset($_SESSION['type']) && $_SESSION['type'] === 'registered')
    {
        $arrParams = $_POST;

        //PasswordLogin
        $password = (false === empty($_POST['hyb_user_passwordLogin'])) 
                ? trim($_POST['hyb_user_passwordLogin']) : null;  
        
        $arrParams['objFormElemGroupContentPasswordLogin'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentPasswordLogin($password); 

        //Password
        $password = (false === empty($_POST['hyb_user_password'])) 
                ? trim($_POST['hyb_user_password']) : null;
        
        $arrParams['objFormElemGroupContentPassword'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentPassword($password);   

        //password repeat
        $passwordRepeat = (false === empty($_POST['hyb_user_passwordRepeat'])) 
                ? trim($_POST['hyb_user_passwordRepeat']) : null;  
        
        $arrParams['objFormElemGroupContentPasswordRepeat'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentPasswordRepeat($passwordRepeat); 
        
        $arrParams['objFormElemGroupContentPasswordRepeat']
                ->setObjDependentFormElemGroupContentPassword
                ($arrParams['objFormElemGroupContentPassword']);
                
        $objContrDashPass = 
                \HybridCMS\Plugins\User\Controller\ControllerFactory
                ::create('ControllerDashboardUpdatePassword', $arrParams);
        
        assert(false === empty($objContrDashPass));

        $hcms->loadPlugin($objContrDashPass);    
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
          
            <h1>Passwort ändern</h1>
            
            <?php
                //Show profiledata of current user  
                if(false === empty($objContrDashPass))
                {
                    echo $objContrDashPass->toString();
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