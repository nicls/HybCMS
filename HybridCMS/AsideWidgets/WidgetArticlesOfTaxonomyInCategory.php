<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetArticlesOfTaxonomyInCategory
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetArticlesOfTaxonomyInCategory extends AsideWidget 
{
    /**
     * attributes
     */
    protected $catName;
    protected $arrObjTaxonomy;
    protected $numberOfResults;    
    protected $arrArticles;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of 
     * other widgets
     * @param arrParams - array with key catName and taxonomy
     */
    public function __construct(
            $widgetName, 
            $positionName, 
            $priority, 
            $arrParams) 
    {

        //check if catName is an alphanumeric string
        if (!(isset($arrParams['catName']))) {
            throw new \Exception(
                    "Error Processing Request: __construct(), "
                    . "catName mis null.", 1);
        }

        //check if taxonomie ist given
        if (!(isset($arrParams['taxonomy']))) {
            throw new \Exception(
                    "Error Processing Request: __construct(), "
                    . "taxonomy is null.", 1);
        }
        
        //check if numberOfResults is an integer
        if (!(isset($arrParams['numberOfResults']))) {
            throw new \Exception(
                    "Error Processing Request: __construct(), "
                    . "numberOfResults is null.", 1);
        }        

        try {

            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);

            //setcatName and numberOfResults
            $this->setCatName($arrParams['catName']);
            $this->setNumberOfResults($arrParams['numberOfResults']);
            
            //get Articles
            $objTaxonomy = new \HybridCMS\Content\Section\Taxonomy(
                    $arrParams['taxonomy']);
            $this->arrArticles = $objTaxonomy->getArrArticles();
            
        } 
        catch (\Exception $e) 
        {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                    \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * shuffleArticles
     */
    public function shuffleArticles()
    {
        if(true === is_array($this->arrArticles))
        {
            shuffle($this->arrArticles);
        }
    }       

    /**
     * setter
     */
    private function setCatName($catName) {
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $catName)) {
            throw new \Exception(
            "Error Processing Request: setCatName(),
                        catName is not valid", 1);
        }
        $this->catName = $catName;
    }

    private function setNumberOfResults($numberOfResults) {
        if (!is_numeric($numberOfResults) && $numberOfResults > 100) {
            throw new \Exception(
            "Error Processing Request: setNumberOfResults(),
                        numberOfResults must be an integer lower than 100.", 1);
        }

        $this->numberOfResults = $numberOfResults;
    }
      

    /**
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a.
     * thumb. imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) {

        //check if $args is of type array
        if(!is_array($args)) {
            throw new \Exception(
                    "Error Processing Request: toString(),
                        args must be an array.", 1);
        }

        //check wether parameter are passed through
        if(count($args) > 0) {

            //check if paramter are valid
            if(isset($args['imgWidth'], $args['imgHeight']) && is_numeric(
                    $args['imgWidth']) && is_numeric($args['imgHeight'])) {

                //check if image should be scaled or cropped
                if(!isset($args['imgScale']) || !is_bool($args['imgScale'])) {
                    $args['imgScale'] = true;
                }
            } 
            else 
            {
                throw new \Exception(
                        "Error Processing Request: toString(),
                            values of parameter args are not valid.", 1);
            }
        }

        //output-String
        $op = '';
        
        //print headline
        if(count($this->arrArticles) > 0) {
            $op .= "<section class='widget c12'>";
            $op .= '<header><h3>' . htmlentities($this->getHeadline()) 
                    . '</h3></header>';
            
        }

        //iterate to get the articles
        for ($i=0; $i < min($this->numberOfResults, 
                count($this->arrArticles)); $i++) 
        {

            $article = &$this->arrArticles[$i];
            
            //exclude current article
            if($article->getUrl() === HYB_CURRURL) {
                
                if($i < count($this->arrArticles)-1) {
                    //use the last article
                    $article = array_pop($this->arrArticles);
                } else {
                    //unfortunately there are no more articles
                    continue;  
                }
            }
                        
            //Headline
            $op .= "<article>";
            $op .= "<h4>";
            $op .= "<a href='" . htmlentities($article->getUrl()) 
                    . "' title='" . htmlentities($article->getFirstHeadline()) 
                    . "'>";
            $op .= htmlspecialchars($article->getFirstHeadline());
            $op .= "</a>";
            $op .= "</h4>";

            //check if Thumb is needed
            if(count($args) > 0) {

                //get Thumb of primaryIamgeOfPage
                $thumb = $article->getThumbOfPrimaryImage($args['imgWidth'], 
                        $args['imgHeight'], $args['imgScale']);

                //get primaryImageOfPage as Thumb
                if ($thumb) {
                    $op .= "<figure>";
                    $op .= "<a href='" . htmlentities($article->getUrl()) 
                            . "' title='" 
                            . htmlentities($article->getFirstHeadline()) 
                            . "'>";
                    $op .= "<img src='" . htmlentities($thumb) 
                            . "' width='" . htmlentities($args['imgWidth']) 
                            . "' alt='" . htmlentities($article->getTitle()) 
                            . "' />";
                    $op .= "</a>";
                    $op .= "</figure>";
                }
            } 
            
            $op .= "<p>" . htmlspecialchars($article->getDescription()) 
                    . "</p>";

            //close article-tag
            $op .= "</article>";
        }
                  
        
        //print section end
        if(count($this->arrArticles) > 0) {
            $op .= "</section>";
        }
        
        return $op;
    }
}
?>
