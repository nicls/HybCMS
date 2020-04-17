<?php

namespace HybridCMS\Plugins\Comments\Admin\View;

/**
 * class ViewcommentList
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewNewCommentList extends 
    \HybridCMS\Plugins\Comments\Admin\View\ViewComments
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
     * toString
     * @return string
     */
    public function toString($arrParams = array()) 
    {
        assert(true === isset($this->arrSettings['arrObjNewComments']));
        assert(true === isset($this->arrSettings['objButtonDeleteComment']));
        assert(true === isset($this->arrSettings['objButtonPublishComment']));
        
        //get comments
        $arrObjNewComments = $this->arrSettings['arrObjNewComments'];        
               
        $op = '';                
        
        //add user response on published Comments
        if(true === isset($this->arrSettings['commentPublished'])
           &&
           true === $this->arrSettings['commentPublished'] 
           && 
           true === isset($this->arrSettings['commentId'])
           &&
           true === is_numeric($this->arrSettings['commentId'])
          )
        {
            $op .= '<a class="hyb_comments_userResponse" name="comment-'
                    . htmlentities($this->arrSettings['commentId']) .'">';
            $op .= '<p class="add-top-50 bg-success">Der Kommentar wurde erfolgreich veröffentlicht.</p>';
            $op .= '</a>';
        }
        else if(true === isset($this->arrSettings['commentPublished'])
                &&
                false === $this->arrSettings['commentPublished']
               )
        {
            $op .= '<a class="hyb_comments_userResponse" name="comment-'
                    . htmlentities($this->arrSettings['commentId']) .'">';
            $op .= '<p class="add-top-50 bg-danger">Der Kommentar wurde nicht veröffentlicht.</p>';
            $op .= '</a>';
        }
        
        //add user response on deleted Comments
        if(true === isset($this->arrSettings['commentDeleted'])
           &&
           true === $this->arrSettings['commentDeleted'] 
           && 
           true === isset($this->arrSettings['commentId'])
           &&
           true === is_numeric($this->arrSettings['commentId'])
          )
        {
            $op .= '<a class="hyb_comments_userResponse" name="comment-'
                    . htmlentities($this->arrSettings['commentId']) .'">';
            $op .= '<p class="add-top-50 bg-success">Der Kommentar wurde erfolgreich gelöscht.</p>';
            $op .= '</a>';
        }
        else if(true === isset($this->arrSettings['commentDeleted'])
                &&
                false === $this->arrSettings['commentDeleted']
               )
        {
            $op .= '<a class="hyb_comments_userResponse" name="comment-'
                    . htmlentities($this->arrSettings['commentId']) .'">';
            $op .= '<p class="add-top-50 bg-danger">Der Kommentar wurde nicht gelöscht.</p>';
            $op .= '</a>';
        }        
        
        $op .= '<div class="panel-group" id="accordion">';
        $op .= '<div class="panel panel-default">';
        $op .= '<div class="panel-heading">';
        $op .= '<header class="panel-title">';
        $op .= '<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">';
        $op .= count($arrObjNewComments) . ' neue Lesermeinungen';
        $op .= '</a>';
        $op .= '</header>';
        $op .= '</div>';
        $op .= '<div id="collapseOne" class="panel-collapse collapse">';
        $op .= '<div class="panel-body">';
                        
        foreach($arrObjNewComments as &$objCmnt)
        {
            $objUser = $objCmnt->getobjUser();
            
            $avatarUrl = $objUser->getAvatarUrl();
            $username = $objUser->getUsername();
            $email = $objUser->getEmail();
            $userType = $objUser->getType();
            
            $url = $objCmnt->getUrl();
            $timeCreated = $objCmnt->getTimeCreated();
            $date = date('d.m.Y H:m:s', $timeCreated);
            $published =  $objCmnt->getPublished() ? 'yes' : 'no';
            $comment = $objCmnt->getCommentFormatted();
            $commentId = $objCmnt->getCommentId();
            
            $objFormActionUrl = new \HybridCMS\Modules\Url\Url(
                    HYB_PROTOCOL . HYB_HOST_NAME . $_SERVER['PHP_SELF'] . '?' 
                    . $_SERVER['QUERY_STRING']);
            assert(true === $objFormActionUrl->urlIsInternal());
            
            
            $op .= "<article class='hyb_comment_container'>";
            
            //add anchor to the current comment
            $op .= '<a name="comment-'. htmlentities($commentId) .'"></a>';
            
            //add avatar
            $op .= '<img class="hyb_comment_avatar" height="60" width="60" src="'
                    . htmlentities($avatarUrl) .'" />';
            
            $op .= '<table class="hyb_comment_userdatatable">';
                        
            //add username
            $op .= '<tr>'; 
            $op .= '<th>Username:</th>';
            $op .= '<td>';
            $op .= '<span class="hyb_comment_username">'
                    . htmlspecialchars($username) .'</span>';
            $op .= '</td>';
            $op .= '</tr>';
            
            //add type
            $op .= '<tr>'; 
            $op .= '<th>User-Type:</th>';
            $op .= '<td>';
            $op .= '<span class="hyb_comment_usertype">'
                    . htmlspecialchars($userType) .'</span>';
            $op .= '</td>';
            $op .= '</tr>';            
            
            //add email
            $op .= '<tr>'; 
            $op .= '<th>E-Mail:</th>';
            $op .= '<td>';
            $op .= '<span class="hyb_comment_email">'
                    . htmlspecialchars($email) .'</span>';
            $op .= '</td>';
            $op .= '</tr>';            
            
            //add timeCreated
            $op .= '<tr>'; 
            $op .= '<th>Submitted on:</th>';
            $op .= '<td>';
            $op .= '<span class="hyb_comment_timeCreated">'
                    . htmlspecialchars($date) .'</span>';
            $op .= '</td>';
            $op .= '</tr>';             
            
            //add url
            $op .= '<tr>'; 
            $op .= '<th>Url:</th>';
            $op .= '<td>';
            $op .= '<span class="hyb_comment_url">'
                    . htmlspecialchars($url) .'</span>';
            $op .= '</td>';
            $op .= '</tr>';    
            
            //add published
            $op .= '<tr>'; 
            $op .= '<th>Published:</th>';
            $op .= '<td>';
            $op .= '<span class="hyb_comment_published">'
                    . htmlspecialchars($published) .'</span>';
            $op .= '</td>';
            $op .= '</tr>';    
            
            //add comment
            $op .= '<tr>'; 
            $op .= '<td colspan="2">';
            $op .= '<span class="hyb_comment_comment">'
                    . $comment .'</span>';
            $op .= '</td>';
            $op .= '</tr>';             
            
            
            $op .= '</table>';
            
            //add admin options
            $op .= '<form method="POST" action="'
                    . htmlentities($objFormActionUrl->getUrl()) 
                    . '#comment-' . htmlentities($commentId).'">';
            
            $op .= '<input type="hidden" name="commentId" value="'
                    .  htmlentities($commentId) .'" />';
                        
            $op .= '<div class="clearfix add-bottom-10">';
            
            //print buttons that provide action-name and action value by 
            //submitting the form
            $op .= $this->toStringFormElemGroup('objButtonPublishComment');            
            $op .= $this->toStringFormElemGroup('objButtonDeleteComment');            
            $op .= '</div>';
            
            $op .= '</form>';
            
            $op .= "</article>";            
        }
        
        $op .= '</div>'; //close <div id="collapseOne">
        $op .= '</div>'; //close <div class="panel-body">
        $op .= '</div>'; //close <div class="panel-group" id="accordion">
        $op .= '</div>'; //close <div class="panel panel-default">
        
        return $op;
    }
}

?>