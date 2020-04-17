<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetLatestArticles - This class holds all AsideWidgets for different Positions
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetRandomArticles extends AsideWidget {

    /**
     * attributes
     */
    private $catName;
    private $numberOfResults;
    private $arrArticles;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams - array with key catName and numberOfResults
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        //check if catName is an alphanumeric string
        if (!(isset($arrParams['catName']) || !ctype_alnum($arrParams['catName']))) {
            throw new \Exception(
            "Error Processing Request: __construct(), catName must be an alphanumeric String.", 1);
        }

        //check if numberOfResults is an integer
        if (!(isset($arrParams['numberOfResults']) && is_numeric($arrParams['numberOfResults']))) {
            throw new \Exception(
            "Error Processing Request: __construct(), numberOfResults must be an Integer.", 1);
        }

        try {

            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);

            //setcatName and numberOfResults
            $this->setCatName($arrParams['catName']);
            $this->setNumberOfResults($arrParams['numberOfResults']);

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //fetch Articles from Database
            $dbSection = new \HybridCMS\Database\DBSection();
            $this->arrArticles = $dbSection->selectLatestNRandomArticles($db, $this->catName, $this->numberOfResults);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
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
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a thumb.
     * imgScale indicates wether the image should be scaled or cropped
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
            if(isset($args['imgWidth'], $args['imgHeight']) && is_numeric($args['imgWidth']) && is_numeric($args['imgHeight'])) {

                //check if image should be scaled or cropped
                if(!isset($args['imgScale']) || !is_bool($args['imgScale'])) {
                    $args['imgScale'] = true;
                }

            } else {
                throw new \Exception(
                        "Error Processing Request: toString(),
                            values of parameter args are not valid.", 1);
            }
        }
                
        //output-String
        $op = '';
        
        //print headline
        if(count($this->arrArticles) > 0) {
                                                        
            $op .= "<section class='widget'>";
            $op .= '<header>';
            
            //open a-tag
            if(isset($this->headlineUrl)) {
                $op .= '<a href="' . htmlentities($this->getHeadlineUrl()) . '" title="' . htmlentities($this->getHeadline()) . '">';
            }
            
            $op .= '<h3>' . htmlentities($this->getHeadline()) . '</h3>';
            
            //close a-tag
            if(isset($this->headlineUrl)) {
                $op .= '</a>';
            }
            
            $op .= '</header>';
            
        }

        foreach ($this->arrArticles as $article) {          
            
            //exclude current article
            if($article->getUrl() === HYB_CURRURL) continue;
                                    
            //Headline
            $op .= "<article class='add-bottom-30'>";
            $op .= "<h4>";
            $op .= "<a href='" . htmlentities($article->getUrl()) . "' title='" . htmlentities($article->getFirstHeadline()) . "'>";
            $op .= htmlspecialchars($article->getFirstHeadline());
            $op .= "</a>";
            $op .= "</h4>";

            //check if Thumb is needed
            if(count($args) > 0) {

                //get Thumb of primaryIamgeOfPage
                $thumb = $article->getThumbOfPrimaryImage($args['imgWidth'], $args['imgHeight'], $args['imgScale']);

                //get primaryImageOfPage as Thumb
                if ($thumb) {
                    $op .= "<figure>";
                    $op .= "<a href='" . htmlentities($article->getUrl()) . "' title='" . htmlentities($article->getFirstHeadline()) . "'>";
                    $op .= "<img class='img-responsive add-bottom-10' src='" . htmlentities($thumb) . "' width='" . htmlentities($args['imgWidth']) . "' alt='" . htmlentities($article->getTitle()) . "' />";
                    $op .= "</a>";
                    $op .= "</figure>";
                }
            } 
            
            $op .= "<p>" . htmlspecialchars($article->getDescription());
            $op .= " <a href='" . htmlentities($article->getUrl()) 
                    . "' title='" 
                    . htmlentities($article->getFirstHeadline()) . "'>";
            $op .= " <i class='fa fa-chevron-circle-right'></i>";
            $op .= "</a>";
            
            $op .= "</p>";
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
