<?php

namespace HybridCMS\Plugins\Comments\View;

/**
 * class ViewComments
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class ViewComments extends \HybridCMS\Plugins\Plugin\View 
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
     * toStringFormElemGroup returns an FormElemGroup as String if 
     * the coresponding object is in array $this->arrSettings
     * @param String $indexName
     * @return String
     * @throws \InvalidArgumentException
     */
    protected function toStringFormElemGroup($indexName)
    {
        
        $ret = '';
        
        if(false === ctype_alnum($indexName))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    toStringFormElemGroup(),                    
                    $indexName is not alphanumeric.', 1);
        }
        
        if(false === empty($this->arrSettings[$indexName]))
        {
            $ret .= $this->arrSettings[$indexName]->toString();
        } 
        
        return $ret;
    }    
}
 
?>