<?php
    require '../../inc/connect_db.php';

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

    //get unanswered questions
    $qs1 = getExamQuestions($exam_id);
    $qs2 = getExamDetailQuestions($exam_id);

    $qs = "";
    foreach ($qs1 as $idx => $q1) {
        $flag = false;
        foreach ($qs2 as $q2) {
            if ($q1['id'] == $q2['que_id'] && !empty($q2['answers'])) {
                $flag = true;
            }
        }
        if ($flag == false) {
            $qs .= ($idx + 1) . ", ";
        }
    }
    
    if ($qs == "") {
        $has_unanswered = false;
    } else {
        $has_unanswered = true;
    }

    echo json_encode(
        array(
            "status"                => true, 
            "has_unanswered"        => $has_unanswered,
            "questions"             => substr($qs, 0, -2)
        )
    );
?>