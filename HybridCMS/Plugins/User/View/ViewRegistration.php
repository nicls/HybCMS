<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewRegistration
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewRegistration extends \HybridCMS\Plugins\User\View\ViewUser
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
    public function toString() 
    {       
        $op = '';

        //open form
        $op .= '<form role="form" action="/register.html" method="POST">';

        //open conatainer needed data
        $op .= '<div id="hyb_user_neddedData">';
        
        //Add Gender
        $op .= '<div class="row add-bottom-20">';
        $op .= '<div class="col-md-12">';        
        $op .= $this->toStringFormElemGroup('objFormElemGroupGender');    
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';        
        
        //Add Firstname Inputfield      
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';          
        $op .= $this->toStringFormElemGroup('objFormElemGroupFirstname');   
        $op .= '</div>';        
        
        //Add Lastname Inputfield   
        $op .= '<div class="col-md-6">';         
        $op .= $this->toStringFormElemGroup('objFormElemGroupLastname');   
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';        
                      
        //Add Username Inputfield    
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';               
        $op .= $this->toStringFormElemGroup('objFormElemGroupUsernameUnique'); 
        $op .= '</div>';    
        
        //Add email Inputfield    
        $op .= '<div class="col-md-6">';         
        $op .= $this->toStringFormElemGroup('objFormElemGroupEmailUnique');    
        $op .= '</div>';
        $op .= '</div><!-- end .row -->'; 
        
        //Add password Inputfield   
        $op .= '<div class="row">';
        $op .= '<div class="col-md-6">';         
        $op .= $this->toStringFormElemGroup('objFormElemGroupPassword');
        $op .= '</div>';    
        
        //Add passwordRepeat Inputfield 
        $op .= '<div class="col-md-6">';        
        $op .= $this->toStringFormElemGroup('objFormElemGroupPasswordRepeat');              
        $op .= '</div>';
        $op .= '</div><!-- end .row -->'; 
        
        //close container needed data
        $op .= '</div>';

        //open container optional data
        $op .= '<div class="form-group" id="hyb_user_optionalData">';

        if (true) {
            
            $op .= '<div class="row">';
            $op .= '<div class="col-md-12">';  
            $op .= '<div class="panel-group add-top-30" id="accordion">';
            $op .= '<div class="panel panel-default">';
            $op .= '<div class="panel-heading">';
            $op .= '<h4 class="panel-title">';
            $op .= '<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">';
            $op .= '<i class="fa fa-pencil"></i> Optionale Angaben';
            $op .= '</a>';
            $op .= '</h4>';
            $op .= '</div>';
            $op .= '<div id="collapseOne" class="panel-collapse collapse">';
            $op .= '<div class="panel-body">';
        }

        //Add Twitter Inputfield
        $op .= $this->toStringFormElemGroup('objFormElemGroupTwitterName');
        
        //Add Facebook Inputfield
        $op .= $this->toStringFormElemGroup('objFormElemGroupFacebookUrl');
        
        //Add GoogleplusId Inputfield
        $op .= $this->toStringFormElemGroup('objFormElemGroupGoogleplusId');       
        
        //Add YoutubeChannelName Inputfield
        $op .= $this->toStringFormElemGroup('objFormElemGroupYoutubeChannelName'); 

        //Add website Inputfield
        $op .= $this->toStringFormElemGroup('objFormElemGroupWebsite');

        //Add Aboutme Inputfield 
        $op .= $this->toStringFormElemGroup('objFormElemGroupAboutme');

        if (true) {
            
            $op .= '</div>';
            $op .= '</div>';
            $op .= '</div>';
            $op .= '</div>';
            $op .= '</div>';
            $op .= '</div><!-- end .row -->';            
        }

        //close container optional data
        $op .= '</div>';

        $op .= '<div class="row">';
        $op .= '<div class="col-md-12">';
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_submitRegistration" type="submit" '
                . 'value="Jetzt registrieren" />';
        $op .= '</div>';
        $op .= '</div><!-- end .row -->'; 
        
        //close from
        $op .= '</form>';

        return $op;
    }        
}
 
?>