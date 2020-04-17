<?php

namespace HybridCMS\Modules\TextProcessing;

/**
 * class TextProcessing - read, process, mine and 
 * visualize textual data in a conveniet way
 *
 * @package Modules
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class TextProcessing {
    
    /**
     * Terms of the querry
     * @var String[]
     */
    private $arrTerms;
    
    /**
     * Stopwords to filter the terms of the query
     * (Can be overridden with external stopwords by using the setter)
     * @var String[]
     */
    private $arrStopwords = array('aber', 'als', 'am', 'an', 'auch', 'auf', 'aus', 'bei', 'bin', 'bis', 'bist', 'da', 'dadurch', 'daher', 'darum', 'das', 'daß', 'dass', 'dein', 'deine', 'dem', 'den', 'der', 'des', 'dessen', 'deshalb', 'die', 'dies', 'dieser', 'dieses', 'doch', 'dort', 'du', 'durch', 'ein', 'eine', 'einem', 'einen', 'einer', 'eines', 'er', 'es', 'euer', 'eure', 'für', 'hatte', 'hatten', 'hattest', 'hattet', 'hier	', 'hinter', 'ich', 'ihr', 'ihre', 'im', 'in', 'ist', 'ja', 'jede', 'jedem', 'jeden', 'jeder', 'jedes', 'jener', 'jenes', 'jetzt', 'kann', 'kannst', 'können', 'könnt', 'machen', 'mein', 'meine', 'mit', 'muß', 'mußt', 'musst', 'müssen', 'müßt', 'nach', 'nachdem', 'nein', 'nicht', 'nun', 'oder', 'seid', 'sein', 'seine', 'sich', 'sie', 'sind', 'soll', 'sollen', 'sollst', 'sollt', 'sonst', 'soweit', 'sowie', 'und', 'unser	', 'unsere', 'unter', 'vom', 'von', 'vor', 'wann', 'warum', 'was', 'weiter', 'weitere', 'wenn', 'wer', 'werde', 'werden', 'werdet', 'weshalb', 'wie', 'wieder', 'wieso', 'wir', 'wird', 'wirst', 'wo', 'woher', 'wohin', 'zu', 'zum', 'zur', 'über');
    


    /**
     * ### gehört in eigene Klasse
     * 
     * Queries that are interesting for somesting 
     * like keyword injection or something similar
     * @var String[]
     */
    private $arrQueriesOfInterest;

    /**      
     * ### gehört in eigene Klasse
     * 
     * Terms that are interesting for something like 
     * Keyword injection or something like that
     * @var String[]
     */
    private $arrTermsOfInterest;
    
    /**
     * ### gehört in eigene Klasse
     * 
     * isQueryOfInterest - returns true when the query 
     * is of interest, else false. When the param is passed, param and 
     * passed query will compared, otherwise the attribute query is used.
     * 
     * @param $queryOfInterest
     * @return Boolean
     */
    public function isQueryOfInterest($queryOfInterest = null) {
        
    }

    /**
     * ### gehört in eigene Klasse
     * 
     * queryHasTermOfInterest - return terms of interest. If param is null
     * the attribute arrTerms is used to compare the query's Terms of the user
     * 
     * @param void
     * @return String[]
     */
    public function queryHasTermOfInterest($termOfInterest = null) {
        
    }    
}