<?php

namespace HybridCMS\Plugins\News;

/**
 * class News
 *
 * @package News
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class News extends \HybridCMS\Plugins\Plugin\Plugin {

    /**
     * Array of NewsTeaser Objects
     * @var NewsTeaser[]
     */
    private $arrObjNewsTeaser = array();

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() 
    {
        //call constructor of parent class
        parent::__construct();                    

        //Add CSSResource
        $objCSSResource1 = new \HybridCMS\Page\Resources\CSSResource(
                'news', '/HybridCMS/Plugins/News/css/f.css');
        $this->addObjCSSResource($objCSSResource1); 
    }

    /**
     * fetchNews - fetches all NewsTeaser from the Database
     * @param $n Number of News to fetch.
     * @returns NewsTeaser[]
     * @throws \Exception
     */
    public function fetchNews($n) {
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::
                    getFactory()->getConnection();

            //get Database object
            $objDBNews = new \HybridCMS\Plugins\News\
                    Database\DBNews();

            $this->arrObjNewsTeaser = $objDBNews->
                    selectNArrObjNewsTeaserOrderedByDateDesc($db, $n);
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();         
                        
            return $this->arrObjNewsTeaser;
        } 
        catch (Exception $e) 
        {
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    public function getArrObjNewsTeaser() { return $this->arrObjNewsTeaser; }
    
    
    /**
     * NewsTeaser to String
     * @returns String
     */
    public function toString($args = array()) 
    {

        $op = "<section class='hyb_news_conainer'>";
        
        foreach ($this->arrObjNewsTeaser as &$objNewsTeaser) {
            
            $title = $objNewsTeaser->getTitle();
            $url = $objNewsTeaser->getUrl();
            $text = $objNewsTeaser->gettext();
            $objDate = $objNewsTeaser->getObjDate();
            $strDate = $objDate->format('d.m.Y');
            
            $op .= '<article>';
            
            $op .= '<h4>';
            $op .= "<a href='" . htmlspecialchars($url) 
                    . "' title='". htmlspecialchars($title) 
                    ."' target='_blank' rel='nofollow'>";
            $op .= htmlspecialchars($title) . '</h4>';
            $op .= '</a>';
            $op .= '<p><span class="hyb_news_date">('. htmlspecialchars($strDate) .')</span> ' 
                    . htmlspecialchars($text) . '</p>';
            
        }
        
        $op .= "</section>";
        return $op;
    }

}

?>
