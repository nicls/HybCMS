<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comptable', '/HybridCMS/Plugins/Poll/Admin/js/Poll.js', 4, false, true, 'footer', false);
    $hcms->registerJS('addComptable', '/HybridCMS/Plugins/Poll/Admin/js/initManagePolls.js', 5, false, true, 'footer', false);

    /* CSS
      ================================================== */
    $hcms->registerCSS('Comptable', '/HybridCMS/Plugins/Poll/Admin/css/poll.css');

    /* load Plugins
      ================================================== */
    $objPolls = new \HybridCMS\Plugins\Poll\Polls();
    $nPolls = $objPolls->fetchPolls();
    $arrObjPolls = $objPolls->getArrObjPolls();
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
            <h1>Manage Polls</h1>
            <hr class="add-top-30"/> 

            <h2>Add a Poll</h2>

            <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">
                <label>Poll Name:</label>
                <input class="form-control add-bottom-20" type="text" placeholder="PollName eingeben" name="pollName" />
                <input class="btn btn-success float_right" type="submit" value="Insert Poll" name="insertPoll">
            </form>


            <hr class="add-top-30"/>
            <h2>My Polls</h2>
            <table id="hyb_tableOfPolls" class="table table-striped">
                <tr>
                    <th>Poll Name</th>
                    <th>Info</th>
                    <th>Poll Id</th>
                    <th>TimeCreated</th>
                    <th><!-- edit --></th>
                    <th><!-- delete --></th>
                </tr>
                <?php
                if ($nPolls > 0) {

                    $op = '';

                    foreach ($arrObjPolls as &$objPoll) {

                        $pollId = $objPoll->getPollId();
                        $pollName = $objPoll->getPollName();
                        $pollInfo = $objPoll->getInfo();
                        $timeCreated = $objPoll->getTimeCreated();


                        $op .= '<tr>';

                        //poll-name
                        $op .= '<td class="hyb_poll_pollName hyb_editText">';
                        $op .= '<span class="hyb_edit" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                . '">';
                        $op .= htmlspecialchars($pollName);
                        $op .= '</span>';
                        $op .= '</td>';
                        
                        //poll-info
                        $op .= '<td class="hyb_poll_pollInfo hyb_editText">';
                        $op .= '<span class="hyb_edit" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                . '">';
                        $op .= htmlspecialchars($pollInfo);
                        $op .= '</span>';
                        $op .= '</td>';                        

                        //pollId
                        $op .= '<td>';
                        $op .= htmlspecialchars($pollId);
                        $op .= '</td>';

                        //time Created
                        $op .= '<td>';
                        $op .= date('d.m.Y', $timeCreated);
                        $op .= '</td>';

                        //edit poll
                        $op .= '<td>';
                        $op .= '<a href="/admin/plugins/index.php?name=poll&action=editPoll&pollId='
                                . htmlentities($pollId)
                                . '&pollName=' . htmlentities($pollName) . '">';
                        $op .= '<i class="fa fa-cog"></i>';
                        $op .= '</a>';
                        $op .= '</td>';

                        //delete table from db
                        $op .= '<td>';
                        $op .= '<i class="fa fa-trash-o btn_delete_poll" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                . 'pollName="' . htmlentities($pollName)
                                . '"></i>';
                        $op .= '</td>';

                        $op .= '</tr>';
                    }

                    echo $op;
                }
                ?>
            </table>
        </article>

        <!-- sidebar -->
        <div id="sidebar" class="col-xs-6 col-md-5">
            <h3>Poll Setup</h3>
            <hr class="add-top-30">
            <div id="container_hyb_poll_DontTrackMe">
                <ul>
                    <li id="hyb_poll_dontTrackMe"><i class="fa fa-circle-o"></i> Don't track my visits!</li>
                </ul>
            </div>
        </div>

    </div><!-- end .row -->       
</div><!-- container -->
