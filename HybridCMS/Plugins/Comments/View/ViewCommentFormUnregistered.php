<?php

namespace HybridCMS\Plugins\Comments\View;

/**
 * class ViewCommentFormUnregistered
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewCommentFormUnregistered extends 
    \HybridCMS\Plugins\Comments\View\ViewComments
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
    public function toString($arrParams = array()) 
    {     
        assert(true === isset($this->arrSettings['objUrl']));
        
        $objUrl = $this->arrSettings['objUrl'];
        
        //get url-fragment 
        $urlFragment = $objUrl->getFragment();
        
        //ckeck if fragment is not null and valid
        if(false === isset($urlFragment))
        {               
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    toString(), url-fragment is null.', 1);  
        }
           
        $op = '';
        
        $op .= '<header class="f20 bold borderBottom add-bottom-20">'
                . 'Deine Meinung:</header>';
        
        //add anchor
        $op .= '<a name="' . htmlentities($urlFragment) . '-comments">';
        $op .= '</a>';
        //open form
        $op .= '<form role="form" action="'
                . htmlentities($objUrl->getUrl()) . '-comments' 
                .'" method="POST">';
        
        //hidden fields
        $op .= '<input name="hyb_comments_url" type="hidden" '
                . 'value="'. htmlentities($objUrl->getUrl()) .'" />';

        //open conatainer needed data
        $op .= '<div id="hyb_user_neddedData">';                          
                      
        //Add Username Inputfield    
        $op .= '<div class="row">';
        $op .= '<div class="col-md-12">';               
        $op .= $this->toStringFormElemGroup('objUsernameUnregistered'); 
        $op .= '</div>';    
        $op .= '</div><!-- end .row -->';
        
        //Add email Inputfield    
        $op .= '<div class="row">';
        $op .= '<div class="col-md-12">';         
        $op .= $this->toStringFormElemGroup('objEmailUnique');    
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';         
        
        //Add Comment Textarea 
        $op .= '<div class="row">';
        $op .= '<div class="col-md-12">';         
        $op .= $this->toStringFormElemGroup('objComment');        
        $op .= '</div>';
        $op .= '</div><!-- end .row -->';     
        
        //close container needed data
        $op .= '</div>';

        //Add Comment Textarea 
        $op .= $this->toStringFormElemGroup('objButtonAddComment');                  
                        
        //close from
        $op .= '</form>';

        return $op;
    }        
}
 
?>