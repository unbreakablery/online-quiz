<?php
    require '../../inc/connect_db.php';

    $exam_id        = $_POST['exam_id'];
    $que_id         = $_POST['que_id'];
    $cur_que_idx    = $_POST['cur_que_idx'];
    $ans_list       = $_POST['ans_list'];
    $spent_time     = $_POST['spent_time'];

    //update exams table
    $data = array(
                    'cur_que_idx'   => $cur_que_idx,
                    'spent_time'    => $spent_time,
                    'exam_id'       => $exam_id
                );
    updateExamMock($data);

    //save current exam detail
    $exam_detail = getExamDetail($exam_id, $que_id);
    if ($exam_detail) {
        //in case exists the exam, update the exam_detail
        $data = array(
                        'exam_id'   => $exam_id,
                        'que_id'    => $que_id,
                        'answers'   => $ans_list
                    );
        updateExamDetail($data);
    } else {
        //in case doesn't exist the exam, insert the exam_detail
        $data = array(
            'exam_id'   => $exam_id,
            'que_id'    => $que_id,
            'answers'   => $ans_list
        );
        saveExamDetail($data);
    }

    //get next or previous question
    $exam           = getExam($exam_id);
    $cur_que_idx    = $exam['cur_que_idx'];
    $cnt_que        = $exam['cnt_que'];
    $que_ids        = explode("|", $exam['que_ids']);
    $cur_que_id     = $que_ids[$cur_que_idx];
    $cur_que        = getQuestion($cur_que_id);

    $exam_detail    = getExamDetail($exam_id, $cur_que_id);
    
    if (existExamDetail($exam_id, $cur_que_id) && $exam_detail['answers'] != "" && strlen($cur_que['cor_ans']) == strlen($exam_detail['answers'])) {
        $mode = "load";
    } else {
        $mode = "new";
    }

    $questions = getExamQuestions($exam_id);
?>

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
        <div class="table-responsive push-10-t mr-view">
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
        </div>
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