<?php require '../inc/config_student.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>
<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>
<?php
    require '../inc/connect_db.php';
    if (!isset($_POST['exam-id'])) {
        header('Location: index.php');
    }
    $exam_id = $_POST['exam-id'];

    $exam = getExam($exam_id);
    
    if (!isset($_POST['cur-que-idx'])) {
        $cur_que_idx = 1;
    } else {
        $cur_que_idx = $_POST['cur-que-idx'];
    }

    if ($cur_que_idx >= $exam['cnt_que']) {
        $isLast = true;
    } else {
        $isLast = false;
    }
    
    $cnt_que = $exam['cnt_que'];
    $que_ids = explode("|", $exam['que_ids']);
    $cur_que_id = $que_ids[$cur_que_idx - 1];

    $quiz           = getQuiz($exam['quiz_id']);
    $cur_que        = getQuestion($cur_que_id);
    $exam_detail    = getExamDetail($exam_id, $cur_que_id);
    $answers        = isset($exam_detail['answers']) ? $exam_detail['answers'] : '';
    $points         = empty($cur_que['points']) ? 4 : $cur_que['points'];
    $score          = getScore($cur_que['que_type'], $cur_que['cor_ans'], $answers, $points);

    $questions = getExamQuestions($exam_id);
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
                                    <span class="nav-title"><?php echo "Question " . $cur_que_idx . " of " . $cnt_que; ?></span> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#">
                                            <div class="row">
                                                <div class="col-md-1 col-xs-1 text-right">
                                                    #
                                                </div>
                                                <div class="col-md-10 col-xs-10">
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
                                        <li>
                                            <a href="#" class="question" data-idx="<?php echo $idx; ?>">
                                                <div class="row">
                                                    <div class="col-md-1 col-xs-1 text-right">
                                                        <?php echo ($idx + 1) . ". "; ?>
                                                    </div>
                                                    <div class="col-md-10 col-xs-10">
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
                            <?php if (strtolower($quiz['quiz_type']) == 'timed') { ?>
                            <li class="dropdown full-width  pull-right">
                                <a href="#" class="navbar-brand text-right">
                                    <span class=""><i class="fa fa-clock-o"></i> </span> 
                                    <span class="time text-warning"><?php echo getYourTime($quiz['limit_time'] - $exam['spent_time']); ?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-narrow">
                <h4 id="que-text"><?php echo nl2br($cur_que['que_text']); ?></h4>
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
                            $cor_ans = $cur_que['cor_ans'];
                            $cnt_ans = strlen(trim($answers));
                        ?>
                        <?php if ($cnt_ans > 0) { ?>
                            <?php for ($i = 1; $i <= $cnt_ans; $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer" style="width:50%">
                                    <div class="card <?php echo ($answers[$i - 1] == (ord($cor_ans[$i - 1]) - 65 + 1)) ? 'text-success' : 'text-danger'; ?>" data-ans="<?php echo $answers[$i - 1]; ?>"><?php echo $cur_que['ans_' . $answers[$i - 1]]; ?></div>
                                </td>
                                <td class="d-sm-table-cell empty"></td>
                                <td class="option">
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <?php for ($i = 1; $i <= strlen($cor_ans); $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer" style="width:50%"></td>
                                <td class="d-sm-table-cell empty"></td>
                                <td class="option">
                                    <div class="card <?php echo ($i == (ord($cor_ans[$i - 1]) - 65 + 1)) ? 'text-success' : 'text-danger'; ?>" data-ans="<?php echo $i; ?>"><?php echo $cur_que['ans_' . $i]; ?></div>
                                </td>
                            </tr>
                            <?php } ?>
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
                            $cor_ans = $cur_que['cor_ans'];
                            $cnt_ans = strlen(trim($answers));
                        ?>
                        <?php if ($cnt_ans > 0) { ?>
                            <?php for ($i = 1; $i <= $cnt_ans; $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer">
                                    <div class="card <?php echo ($answers[$i - 1] == (ord($cor_ans[$i - 1]) - 65 + 1)) ? 'text-success' : 'text-danger'; ?>" data-ans="<?php echo $answers[$i - 1]; ?>"><?php echo $cur_que['ans_' . $answers[$i - 1]]; ?></div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <?php for ($i = 1; $i <= strlen($cor_ans); $i++) { ?>
                            <tr>
                                <td class="d-sm-table-cell keyword"><div><?php echo $i; ?></div></td>
                                <td class="answer">
                                    <div class="card <?php echo ($i == (ord($cor_ans[$i - 1]) - 65 + 1)) ? 'text-success' : 'text-danger'; ?>" data-ans="<?php echo $i; ?>"><?php echo $cur_que['ans_' . $i]; ?></div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } elseif ($cur_que['que_type'] == 'MR') { ?>
                <div class="table-responsive push-10-t mr-view">
                    <table class="table table-striped table-vcenter">
                        <?php for($i = 1; $i <= 8; $i++) { ?>
                        <?php
                                if (strpos($answers, "$i") !== FALSE) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }

                                $cor_ans = $cur_que['cor_ans'];
                                if (strpos($cor_ans, chr(65 + $i - 1)) !== FALSE) {
                                    $chk_class = "text-success";
                                } else {
                                    $chk_class = "text-danger";
                                }
                        ?>
                        <tr>
                            <td>
                                <div class="checkbox answer <?php echo $chk_class; ?>">
                                    <label for="answer<?php echo $i; ?>">
                                        <input type="checkbox" id="answer<?php echo $i; ?>" name="answers" value="<?php echo $i; ?>" <?php echo $checked; ?> disabled> 
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
                        <?php
                                if (strpos($answers, "$i") !== FALSE) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }

                                $cor_ans = $cur_que['cor_ans'];
                                if (strpos($cor_ans, chr(65 + $i - 1)) !== FALSE) {
                                    $chk_class = "text-success";
                                } else {
                                    $chk_class = "text-danger";
                                }
                        ?>
                        <?php if (isset($cur_que['ans_' . $i]) && trim($cur_que['ans_' . $i]) != "") { ?>
                        <tr>
                            <td>
                                <div class="radio answer <?php echo $chk_class; ?>">
                                    <label for="answer<?php echo $i; ?>">
                                        <input type="radio" id="answer<?php echo $i; ?>" name="answers" value="<?php echo $i; ?>" <?php echo $checked; ?> disabled> 
                                        <?php echo $cur_que['ans_' . $i]; ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </table>
                </div>
                <?php } ?>
                <div class="row push push-5-t-quiz">
                    <form id="review-form" name="review-form" action="" method="post">
                        <input type="hidden" name="cur-que-idx" id="cur-que-idx" value="<?php echo $cur_que_idx; ?>" />
                        <input type="hidden" name="exam-id" id="exam-id" value="<?php echo $exam_id; ?>" />
                        
                        <div class="col-lg-12 col-md-12 text-left push-10-t score-card">
                            <h3>Score: <?php echo $score['checked']; ?>/<?php echo $score['total']; ?></h3>
                        </div>
                        <div class="row push-5-r push-5-l">
                            <div class="col-lg-4 col-md-4 col-xs-12 text-left push-10-t">
                                <button type="button" class="col-xs-12 btn btn-darker result-quiz">Result</button>    
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-12 text-right push-10-t">
                                <button type="button" class="col-md-4 pull-right col-xs-6 btn btn-default next-question" <?php echo ($cur_que_idx == $cnt_que) ? 'disabled' : ''; ?>>Next <i class="fa fa-caret-right"></i></button>    
                                <button type="button" class="col-md-4 pull-right col-xs-6 btn btn-default prev-question" <?php echo ($cur_que_idx == 1) ? 'disabled' : ''; ?>><i class="fa fa-caret-left"></i> Prev</button>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 text-left correct-feedback push-10-t">
                            <div class="alert alert-default alert-dismissable" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <p>
                                    <em>
                                        <?php echo nl2br($cur_que['cor_fb']); ?>
                                    </em>
                                </p>
                            </div>
                        </div>
                        <div class="row push-5-r push-5-l">
                            <div class="col-lg-4 col-md-4 col-xs-12 text-left push-10-t">
                                <button type="button" class="col-xs-12 btn btn-darker result-quiz">Result</button>    
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-12 text-right push-10-t">
                                <button type="button" class="col-md-4 pull-right col-xs-6 btn btn-default next-question" <?php echo ($cur_que_idx == $cnt_que) ? 'disabled' : ''; ?>>Next <i class="fa fa-caret-right"></i></button>    
                                <button type="button" class="col-md-4 pull-right col-xs-6 btn btn-default prev-question" <?php echo ($cur_que_idx == 1) ? 'disabled' : ''; ?>><i class="fa fa-caret-left"></i> Prev</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3"></div>
</div>
<!-- END Page Content -->

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
        } else {
            $(".mobile-view").remove();
        }
        
        $("button.result-quiz").click(function() {
            $("form#review-form").attr("action", "result.php");
            $("form#review-form").submit();
        });
        $("button.prev-question").click(function() {
            $("#cur-que-idx").val(parseInt($("#cur-que-idx").val()) - 1);
            $("form#review-form").attr("action", "review.php");
            $("form#review-form").submit();
        });
        $("button.next-question").click(function() {
            $("#cur-que-idx").val(parseInt($("#cur-que-idx").val()) + 1);
            $("form#review-form").attr("action", "review.php");
            $("form#review-form").submit();
        });
        $(".question").click(function() {
            let cur_que_idx = $(this).data("idx");
            $("#cur-que-idx").val(cur_que_idx + 1);
            $("form#review-form").attr("action", "review.php");
            $("form#review-form").submit();
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>