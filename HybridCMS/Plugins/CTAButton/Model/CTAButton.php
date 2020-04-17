<?php

namespace HybridCMS\Plugins\CTAButton\Model;

/**
 * class CTAButton
 *
 * @package CTAButton
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class CTAButton {
    
    /**
     * target of the button.
     * @var String
     */
    private $url;
    
    /**
     * Button text (Call to Action).
     * @var String
     */
    private $cta;
    
    /**
     * class-Names of the a-tag
     * @var String[]
     */
    private $arrClassNames;
    
    /**
     * Indicates if link is nofollow
     * @var Boolean
     */
    private $nofollow;
    
    /**
     * Indicates if the target of the link is _blank
     * @var Boolean
     */
    private $targetBlank;
    
    /**
     * classes of e.g. fontawesome 
     * @var type String[]
     */
    private $arrIconClassNames = array();
    
    /**
     * Data-Attributes
     * array(
     *     'data-name1' => 'atrrval1 attrval2',
     *     'data-name2' => 'atrrval1 attrval2',
     * )
     * @var String[]
     */
    private $arrDataAttributes = array();

    /**
     * __construct
     * 
     */
    public function __construct($url = null, $cta = null) 
    {
        //set url and CTA
        if(false === empty($url)) 
        {
            $this->setUrl($url);  
        }
        if(false === empty($cta)) 
        {
            $this->setCTA($cta);   
        }
           
        $this->arrClassNames = array();
        $this->arrIconClassNames = array();
    }
    
    /**
     * setCTA
     * @param String $cta
     * @throws \Exception
     */
    public function setCTA($cta) {
        if (false === preg_match('/^[a-zA-Z0-9öäüÖÄÜß\-\s\!\.,]+$/', $cta)) {
            throw new \Exception(
            'Error Processing Request: setCTA(),
                        $cta is not valid.', 1);
        }
        
        $this->cta = $cta;
    }
    
    /**
     * setUrl
     * @param String $linkSchnitte
     * @throws \Exception
     */
    public function setUrl($url) 
    {
        if ( false === \HybridCMS\Modules\Url\Url::isValidURL($url)) {
            throw new \Exception(
            'Error Processing Request: setUrl(),
                        $url is not valid.', 1);
        }
        
        $this->url = $url;
    }  
    
    /**
     * add a ClassName to the array of classnames
     * @param String $className
     * @throws \Exception
     */
    public function addClassName($className) 
    {
        
        if (false === preg_match('/^\w[a-zA-Z0-9\-_]+$/', $className))
        {
            throw new \Exception(
            'Error Processing Request: addClassNames(),
                        $className id not valid.', 1);
        }
        
        $this->arrClassNames[] = $className;
    }
    
    /**
     * Implodes the array of classnames and returns them as string
     * @return String
     */
    public function getClassNamesAsString() {
        
        return implode(' ', $this->arrClassNames);
    }    
    
    /**
     * add a IconClassName to the array of Iconclassnames
     * @param String $iconClassName
     * @throws \Exception
     */
    public function addIconClassName($iconClassName) 
    {
        
        if (false === preg_match('/^\w[a-zA-Z0-9\-_]+$/', $iconClassName))
        {
            throw new \Exception(
            'Error Processing Request: addIconClassNames(),
                        $className id not valid.', 1);
        }
        
        $this->arrIconClassNames[] = $iconClassName;
    }
    
    /**
     * Implodes the array of classnames and returns them as string
     * @return String
     */
    public function getIconClassNamesAsString() {
        
        return implode(' ', $this->arrIconClassNames);
    }     

    /**
     * setNofollow
     * @param type $nofollow
     * @throws \Exception
     */
    public function setNofollow($nofollow) 
    {
        
        if(false === is_bool($nofollow)) {
            throw new \Exception(
            'Error Processing Request: setNofollow(),
                        $nofollow has to be boolean.', 1);
        }
        
        $this->nofollow = $nofollow;
    }

    /**
     * setTargetBlank
     * @param type $targetBlank
     * @throws \Exception
     */
    public function setTargetBlank($targetBlank) 
    {
        
        if(false === is_bool($targetBlank)) {
            throw new \Exception(
            'Error Processing Request: setTargetBlank(),
                        $targetBlank has to be boolean.', 1);
        }
        
        $this->targetBlank = $targetBlank;
    }    
    
    /**
     * Add Dataattributes of the form
     *  array(
     *      'data-name1' => 'atrrval1 attrval2',
     *      'data-name2' => 'atrrval1 attrval2',
     *  )
     * @param array $arrDataAttr
     * @throws \InvalidArgumentException
     */
    public function setArrDataAttributes($arrDataAttr)
    {
        if(false === is_array($arrDataAttr)) {
            throw new \InvalidArgumentException(
            'Error Processing Request: setDataAttributes(),
                        $arrDataAttr has to be an array.', 1);
        } 
        
        foreach ($arrDataAttr as $key => $value) 
        {
            if (false === preg_match('/^\w[a-zA-Z0-9\-_]+$/', $key))
            {
                throw new \InvalidArgumentException(
                'Error Processing Request: setDataAttributes(),
                            $key is not valid.', 1);
            }         
            
            if (false === preg_match('/^\w[a-zA-Z0-9\-_\s]+$/', $value))
            {
                throw new \InvalidArgumentException(
                'Error Processing Request: setDataAttributes(),
                            $value is not valid.', 1);
            }  
            $this->arrDataAttributes = $arrDataAttr;
        }
    }    
    
    /**
     * getNofollow as String
     * @return String
     */
    public function getNofollowAsString() 
    {
        
        $noFollow = 'follow';
        
        if(true === $this->nofollow) {
            $noFollow = 'nofollow';
        }
        
        return $noFollow;
    }

    /**
     * getTargetBlank as String
     * @return String
     */
    public function getTargetBlankAsString() {
        $target = '_self';
        
        if(true === $this->targetBlank) {
            $target = '_blank';
        }
        
        return $target;
    }
        
    /**
     * getUrl
     * @return String
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * getCta
     * @return String
     */
    public function getCta() {
        return $this->cta;
    }    
    
    /**
     * Getter arrDataAttributes
     * @return array
     */
    public function getArrDataAttributes()
    {
        return $this->arrDataAttributes;
    }
}

?>
