<?php

namespace HybridCMS\Plugins\WasItHelpful\Model;

/**
 * class Comment
 *
 * @package WasItHelpful/Model
 * @version 0.0.1
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Response 
{
    /**
     * Indicates weather it was Helpful or not
     * @var Boolean
     */
    private $wasItHelpful;
    
    /**
     * Url of the Response
     * @var String
     */
    private $objUrl;   
    
    /**
     * Custom Answer/Explenation of the user.
     * @var String
     */
    private $customAnswer;
    
    /**
     * Timestamp of the response when it was created.
     * @var String
     */
    private $timeCreated;
    
    /**
     * Constructor
     */
    public function __construct($url, $wasItHelpful) 
    {
       $this->setobjUrl($url); 
       $this->setWasItHelpful($wasItHelpful);
    }
    
    function getWasItHelpful() {
        return $this->wasItHelpful;
    }

    function getObjUrl() {
        return $this->objUrl;
    }

    function getCustomAnswer() {
        return $this->customAnswer;
    }

    function getTimeCreated() {
        return $this->timeCreated;
    }

    /**
     * Setter for flag WasItHelpful
     * @param Boolean $wasItHelpful
     * @throws \Exception
     */
    function setWasItHelpful($wasItHelpful) 
    {
        //check if published is an boolean
        if(!is_bool($wasItHelpful)) 
        {
            throw new \Exception(
                "Error Processing Request: setWasItHelpful(),
                    'wasItHelpful must be a Boolean.'", 1);
        }        
        $this->wasItHelpful = $wasItHelpful;
    }

    /**
     * setUrl
     * @param String $url
     */
    public function setobjUrl($url)
    {
        //check if a valid url is given
        if(
            true === empty($url)
            ||  
            false === is_string($url)
            ||
            false === \HybridCMS\Modules\Url\Url::isValidUrl($url)
          )
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), no valid url given.', 1);
        }
        
        $objUrl = new \HybridCMS\Modules\Url\Url($url);
        assert(false === empty($objUrl));

        //check if url is internal
        if(false === $objUrl->urlIsInternal())
        {               
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), url is not internal.', 1);  
        }
        
        $this->url = $objUrl->getUrl();
    }

    /**
     * Setter customAnswer
     * @param String $customAnswer
     * @throws \Exception
     */
    function setCustomAnswer($customAnswer) 
    {
        //check if answer is a String width 800 charekters 
        //length in maximum
        if(!is_string($customAnswer) || strlen($customAnswer) >= 800) 
        {
            throw new \Exception(
                "Error Processing Request: setCustomAnswer(),
                    'answer must be an String width 800 
                    charekters length in maximum.'", 1);
        }
        
        $this->customAnswer = $customAnswer;
    }

    /**
     * setTimeCreated
     *
     * @param Integer $timeCreated
     * @throws \Exception
     */
    public function setTimeCreated($timeCreated) 
    {
        //check if time created is an Integer with 10 digits
        if(!is_numeric($timeCreated) || $timeCreated < 1361472873) {
            throw new \Exception(
                "Error Processing Request: setTimeCreated(),
                    'timeCreated must be a 10 digit Integer.'", 1);
        }

        $this->timeCreated = $timeCreated;
    }

}
