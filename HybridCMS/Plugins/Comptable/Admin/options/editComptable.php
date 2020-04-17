<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comptable', '/HybridCMS/Plugins/Comptable/Admin/js/Comptable.js', 4, false, true, 'footer', false);
    $hcms->registerJS('addComptable', '/HybridCMS/Plugins/Comptable/Admin/js/editComptable.js', 5, false, true, 'footer', false);

    /* CSS
      ================================================== */
    $hcms->registerCSS('Comptable', '/HybridCMS/Plugins/Comptable/Admin/css/comptable.css');

    /* load Plugins
      ================================================== */
    $comptableName = '';
    if (isset($_GET['comptable'])) {

        $comptableName = trim($_GET['comptable']);
        
        $objComptable = new \HybridCMS\Plugins\Comptable\Comptable(
                $comptableName);                       
        $arrObjTables = $objComptable->fetchComptable();
    }
} 
catch (\Exception $e) 
{
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
            <div class="row">
                <div class="col-xs-12 col-md-7">
                    <h1>Manage Comptable <span class="text-info"><?php echo htmlspecialchars($comptableName); ?></span></h1>
                    <hr class="add-top-30"/>  

                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        Add a Table
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">
                                        <input type="hidden" name="comptableName" value="<?php echo htmlentities($comptableName); ?>" />
                                        <label>Table Name:</label>
                                        <input class="form-control add-bottom-20" type="text" placeholder="TableName eingeben" name="tableName" />
                                        <div class="errorMsg"></div>


                                        <label>Url:</label>
                                        <input class="form-control add-bottom-20" type="text" placeholder="Url eingeben" name="tableUrl" />    
                                        <div class="errorMsg"></div>

                                        <label>Image-Url:</label>
                                        <input class="form-control add-bottom-20" type="text" placeholder="Image Url eingeben" name="tableImgUrl" /> 
                                        <div class="errorMsg"></div>

                                        <label>Table-Note:</label>
                                        <textarea class="form-control" rows="3" name="tableNote" placeholder="Notiz eingeben"></textarea>

                                        <label class="checkbox">
                                            <input type="checkbox" name="isActive"> Is Active
                                        </label>
                                        
                                        <label class="checkbox">
                                            <input type="checkbox" name="isFavorit"> Is Favorit
                                        </label>

                                        <p class="userResponse"></p>
                                        <input class="btn btn-success float_right" type="submit" value="Insert Table" name="insert" id="btn_insertTable">
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>            
            <hr class="add-top-30"/>            


            <h2>Edit a Table</h2>
            <?php
            if (false === isset($comptableName) 
                || 
                false === empty($comptableName)) 
            {
                echo "<p>No Comptable selected!</p>";
            }
            ?>

            <?php

                $op = '';
                $op .= '<table id="hyb_tableOfTables" class="table table-striped">';
                $op .= '<tr>';
                $op .= '<th>Tablename</th>';
                $op .= '<th>Url</th>';
                $op .= '<th>Img-Url</th>';
                $op .= '<th>Active</th>';
                $op .= '<th>Favorit</th>';
                $op .= '<th>Note</th>';
                $op .= '<th>Created</th>';                
                $op .= '<th><!-- delete --></th>';
                $op .= '</tr>';
                
            if (true === isset($arrObjTables) 
                && 
                false === empty($arrObjTables)) 
            {
                foreach ($arrObjTables as &$objTable) 
                {
                    $tableName = $objTable->getTableName();
                    $url = $objTable->getUrl();
                    $imgUrl = $objTable->getImgUrl();

                    $op .= '<tr>';

                    //table name
                    $op .= '<td>';
                    $op .= '<a href="/admin/plugins/index.php?'
                            . 'name=comptable&action=editTable&comptable='
                            . htmlentities($comptableName) 
                            . "&table=" . htmlentities($tableName)
                            . '" title="Edit ' . htmlentities($tableName) . '">';
                    $op .= htmlspecialchars($tableName);
                    $op .= '</a>';
                    $op .= '</td>';

                    //url
                    $op .= '<td class="hyb_comptable_tbl_url hyb_editText">';
                    $op .= '<span class="hyb_edit"'
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName)
                            . '">';                    
                    $op .= htmlspecialchars($url);
                    $op .= '</span>';                    
                    $op .= '</td>';

                    //image-url
                    $op .= '<td class="hyb_comptable_tbl_imgUrl hyb_editText">';
                    $op .= '<span class="hyb_edit"'
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName)
                            . '">';                    
                    $op .= htmlspecialchars($imgUrl);
                    $op .= '</span>';                    
                    $op .= '</td>';
                    
                    //IsActive
                    $op .= '<td class="hyb_comptable_tbl_isActive">';
                    if (true === $objTable->getIsActive()) 
                    {
                        $op .= '<i class="hyb_edit fa fa-check-square" '
                                . 'tableName="' . htmlentities($tableName) . '" '
                                . 'comptableName="' . htmlentities($comptableName)
                                . '">'
                                . '</i>';
                    } 
                    else 
                    {
                        $op .= '<i class="hyb_edit fa fa-square-o" '
                                . 'tableName="' . htmlentities($tableName) . '" '
                                . 'comptableName="' . htmlentities($comptableName)
                                . '">'
                                . '</i>';
                    }
                    $op .= '</td>';                    

                    //IsFavorit
                    $op .= '<td class="hyb_comptable_tbl_isFavorit">';
                    if (true === $objTable->getIsFavorit()) {
                        $op .= '<i class="hyb_edit fa fa-check-square" '
                                . 'tableName="' . htmlentities($tableName) . '" '
                                . 'comptableName="' . htmlentities($comptableName)
                                . '">'
                                . '</i>';
                    } else {
                        $op .= '<i class="hyb_edit fa fa-square-o" '
                                . 'tableName="' . htmlentities($tableName) . '" '
                                . 'comptableName="' . htmlentities($comptableName)
                                . '">'
                                . '</i>';
                    }
                    $op .= '</td>';

                    //note
                    $op .= '<td class="hyb_comptable_tbl_note hyb_editText">';
                    $op .= '<span class="hyb_edit"'
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName)
                            . '">';
                    $op .= htmlspecialchars($objTable->getNote());
                    $op .= '</span>';
                    $op .= '</td>';

                    //created
                    $op .= '<td class="hyb_comptable_tbl_created">';
                    $op .= '<span>';
                    $op .= date('d.m.Y', $objTable->getCreated());
                    $op .= '</span>';
                    $op .= '</td>';                      

                    //delete table from db
                    $op .= '<td>';
                    $op .= '<i class="fa fa-trash-o btn_delete_table" '
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName)
                            . '"></i>';
                    $op .= '</td>';

                    $op .= '</tr>';
                }
            }
            
                $op .= '</table>';

                echo $op;            
            ?>

        </article>
    </div><!-- end .row -->
</div><!-- container -->