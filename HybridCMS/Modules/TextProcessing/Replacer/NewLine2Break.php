<?php

namespace HybridCMS\Modules\TextProcessing\Replacer;

/**
 * Replaces \n  with <br />
 *
 * @package Modules\Replacer
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class NewLine2Break implements IReplacer
{
    /**
     * Repalces all \n with </br />
     * @param String $string
     * @return String
     */
    public function replace($string)
    {        
        if(false === is_string($string))
        {
            throw new \Exception(
                'Error Processing Request: replace(),
                    $string must be a String.', 1);
        }
        
        $newString = nl2br(trim($string));
                
        return $newString;
    }

}