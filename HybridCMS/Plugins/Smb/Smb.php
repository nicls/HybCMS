<?php

namespace HybridCMS\Plugins\Smb;

/**
 * class Smb
 *
 * @package Smb
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Smb extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * Indicates if a facebook Button should be shown
     * @var Boolean
     */
    private $facebook;
    
    /**
     * Indicates if a Twitter Button should be shown
     * @var Boolean
     */
    private $twitter;
    
    /**
     * Indicates if a Google+ Button should be shown
     * @var Boolean
     */
    private $googleplus;
    
    /**
     * Indicates if Whats App should be shown
     * @var Boolean
     */
    private $whatsapp;    
    
    /**
     * Indicates if the sum of all shares should be shown
     * @var Booelan 
     */
    private $totalShares;
    
    /**
     * Thank you Text for all Shares
     * @var String
     */
    private $thankYouText;
    
    /**
     * Url to share
     * @var String
     */
    private $shareUrl;
    
    /**
     * Sharing Text
     * @var String
     */
    private $text;
    
    /**
     * Constructor
     * @param String $shareUrl
     * @param String $text - Twitter Message Text
     * @param Boolean $totalShares
     * @param Boolean $facebook
     * @param Boolean $twitter
     * @param Boolean $googleplus
     * @throws \Exception
     */
    public function __construct(
            $shareUrl,
            $text = '',
            $totalShares = false,
            $facebook = true, 
            $twitter = true, 
            $googleplus = true,
            $whatsapp = true) 
    {

        try {
            
            //call parent constructor
            parent::__construct();
            
            $this->setFacebook($facebook);
            $this->setTwitter($twitter);
            $this->setGoogleplus($googleplus);
            $this->setWhatsapp($whatsapp);
            $this->setShareUrl($shareUrl);
            $this->setText($text);
            $this->setTotalShares($totalShares);
            

            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                            'smb',
                            '/HybridCMS/Plugins/Smb/js/f.js',
                            5,
                            false,
                            true,
                            'footer',
                            true
            );            
            $this->addObjJSResource($objJSResource);                    
            
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
                    'smTwoClickButton', //1
                    '/HybridCMS/Plugins/Smb/css/f.css', //2
                    5, //3
                    true, //4
                    true //5
                    );
            $this->addObjCSSResource($objCSSResource);            
            
            
        } 
        catch (Exception $e) 
        {
            throw $e;
        }
    }
    
    /**
     * setFacebook
     * @param Boolean $facebook
     * @throws \Exception
     */
    private function setFacebook($facebook) 
    {
        if (!is_bool($facebook)) 
        {
            throw new \Exception(
            "Error Processing Request: setFacebook(),
                        facebook must be of type Boolean.", 1);
        }
        
        $this->facebook = $facebook;
    }
    
    /**
     * setTwitter
     * @param Boolean $twitter
     * @throws \Exception
     */
    private function setTwitter($twitter) 
    {
        if (!is_bool($twitter)) 
        {
            throw new \Exception(
            "Error Processing Request: setTwitter(),
                        twitter must be of type Boolean.", 1);
        }
        
        $this->twitter = $twitter;
    }    
    
    /**
     * setGoogleplus
     * @param Boolean $googleplus
     * @throws \Exception
     */
    private function setGoogleplus($googleplus) 
    {
        if (!is_bool($googleplus)) 
        {
            throw new \Exception(
            "Error Processing Request: setGoogleplus(),
                        googleplus must be of type Boolean.", 1);
        }
        
        $this->googleplus = $googleplus;
    }  
    
    /**
     * setWhatsapp
     * @param Boolean $whatsapp
     * @throws \Exception
     */
    private function setWhatsapp($whatsapp) 
    {
        if (!is_bool($whatsapp)) 
        {
            throw new \Exception(
            "Error Processing Request: setWhatsapp(),
                        whatsapp must be of type Boolean.", 1);
        }
        
        $this->whatsapp = $whatsapp;
    }      
    
    /**
     * setTotalShares
     * @param Boolean $totalShares
     * @throws \Exception
     */
    public function setTotalShares($totalShares) 
    {
        if (false === is_bool($totalShares)) 
        {
            throw new \Exception(
            'Error Processing Request: setTotalShares(),
                        $totalShares must be of type Boolean.', 1);
        }
        
        $this->totalShares = $totalShares;
    }     
        
    /** 
     * Sets the url to share
     * @param String $url
     */
    private function setShareUrl($url)
    {
        if (true === empty($url)) 
        {
            throw new \Exception(
            'Error Processing Request: setShareUrl,
                        $url is not set.', 1);
        }
        
        if(false === \HybridCMS\Modules\Url\Url::isValidUrl($url))
        {
            throw new \Exception(
            'Error Processing Request: setShareUrl,
                        $url is not valid.', 1);
        }
        
        $objUrl = new \HybridCMS\Modules\Url\Url($url);
        
        if(false === $objUrl->urlIsInternal())
        {
            throw new \Exception(
            'Error Processing Request: setShareUrl,
                        $url is not internal.', 1);
        }   
        
        $this->shareUrl = $url;
    }
    
    /**
     * setText, actual the twitter msg
     * @param Boolean $text
     * @throws \Exception
     */
    public function setText($text) {
        if (!preg_match('/^[a-zA-Z0-9\-_\!\?,ßöäüÄÖÜ\s\.\:]+$/', $text)) 
        {
            throw new \Exception(
            "Error Processing Request: setText(),
                        text is not valid.", 1);
        }
        
        $this->text = $text;
    }     
    
    /**
     * Getter
     * @return Boolean
     */
    public function getFacebook() { return $this->facebook; }
    public function getTwitter() { return $this->twitter; }
    public function getGoogleplus() { return $this->googleplus; }
    public function getWhatsapp() { return $this->whatsapp; }
    
    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) 
    {
        
        assert(false === empty($this->shareUrl));

        //output-String
        $op = '';  
                        
        //open unordered List
        $op .= '<aside class="hyb_socialMediaButton add-bottom-30">';
        
        //show total shares
        if(true === $this->totalShares)
        {
            $op .= '<span class="hyb_totalShares"></span>';
        }
               
        //add Facebook
        if(true === $this->facebook) 
        {
            $url = "http://www.facebook.com/sharer.php?u=" 
                    . urlencode($this->shareUrl);
            $op .= '<a class="hyb_facebookBtn" rel="popup" target="_blank" '
                    . 'href="'. htmlentities($url) .'">';
            
            $op .= '<i class="fa fa-facebook"></i>';
            $op .= '<span class="hyb_shares">teilen</span>';
            $op .= '</a>';
        }
        
        
        //add Twitter
        if(true === $this->twitter) 
        {
            $url = "http://twitter.com/intent/tweet"
                    . "?text=" . $this->text
                    . "&url=" . urlencode($this->shareUrl)
                    . "&lang=de";
            
            $op .= '<a class="hyb_twitterBtn" rel="popup" target="_blank" '
                    . 'href="'. htmlentities($url) .'">';
            
            $op .= '<i class="fa fa-twitter"></i>';
            $op .= '<span class="hyb_shares">twittern</span>';
            $op .= '</a>';
        }
                
        //add Google Plus
        if(true === $this->facebook) 
        {
            $url = "https://plus.google.com/share?url=" 
                    . urlencode($this->shareUrl);
            
            $op .= '<a class="hyb_googleplusBtn" rel="popup" target="_blank" '
                    . 'href="'. htmlentities($url) .'">';
            $op .= '<i class="fa fa-google-plus"></i>';
            $op .= '<span class="hyb_shares">teilen</span>';
            $op .= '</a>';
        }  
        
        //add Whats App
        if(true === $this->whatsapp) 
        {
            $url = "WhatsApp://send?text="
                    . htmlentities($this->text) . " "
                    . urlencode($this->shareUrl);
            
            $op .= '<a class="hyb_whatsappBtn" rel="popup" target="_blank" '
                    . 'href="'. htmlentities($url) .'">';
            $op .= '<i class="fa fa-whatsapp"></i>';
            $op .= '<span class="hyb_shares">teilen</span>';
            $op .= '</a>';
        }        
                
        //close unordered List
        $op .= '</aside>';
                
        return $op;
    }    
}
?>
