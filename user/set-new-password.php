<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Set new Password', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Login'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/reset-password.html'));
    $hcms->setupPage('noindex', array());

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
        
    //Plugin-User
    $arrParams = array_merge($_POST, $_GET);
    
    //Password
    $password = (false === empty($_POST['hyb_user_password'])) 
            ? trim($_POST['hyb_user_password']) : null;
    $arrParams['objFormElemGroupContentPassword'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentPassword($password);   
    
    //PasswordRepeat
    $passwordRepeat = (false === empty($_POST['hyb_user_passwordRepeat'])) 
            ? trim($_POST['hyb_user_passwordRepeat']) : null;  
    
    $arrParams['objFormElemGroupContentPasswordRepeat'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentPasswordRepeat($passwordRepeat); 
    
    $arrParams['objFormElemGroupContentPasswordRepeat']
            ->setObjDependentFormElemGroupContentPassword(
            $arrParams['objFormElemGroupContentPassword']);
    
    $objControllerSetNewPass 
            = \HybridCMS\Plugins\User\Controller\ControllerFactory
            ::create('ControllerPasswordResetSetNew', $arrParams);
    
    assert(false === empty($objControllerSetNewPass));
    $hcms->loadPlugin($objControllerSetNewPass);

    /* add asideWidgets
      =================================================== */
    
} 
catch (\Exception $e) 
{
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
        <div  class="col-sm-12 col-md-4 col-md-offset-4">
                    
            <div class="row center">
                <img class="add-top-60 col-sm-12 col-md-6 col-md-offset-3" src="/images/logo-hybcms.svg" alt="Logo HybCMS" height="180" width="180" />
            </div>
            
            <div class="row">
                <header id="logo" class="center add-bottom-60 col-sm-12 col-md-6 col-md-offset-3">HybCMS</header>
            </div>            
            
            <?php
                //Show login-form or user-response after a successful
                //submition of the login-form
                if(false === empty($objControllerSetNewPass))
                {
                    echo $objControllerSetNewPass->toString();
                }
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