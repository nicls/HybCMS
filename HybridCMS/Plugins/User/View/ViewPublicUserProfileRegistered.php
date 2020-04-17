<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPublicUserProfileRegisterd
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPublicUserProfileRegistered extends \HybridCMS\Plugins\User\View\ViewUser
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
    
    public function toString() 
    {
        $objUser = $this->arrSettings['objUser'];
        
        $op = '';
        
        if(false === empty($objUser))
        {
            //Image
            $op .= '<img class="hyb_user_profileImg" height="140" width="140" '
                . 'src="' . $objUser->getGravatar(140) . '" '
                . 'alt="'. htmlentities($objUser->getUsername()) .'"'
                . ' />';
            
            //username
            $username = $objUser->getUsername();
            if(false === empty($username))
            {
               $op .= '<h2>' . htmlspecialchars($username) . '</h2>'; 
            }
            
            //timeCreated
            $duration = time() - $objUser->getTimeCreated();
            $op .= '<p class="hyb_user_memberSince">Mitglied seit ' . $this->sec2view($duration) . '</p>'; 
            
            $op .= '<div class="hyb_user_socialMedia">';
            
            //twitter
            $twitterName = $objUser->getTwitterName();
            if(false === empty($twitterName))
            {
                $op .= '<a href="https://twitter.com/'. htmlentities($twitterName) . '" '
                        . 'title="Twitter-Profil von '. htmlentities($username) . '" '
                        . 'rel="nofollow" '
                        . 'target="_blank"'
                        . '>';
                $op .= '<i class="fa fa-twitter"></i>'; 
                $op .= '</a>';
            }
            
            //facebook
            $facebookUrl = $objUser->getFacebookUrl();
            if(false === empty($facebookUrl))
            {
                $op .= '<a href="'. htmlentities($facebookUrl) . '" '
                        . 'title="Facebook-Profil von '. htmlentities($username) . '" '
                        . 'rel="nofollow" '
                        . 'target="_blank"'
                        . '>';
                $op .= '<i class="fa fa-facebook"></i>'; 
                $op .= '</a>';
            }
                
            //googleplus
            $googleplusId = $objUser->getGoogleplusId();
            if(false === empty($googleplusId))
            {
                $op .= '<a href="https://plus.google.com/u/0/'. htmlentities($googleplusId) . '" '
                        . 'title="Google+ Profil von '. htmlentities($username) . '" '
                        . 'rel="nofollow" '
                        . 'target="_blank"'
                        . '>';
                $op .= '<i class="fa fa-google-plus"></i>'; 
                $op .= '</a>';                
            }
            
            //youtube
            $youtubeChannelName = $objUser->getYoutubeChannelName();
            if(false === empty($googleplusId))
            {
                $op .= '<a href="https://www.youtube.com/user/'
                        . htmlentities($youtubeChannelName) . '" '
                        . 'title="Youtube Channel von '. htmlentities($username) . '" '
                        . 'rel="nofollow" '
                        . 'target="_blank"'
                        . '>';
                $op .= '<i class="fa fa-youtube"></i>'; 
                $op .= '</a>';                
            }    
            
            //website
            $website = $objUser->getWebsite();
            if(false === empty($website))
            {
                $op .= '<a href="' . htmlentities($website) . '" '
                        . 'title="Webseite von '. htmlentities($username) . '" '
                        . 'rel="nofollow" '
                        . 'target="_blank"'
                        . '>';
                $op .= '<i class="fa fa-laptop"></i>'; 
                $op .= '</a>';                
            }        
            
            //end .hyb_user_socialMedia
            $op .= '</div>';
            
                        
            //aboutme
            $aboutme = $objUser->getAboutme();
            if(false === empty($aboutme))
            {
                $op .= '<div class="hyb_user_aboutmeContainer">';            
                $op .= '<h2>Über '. htmlspecialchars($username) .'</h2>';
            
                $op .= '<p>' . preg_replace('/\n/', '<br />', htmlspecialchars($aboutme)) . '</p>'; 
                
                $op .= '</div>';
            }
        }

        return $op;
        
    }
    
    /**
    * Convert number of seconds into years, days, hours, minutes and seconds
    * and return an string containing those values
    *
    * @param integer $seconds Number of seconds to parse
    * @return string
    */    
    private function sec2view($seconds)
    {
        $y = floor($seconds / (86400*365.25));
        $d = floor(($seconds - ($y*(86400*365.25))) / 86400);
        $h = gmdate('H', $seconds);
        $m = gmdate('i', $seconds);
        $s = gmdate('s', $seconds);

        $string = '';

        if($y > 0)
        {
        $yw = $y > 1 ? ' Jahren ' : ' Jahr ';
        $string .= $y . $yw . ', ';
        }

        if($d > 0)
        {
        $dw = $d > 1 ? ' Tagen ' : ' Tag ';
        $string .= $d . $dw . ', ';
        }

        if($h > 0)
        {
        $hw = $h > 1 ? ' Stunden ' : ' Stunde ';
        $string .= $h . $hw . ', ';
        }

        if($m > 0)
        {
        $mw = $m > 1 ? ' Minuten ' : ' Minute ';
        $string .= $m . $mw;
        }

        if($s > 0)
        {
        $sw = $s > 1 ? ' Sekunden ' : ' Sekunde ';
        $string .= 'und ' . $s . $sw;
        }

        return preg_replace('/\s+/',' ', $string);
    }    
}