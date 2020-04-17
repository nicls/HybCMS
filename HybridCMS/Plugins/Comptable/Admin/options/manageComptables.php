<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comptable', '/HybridCMS/Plugins/Comptable/Admin/js/Comptable.js', 4, false, true, 'footer', false);
    $hcms->registerJS('addComptable', '/HybridCMS/Plugins/Comptable/Admin/js/manageComptable.js', 5, false, true, 'footer', false);
    
    /* CSS
      ================================================== */
    $hcms->registerCSS('Comptable', '/HybridCMS/Plugins/Comptable/Admin/css/comptable.css');    

    /* load Plugins
      ================================================== */
    $objComptables = new \HybridCMS\Plugins\Comptable\Comptables();
    $nComptables = $objComptables->fetchComptables();
    $arrObjComptables = $objComptables->getArrObjComptables();
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
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
        <article class="col-xs-12 col-md-7">
            <h1>Manage Comptables</h1>
            <hr class="add-top-30"/> 

            <h2>Add a Comptable</h2>

            <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">
                <label>Comptable Name:</label>
                <input class="form-control add-bottom-20" type="text" placeholder="ComptableName eingeben" name="comptableName" />
                <div class="errorMsg"></div>

                <p class="userResponse"></p>
                <input class="btn btn-success float_right" type="submit" value="Insert Comptable" name="insert">
            </form>

            <hr class="add-top-30"/>
            <h2>My Comptables</h2>
            <table id="hyb_tableOfComptables" class="table table-striped">
                <tr>
                    <th>Comptable Name</th>
                    <th><!-- delete --></th>
                </tr>
                <?php
                if ($nComptables > 0) {

                    $op = '';

                    foreach ($arrObjComptables as &$objComptable) {

                        $comptableName = $objComptable->getComptableName();

                        $op .= '<tr>';

                        //comptable-name
                        $op .= '<td>';
                        $op .= '<a href="/admin/plugins/index.php?name=comptable&action=editComptable&comptable='
                                . htmlentities($comptableName)
                                . '" title="Edit ' . htmlentities($comptableName) . '">';
                        $op .= htmlspecialchars($comptableName);
                        $op .= '</a>';
                        $op .= '</td>';

                        //delete table from db
                        $op .= '<td>';
                        $op .= '<i class="fa fa-trash-o btn_delete_comptable" '
                                . 'comptableName="' . htmlentities($comptableName)
                                . '"></i>';
                        $op .= '</td>';

                        $op .= '</tr>';
                    }

                    echo $op;
                }
                ?>
            </table>
        </article>
    </div><!-- end .row -->
</div><!-- container -->