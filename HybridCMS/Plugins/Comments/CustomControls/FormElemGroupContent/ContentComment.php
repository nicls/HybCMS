<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent;

/**
 * class represents the content of a comment-textarea-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentComment
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentTextarea
{
    /**
     * List of Blacklistet Words to look for spam Comments.
     * @var String
     */
    private $arrBlacklist;
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Deine Meinung:');
        $this->setPlaceholder('Dein Kommentar');    
        $this->setErrorMsg('Das Kommentarfeld darf nicht leer sein.');                     
        
        if(false === empty($value))
        {
            $this->setValue($value);
        }
    }    
        
    public function valueIsValid() 
    {
        
        //comment was submitted
        $ret = false === empty($this->value);
        
        //check for spam signals 
        if($ret === true)
        {
            //check for too many links in comment
            $countLinks = mb_substr_count($this->value, "://");            
            if($countLinks > 2)
            {
                $this->setErrorMsg('Es sind nicht mehr als 3 URLs '
                        . 'im Kommentar erlaubt.');
                $ret = false;                
            }
                        
            //Check for spamwords in comment
            $this->readBlacklist();            
            foreach ($this->arrBlacklist as $spamword) 
            {          
                if(false !== stripos($this->value, trim($spamword))) 
                {
                    //found spamword in comment
                    $this->setErrorMsg('Kommentar wurde als Spam erkannt.');
                    $ret = false;  
                    break;
                }
            }
            
        }
                  
        return $ret;
    }
    
    /**
     * Read the Blacklist of Words to look for Spamcomments.
     */
    private function readBlacklist()
    {        
        $pathToBlacklist = HYB_ROOT . "HybridCMS/Plugins/"
                . "Comments/Model/blacklist.txt";
        $this->arrBlacklist = file($pathToBlacklist, 
                FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);  
        
        if(false === is_array($this->arrBlacklist)) 
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    readBlacklist(), no valid blacklist.txt given: ' .
                htmlspecialchars($pathToBlacklist), 1);
        }
    }

}
