<?php

namespace HybridCMS\Modules\Url;

/**
 * class Url - Class to decompound and handle URLs
 *
 * @package Modules
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Url {

    /**
     * complete url
     * @var String
     */
    protected $url;

    /**
     * e.g. http or https
     * @var String
     */
    protected $scheme;

    /**
     * hostname e.g. hybcms.vs
     * @var String
     */
    protected $hostName;

    /**
     * port like 81
     * @var numeric
     */
    protected $port;

    /**
     * username
     * @var String
     */
    protected $user;

    /**
     * password
     * @var String
     */
    protected $pass;

    /**
     * path
     * @var String
     */
    protected $path;

    /**
     * e.g. key1=value1&key2=value2
     * @var String
     */
    protected $keyValues;
    
    /**
     * e.g. array('key1' => 'value1', ...)
     * @var String[]
     */
    protected $arrKeyValues;

    /**
     * anchor after #
     * @var String
     */
    protected $fragment;
    
    /**
     * e.g. /www/htdocs/inc from /www/htdocs/inc/lib.inc.php
     * @var String
     */
    protected $dirname;
    
    /**
     * e.g. lib.inc.php from /www/htdocs/inc/lib.inc.php
     * @var String
     */
    protected $basename;
    
    /**
     * e.g. php from /www/htdocs/inc/lib.inc.php
     * @var String
     */
    protected $extension;
    
    /**
     * e.g. lib.inc from /www/htdocs/inc/lib.inc.php
     * @var String
     */
    protected $filename;

    /**
     * __construct
     * @param String $url
     * @throws \Exception
     */
    public function __construct($url) 
    {
        $this->setUrl($url);

        //parse url and set attributes
        $this->parseUrl();

        //parse path and set attributes
        $this->parsePath();            
    }
    
    protected function parsePath()
    {
        if(false === empty($this->path)) 
        {
            $pathComponents = pathinfo($this->path);

            if(false === empty($pathComponents['dirname']))
            {
                $this->setDirname($pathComponents['dirname']);
            }

            if(false === empty($pathComponents['basename']))
            {
                $this->setBasename($pathComponents['basename']);
            }

            if(false === empty($pathComponents['extension']))
            {
                $this->setExtension($pathComponents['extension']);
            }

            if(false === empty($pathComponents['filename']))
            {
                $this->setFilename($pathComponents['filename']);
            }  
        }
    }

    /**
     * Parses the url and sets all componentent 
     */
    protected function parseUrl()
    {
        assert(false === empty($this->url));
        
        //parse url
        $arrUrlComponents = parse_url($this->url);

        //set Scheme
        if (!empty($arrUrlComponents['scheme'])) 
        {
            $this->setScheme($arrUrlComponents['scheme']);
        } 

        //set hostName
        if (!empty($arrUrlComponents['host'])) 
        {
            $this->setHostName($arrUrlComponents['host']);
        } 

        //set port             
        if (!empty($arrUrlComponents['port'])) {
            $this->setPath($arrUrlComponents['port']);
        } 

        //set user
        if (!empty($arrUrlComponents['user'])) 
        {
            $this->setUser($arrUrlComponents['user']);
        }

        //set pass
        if (!empty($arrUrlComponents['pass'])) 
        {
            $this->setPass($arrUrlComponents['pass']);
        }

        //set path  
        if (!empty($arrUrlComponents['path'])) 
        {
            $this->setPath($arrUrlComponents['path']);
        }          

        //set keyValue
        if (!empty($arrUrlComponents['query'])) 
        {
            $this->setKeyValues($arrUrlComponents['query']);
            $this->convertUrlQuery();
        }

        //set fragment
        if (!empty($arrUrlComponents['fragment'])) 
        {
            $this->setFragment($arrUrlComponents['fragment']);
        }              
    }

    
    /**
     * setScheme
     * @param String $scheme
     */
    protected function setScheme($scheme) {
        $this->scheme = $scheme;
    }

    /**
     * setHostName
     * @param String $hostName
     */
    protected function setHostName($hostName) {
        $this->hostName = $hostName;
    }

    /**
     * setHostName
     * @param Int setPort
     */
    protected function setPort($port) {

        if (!is_numeric($port)) {
            throw new \Exception(
            "Error Processing Request: setPort(),
                            port is not numeric.", 1);
        }

        $this->port = $port;
    }

    /**
     * setUser
     * @param String $user
     */
    protected function setUser($user) {
        $this->user = $user;
    }

    /**
     * setPass
     * @param String $pass
     */
    protected function setPass($pass) {
        $this->pass = $pass;
    }

    /**
     * setPath
     * @param String $path
     */
    protected function setPath($path) {
        $this->path = $path;
    }

    /**
     * setKeyValue
     * @param String $keyValue
     */
    protected function setKeyValues($keyValues) {
        $this->keyValues = $keyValues;
    }

    /**
     * setFragment
     * @param String $fragment
     */
    protected function setFragment($fragment) {
        $this->fragment = $fragment;
    }

    /**
     * setUrl
     * 
     * @param String $url
     * @throws \Exception
     */
    protected function setUrl($url) {

        if (!self::isValidUrl($url)) {
            throw new \Exception(
            "Error Processing Request: isValidUrl(),
                            url is not valid.", 1);
        }

        $this->url = $url;
    }
    
    /**
     * setDirname
     * @param String $dirname
     */
    protected function setDirname($dirname)
    {
        $this->dirname = $dirname;
    }
    
    /**
     * setBasename
     * @param String $basename
     */
    public function setBasename($basename) {
        $this->basename = $basename;
    }

    /**
     * setExtension
     * @param String $extension
     */
    public function setExtension($extension) {
        $this->extension = $extension;
    }

    /**
     * setFilename
     * @param String $filename
     */
    public function setFilename($filename) {
        $this->filename = $filename;
    }

    /**
     * Assign the url query as associative array 
     * 
     * @param string query 
     */
    function convertUrlQuery() {
        
        $queryParts = explode('&', $this->keyValues);

        $params = array();
        foreach ($queryParts as &$param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }

        $this->arrKeyValues = &$params;
    }

    /**
     * isValidUrl
     * 
     * @param String $url
     * @return boolean
     */
    public static function isValidUrl($url) {

        if (!is_string($url) || !preg_match(
                        '~^((?P<scheme>[^:/?#]+):(//))?((\\3|//)?(?:(?P<user>[^:]+):(?P<pass>[^@]+)@)?(?P<host>[^/?:#]*))(:(?P<port>\\d+))?' .
                        '(?P<path>[^?#]*)(\\?(?P<query>[^#]*))?(#(?P<fragment>.*))?~u', $url)) {

            return false;
        } else {
            return true;
        }
    } 
    
    /**
     * Check if url is from $hostname
     * @param String $hostname
     * @return Boolean
     */
    public function isUrlFromHost($hostname)
    {
        return (false === empty($hostname)
                &&
                true === is_string($hostname)
                &&
                $this->hostName === $hostname); 
    }
    
    /**
     * urlIsInternal - returns true if the url is from the current host
     * @return boolean
     */
    public function urlIsInternal() 
    {            
        return (false === empty($this->hostName) 
                && 
                $this->hostName === HYB_HOST_NAME);                
    }    
    
    /**
     * getUrl
     * 
     * @return String
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * getScheme
     * @return String
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * getHostName
     * @return String
     */
    public function getHostName() {
        return $this->hostName;
    }

    /**
     * getPort
     * @return Integer
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * getUser
     * @return String
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * getPass
     * @return String
     */
    public function getPass() {
        return $this->pass;
    }

    /**
     * getPath
     * @return String
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * getKeyValues
     * @return String
     */
    public function getKeyValues() {
        return $this->keyValues;
    }

    /**
     * getArrKeyValues
     * @return String[]
     */
    public function getArrKeyValues() {
        return $this->arrKeyValues;
    }

    /**
     * getFragment
     * @return String
     */
    public function getFragment() {
        return $this->fragment;
    }
    
    /**
     * getDirname
     * @return String
     */
    public function getDirname() {
        return $this->dirname;
    }

    /**
     * getBasename
     * @return String
     */
    public function getBasename() {
        return $this->basename;
    }

    /**
     * getExtension
     * @return String
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * getFilename
     * @return String
     */
    public function getFilename() {
        return $this->filename;
    }


}//end class