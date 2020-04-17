<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comptable', '/HybridCMS/Plugins/News/Admin/js/f.js', 4, false, true, 'footer', false);    
    
    /* CSS
      ================================================== */
    $hcms->registerCSS('Comptable', '/HybridCMS/Plugins/News/Admin/css/f.css');    

    /* load Plugins
      ================================================== */
    $objNews = new \HybridCMS\Plugins\News\News();
    $objNews->fetchNews(99999);
    $arrObjNewsTeaser = $objNews->getArrObjNewsTeaser();
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(
            LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');
?>


<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row" id="mainContent" itemprop="mainContentOfPage">
        <article class="col-xs-12 col-md-12">
            <h1>Manage News</h1>
            <hr class="add-top-30"/> 

            <h2>Add a News</h2>

            <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">
                <label>News Title:</label>
                <input class="form-control add-bottom-20" type="text" placeholder="News Title eingeben" name="newsTitle" />
                <div class="errorMsg"></div>
                
                <label>News URL:</label>
                <input class="form-control add-bottom-20" type="text" placeholder="News URL eingeben" name="newsUrl" />
                <div class="errorMsg"></div>    
                
                <label>News Teaser Text:</label>
                <textarea class="form-control add-bottom-20" type="text" placeholder="News Teaser Text eingeben" name="newsText"></textarea>
                <div class="errorMsg"></div> 
                
                <label>News Date (mm-dd-YYYY):</label>
                <input class="form-control add-bottom-20" type="text" placeholder="News Date eingeben" name="newsDate" />
                <div class="errorMsg"></div>                 

                <p class="userResponse"></p>
                <input class="btn btn-success float_right" type="submit" value="Insert News" name="insert">
            </form>

            <hr class="add-top-30"/>
            <h2>My News</h2>
            <table id="hyb_tableOfNewsTeaser" class="table table-striped">
                <tr>
                    <th>Title</th>
                    <th>Text</th>
                    <th>Link</th>
                    <th>Date</th>
                    <th><!-- delete --></th>
                </tr>
                <?php
                    $op = '';

                    foreach ($arrObjNewsTeaser as &$objNewsTeaser) {

                        $newsId = $objNewsTeaser->getNewsId();
                        $title = $objNewsTeaser->getTitle();
                        $text = $objNewsTeaser->getText();
                        $url = $objNewsTeaser->getUrl();
                        $objDate = $objNewsTeaser->getObjDate();

                        $op .= '<tr>';

                        //title
                        $op .= '<td>';                    
                        $op .= htmlspecialchars($title);
                        $op .= '</td>';
                        
                        //text
                        $op .= '<td>';                    
                        $op .= htmlspecialchars($text);
                        $op .= '</td>';   
                        
                        //url
                        $op .= '<td>';                    
                        $op .= htmlspecialchars($url);
                        $op .= '</td>';    
                        
                        //date
                        $op .= '<td>';                    
                        $op .= htmlspecialchars($objDate->format('Y-m-d'));
                        $op .= '</td>';                         

                        //delete table from db
                        $op .= '<td>';
                        $op .= '<i class="fa fa-trash-o btn_delete_newsTeaser" '
                                . 'data-newsId="' . htmlentities($newsId)
                                . '"></i>';
                        $op .= '</td>';

                        $op .= '</tr>';
                    }

                    echo $op;
                ?>
            </table>
        </article>
    </div><!-- end .row -->
</div><!-- container -->