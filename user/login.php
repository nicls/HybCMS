<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Login', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Login'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/login.html'));
    $hcms->setupPage('noindex', array());

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Plugin-User  

    ###############################
    ## Use OpenId Login Buttons   #
    ###############################    
    $arrParamsOpenId = $_POST;
    
    //add Twitter Button
    $arrParamsOpenId['objFormElemGroupButtonLoginTwitter'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentButtonLoginTwitter();
    
    //add Facebook Button
    $arrParamsOpenId['objFormElemGroupButtonLoginFacebook'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentButtonLoginFacebook(); 
    
    //add Google+ Button
    $arrParamsOpenId['objFormElemGroupButtonLoginGoogleplus'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentButtonLoginGoogleplus();    
    
    //Controller OpenId Login Button
    $objControllerLoginOpenId = 
            \HybridCMS\Plugins\User\Controller\ControllerFactory
            ::create('ControllerLoginOpenId', $arrParamsOpenId);
    
    assert(false === empty($objControllerLoginOpenId));
    
    $hcms->loadPlugin($objControllerLoginOpenId);
    

    $arrParams = $_POST;
    ###############################
    ## Use standard Login Form    #
    ###############################
    
    //email
    $email = (false === empty($_POST['hyb_user_email'])) 
            ? trim($_POST['hyb_user_email']) : null;
    
    $arrParams['objFormElemGroupContentEmailExistingAndRegistered'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentEmailExistingAndRegistered($email);
    
    //password Login
    $password = (false === empty($_POST['hyb_user_passwordLogin'])) 
            ? trim($_POST['hyb_user_passwordLogin']) : null;  
    
    $arrParams['objFormElemGroupContentPasswordLogin'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentPasswordLogin($password); 
    
    $arrParams['objFormElemGroupContentPasswordLogin']
            ->setObjDependentFormElemGroupContentEmail(
            $arrParams['objFormElemGroupContentEmailExistingAndRegistered']);      
    
    //controller Login
    $objControllerLoginRegistered = 
            HybridCMS\Plugins\User\Controller\ControllerFactory
            ::create('ControllerLoginRegistered', $arrParams);
    
    assert(false === empty($objControllerLoginRegistered));
    $hcms->loadPlugin($objControllerLoginRegistered);
    
    
    /* add asideWidgets
      =================================================== */
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/header-whitepage.php');
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <section class="row" id="mainContent" itemprop="mainContentOfPage">
        <div  class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                    
            <div class="row center">
                <img class="add-top-60 col-sm-12 col-md-8 col-md-offset-2" src="/images/logo-hybcms.svg" alt="Logo HybCMS" height="180" width="180" />
            </div>
            
            <div class="row">
                <header id="logo" class="center add-bottom-60 col-sm-12 col-md-6 col-md-offset-3">HybCMS</header>
            </div>            
            
            <?php
            
                //show OpenId Login Buttons
                echo $objControllerLoginOpenId->toString();
                
                echo '<p class="center">&mdash; oder &mdash;</p>';
                
                //Show login-form or user-response after a successful
                //submition of the login-form
                echo $objControllerLoginRegistered->toString();
            ?>
            
            <div class="row small">
                <div class="add-top-60 col-sm-12 col-md-12 center">
                    <a href="/register.html" title="Account erstellen.">Account erstellen</a> &middot;
                    <a href="/reset-password.html" title="Passwort vergessen?">Passwort vergessen?</a>
                </div>
            </div>
            
            <div class="row small">
                <div class="add-top-20 add-bottom-60 col-sm-12 col-md-12 center">
                    Copyright <?php echo date('Y'); ?> &middot;
                    <a href="/impressum.html" title="Impressum">Impressum</a> &middot;
                    <a href="/datenschutz.html" title="Datenschutz">Datenschutz</a>
                </div>
            </div>            
        </div>
        
    </section><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer-whitepage.php'); ?>