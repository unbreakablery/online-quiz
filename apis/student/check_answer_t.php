<?php
    require '../../inc/connect_db.php';

    session_start();

    $exam_id        = $_POST['exam_id'];
    $que_id         = $_POST['que_id'];
    $cur_que_idx    = $_POST['cur_que_idx'];
    $ans_list       = $_POST['ans_list'];
    $spent_time     = $_POST['spent_time'];

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

    //insert blank answers in case not ranked/picked questions when submit all
    $exam = getExam($exam_id);
    $qs1 = getQuestions($exam['quiz_id']);
    foreach ($qs1 as $q) {
        if (!existExamDetail($exam_id, $q['id'])) {
            $data = array(
                            'exam_id'   => $exam_id,
                            'que_id'    => $q['id'],
                            'answers'   => ''
                        );
            saveExamDetail($data);
        }
    }

    //check answers
    // $exam = getExam($exam_id);
    // $qs1 = getQuestions($exam['quiz_id']);
    $qs2 = getExamDetailQuestions($exam_id);

    $score = 0;
    $total_score = 0;
    foreach ($qs1 as $q1) {
        $points = empty($q1['points']) ? 4 : $q1['points'];
        $total_score += strlen($q1['cor_ans']) * $points;
        foreach ($qs2 as $q2) {
            if ($q1['id'] == $q2['que_id']) {
                if ($q1['que_type'] == "SEQ") {
                    for ($i = 0; $i < strlen($q1['cor_ans']); $i++) {
                        for ($j = 0; $j < strlen($q2['answers']); $j++) {
                            if ((ord($q1['cor_ans'][$i]) - 65 + 1) == $q2['answers'][$j]) {
                                $score += $points - abs($i - $j);
                            }
                        }	
                    }
                } elseif ($q1['que_type'] == "MR") {
                    if (strlen($q2['answers']) > strlen($q1['cor_ans'])) {
                        $score = 0;
                    } else {
                        for ($i = 0; $i < strlen($q1['cor_ans']); $i++) {
                            for ($j = 0; $j < strlen($q2['answers']); $j++) {
                                if ((ord($q1['cor_ans'][$i]) - 65 + 1) == $q2['answers'][$j]) {
                                    $score += $points;
                                }
                            }	
                        }    
                    }
                } elseif ($q1['que_type'] == "MC") {
                    if (strlen($q2['answers']) > strlen($q1['cor_ans'])) {
                        $score = 0;
                    } else {
                        $score += ($q2['answers'] == 0) ? 0 : $points - abs(ord($q1['cor_ans']) - 65 + 1 - $q2['answers']);
                    }
                }
            }
        }
    }

    //update exams table
    $data = array(
                    'cur_que_idx'   => $cur_que_idx,
                    'spent_time'    => $spent_time,
                    'exam_id'       => $exam_id,
                    'score'         => $score,
                    'total_score'   => $total_score
                );
    updateExamMock1($data);

    $percentage     = round($score / $total_score * 100);
        
    //get evaluation
    $evaluation = getEvalFromValue($percentage);
    if (isset($evaluation['id'])) {
        $badge_text     = nl2br($evaluation['feedback_text']);
        $badge_class    = $evaluation['feedback_class'];
        $chart_color    = $evaluation['chart_color'];
        $chart_class    = $evaluation['chart_class'];
    } else {
        $badge_text     = "Unknown Evalution";
        $badge_class    = "alert-danger";
        $chart_color    = "#d26a5c";
        $chart_class    = "text-danger";
    }
    
    $quiz = getQuiz($_SESSION['quiz_id']);
    $your_time = getYourTime($quiz['limit_time'] - $spent_time);

    // removeExamDetail($exam_id);

    //Unset session data for quiz
    // if (isset($_SESSION)) {
    //     unset($_SESSION['user_id']);
    //     unset($_SESSION['quiz_id']);
    //     unset($_SESSION['quiz_code']);
    //     unset($_SESSION['exam_id']);
    // }
?>
<div class="block block-themed">
    <div class="block-header bg-gray-darker">
        <h3 class="block-title">Your Exam Result</h3>
    </div>
    <div class="block-content block-content-narrow">
        <div class="row">
            <div class="block block-themed">
                <div class="block-content block-content-full text-center">
                    <!-- Pie Chart Container -->
                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $percentage; ?>" data-line-width="10" data-size="150" data-bar-color="<?php echo $chart_color; ?>" data-track-color="#eeeeee">
                        <span class="h4 <?php echo $chart_class; ?>"><strong><?php echo $percentage; ?>%</strong></span>
                    </div>
                </div>
                <div class="block-content bg-gray-lighter">
                    <div class="row items-push text-center">
                        <div class="col-xs-12">
                            <div class="alert <?php echo $badge_class; ?> alert-dismissable">
                                <h3 class="font-w300 push-15">
                                    <strong><?php echo $badge_text; ?></strong>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row items-push text-center">
                        <div class="col-xs-4">
                            <div class="push-5"><i class="si si-target fa-2x"></i></div>
                            <div class="h3 font-w300 text-muted">Total Score: <?php echo $total_score; ?></div>
                        </div>
                        <div class="col-xs-4">
                            <div class="push-5"><i class="si si-speedometer fa-2x"></i></div>
                            <div class="h3 font-w300 text-muted">Your Score: <?php echo $score; ?></div>
                        </div>
                        <div class="col-xs-4">
                            <div class="push-5"><i class="si si-clock fa-2x"></i></div>
                            <div class="h3 font-w300 text-muted">Your Time: <?php echo $your_time; ?></div>
                        </div>
                    </div>
                    <div class="row push push-5-t-quiz">
                        <form name="exam-form" action="review.php" method="post">
                            <input type="hidden" id="exam-id" name="exam-id" value="<?php echo $exam_id; ?>" />
                            <div class="col-lg-12 col-md-12 text-right push-10-t">
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <button type="submit" class="push-10-t col-xs-12 btn btn-dark review-quiz">Review Answers + Explanations</button>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <button type="button" class="push-10-t col-xs-12 btn btn-default finish-quiz">Finish and Exit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>