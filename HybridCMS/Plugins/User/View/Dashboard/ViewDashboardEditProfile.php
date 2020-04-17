<?php

namespace HybridCMS\Plugins\User\View\Dashboard;

/**
 * class ViewDashboardEditProfile
 *
 * @package HybridCMS\Plugins\User\View\Dashboard
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewDashboardEditProfile extends \HybridCMS\Plugins\User\View\ViewUser
{    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) 
    {
        //call constructor of parent class
        parent::__construct($arrSettings);
    }    
    
    /**
     * Builds the formated registration formular
     * @return string Formated registration formular
     */
    public function toString() {
        
        $op = '';

        $op .= '<h1>Profil ändern und erweitern</h1>';
        
        //open form
        $op .= '<form class="add-bottom-50" role="form" action="/user/dashboard/profile.html" method="POST">';       
        
        //Add Gender
        $op .= '<div class="row add-bottom-20">';
        $op .= '<div class="col-md-12">';
        $op .= $this->toStringFormElemGroup('objFormElemGroupGender');  
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';
        
        //FormElemGroupEmailExistingAndRegistered
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';        
        $op .= $this->toStringFormElemGroup('objFormElemGroupEmailUniqueOrSessionEmail');         
        $op .= '</div>';
        
        //FormElemGroupUsernameExisting
        $op .= '<div class="col-md-6">'; 
        $op .= $this->toStringFormElemGroup('objFormElemGroupUsernameUniqueOrSessionUsername');         
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';
        
        //FormElemGroupFirstname
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';         
        $op .= $this->toStringFormElemGroup('objFormElemGroupFirstname');  
        $op .= '</div>';
        
        //FormElemGroupLastname
        $op .= '<div class="col-md-6">';         
        $op .= $this->toStringFormElemGroup('objFormElemGroupLastname');          
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';
        
        //FormElemGroupTwitterName
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';          
        $op .= $this->toStringFormElemGroup('objFormElemGroupTwitterName');       
        $op .= '</div>';
        
        //FacebookUrl
        $op .= '<div class="col-md-6">';         
        $op .= $this->toStringFormElemGroup('objFormElemGroupFacebookUrl'); 
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';
        
        //GoogleplusId
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';          
        $op .= $this->toStringFormElemGroup('objFormElemGroupGoogleplusId');
        $op .= '</div>';        
        
        //YoutubeChannelName
        $op .= '<div class="col-md-6">';        
        $op .= $this->toStringFormElemGroup('objFormElemGroupYoutubeChannelName'); 
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';        
        
        //Website
        $op .= $this->toStringFormElemGroup('objFormElemGroupWebsite');     
        
        //Add Aboutme Inputfield 
        $op .= $this->toStringFormElemGroup('objFormElemGroupAboutme');   
        
        
        $op .= '<div class="row">';
        $op .= '<div class="col-md-12">';
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_updateProfile" type="submit" '
                . 'value="Änderungen speichern" />';
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';
        
        //close from
        $op .= '</form>';

        return $op;
        
    }
}
?>