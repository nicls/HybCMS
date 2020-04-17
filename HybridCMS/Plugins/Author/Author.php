<?php

namespace HybridCMS\Plugins\Author;

/**
 * class Author
 *
 * @package Author
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Author extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * User
     * @var \HybridCMS\Admin\Auth\User
     */
    private $objUser;
    
    /**
     * Hash of the email of the user for the gravatar image
     * @var String
     */
    private $hashGravatar;
    
    /**
     * __construct
     * 
     */
    public function __construct($objUser) {
        
        //check if $objUser is of type User
        if (!$objUser instanceof \HybridCMS\Admin\Auth\User) {
            throw new \Exception(
            'Error Processing Request: __construct(),
                            $objUser is not of type \HybridCMS\Admin\Auth\User.', 1);
        }
        
        $this->objUser = $objUser;
        
        //create hash for avatar
        $this->hashGravatar = md5(strtolower(trim($objUser->getEmail())));

        try {
            
            //call parent constructor
            parent::__construct();
                        
            /**
             * CSS
             *
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             */
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                    'author', //1
                    '/HybridCMS/Plugins/Author/css/f.css', //2
                    4, //3
                    false, //4
                    true //5
                    );
            $this->addObjCSSResource($objCSSResource);            
            
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
        
    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {
        
        $twitter = $this->objUser->getTwitter();
        $facebook = $this->objUser->getFacebook();
        $googleplus = $this->objUser->getGooglePlus();
        $youtube = $this->objUser->getYoutube();
        $website = $this->objUser->getWebsite();
        $aboutme = $this->objUser->getAboutme();
        $username = $this->objUser->getUsername();
        
        $defaultLinkBegin = '';
        if(!empty($youtube)) {
            $defaultLinkBegin = '<a href="' . htmlentities($youtube) . '" title="'. $username .' auf Youtube" target="_blank">';
        }
        
        $defaultLinkEnd = '';
        if(!empty($youtube)) {
            $defaultLinkEnd .= '</a>';
        } 

        //output-String
        $op = '';  
        $op .= '<div class="hyb_author">';
        
        //add gravatar (and link it to youtube)
        $op .= $defaultLinkBegin;        
        
        $op .= '<img src="http://www.gravatar.com/avatar/' . $this->hashGravatar . '?s=100"/>';
        
        $op .= $defaultLinkEnd;                   
        
        //add username
        $op .= '<header>Dieser Artikel wurde von ';        
        $op .= $defaultLinkBegin . htmlspecialchars($username) . $defaultLinkEnd;
        $op .= ' geschrieben</header>';             
                
        $op .= '<ul class="hyb_authorProfiles">';
        
        //add twitter
        if(!empty($twitter)) {
            $op .= '<li>';
            $op .= '<a href="' . htmlentities($twitter) . '" title="'. $username .' auf Twitter" target="_blank" >';
            $op .= '<i class="fa fa-twitter-square"></i>';
            $op .= '</a>';
            $op .= '</li>';
        }
        
        //add facebook
        if(!empty($facebook)) {
            $op .= '<li>';
            $op .= '<a href="' . htmlentities($facebook) . '" title="'. $username .' auf Facebook" target="_blank" >';
            $op .= '<i class="fa fa-facebook-square"></i>';
            $op .= '</a>';
            $op .= '</li>';
        }
        
        //add google plus
        if(!empty($googleplus)) {
            $op .= '<li>';
            $op .= '<a href="' . htmlentities($googleplus) . '" title="'. $username .' auf Google+" target="_blank" >';
            $op .= '<i class="fa fa-google-plus-square"></i>';
            $op .= '</a>';
            $op .= '</li>';
        }
        
        //add youtube
        if(!empty($youtube)) {
            $op .= '<li>';
            $op .= '<a href="' . htmlentities($youtube) . '" title="'. $username .' auf Youtube" target="_blank" >';
            $op .= '<i class="fa fa-youtube-square"></i>';
            $op .= '</a>';
            $op .= '</li>';
        }
        
        //close ul
        $op .= '</ul>';
             
        //add about me
        $op .= '<p>' . htmlspecialchars($aboutme) . '</p>';    
        
        //close div
        $op .= '</div>';
        
        return $op;
       
    }    
}
?>
