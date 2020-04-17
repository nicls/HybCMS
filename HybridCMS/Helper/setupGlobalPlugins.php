<?php if(!defined('ROOTDOC')) die();

//User Plugin LoginButton
$arrParams = array('hyb_user_showLoginButton' => 'true');
$objControllerLoginButton 
        = \HybridCMS\Plugins\User\Controller\ControllerFactory::create(
                'ControllerLoginButton', 
                $arrParams);
assert(false === empty($objControllerLoginButton));

//Bookmark Plugin
$objBookmarks = new \HybridCMS\Plugins\Bookmarks\Bookmarks();
assert(false === empty($objBookmarks));

//to Top Slider
$objToTop = new \HybridCMS\Plugins\ToTop\ToTop();
assert(false === empty($objToTop));

?>
