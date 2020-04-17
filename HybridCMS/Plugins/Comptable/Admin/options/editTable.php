<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comptable', '/HybridCMS/Plugins/Comptable/Admin/js/Comptable.js', 4, false, true, 'footer', false);
    $hcms->registerJS('addComptable', '/HybridCMS/Plugins/Comptable/Admin/js/editTable.js', 5, false, true, 'footer', false);

    /* CSS
      ================================================== */
    $hcms->registerCSS('Comptable', '/HybridCMS/Plugins/Comptable/Admin/css/comptable.css');

    /* load Plugins
      ================================================== */
    $comptableName = '';
    $tableName = '';
    if (isset($_GET['comptable'], $_GET['table'])) {

        $comptableName = trim($_GET['comptable']);
        $tableName = trim($_GET['table']);

        $objComptable = new \HybridCMS\Plugins\Comptable\Comptable($comptableName);
        $objComptable->fetchComptable();

        $objTable = $objComptable->getObjTable($tableName);
    }
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
        <article class="col-xs-12 col-md-12">
            <div class="row">
                <div class="col-xs-12 col-md-7">
                    <h1>Manage Table 
                        <span class="text-info">
                            <?php
                            echo htmlspecialchars($tableName)
                            . '</span> of Comptable <span class="text-info">'
                            . '<a href="/admin/plugins/index.php?name=comptable&action=editComptable&comptable='
                            . htmlspecialchars($comptableName) . '" title="Edit " ' . htmlspecialchars($comptableName) . '">'
                            . htmlspecialchars($comptableName) . '</a>';
                            ?>
                        </span>
                    </h1>
                    <hr class="add-top-30"/>  

                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        Add a Dataset
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">


                                    <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">
                                        <input type="hidden" name="comptableName" value="<?php echo htmlentities($comptableName); ?>" />
                                        <input type="hidden" name="tableName" value="<?php echo htmlentities($tableName); ?>" />
                                        <label>Key:</label>
                                        <input class="form-control add-bottom-20" type="text" placeholder="Key eingeben" name="key" />
                                        <div class="errorMsg"></div>

                                        <label>Value:</label>
                                        <input class="form-control add-bottom-20" type="text" placeholder="Value eingeben" name="value" />
                                        <div class="errorMsg"></div>                

                                        <label>Table-Note:</label>
                                        <textarea class="form-control" rows="3" name="datasetNote" placeholder="Notiz eingeben"></textarea>

                                        <label class="checkbox">
                                            <input type="checkbox" name="isPrivate"> Is private
                                        </label>



                                        <p class="userResponse"></p>
                                        <input class="btn btn-success float_right" type="submit" value="Insert Dataset" name="insertDataset" id="btn_insertDataset">
                                    </form>

                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        Bulk-Insert Datasets
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">


                                    <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">
                                        <input type="hidden" name="comptableName" value="<?php echo htmlentities($comptableName); ?>" />
                                        <input type="hidden" name="tableName" value="<?php echo htmlentities($tableName); ?>" />
                                        <label>One Key;Value per Line</label>
                                        <textarea class="form-control" rows="3" name="datasets" placeholder="Key;Value"></textarea>

                                        <p class="userResponse"></p>
                                        <input class="btn btn-success float_right" type="submit" value="Bulk insert Datasets" name="bulkInsertDatasets" id="btn_bulkInsertDatasets">
                                    </form>

                                </div>
                            </div>
                        </div>                        
                    </div>                                    
                </div>
            </div>
            <hr class="add-top-30"/>            


            <h2>Edit Datasets</h2>
            <?php
            if (!isset($comptableName) || empty($comptableName)) {
                echo "<p>No Comptable selected!</p>";
            }

            if (!isset($tableName) || empty($tableName)) {
                echo "<p>No Table selected!</p>";
            }
            ?>

            <?php
            if (isset($objTable) && !empty($objTable)) {

                $op = '';
                $op .= '<table id="hyb_tableOfDatasets" class="table table-striped">';
                $op .= '<tr>';
                $op .= '<th>Key</th>';
                $op .= '<th>Value</th>';
                $op .= '<th>Private</th>';
                $op .= '<th>Note</th>';
                $op .= '<th>Created</th>';                
                $op .= '<th>Last changed</th>';                
                $op .= '<th><!-- delete --></th>';
                $op .= '</tr>';

                //get Datasets of the current table
                $arrObjDatasets = $objTable->getArrObjDatasets();

                foreach ($arrObjDatasets as &$objDataset) {

                    $tableName = $objTable->getTableName();

                    //begin new Dataset
                    $op .= '<tr>';

                    //Key
                    $op .= '<td class="hyb_comptable_ds_key">';
                    $op .= htmlspecialchars($objDataset->getKey());
                    $op .= '</td>';

                    //value
                    $op .= '<td class="hyb_comptable_ds_value hyb_editText">';
                    $op .= '<span class="hyb_edit" '
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName) . '" '
                            . 'datasetKey="' . htmlentities($objDataset->getKey())
                            . '">';
                    $op .= htmlspecialchars($objDataset->getValue());
                    $op .= '</span>';
                    $op .= '</td>';

                    //Private
                    $op .= '<td class="hyb_comptable_ds_isPrivate">';
                    if ($objDataset->getPrivate()) {
                        $op .= '<i class="hyb_edit fa fa-check-square" '
                                . 'tableName="' . htmlentities($tableName) . '" '
                                . 'comptableName="' . htmlentities($comptableName) . '" '
                                . 'datasetKey="' . htmlentities($objDataset->getKey())
                                . '">'
                                . '</i>';
                    } else {
                        $op .= '<i class="hyb_edit fa fa-square-o" '
                                . 'tableName="' . htmlentities($tableName) . '" '
                                . 'comptableName="' . htmlentities($comptableName) . '" '
                                . 'datasetKey="' . htmlentities($objDataset->getKey())
                                . '">'
                                . '</i>';
                    }
                    $op .= '</td>';

                    //note
                    $op .= '<td class="hyb_comptable_ds_note hyb_editText">';
                    $op .= '<span class="hyb_edit"'
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName) . '" '
                            . 'datasetKey="' . htmlentities($objDataset->getKey())
                            . '">';
                    $op .= htmlspecialchars($objDataset->getNote());
                    $op .= '</span>';
                    $op .= '</td>';
                    
                    //created
                    $op .= '<td class="hyb_comptable_ds_created">';
                    $op .= '<span>';
                    $op .= date('d.m.Y', $objDataset->getCreated());
                    $op .= '</span>';
                    $op .= '</td>';                      
                    
                    //lastChanged
                    $op .= '<td class="hyb_comptable_ds_created">';
                    $op .= '<span>';
                    $lastCanged = $objDataset->getLastChanged();
                    if(!empty($lastCanged)) {
                        $op .= date('d.m.Y', $objDataset->getLastChanged());
                    } else {
                        $op .= '-';
                    }
                    $op .= '</span>';
                    $op .= '</td>';                    

                    //delete table from db
                    $op .= '<td>';
                    $op .= '<i class="hyb_edit fa fa-trash-o btn_delete_dataset" '
                            . 'tableName="' . htmlentities($tableName) . '" '
                            . 'comptableName="' . htmlentities($comptableName) . '" '
                            . 'datasetKey="' . htmlentities($objDataset->getKey())
                            . '"></i>';
                    $op .= '</td>';

                    $op .= '</tr>';
                }//end foreach

                $op .= '</table>';
                echo $op;
            }//end if
            ?>

        </article>
    </div><!-- end .row -->
</div><!-- container -->