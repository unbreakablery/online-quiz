<?php require '../inc/config_student.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>
<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>
<?php
    require '../inc/connect_db.php';
    if (isset($_SESSION['user_id']) && isset($_SESSION['quiz_id']) && isset($_POST['exam-id']) && $_POST['exam-id'] != 0) {
        //load exam
        $exam_id = $_POST['exam-id'];
        $exam = getExam($exam_id);
                
        $quiz_code      = $_SESSION['quiz_code'];
        $cur_que_idx    = $exam['cur_que_idx'];
        $cnt_que        = $exam['cnt_que'];
        $que_ids        = explode("|", $exam['que_ids']);
        $cur_que_id     = $que_ids[$cur_que_idx];
        $state          = $exam['state'];
        $spent_time     = $exam['spent_time'];

        if ($state == 1) {
            //End exam
            header('Location: result_t.php');
            exit;
        }

        $cur_que = getQuestion($cur_que_id);

        $exam_detail = getExamDetail($exam_id, $cur_que_id);
    
        if (existExamDetail($exam_id, $cur_que_id) && $exam_detail['answers'] != "" && strlen($cur_que['cor_ans']) == strlen($exam_detail['answers'])) {
            $mode = "load";
        } else {
            $mode = "new";
        }
        
        //set session
        $_SESSION['user_id']        = $exam['user_id'];
        $_SESSION['quiz_id']        = $exam['quiz_id'];
        $_SESSION['quiz_code']      = $quiz_code;
        $_SESSION['exam_id']        = $exam_id; 

    } elseif (isset($_SESSION['user_id']) && isset($_SESSION['quiz_id']) && isset($_SESSION['exam_id'])) {
        //in progress exam
        $exam_id = $_SESSION['exam_id'];
        $exam = getExam($exam_id);
        
        $quiz_code      = $_SESSION['quiz_code'];
        $cur_que_idx    = $exam['cur_que_idx'];
        $cnt_que        = $exam['cnt_que'];
        $que_ids        = explode("|", $exam['que_ids']);
        $cur_que_id     = $que_ids[$cur_que_idx];
        $state          = $exam['state'];
        $spent_time     = $exam['spent_time'];

        if ($state == 1) {
            //End exam
            header('Location: result_t.php');
            exit;
        }

        $cur_que = getQuestion($cur_que_id);

        $exam_detail = getExamDetail($exam_id, $cur_que_id);
    
        if (existExamDetail($exam_id, $cur_que_id) && $exam_detail['answers'] != "" && strlen($cur_que['cor_ans']) == strlen($exam_detail['answers'])) {
            $mode = "load";
        } else {
            $mode = "new";
        }
        
    } else { 
        //create new exam
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['quiz_id'])) {
            header('Location: index.php');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $quiz_id = $_SESSION['quiz_id'];
        $quiz_code = $_SESSION['quiz_code'];

        //delete old exam in case new exam from welcome
        if (isset($_SESSION['user_id']) && isset($_SESSION['quiz_id']) && isset($_POST['exam-id']) && $_POST['exam-id'] == 0) {
            $exam = getExam2($user_id, $quiz_id);
            removeExam($user_id, $quiz_id);
            if ($exam) {
                removeExamDetail($exam['id']);
            }
        }

        //Get quiz
        $quiz = getQuiz($quiz_id);
        $cnt_que = $quiz['cnt_que'];
        $cur_que_idx = 0;

        //Get ids for questions
        $que_ids = getQueIDs($quiz_id);
        $cur_que_id = $que_ids[$cur_que_idx];
        
        //Get first question
        $cur_que = getQuestion($cur_que_id);

        $spent_time = $quiz['limit_time'];
        $state = 0;
        
        //save new exam
        $query = "INSERT INTO `exams` 
                            (
                                `user_id`,
                                `quiz_id`,
                                `cnt_que`,
                                `que_ids`,
                                `cur_que_idx`,
                                `score`,
                                `total_score`,
                                `spent_time`,
                                `state`,
                                `start_date`
                            ) VALUES ("
                                . "'" . $user_id . "',"
                                . $quiz_id . ","
                                . $cnt_que . ","
                                . "'" . implode("|", $que_ids) . "',"
                                . $cur_que_idx . ","
                                . "0,"
                                . "0,"
                                . $spent_time . ","
                                . $state . ","
                                . "NOW()"
                                . ")";
        mysqli_query($connect, $query);
        $exam_id = mysqli_insert_id($connect);

        $mode = "new";

        //Initialize session
        $_SESSION['user_id']        = $user_id;
        $_SESSION['quiz_id']        = $quiz_id;
        $_SESSION['quiz_code']      = $quiz_code;
        $_SESSION['exam_id']        = $exam_id;
    }
    $questions = getExamQuestions($exam_id);
    $f_que_ids = getExamQueFlagged($exam_id);
    $flag = in_array($cur_que_id, $f_que_ids) ? '<i class="fa fa-flag"></i>' : '<i class="fa fa-flag-o"></i>';
?>

<!-- Page Content -->
<div class="content">
    <div class="col-lg-3 col-md-3"></div>
    <div class="col-lg-6 col-md-6">
        <div class="block block-themed" id="quiz-block">
            <div class="block-header bg-quiz-header push-5-quiz">
                <div class="navbar navbar-inverse bg-quiz-header">
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown full-width">
                                <a href="#" class="navbar-brand dropdown-toggle" data-toggle="dropdown">
                                    <span class="nav-title"><?php echo "Question " . ($cur_que_idx + 1) . " of " . $cnt_que; ?></span> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#">
                                            <div class="row">
                                            <div class="col-md-1 col-xs-1 text-center"></div>
                                                <div class="col-md-1 col-xs-1 text-right">
                                                    #
                                                </div>
                                                <div class="col-md-9 col-xs-9">
                                                    Question
                                                </div>
                                                <div class="col-md-1 hidden-xs text-right">
                                                    Points
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <?php foreach ($questions as $idx => $q) { ?>
                                        <li class="<?php if (in_array($q['id'], $f_que_ids)) { echo "alert-warning"; } ?>">
                                            <a href="#" class="question" data-idx="<?php echo $idx; ?>" data-id="<?php echo $q['id']; ?>">
                                                <div class="row">
                                                    <div class="col-md-1 col-xs-1 text-center flag-field">
                                                        <?php if (in_array($q['id'], $f_que_ids)) { ?>
                                                            <i class="fa fa-flag"></i>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-1 col-xs-1 text-right">
                                                        <?php echo ($idx + 1) . ". "; ?>
                                                    </div>
                                                    <div class="col-md-9 col-xs-9">
                                                        <?php echo substr(nl2br($q['que_text']), 0, 100) . "..."; ?>
                                                    </div>
                                                    <div class="col-md-1 hidden-xs text-right">
                                                        <?php echo strlen($q['cor_ans']) * 4; ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li class="dropdown full-width">
                                <a href="#" class="navbar-brand text-right set-flag">
                                    <span class=""><?php echo $flag; ?></span>
                                </a>
                            </li>
                            <li class="dropdown full-width pull-right">
                                <a href="#" class="navbar-brand text-right">
                                    <span class=""><i class="fa fa-clock-o"></i> </span> 
                                    <span class="time text-warning"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-narrow" id="quiz-block-content">
                <h4 id="que-text"><?php echo nl2br($cur_que['que_text']); ?></h4>
                <?php if ($mode == "new") { ?>
                    <?php if ($cur_que['que_type'] == 'SEQ') { ?>
                    <div class="table-responsive push-10-t desktop-view">
                        <table class="table table-striped table-vcenter table-ws-none">
                            <thead>
                                <tr>
                                    <th class="th-quiz-answer d-sm-table-cell keywords" style="width: 1px;"></th>
                                    <th class="th-quiz-answer answers text-center">Answers</th>
                                    <th class="d-sm-table-cell empty"></th>
                                    <th class="th-quiz-option options text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $cnt_ans = 1;
                                $ans_idxs = array();
                                while (isset($cur_que['ans_' . $cnt_ans]) && trim($cur_que['ans_' . $cnt_ans]) != "") {
                                    $ans_idxs[] = $cnt_ans;
                                    $cnt_ans++;
                                }
                                //shuffle($ans_idxs);
                            ?>
                            <?php for ($i = 1; $i < $cnt_ans; $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer" style="width:50%"></td>
                                <td class="d-sm-table-cell empty"></td>
                                <td class="option">
                                    <div class="card" data-ans="<?php echo $ans_idxs[$i - 1]; ?>"><?php echo $cur_que['ans_' . $ans_idxs[$i - 1]]; ?></div>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive push-10-t mobile-view">
                        <table class="table table-striped table-vcenter table-ws-none" id="answerTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="th-quiz-answer d-sm-table-cell keywords" style="width: 1px;"></th>
                                    <th class="th-quiz-answer answers text-center">Answers</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $cnt_ans = 1;
                                $ans_idxs = array();
                                while (trim($cur_que['ans_' . $cnt_ans]) != "") {
                                    $ans_idxs[] = $cnt_ans;
                                    $cnt_ans++;
                                }
                                //shuffle($ans_idxs);
                            ?>
                            <?php for ($i = 1; $i < $cnt_ans; $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer">
                                    <div class="card" data-ans="<?php echo $ans_idxs[$i - 1]; ?>"><?php echo $cur_que['ans_' . $ans_idxs[$i - 1]]; ?></div>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } elseif ($cur_que['que_type'] == 'MR') { ?>
                    <div class="table-responsive push-10-t mr-view">
                        <table class="table table-striped table-vcenter">
                            <?php for($i = 1; $i <= 8; $i++) { ?>
                            <tr>
                                <td>
                                    <div class="checkbox answer">
                                        <label for="answer<?php echo $i; ?>">
                                            <input type="checkbox" id="answer<?php echo $i; ?>" name="answers" value="<?php echo $i; ?>"> 
                                            <?php echo $cur_que['ans_' . $i]; ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <?php } elseif ($cur_que['que_type'] == 'MC') { ?>
                        <table class="table table-striped table-vcenter">
                            <?php for($i = 1; $i <= 8; $i++) { ?>
                            <?php if (isset($cur_que['ans_' . $i]) && trim($cur_que['ans_' . $i]) != "") { ?>
                            <tr>
                                <td>
                                    <div class="radio answer">
                                        <label for="answer<?php echo $i; ?>">
                                            <input type="radio" id="answer<?php echo $i; ?>" name="answers" value="<?php echo $i; ?>"> 
                                            <?php echo ltrim($cur_que['ans_' . $i], '*'); ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </table>
                    <?php } ?>
                <?php } else { ?>
                    <?php if ($cur_que['que_type'] == 'SEQ') { ?>
                    <div class="table-responsive push-10-t desktop-view">
                        <table class="table table-striped table-vcenter table-ws-none">
                            <thead>
                                <tr>
                                    <th class="th-quiz-answer d-sm-table-cell keywords" style="width: 1px;"></th>
                                    <th class="th-quiz-answer answers text-center">Answers</th>
                                    <th class="d-sm-table-cell empty"></th>
                                    <th class="th-quiz-option options text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $answers = $exam_detail['answers'];
                                $cnt_ans = strlen(trim($cur_que['cor_ans']));
                            ?>
                            <?php for ($i = 1; $i <= $cnt_ans; $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer" style="width:50%">
                                    <div class="card" data-ans="<?php echo $answers[$i - 1]; ?>"><?php echo $cur_que['ans_' . $answers[$i - 1]]; ?></div>
                                </td>
                                <td class="d-sm-table-cell empty"></td>
                                <td class="option">
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive push-10-t mobile-view">
                        <table class="table table-striped table-vcenter table-ws-none" id="answerTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="th-quiz-answer d-sm-table-cell keywords" style="width: 1px;"></th>
                                    <th class="th-quiz-answer answers text-center">Answers</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $answers = $exam_detail['answers'];
                                $cnt_ans = strlen(trim($cur_que['cor_ans']));
                            ?>
                            <?php for ($i = 1; $i <= $cnt_ans; $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer">
                                    <div class="card" data-ans="<?php echo $answers[$i - 1]; ?>"><?php echo $cur_que['ans_' . $answers[$i - 1]]; ?></div>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } elseif ($cur_que['que_type'] == 'MR') { ?>
                    <div class="table-responsive push-10-t mr-view">
                        <table class="table table-striped table-vcenter">
                            <?php for($i = 1; $i <= 8; $i++) { ?>
                            <?php
                                    if (strpos($exam_detail['answers'], "$i") !== FALSE) {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                            ?>
                            <tr>
                                <td>
                                    <div class="checkbox answer">
                                        <label for="answer<?php echo $i; ?>">
                                            <input type="checkbox" id="answer<?php echo $i; ?>" name="answers" value="<?php echo $i; ?>" <?php echo $checked; ?>> 
                                            <?php echo $cur_que['ans_' . $i]; ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <?php } elseif ($cur_que['que_type'] == 'MC') { ?>
                    <div class="table-responsive push-10-t mr-view">
                        <table class="table table-striped table-vcenter">
                            <?php for($i = 1; $i <= 8; $i++) { ?>
                            <?php if (isset($cur_que['ans_' . $i]) && trim($cur_que['ans_' . $i]) != "") { ?>
                            <?php
                                    if (strpos($exam_detail['answers'], "$i") !== FALSE) {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                            ?>
                            <tr>
                                <td>
                                    <div class="radio answer">
                                        <label for="answer<?php echo $i; ?>">
                                            <input type="radio" id="answer<?php echo $i; ?>" name="answers" value="<?php echo $i; ?>" <?php echo $checked; ?>> 
                                            <?php echo ltrim($cur_que['ans_' . $i], '*'); ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </table>
                    </div>
                    <?php } ?>
                <?php } ?>
                <div class="row push push-5-t-quiz">
                    <div class="col-lg-6 col-md-6 col-xs-12 text-left push-10-t">
                        <button type="button" class="col-xs-12 btn btn-darker check-answer">
                            Submit All + Finish Mock
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 text-right push-10-t">
                        <button type="button" class="col-md-4 pull-right col-xs-6 btn btn-default next-question" <?php echo ($cur_que_idx == $cnt_que || $cur_que_idx == $cnt_que - 1) ? 'disabled' : ''; ?>>Next <i class="fa fa-caret-right"></i></button>    
                        <button type="button" class="col-md-4 pull-right col-xs-6 btn btn-default prev-question" <?php echo ($cur_que_idx == 0) ? 'disabled' : ''; ?>><i class="fa fa-caret-left"></i> Prev</button>
                    </div>
                    <input type="hidden" name="que-id" id="que-id" value="<?php echo $cur_que['id']; ?>" />
                    <input type="hidden" name="cur-que-idx" id="cur-que-idx" value="<?php echo $cur_que_idx; ?>" />
                    <input type="hidden" name="exam-id" id="exam-id" value="<?php echo $exam_id; ?>" />
                    <input type="hidden" name="que-type" id="que-type" value="<?php echo $cur_que['que_type']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3"></div>
</div>
<!-- END Page Content -->

<!-- Pop Out Modal -->
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Notification</h3>
                </div>
                <div class="block-content">
                    <h3><i class="fa fa-close text-danger"></i> Time is up! Please click "Submit All" button.</h3>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-dark check-answer" type="button" data-dismiss="modal">Submit All</button>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->

<!-- Unanswered Modal -->
<div class="modal fade" id="modal-unanswered" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Warning</h3>
                </div>
                <div class="block-content">
                    <h3><i class="fa fa-warning text-warning"></i> You have not answered the following questions.</h3>
                    <h5 class="push-30-l">Question #: <span id="unanswered-ids" class="text-danger"></span></h5>
                    <h5 class="push-30-l push-10-t">If you submit now, you will not get any marks for these questions.</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-dark force-check-answer" type="button" data-dismiss="modal">Submit All Anyway</button>
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- END Unanswered Modal -->

<?php require '../inc/views/base_footer.php'; ?>

<?php require '../inc/views/template_footer_start.php'; ?>

<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        let isMobile = false; //initiate as false
        // device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
            isMobile = true;
            $(".desktop-view").remove();
            $(function () {
                $("#answerTable").sortable({
                    items: 'tbody tr',
                    cursor: 'pointer',
                    axis: 'y',
                    dropOnEmpty: false,
                    start: function (e, ui) {
                        ui.item.addClass("selected");
                    },
                    stop: function (e, ui) {
                        ui.item.removeClass("selected");
                        $(this).find("tr").each(function (index) {
                            if (index > 0) {
                                $(this).find("td").eq(0).html(index);
                            }
                        });
                    }
                });
            });
        } else {
            $(".mobile-view").remove();
            // Enable drag and drop...
            function dragAndDrop(dragTarget, dropTarget) {
                // Enable draggable events...
                $(dragTarget).draggable({ revert: true });

                // Enable the droppable events...
                $(dropTarget).droppable({
                    drop: function(event, ui) {
                        if ($(this).children().length > 0) return;

                        // Append the dropped item into its drop target...
                        $(this).append(ui.draggable);
                        // Place the drag target in the normal document flow...
                        ui.draggable.css({
                            position: "static",
                            top: "auto",
                            left: "auto"
                        });
                        // jQuery UI requires the draggable element to have position: relative...
                        ui.draggable.css({
                            position: "relative"
                        });
                    }
                });
            }
            // Enable drag and drop in both directions...
            dragAndDrop(".card", ".answer");
            dragAndDrop(".card", ".option");
        }
        
        function getYourTime(seconds) {
            let mins = Math.floor(seconds / 60);
            let secs = seconds % 60;
            return (mins < 10 ? "0" + mins : mins) + ":" + (secs < 10 ? "0" + secs : secs);
        }

        function downCouter() {
            if (time == 0) {
                $("#modal-popout").modal({backdrop: 'static', keyboard: false});
                clearInterval(timeControl);
                return;
            } else {
                time -= 1;
                $("span.time").html(getYourTime(time));
            }
        }

        let time = <?php echo (isset($spent_time) ? $spent_time : 0); ?>;

        let timeControl = setInterval(downCouter, 1000);

        $("span.time").html(getYourTime(time));
        
        if($("input#cur-que-idx").val() == 0 || $("input#cur-que-idx").val() == "0" || $("input#cur-que-idx").val() == "") {
            $("button.prev-question").prop("disabled", true);
        }
        
        $(document).on("click", ".checkbox.answer input[type=checkbox]", function() {
            let this_checked = $(this).prop("checked");
            let cnt_checked = $(".checkbox.answer input[type=checkbox]:checked").length;
            
            if (cnt_checked > 3) {
                if (this_checked) {
                    $(this).prop("checked", false);
                }
            }
        });
        
        $(document).on("click", ".prev-question, .next-question", function() {
            let step = 0;
            if ($(this).hasClass("next-question")) {
                step = 1;
            } else if ($(this).hasClass("prev-question")) {
                step = -1;
            } else {

            }
            let ans_list = "";
            let que_id = $("input#que-id").val();
            let cur_que_idx = $("input#cur-que-idx").val();
            let exam_id = $("input#exam-id").val();
            let que_type = $("input#que-type").val();

            let cnt_que = <?php echo $cnt_que; ?>;

            $("span.nav-title").html("Question " + (parseInt(cur_que_idx) + step + 1) + " of " + cnt_que);
            
            $(".answer").each(function(i) {
                if (que_type == 'SEQ') {
                    let guess = $(this).find("div.card").data("ans");
                    ans_list += (guess == undefined ? '' : guess);
                } else if (que_type == 'MR') {
                    if ($(this).find("input[type=checkbox]").prop("checked")) {
                        let guess = $(this).find("input[type=checkbox]").val();
                        ans_list += (guess == undefined ? '' : guess);
                    }
                }
            });

            if (que_type == 'MC') {
                ans_list = $("input[name=answers]:checked").val();
                ans_list = (ans_list == undefined) ? 0 : ans_list;
            }

            let data = {
                            exam_id:        exam_id,
                            que_id:         que_id,
                            cur_que_idx:    parseInt(cur_que_idx) + step,
                            ans_list:       ans_list,
                            spent_time:     time
                        };
            
            $("div#quiz-block-content").load("/apis/student/get_que.php", data, function(response, status, xhr) {
                if (status == "success") {
                    if (isMobile) {
                        $(".desktop-view").remove();
                        
                        $("#answerTable").sortable({
                            items: 'tbody tr',
                            cursor: 'pointer',
                            axis: 'y',
                            dropOnEmpty: false,
                            start: function (e, ui) {
                                ui.item.addClass("selected");
                            },
                            stop: function (e, ui) {
                                ui.item.removeClass("selected");
                                $(this).find("tr").each(function (index) {
                                    if (index > 0) {
                                        $(this).find("td").eq(0).html(index);
                                    }
                                });
                            }
                        });
                    } else {
                        $(".mobile-view").remove();
                        dragAndDrop(".card", ".answer");
                        dragAndDrop(".card", ".option");
                    }

                    $.ajax({
                        url: "/apis/student/check_flag.php",
                        dataType: "json",
                        type: "post",
                        data: {
                                exam_id: exam_id,
                                que_id:  $("input#que-id").val()
                            },
                        success: function( res ) {
                            if (!res.status) {
                                return;
                            } else {
                                if (res.flag == 1) {
                                    $(".set-flag span i").removeClass("fa-flag-o");
                                    $(".set-flag span i").addClass("fa-flag");
                                } else {
                                    $(".set-flag span i").removeClass("fa-flag");
                                    $(".set-flag span i").addClass("fa-flag-o");
                                }
                            }
                        }
                    });
                }
                if (status == "error") {
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
                }
            });
            
        });
        
        $(document).on("click", ".check-answer", function() {
            let ans_list    = "";
            let que_id      = $("input#que-id").val();
            let cur_que_idx = $("input#cur-que-idx").val();
            let exam_id     = $("input#exam-id").val();
            let que_type    = $("input#que-type").val();

            //check answers of current question
            $(".answer").each(function(i) {
                if (que_type == 'SEQ') {
                    let guess = $(this).find("div.card").data("ans");
                    ans_list += (guess == undefined ? '' : guess);
                } else if (que_type == 'MR') {
                    if ($(this).find("input[type=checkbox]").prop("checked")) {
                        let guess = $(this).find("input[type=checkbox]").val();
                        ans_list += (guess == undefined ? '' : guess);
                    }
                }
            });

            if (que_type == 'MC') {
                ans_list = $("input[name=answers]:checked").val();
                ans_list = (ans_list == undefined) ? 0 : ans_list;
            }

            //check if there are unanswered questions
            $.ajax({
                url: "/apis/student/check_unanswered.php",
                dataType: "json",
                type: "post",
                data: {
                        exam_id:        exam_id,
                        que_id:         que_id,
                        cur_que_idx:    cur_que_idx,
                        ans_list:       ans_list,
                        spent_time:     time
                    },
                success: function( res ) {
                    if (!res.status) {
                        alert("Error! Please retry to submit answers.");
                        return;
                    } else {
                        if (!res.has_unanswered) {
                            //submit all answers
                            let data = {
                                            exam_id:        exam_id,
                                            que_id:         que_id,
                                            cur_que_idx:    cur_que_idx,
                                            ans_list:       ans_list,
                                            spent_time:     time
                                        };
                            
                            $("div#quiz-block").load("/apis/student/check_answer_t.php", data, function(response, status, xhr) {
                                if (status == "success")
                                    clearInterval(timeControl);
                                    App.initHelpers(['easy-pie-chart']);
                                if (status == "error")
                                    alert("Error: " + xhr.status + ": " + xhr.statusText);
                            });
                        } else {
                            //show modal
                            $("#unanswered-ids").html(res.questions);
                            $("#modal-unanswered").modal({backdrop: 'static', keyboard: false});
                            return;
                        }
                    }
                }
            });
        });

        $(document).on("click", ".force-check-answer", function() {
            let ans_list    = "";
            let que_id      = $("input#que-id").val();
            let cur_que_idx = $("input#cur-que-idx").val();
            let exam_id     = $("input#exam-id").val();
            let que_type    = $("input#que-type").val();

            //check answers of current question
            $(".answer").each(function(i) {
                if (que_type == 'SEQ') {
                    let guess = $(this).find("div.card").data("ans");
                    ans_list += (guess == undefined ? '' : guess);
                } else if (que_type == 'MR') {
                    if ($(this).find("input[type=checkbox]").prop("checked")) {
                        let guess = $(this).find("input[type=checkbox]").val();
                        ans_list += (guess == undefined ? '' : guess);
                    }
                }
            });

            if (que_type == 'MC') {
                ans_list = $("input[name=answers]:checked").val();
                ans_list = (ans_list == undefined) ? 0 : ans_list;
            }

            //submit all answers
            let data = {
                            exam_id:        exam_id,
                            que_id:         que_id,
                            cur_que_idx:    cur_que_idx,
                            ans_list:       ans_list,
                            spent_time:     time
                        };
            
            $("div#quiz-block").load("/apis/student/check_answer_t.php", data, function(response, status, xhr) {
                if (status == "success")
                    clearInterval(timeControl);
                    App.initHelpers(['easy-pie-chart']);
                if (status == "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            });
        });

        $(document).on("click", "button.finish-quiz", function() {
            window.location.href = "index.php";
        });

        $(document).on("click", ".question", function() {
            let cur_que_idx = $(this).data("idx");
            let cnt_que = <?php echo $cnt_que; ?>;

            $("span.nav-title").html("Question " + (parseInt(cur_que_idx) + 1) + " of " + cnt_que);
            
            let ans_list = "";
            let que_id = $("input#que-id").val();
            //let cur_que_idx = $("input#cur-que-idx").val();
            let exam_id = $("input#exam-id").val();
            let que_type = $("input#que-type").val();
            
            $(".answer").each(function(i) {
                if (que_type == 'SEQ') {
                    let guess = $(this).find("div.card").data("ans");
                    ans_list += (guess == undefined ? '' : guess);
                } else if (que_type == 'MR') {
                    if ($(this).find("input[type=checkbox]").prop("checked")) {
                        let guess = $(this).find("input[type=checkbox]").val();
                        ans_list += (guess == undefined ? '' : guess);
                    }
                }
            });

            if (que_type == 'MC') {
                ans_list = $("input[name=answers]:checked").val();
                ans_list = (ans_list == undefined) ? 0 : ans_list;
            }

            if ($(this).find(".flag-field i").hasClass('fa-flag')) {
                $(".set-flag span").html('<i class="fa fa-flag"></i>');
            } else {
                $(".set-flag span").html('<i class="fa fa-flag-o"></i>');
            }

            let data = {
                            exam_id:        exam_id,
                            que_id:         que_id,
                            cur_que_idx:    cur_que_idx,
                            ans_list:       ans_list,
                            spent_time:     time
                        };

            $("div#quiz-block-content").load("/apis/student/get_que.php", data, function(response, status, xhr) {
                if (status == "success")
                    if (isMobile) {
                        $(".desktop-view").remove();
                        
                        $("#answerTable").sortable({
                            items: 'tbody tr',
                            cursor: 'pointer',
                            axis: 'y',
                            dropOnEmpty: false,
                            start: function (e, ui) {
                                ui.item.addClass("selected");
                            },
                            stop: function (e, ui) {
                                ui.item.removeClass("selected");
                                $(this).find("tr").each(function (index) {
                                    if (index > 0) {
                                        $(this).find("td").eq(0).html(index);
                                    }
                                });
                            }
                        });
                    } else {
                        $(".mobile-view").remove();
                        dragAndDrop(".card", ".answer");
                        dragAndDrop(".card", ".option");
                    }
                   
                if (status == "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            });
        });

        $(document).on("click", ".set-flag", function() {
            let cur_que_id = $("input#que-id").val();
            let exam_id = $("input#exam-id").val();
            let flag = 0;
            if ($(this).find("span i").hasClass("fa-flag")) {
                $(this).find("span i").removeClass("fa-flag");
                $(this).find("span i").addClass("fa-flag-o");
                flag = 0;
            } else {
                $(this).find("span i").removeClass("fa-flag-o");
                $(this).find("span i").addClass("fa-flag");
                flag = 1;
            }

            $.ajax({
                url: "/apis/student/set_flag.php",
                dataType: "json",
                type: "post",
                data: {
                        exam_id:    exam_id,
                        cur_que_id: cur_que_id,
                        flag:       flag
                    },
                success: function( res ) {
                    if (!res.status) {
                        alert("Error! Please retry to set flag.");
                        return;
                    } else {
                        $(".question").each(function(i) {
                            if ($(this).data('id') == cur_que_id) {
                                if (flag == 1) {
                                    $(this).find(".flag-field").html('<i class="fa fa-flag"></i>');
                                    $(this).closest("li").addClass("alert-warning");
                                } else {
                                    $(this).find(".flag-field").html('');
                                    $(this).closest("li").removeClass("alert-warning");
                                }
                            }
                        });
                    }
                }
            });
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>