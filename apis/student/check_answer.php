<?php
    require '../../inc/connect_db.php';

    session_start();
    
    $status = true;
    $msg = "";

    $exam_id        = $_POST['exam_id'];
    $que_id         = $_POST['que_id'];
    $ans_list       = $_POST['ans_list'];
    $spent_time     = $_POST['spent_time'];
    $cur_que_idx    = $_POST['cur_que_idx'];

    $query = "
                SELECT
                    `que_type`,
                    UPPER(`cor_ans`) AS `cor_ans`,
                    `cor_fb`,
                    `points`
                FROM
                    `questions`
                WHERE
                    `id` = $que_id
            ";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $row = mysqli_fetch_array($result);
        $cor_ans = $row['cor_ans'];
        $que_type = $row['que_type'];
        $points = empty($row['points']) ? 4 : $row['points'];
        
        $sub_total_score = $points * strlen($cor_ans);
        $score = 0;

        if ($que_type === 'SEQ') {
            for ($i = 0; $i < strlen($cor_ans); $i++) {
                for ($j = 0; $j < strlen($ans_list); $j++) {
                    if ((ord($cor_ans[$i]) - 65 + 1) == $ans_list[$j]) {
                        $score += $points - abs($i - $j);
                    }
                }	
            }
        } elseif ($que_type === 'MR') {
            if (strlen($ans_list) > strlen($cor_ans)) {
                $score = 0;
            } else {
                for ($i = 0; $i < strlen($cor_ans); $i++) {
                    for ($j = 0; $j < strlen($ans_list); $j++) {
                        if ((ord($cor_ans[$i]) - 65 + 1) == $ans_list[$j]) {
                            $score += $points;
                        }
                    }	
                }    
            }
        } elseif ($que_type === 'MC') {
            $score = ($ans_list == 0) ? 0 : $points - abs(ord($cor_ans) - 65 + 1 - $ans_list);
        } 

        //save exam_detail
        $data = array(
                        'exam_id'   => $exam_id,
                        'que_id'    => $que_id,
                        'answers'   => $ans_list
                    );
        saveExamDetail($data);

        //update exam
        $query = "
                    UPDATE `exams`
                    SET
                        `cur_que_idx` = IF(`cnt_que` = `cur_que_idx`, `cur_que_idx`, $cur_que_idx + 1),
                        `score` = `score` + $score,
                        `spent_time` = $spent_time,
                        `state` = IF(`cnt_que` = `cur_que_idx`, 1, 0),
                        `end_date` = IF(`cnt_que` = `cur_que_idx`, NOW(), NULL)
                    WHERE `id` = $exam_id
                ";
        if (mysqli_query($connect, $query)) {
            $exam = getExam($exam_id);
            $tscore = getTargetScore($exam['quiz_id']);
            echo json_encode(
                array(
                    "status"            => $status,
                    "msg"               => $msg,
                    "score"             => $score,
                    "sub_total_score"   => $sub_total_score,
                    "tscore"            => $tscore,
                    "yscore"            => $exam['score']
                )
            );
        } else {
            $status = false;
            $msg = "Error updating record: " . mysqli_error($connect);

            echo json_encode(
                array(
                    "status"            => $status,
                    "msg"               => $msg
                )
            );
        }

        
    } else {
        $status = false;
        $msg = "SQL running error!";
        
        echo json_encode(
            array(
                "status" => $status, 
                "msg" => $msg
            )
        );
    }
  
?>