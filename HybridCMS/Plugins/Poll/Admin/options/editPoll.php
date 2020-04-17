<?php
if (!defined('ROOTDOC')) {
    die();
}

try {

    /* Javascripts
      ================================================== */
    $hcms->registerJS('Comptable', '/HybridCMS/Plugins/Poll/Admin/js/Poll.js', 4, false, true, 'footer', false);
    $hcms->registerJS('addComptable', '/HybridCMS/Plugins/Poll/Admin/js/initEditPoll.js', 5, false, true, 'footer', false);

    /* CSS
      ================================================== */
    $hcms->registerCSS('Comptable', '/HybridCMS/Plugins/Poll/Admin/css/poll.css');

    /* load Plugins
      ================================================== */
    $objPoll = new \HybridCMS\Plugins\Poll\Poll($_GET['pollName']);
    $objPoll->setPollId($_GET['pollId']);
    $nPollQuestions = $objPoll->fetchPollQuestions();
    $arrObjPollQuestions = $objPoll->getArrObjPollQuestions();
    $pollName = $objPoll->getPollName();
    $pollId = $objPoll->getPollId();
    
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
            <h1>Edit a Poll</h1>
            <hr class="add-top-30"/> 

            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                Add a Question
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse">
                        <div class="panel-body">

                            <form class="clearfix" method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']); ?>">                
                                <input type="hidden" value="<?php echo htmlentities($pollName) ?>" name="pollName"/>
                                <input type="hidden" value="<?php echo htmlentities($pollId) ?>" name="pollId"/>

                                <label>Poll Question:</label>
                                <input class="form-control add-bottom-20" type="text" placeholder="Question eingeben" name="pollQuestion" />

                                <div class="row add-bottom-20">
                                    <div class="col-md-6">
                                        <label>Question Type:</label>
                                        <select class="form-control" name="questionType">
                                            <option value="radio">radio</option>
                                            <option value="checkbox">checkbox</option>
                                            <option value="customText">customText</option>                    
                                        </select>   
                                    </div>
                                    <div class="col-md-6">
                                        <label>Question Priority:</label>
                                        <select class="form-control" name="questionPriority">
                                            <?php
                                            for ($i = 1; $i < 10; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                            ?>                                                         
                                        </select>   
                                    </div>                                                                 
                                </div>
                                <div class="add-top-10">
                                    <label class="checkbox">
                                        <input type="checkbox" checked="checked" name="questionActive"> Question Active
                                    </label>                                              
                                </div>     

                                <input class="btn btn-success float_right" type="submit" value="Insert PollQuestion" name="insertPollQuestion">
                            </form>


                        </div>
                    </div>
                </div>                        
            </div>                                        

            <hr class="add-top-30"/>
            <h2>My Polls</h2>
            <table id="hyb_tableOfPollQuestions" class="table table-striped">
                <tr>
                    <th>Question</th>
                    <th>Priority</th>
                    <th>Type</th> 
                    <th>Active</th>  
                    <th>Views</th>
                    <th>Selected</th>  
                    <th>Ratio</th>
                    <th>TimeCreated</th>                    
                    <th><!-- delete --></th>
                </tr>
                <?php
                if ($nPollQuestions > 0) {

                    $op = '';

                    foreach ($arrObjPollQuestions as &$objPollQuestion) {

                        $questionId = $objPollQuestion->getQuestionId();
                        $question = $objPollQuestion->getQuestion();
                        $timeCreated = $objPollQuestion->getTimeCreated();
                        $views = $objPollQuestion->getViews();
                        $selected = $objPollQuestion->getSelected();
                        $selectedRatio = 0;
                        if($views > 0) { $selectedRatio = round($selected / $views, 4); }
                        $priority = $objPollQuestion->getPriority();
                        $type = $objPollQuestion->getType();
                        $active = $objPollQuestion->getActive();


                        $op .= '<tr>';

                        //question
                        $op .= '<td class="hyb_pollQuestion_question hyb_editText">';
                        $op .= '<span class="hyb_edit" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                . 'questionId="' . htmlentities($questionId) . '" '
                                . '">';
                        $op .= htmlspecialchars($question);
                        $op .= '</span>';
                        $op .= '</td>';

                        //priority
                        $op .= '<td class="hyb_pollQuestion_priority hyb_editSelectBox">';
                        $op .= '<span class="hyb_edit" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                . 'questionId="' . htmlentities($questionId) . '" '
                                . '">';
                        $op .= htmlspecialchars($priority);
                        $op .= '</span>';
                        $op .= '</td>';

                        //type
                        $op .= '<td class="hyb_pollQuestion_type hyb_editSelectBox">';
                        $op .= '<span class="hyb_edit" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                . 'questionId="' . htmlentities($questionId) . '" '
                                . '">';
                        $op .= htmlspecialchars($type);
                        $op .= '</span>';
                        $op .= '</td>';

                        //Private
                        $op .= '<td class="hyb_pollQuestion_active">';
                        if ($active === true) {
                            $op .= '<i class="hyb_edit fa fa-check-square" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                    . 'questionId="' . htmlentities($questionId) . '" '
                                    . '">'
                                    . '</i>';
                        } else {
                            $op .= '<i class="hyb_edit fa fa-square-o" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                                    . 'questionId="' . htmlentities($questionId) . '" '
                                    . '">'
                                    . '</i>';
                        }
                        $op .= '</td>';

                        //views
                        $op .= '<td class="hyb_pollQuestion_views">';
                        $op .= '<span>';
                        $op .= htmlspecialchars($views);
                        $op .= '</span>';
                        $op .= '</td>';

                        //selected
                        $op .= '<td class="hyb_pollQuestion_selected">';
                        $op .= '<span>';
                        $op .= htmlspecialchars($selected);
                        $op .= '</span>';
                        $op .= '</td>';

                        //selectio-ratio
                        $op .= '<td class="hyb_pollQuestion_ratio">';
                        $op .= '<span>';
                        $op .= $selectedRatio;
                        $op .= '</span>';
                        $op .= '</td>';

                        //TimeCreated
                        $op .= '<td class="hyb_pollQuestion_timeCreated">';
                        $op .= '<span>';
                        $op .= date('d.m.Y', $timeCreated);
                        $op .= '</span>';
                        $op .= '</td>';

                        //delete table from db
                        $op .= '<td>';
                        $op .= '<i class="fa fa-trash-o btn_delete_pollQuestion" '
                                . 'pollName="' . htmlentities($pollName) . '" '
                                . 'pollId="' . htmlentities($pollId) . '" '
                            . 'questionId="' . htmlentities($questionId)
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