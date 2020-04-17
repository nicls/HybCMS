<?php

namespace HybridCMS\Plugins\Comments\View;

/**
 * class ViewCommentAdded
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewCommentAdded extends 
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
        assert(true === isset($this->arrSettings['objComment']));
        assert(true === ($this->arrSettings['objComment']) instanceof 
                \HybridCMS\Plugins\Comments\Model\Comment);
                
        $content = $this->arrSettings['objComment']->getCommentFormatted();
        
        assert(true === isset($this->arrSettings['hyb_comments_url']));
        
        $objUrl = new \HybridCMS\Modules\Url\Url(
                    $this->arrSettings['hyb_comments_url']);
        
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
        
        $op .= '<a name="'
                . htmlentities($urlFragment) 
                .'-comments"></a>';
        $op .= '<div style="height:60px;"></div>';
        $op .= '<p class="bold alert alert-success fade in">'
                . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                . '<span class="fa fa-cogs f14"> '
                . 'Vielen Dank :) '
                . 'Dein Kommentar wird so schnell wie möglich freigeschaltet.'
                . '</span>'
                . '</p>';
        
        //build comment
        $op .= '<article class="hyb_commentContainer" '
                . 'itemscope itemtype="http://schema.org/Comment">';
        
        
        $op .= '<p class="italic add-bottom-60">';
        
        //add comment Text
        $op .= $content;
        
        $op .= '</p>';
        
        //close article tag
        $op .= '</article>';
               
        return $op;
    }        
}
 
?>