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
    $hcms->setupPage('title', array('title' => 'Profileinstellungen', 'prepend' => ' - hybcms.de'));
    $hcms->setupPage('description', array('description' => 'Profileinstellungen.'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de/dashboard/profile.html'));
    $hcms->setupPage('noindex', array());     

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);
    
    //user Plugin Dashboard for registered users
    if(true === isset($_SESSION['type']) && $_SESSION['type'] === 'registered')
    {
        //Plugin-User
        $arrParams = $_POST;

        //show Gender in the registration form
        $gender = (false === empty($_POST['hyb_user_gender'])) 
                ? trim($_POST['hyb_user_gender']) : null;
        
        $arrParams['objFormElemGroupContentGender'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentGender($gender);      

        //EmailExistingAndRegistered
        $email = (false === empty($_POST['hyb_user_email'])) 
                ? trim($_POST['hyb_user_email']) : null;
        
        $arrParams['objFormElemGroupContentEmailUniqueOrSessionEmail'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentEmailUniqueOrSessionEmail($email);         

        //show existing Username
        $username = (false === empty($_POST['hyb_user_username'])) 
                ? trim($_POST['hyb_user_username']) : null;
        
        $arrParams['objFormElemGroupContentUsernameUniqueOrSessionUsername'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentUsernameUniqueOrSessionUsername($username);

        //show Firstname
        $firstname = (false === empty($_POST['hyb_user_firstname'])) 
                ? trim($_POST['hyb_user_firstname']) : null;
        
        $arrParams['objFormElemGroupContentFirstname'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentFirstname($firstname);    

        //show Lastname 
        $lastname = (false === empty($_POST['hyb_user_lastname'])) 
                ? trim($_POST['hyb_user_lastname']) : null;
        
        $arrParams['objFormElemGroupContentLastname'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentLastname($lastname);    

        //show Twitter 
        $twitterName = (false === empty($_POST['hyb_user_twitterName'])) 
                ? trim($_POST['hyb_user_twitterName']) : null;
        
        $arrParams['objFormElemGroupContentTwitterName'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentTwitterName($twitterName);

        //show FacebookUrl 
        $facebookUrl = (false === empty($_POST['hyb_user_facebookUrl'])) 
                ? trim($_POST['hyb_user_facebookUrl']) : null;
        
        $arrParams['objFormElemGroupContentFacebookUrl'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentFacebookUrl($facebookUrl);    

        //show GooglePlusId 
        $googleplusId = (false === empty($_POST['hyb_user_googleplusId'])) 
                ? trim($_POST['hyb_user_googleplusId']) : null;
        
        $arrParams['objFormElemGroupContentGoogleplusId'] = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentGoogleplusId($googleplusId);  

        //show YoutubeChannelName
        $youtubeChannelName = 
                (false === empty($_POST['hyb_user_youtubeChannelName'])) 
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
    
        $objContrDashProfile = 
                \HybridCMS\Plugins\User\Controller\ControllerFactory
                ::create('ControllerDashboardProfile', $arrParams);
        
        assert(false === empty($objContrDashProfile));
        
        $hcms->loadPlugin($objContrDashProfile);    
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
            
            <div class="row">
                <div class="col-md-12">            
                    <?php
                        //Show profiledata of current user   
                        //if user is registered and not loged in by a 3rd party provider
                        if(false === empty($objContrDashProfile))
                        {
                            echo $objContrDashProfile->toString();
                        }
                    ?>  
                </div>
            </div>
            
            <div class="row">
                <div  class="col-md-12">
                    <h2>Account löschen</h2>
            
                    <div class="add-top-40">
                        <a class="btn btn-danger" href="/user/dashboard/delete-profile.html" title="Account löschen">Account löschen</a>
                    </div>
                </div>
            </div>

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