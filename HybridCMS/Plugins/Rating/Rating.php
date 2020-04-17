<?php

namespace HybridCMS\Plugins\Rating;

/**
 * class Rating
 *
 * @package Rating
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Rating extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * unique url of the rating
     * @var String
     */
    private $url;

    /**
     * the maximum of rating points
     * @var Integer
     */
    private $maxRatingPoints;

    /**
     * number of ratings
     * @var Integer
     */
    private $ratingsTotal;

    /**
     * rating score
     * @var Integer
     */
    private $ratingScoreTotal;
    
    /**
     * Ratingpoint steps 0.5 || 1.0
     * @var String
     */
    private $dataStep = '0.5';
    
    /**
     * Disables rating functionality on 'false' else 'true'
     * @var String
     */
    private $dataDisabled = 'false';
    
    /**
     * 'false' | 'true'
     * @var String
     */
    private $dataDataShowCaption = 'true'; 
    
    /**
     * Allowed Star-sizes: "xs", "sm", "md", "lg", "xl"
     * @var String
     */
    private $dataSize = "md";
    
    /**
     * Font awesome unicode symbol
     * @var String
     */
    private $dataSymbol = "f005";
    
    /**
     * Default caption text
     * @var String
     */
    private $defaultCaption = "{rating} Sterne";
    
    /**
     * Thank you text
     * @var String
     */
    private $thankYouText = 'Danke für Deine Bewertung!';

    /**
     * __construct
     *
     * @param String $url
     * @param Integer $maxRatingPoints
     * @param Integer $ratingsTotal
     * @param Integer $ratingScoreTotal
     */
    public function __construct($url, $maxRatingPoints = 5) {

        try {
            //call parent constructor
            parent::__construct();

            //assign attributes
            $this->setUrl($url);
            $this->setMaxRatingPoints($maxRatingPoints);

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //fetch Rating-Data by URL from Database
            $objDBRating = new \HybridCMS\Database\DBRating();
            $arrRatingData = $objDBRating->selectRatingDataByUrl($db, $this->url);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //check if Rating-Data exists in Database for the current url
            if (!$arrRatingData) {
                $arrRatingData['ratingsTotal'] = 0;
                $arrRatingData['ratingScoreTotal'] = 0;
            } else {

                //check if passed maxRatingPoints is equal to maxRatingPoints from DB
                if ($arrRatingData['maxRatingPoints'] != $this->maxRatingPoints) {
                    throw new \Exception(
                            "Error Processing Request: __construct(),
                                passed maxRatingPoints is unequal to maxRatingPoint from Database.", 1);
                }
            }

            $this->setRatingsTotal($arrRatingData['ratingsTotal']);
            $this->setRatingScoreTotal($arrRatingData['ratingScoreTotal']);

            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                            'rating',
                            '/HybridCMS/Plugins/Rating/js/f.js',
                            12,
                            false,
                            true,
                            'footer',
                            false
            );

            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource2 = new \HybridCMS\Page\Resources\JSResource(
                            'initrating',
                            '/HybridCMS/Plugins/Rating/js/init.js',
                            13,
                            false,
                            true,
                            'footer',
                            false
            );  
            
            $this->addObjJSResource($objJSResource1);
            $this->addObjJSResource($objJSResource2);

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
                            'rating',
                            '/HybridCMS/Plugins/Rating/css/f.css',
                            '2',
                            true,
                            true
                    );

            $this->addObjCSSResource($objCSSResource);
                        
        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setRatingScoreTotal
     *
     * @param type $ratingScoreTotal
     * @return void
     * @throws \Exception
     */
    public function setRatingScoreTotal($ratingScoreTotal) {

        //check if $ratingScoreTotal is an Integer grater or euqal 0
        if (!is_numeric($ratingScoreTotal) && $ratingScoreTotal >= 0) {
            throw new \Exception(
                    "Error Processing Request: setRatingScoreTotal(),
                       ratingScoreTotal must be an Integer greater or equal 0.", 1);
        }

        $this->ratingScoreTotal = $ratingScoreTotal;
    }

    /**
     * setRatingsTotal
     *
     * @param Integer $ratingsTotal
     * @throws \Exception
     * @return void
     */
    public function setRatingsTotal($ratingsTotal) {

        //check if $ratingsTotal is an Integer grater or euqal 0
        if (!is_numeric($ratingsTotal) && $ratingsTotal >= 0) {
            throw new \Exception(
                    "Error Processing Request: setRatingTotal(),
                       ratingsTotal must be an Integer greater or equal 0.", 1);
        }

        $this->ratingsTotal = $ratingsTotal;
    }

    /**
     * setMaxRatingPoints
     *
     * @param Integer $maxRatingPoints
     * @throws \Exception
     * @return void
     */
    public function setMaxRatingPoints($maxRatingPoints) {

        //check if maxRatingPoints is an Integer grater than 0
        if (!is_numeric($maxRatingPoints) && $maxRatingPoints > 0) {
            throw new \Exception(
                    "Error Processing Request: setMaxRatingPoints(),
                       maxRatingPoints must be an Integer greater than 0.", 1);
        }

        $this->maxRatingPoints = $maxRatingPoints;
    }

    /**
     * setUrl
     *
     * @param String $url
     * @return void
     */
    private function setUrl($url) {

        //check if url is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
                    "Error Processing Request: setUrl(),
                       url is not valid.", 1);
        }

        //check if url is from the current host
        if (!stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) === 0) {
            throw new \Exception(
                    "Error Processing Request: setUrl(),
                       url must be from the current host.", 1);
        }

        $this->url = $url;
    }
    
    /**
     * setDataStep
     * @param String $dataStep
     * @throws \Exception
     */
    public function setDataStep($dataStep)
    {
        if ($dataStep !== '0.5' && $dataStep !== '1' && $dataStep !== '1.0') {
            throw new \Exception(
                    'Error Processing Request: setDataStep(),
                       $dataStep must be 0.5 or 1.0.', 1);
        }
        
        $this->dataStep = $dataStep;
    }
    
    /**
     * setDataDisabled
     * @param String $dataDisabled
     * @throws \Exception
     */
    public function setDataDisabled($dataDisabled)
    {
        if ($dataDisabled !== 'true' && $dataDisabled !== 'false') {
            throw new \Exception(
                    'Error Processing Request: setDataStep(),
                       dataDisabled must be true or false as 
                       stringrepresentation.', 1);
        }
        
        $this->dataDisabled = $dataDisabled;
    }   
    
    /**
     * setDataShowCaption
     * @param String $dataDataShowCaption
     * @throws \Exception
     */
    public function setDataShowCaption($dataDataShowCaption)
    {
        if ($dataDataShowCaption !== 'true' && $dataDataShowCaption !== 'false') {
            throw new \Exception(
                    'Error Processing Request: setDataStep(),
                       $dataDataShowCaption must be true or 
                       false as stringrepresentation.', 1);
        }
        
        $this->dataDataShowCaption = $dataDataShowCaption;
    }  
    
    /**
     * setDataSize
     * @param String $dataSize allowed is: "xs", "sm", "md", "lg", "xl"
     * @throws \Exception
     */
    public function setDataSize($dataSize)
    {
        $arrAllowedSizes = array("xs", "sm", "md", "lg", "xl");
        if (false === in_array($dataSize, $arrAllowedSizes)) {
            throw new \Exception(
                    'Error Processing Request: setDataSize(),
                       $arrAllowedSizes is not valid.', 1);
        }
        
        $this->dataSize = $dataSize;
    }    
    
    /**
     * setDataSymbol
     * @param String Fontawesome unicode 
     * @throws \Exception
     */
    public function setDataSymbol($dataSymbol)
    {
        if (4 !== strlen($dataSymbol)) {
            throw new \Exception(
                    'Error Processing Request: setDataSymbol(),
                       $dataSymbol is not valid.', 1);
        }
        
        $this->dataSymbol = $dataSymbol;
    }    
    
    /**
     * setDefaultCaption
     * @param String defaultCaption
     * @throws \Exception
     */
    public function setDefaultCaption($defaultCaption)
    {
        if (100 < strlen($defaultCaption)) {
            throw new \Exception(
                    'Error Processing Request: setDefaultCaption(),
                       $defaultCaption must be not longer than 100 chars.', 1);
        }
        
        $this->defaultCaption = $defaultCaption;
    }  
    
    /**
     * setDataThankYouText
     * @param String thankyoutext
     * @throws \Exception
     */
    public function setDataThankYouText($thankYouText)
    {
        if (100 < strlen($thankYouText)) {
            throw new \Exception(
                    'Error Processing Request: setDataThankYouText(),
                       $thankYouText must be not longer than 100 chars.', 1);
        }
        
        $this->thankYouText = $thankYouText;
    }    

    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {

        return $this->printStars();
       
    }
    
    /**
     * printStars - prints the html-code foir the rating stars
     * @return string
     */
    private function printStars() {
        
        //calc ratingScore
        $ratingScore = 0;
        if ($this->ratingScoreTotal > 0) {
            $ratingScore = round($this->ratingScoreTotal / $this->ratingsTotal, 2);
        }
        
        $op = '';
        $op .= '<p class="rating-thankyoutext"></p>';
        $op .= '<div class="aggregateRating" itemprop="aggregateRating" '
                . 'itemscope itemtype="http://schema.org/AggregateRating">';
        
        $op .= '<meta itemprop="worstRating" content = "1"/>';
        
        //add rating stars
        $op .= '<input id="aggregateRating" '
                . 'class="rating" '
                . 'data-min="1" '
                . 'data-max="' . $this->maxRatingPoints . '" '
                . 'data-step="' . htmlentities($this->dataStep) . '" '
                . 'value=' . $ratingScore . ' '
                . 'data-disabled="' . htmlentities($this->dataDisabled) . '" '             
                . 'data-show-caption="' . htmlentities($this->dataDataShowCaption) . '" '
                . 'data-show-clear="false" '
                . 'data-size="' . htmlentities($this->dataSize) . '" '
                . 'data-symbol="&#x' . $this->dataSymbol . '" '
                . 'data-glyphicon="false" '
                . 'data-rating-class="rating-fa" '
                . 'data-default-caption="' . htmlentities($this->defaultCaption) . '" '
                . 'data-star-captions="{}"'
                . 'data-idBox="' . htmlentities($this->url) . '" '
                . 'data-thankyoutext="' . htmlentities($this->thankYouText) . '">';
        
        $op .= '</div>';        

        $op .= '<span itemprop="ratingValue">' 
                . number_format($ratingScore, 1) 
                . '</span> '
                . 'von ';
        
        $op .= '<span itemprop="bestRating">' 
                . $this->maxRatingPoints 
                . '</span> '
                . 'Sternen ('
                
                . '<span itemprop="reviewCount">' 
                . $this->ratingsTotal . '</span> '
                
                . 'Bewertungen)';
       

        return $op;
    }
}

//end Class
?>
