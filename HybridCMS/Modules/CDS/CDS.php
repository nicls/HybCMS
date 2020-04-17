<?php

namespace HybridCMS\Modules\CDS;

/**
 * class CDS - Content Delivery System
 *
 * @package Modules
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class CDS 
{
    
    /**
     * Detects weather a client is a mobile, tablet or desktop-device.
     * @var Mobile_Detect
     */
    private $objMobileDetect;
    
    /**
     * Url that hosts the content coresponding to the clients device-type
     * @var String
     */
    private $objHostUrl;
  
    /**
     * __construct
     */
    public function __construct($url)   
    {
        //create mobile detection object
        $this->objMobileDetect = new \HybridCMS\Modules\MobileDetect
                \MobileDetect();
        
        //create host-url
        $this->objHostUrl = new \HybridCMS\Modules\Url\Url($url);                
    }
    
    /**
     * Returns the path to the content-file coresponding to 
     * the users device-type
     * @return String
     */
    public function getContentPath()
    {
        $fileName = $this->objHostUrl->getFilename();
        $extension = $this->objHostUrl->getExtension();
        $dirName = $this->objHostUrl->getDirname();        
        $contentPath = null;
        
        //tablet detection must come before mobile detection because a tablet 
        //is also a mobile
        if(true === $this->objMobileDetect->isTablet())
        {
            $contentPathTablet = $dirName . '/CDS_' . $fileName . '/content_' . $fileName 
                    . '/'. $fileName . '.tablet.' . $extension;
            if(true === file_exists(HYB_ROOT . $contentPathTablet))
            {
                $contentPath = $contentPathTablet;
            }
        }
        else if(true === $this->objMobileDetect->isMobile())
        {
            $contentPathMobile = $dirName . '/CDS_' . $fileName . '/content_' . $fileName 
                    . '/'. $fileName . '.mobile.' . $extension;
            if(true === file_exists(HYB_ROOT . $contentPathMobile))
            {
                $contentPath = $contentPathMobile;
            }
        }        
        else
        {
            $contentPathDesktop = $dirName . '/CDS_' . $fileName . '/content_' . $fileName 
                    . '/'. $fileName . '.desktop.' . $extension;            
            if(true === file_exists(HYB_ROOT . $contentPathDesktop))
            {
                $contentPath = $contentPathDesktop;
            }
        }
        
        return $contentPath;        
    }    
    
    /**
     * Returns the path to the given section coresponding to 
     * the users device-type. Section can be 
     * 'content', 'header', 'footer', 'setup'
     * @return String
     */
    public function getPathTo($section, $isAmpRequested = false)
    {
        //check if section is valid
        $arrSections = array('content', 'header', 'footer', 'setup');        
        if(false === in_array($section, $arrSections)) {
            throw new \Exception(
            'Error Processing Request: getPathTo(),
                $section is not valid: ' . htmlspecialchars($section), 1);
        }
        
        $fileName = $this->objHostUrl->getFilename();
        $extension = $this->objHostUrl->getExtension();
        $dirName = $this->objHostUrl->getDirname();        
        $pathToSection = null;
        
        //check if an amp version is available if the user requested it
        //and return
        if(true === $isAmpRequested) {
            $pathAmp = $dirName . '/CDS_' . $fileName 
                    . '/' . htmlspecialchars($section) . '_' . $fileName 
                    . '/'. $fileName . '.amp.' . $extension;
            if(true === file_exists(HYB_ROOT . $pathAmp))
            {
                return $pathAmp;
            }
        }
        
        //tablet detection must come before mobile detection because a tablet 
        //is also a mobile
        if(true === $this->objMobileDetect->isTablet())
        {
            $pathTablet = $dirName . '/CDS_' . $fileName 
                    . '/' . htmlspecialchars($section) . '_' . $fileName 
                    . '/'. $fileName . '.tablet.' . $extension;
            if(true === file_exists(HYB_ROOT . $pathTablet))
            {
                $pathToSection = $pathTablet;
            }
        }
        else if(true === $this->objMobileDetect->isMobile())
        {
           $pathMobile = $dirName . '/CDS_' . $fileName 
                    . '/' . htmlspecialchars($section) . '_' . $fileName 
                    . '/'. $fileName . '.mobile.' . $extension;
            if(true === file_exists(HYB_ROOT . $pathMobile))
            {
                $pathToSection = $pathMobile;
            }
        }        
        else
        {
           $pathDesktop = $dirName . '/CDS_' . $fileName 
                    . '/' . htmlspecialchars($section) . '_' . $fileName 
                    . '/'. $fileName . '.desktop.' . $extension;            
            if(true === file_exists(HYB_ROOT . $pathDesktop))
            {
                $pathToSection = $pathDesktop;
            }
        }
        
        return $pathToSection;         
    }        
}

?>
