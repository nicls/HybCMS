<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Register', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/register.html'));
    $hcms->setupPage('noindex', array());    

    /* get Articles 
      ==================================================== */


    /* load Plugins
      ================================================== */
    
    //Plugin User
    $arrParams = $_POST;
        
    //show Gender in the registration form
    $gender = (false === empty($_POST['hyb_user_gender'])) 
            ? trim($_POST['hyb_user_gender']) : null;
    
    $arrParams['objFormElemGroupContentGender'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentGender($gender);    
    
    //show unique Username in the registration form
    $username = (false === empty($_POST['hyb_user_username'])) 
            ? trim($_POST['hyb_user_username']) : null;
    
    $arrParams['objFormElemGroupContentUsernameUnique'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentUsernameUnique($username); 
    
    //show Firstname in the registration form
    $firstname = (false === empty($_POST['hyb_user_firstname'])) 
            ? trim($_POST['hyb_user_firstname']) : null;
    
    $arrParams['objFormElemGroupContentFirstname'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentFirstname($firstname);    
    
    //show Lastname in the registration form
    $lastname = (false === empty($_POST['hyb_user_lastname'])) 
            ? trim($_POST['hyb_user_lastname']) : null;
    
    $arrParams['objFormElemGroupContentLastname'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentLastname($lastname);        
    
    //EmailUnique
    $email = (false === empty($_POST['hyb_user_email'])) 
            ? trim($_POST['hyb_user_email']) : null;
    
    $arrParams['objFormElemGroupContentEmailUnique'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentEmailUnique($email);
    
    //password
    $password = (false === empty($_POST['hyb_user_password'])) 
            ? trim($_POST['hyb_user_password']) : null;
    
    $arrParams['objFormElemGroupContentPassword'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentPassword($password);   
    
    //password Repeat
    $passwordRepeat = (false === empty($_POST['hyb_user_passwordRepeat'])) 
            ? trim($_POST['hyb_user_passwordRepeat']) : null; 
    
    $arrParams['objFormElemGroupContentPasswordRepeat'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentPasswordRepeat($passwordRepeat); 
    
    $arrParams['objFormElemGroupContentPasswordRepeat']
            ->setObjDependentFormElemGroupContentPassword(
            $arrParams['objFormElemGroupContentPassword']);
           
    //show Twitter in the registration form
    $twitterName = (false === empty($_POST['hyb_user_twitterName'])) 
            ? trim($_POST['hyb_user_twitterName']) : null;
    
    $arrParams['objFormElemGroupContentTwitterName'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentTwitterName($twitterName);
    
    //show FacebookUrl in the registration form
    $facebookUrl = (false === empty($_POST['hyb_user_facebookUrl'])) 
            ? trim($_POST['hyb_user_facebookUrl']) : null;
    
    $arrParams['objFormElemGroupContentFacebookUrl'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentFacebookUrl($facebookUrl);    
    
    //show GooglePlusId in the registration form
    $googleplusId = (false === empty($_POST['hyb_user_googleplusId'])) 
            ? trim($_POST['hyb_user_googleplusId']) : null;
    
    $arrParams['objFormElemGroupContentGoogleplusId'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentGoogleplusId($googleplusId);  
    
    //show YoutubeChannelName in the registration form
    $youtubeChannelName 
            = (false === empty($_POST['hyb_user_youtubeChannelName'])) 
            ? trim($_POST['hyb_user_youtubeChannelName']) : null;
    
    $arrParams['objFormElemGroupContentYoutubeChannelName'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentYoutubeChannelName($youtubeChannelName);    
        
    //show Website in the registration form
    $website = (false === empty($_POST['hyb_user_website'])) 
            ? trim($_POST['hyb_user_website']) : null;
    $arrParams['objFormElemGroupContentWebsite'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentWebsite($website);    
    
    //show Aboutme in the registration form
    $aboutme = (false === empty($_POST['hyb_user_aboutme'])) 
            ? trim($_POST['hyb_user_aboutme']) : null;
    $arrParams['objFormElemGroupContentAboutme'] = 
            new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
            \FormElemGroupContentAboutme($aboutme);     
    
    $objControllerRegistration = 
            \HybridCMS\Plugins\User\Controller\ControllerFactory
            ::create('ControllerRegistration', $arrParams);
    
    assert(false === empty($objControllerRegistration));
    $hcms->loadPlugin($objControllerRegistration);

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
        <div  class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    
            <div class="row center">
                <img class="add-top-60 col-sm-12 col-md-6 col-md-offset-3" src="/images/logo-hybcms.svg" alt="Logo HybCMS" height="180" width="180" />
            </div>
            
            <div class="row">
                <header id="logo" class="center add-bottom-60 col-sm-12 col-md-6 col-md-offset-3">HybCMS</header>
            </div>    
            
            <div class="row">
                <div class="add-bottom-30 col-md-12">
                    <h1 class="f19 bold">Account erstellen in 30 Sekunden</h1>
                    <p>Registriere Dich jetzt für einen kostenlosen Account. Wenn Du schon einen Account hast, <a href="/login.html" title="Login">logge dich ein</a>.</p>
                </div>
            </div>              
            
            <?php
                //Show registration-form or user-response after a successful
                //submition of the registration-form
                echo $objControllerRegistration->toString();
            ?>
            
            <div class="row small">
                <div class="add-bottom-30 add-top-60 col-sm-12 col-md-12">                    
                    <p class="center">Durch klicken dieses Button stimmtst Du unseren <a href="/nutzungsrichtlinien.html">Nutzungsrichtlinien</a> zu.</p>
                </div>
            </div>            
            
            <div class="row small">
                <div class="add-top-60 col-sm-12 col-md-12 center">
                    <a href="/login.html" title="Account erstellen.">Mit bestehendem Account einloggen</a> &middot;
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