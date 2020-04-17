<?php

namespace HybridCMS\Modules\TextProcessing\Replacer;

/**
 * Replaces substrings in a given Text with something other.
 *
 * @package Modules\Replacer
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
interface IReplacer
{
    /**
     * Replaces substrings in a given Text with something other.
     * @param String $text
     * @return String Returns the new text
     */
    public function replace($string);
}