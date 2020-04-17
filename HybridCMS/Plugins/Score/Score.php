<?php

namespace HybridCMS\Plugins\Score;

/**
 * class Score
 *
 * @package Score
 * @author Claas Kalwa
 * @copyright 2015 Claroweb.de
 */
class Score extends \HybridCMS\Plugins\Plugin\Plugin {
            
    /**
     * Categories to score
     * @var String[]
     */
    private $arrCategories;
    
    /**
     * ArticleMeta to store Scores in DB
     * @var String
     */
    private $objArticleMeta;
    
    /**
     * Sum of scores
     * @var Float;
     */
    private $totalScore;
    

    /**
     * @param ArticleMeta $objArticleMeta
     * @param Categories $arrCategories
     */
    public function __construct($objArticleMeta, $arrCategories) 
    {
            
        //call parent constructor
        parent::__construct(); 
        
        $this->setObjArticleMeta($objArticleMeta);
        $this->setArrCategories($arrCategories);

        /**
         * CSS
         *
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         */
        $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                'score', //1
                '/HybridCMS/Plugins/Score/css/f.css', //2
                5, //3
                true, //4
                true //5
                );
        $this->addObjCSSResource($objCSSResource);                      
    }
    
    /**
     * setObjArticleMeta
     * @param \HybridCMS\Content\Article\ArticleMeta $objArticleMeta
     * @throws \Exception
     */
    private function setObjArticleMeta($objArticleMeta)
    {
        if(false === ($objArticleMeta 
                instanceof \HybridCMS\Content\Article\ArticleMeta))
        {
            throw new \Exception(
            "Error Processing Request: setObjArticleMeta(),
                objArticleMeta is not an instance of ArticleMeta.", 1);
        }
        
        $this->objArticleMeta = $objArticleMeta;
    }
    
    /**
     * setArrCategories
     * @param String[] $arrCategories
     * @throws \Exception
     */
    private function setArrCategories($arrCategories) 
    {
        //check if $arrCategories is an array
        if(false === is_array($arrCategories) && count($arrCategories) > 0) {
            throw new \Exception(
            'Error Processing Request: setArrCategories(),
                $arrCategories is not an array.', 1);
        }
        
        foreach ($arrCategories as $key) {
             
            //check if values are strings
            if(false === is_string($key)) {
                throw new \Exception(
                'Error Processing Request: setArrCategories(),
                    key have to be a string.', 1); 
            }
            
            //check if keys are MetaKeys
            if(true === empty($this->objArticleMeta->getArticleMeta($key))) 
            {
                throw new \Exception(
                'Error Processing Request: setArrCategories(),
                    key is not a metaKey:' . htmlspecialchars($key), 1); 
            }
            
            $score = $this->objArticleMeta->getArticleMeta($key);
            //check if values are numeric and between 0 and 10
            if(false === is_numeric($score)
               ||
               0 > $score
               || 
               10 < $score) 
            {
                throw new \Exception(
                'Error Processing Request: setArrCategories(),
                    value is not numeric: ' . htmlspecialchars($score), 1); 
            }   
            
            //summarise scores
            $this->totalScore += $score;
        }        
        $this->arrCategories = $arrCategories;
    }
    
    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) 
    {        
        
        $url = $this->objArticleMeta->getUrl();        
        
        //output-String
        $op = '';  
        
        $op .= '<p class="scoreMean">'
            . '<span class="score">'
            . round($this->totalScore / count($this->arrCategories), 2) 
            . '</span>'
            . '<span class="outof">(von 10)</span>'
            . '</p>';
                        
	$op .= '<ul class="scoreCategories">';
                
        foreach ($this->arrCategories as $key) {
            
            $achor = \HybridCMS\Helper\Helper::mkClassName($key);            
            $score = $this->objArticleMeta->getArticleMeta($key);
            
            $op .= "<li>";
            
            $op .= '<a href="'. htmlentities($url) . '#' . htmlentities($achor) .'" style="width:' . $score * 10 . '%;">';
            $op .= '<div class="key">' . htmlspecialchars($key) . ':</div> ' . $score . ' Punkte';
            $op .= '</a>';
            
            $op .= "</li>";
            
        }
        
        $op .= '</ul>';
                        
        return $op;
    }   
    
    /**
     * Getter Total Score
     * @return float
     */
    public function getTotalScore()
    {
        return $this->totalScore;
    }    
}
?>
