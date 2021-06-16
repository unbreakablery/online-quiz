<?php
    require '../../inc/connect_db.php';
    
    $quiz_id    = $_POST['quiz-id'];
    $old_quiz_code  = $_POST['old-quiz-code'];
    $quiz_code  = $_POST['quiz-code'];
    $quiz_type  = $_POST['quiz-type'];
    $quiz_kind  = $_POST['quiz-kind'];
    
    if ($quiz_type == "untimed") {
        $limit_time = 0;
    } else {
        $limit_time = $_POST['limit-time'];
    }

    $data = array(
                    'quiz_id'       => $quiz_id,
                    'quiz_code'     => $quiz_code,
                    'quiz_type'     => $quiz_type,
                    'limit_time'    => $limit_time,
                    'quiz_kind'     => $quiz_kind
                );

    if ($quiz_id == 0) {
        //in case save new quiz
        if (existQuiz($quiz_id, $quiz_code, $quiz_type)) {
            echo json_encode(
                array(
                    "status"    => false,
                    "msg"       => "Quiz with this code exists already!"
                )
            );
            exit;
        } else {
            saveQuiz($data);
            echo json_encode(
                array(
                    "status"    => true,
                    "msg"       => "Quiz was saved successfully!"
                )
            );
            exit;
        }
    } else {
        //in case update quiz
        if ($old_quiz_code == $quiz_code) {
            updateQuiz($data);
            echo json_encode(
                array(
                    "status"    => true,
                    "msg"       => "Quiz was updated successfully!"
                )
            );
            exit;
        }
        if (existQuiz($quiz_id, $quiz_code, $quiz_type)) {
            echo json_encode(
                array(
                    "status"    => false,
                    "msg"       => "Quiz with this code-[$quiz_code] exists already!"
                )
            );
            exit;
        } else {
            updateQuiz($data);
            echo json_encode(
                array(
                    "status"    => true,
                    "msg"       => "Quiz was updated successfully!"
                )
            );
            exit;
        }
    }
?>