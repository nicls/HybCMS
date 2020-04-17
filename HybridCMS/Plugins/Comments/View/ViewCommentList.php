<?php

namespace HybridCMS\Plugins\Comments\View;

/**
 * class ViewcommentList
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewCommentList extends \HybridCMS\Plugins\Comments\View\ViewComments
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
        assert(true === isset($this->arrSettings['arrObjComments']));
        assert(true === \HybridCMS\Util\VarCheck::issetAndNotEmpty(
                $this->arrSettings['objUrl']));
        
        //get comments
        $arrObjComments = $this->arrSettings['arrObjComments'];
        
        //get URL-Instance
        $objUrl = $this->arrSettings['objUrl'];
               
        $op = '';        
        $op .= "<aside id='"
                . $objUrl->getFragment() . '-commentsContainer' 
                ."' class='add-bottom-60'>";
        
        $op .= '<header class="f20 bold borderBottom add-bottom-20 add-top-40">'
                . count($arrObjComments) . ' Lesermeinungen:</header>';
        $op .= $this->toStringFormElemGroup('objButtonListComments');   
        $op .= "</aside>";
        
        return $op;
    }
}

?>