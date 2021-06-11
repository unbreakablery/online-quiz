<?php
    require '../../inc/connect_db.php';
    
    $id         = $_POST['id'];
    $quiz_id    = $_POST['quiz-id'];
    $que_id     = getMaxQueID($quiz_id);
    if ($que_id == null) {
        $que_id = 1;
    } else {
        $que_id += 1;
    }
    $que_type   = $_POST['que-type'];
    $que_text   = $_POST['que-text'];
    $ans_1      = $_POST['ans-1'];
    $ans_2      = $_POST['ans-2'];
    $ans_3      = $_POST['ans-3'];
    $ans_4      = $_POST['ans-4'];
    $ans_5      = $_POST['ans-5'];
    $ans_6      = $_POST['ans-6'];
    $ans_7      = $_POST['ans-7'];
    $ans_8      = $_POST['ans-8'];
    $cor_ans    = $_POST['cor-ans'];
    $cor_fb     = $_POST['cor-fb'];
    $inc_fb     = $_POST['inc-fb'];
    $points     = $_POST['points'];
    
    $data = array(
        'id'        => $id,
        'quiz_id'   => $quiz_id,
        'que_id'    => $que_id,
        'que_type'  => $que_type,
        'que_text'  => mysqli_real_escape_string($connect, $que_text),
        'ans_1'     => mysqli_real_escape_string($connect, $ans_1),
        'ans_2'     => mysqli_real_escape_string($connect, $ans_2),
        'ans_3'     => mysqli_real_escape_string($connect, $ans_3),
        'ans_4'     => mysqli_real_escape_string($connect, $ans_4),
        'ans_5'     => mysqli_real_escape_string($connect, $ans_5),
        'ans_6'     => mysqli_real_escape_string($connect, $ans_6),
        'ans_7'     => mysqli_real_escape_string($connect, $ans_7),
        'ans_8'     => mysqli_real_escape_string($connect, $ans_8),
        'cor_ans'   => mysqli_real_escape_string($connect, $cor_ans),
        'cor_fb'    => mysqli_real_escape_string($connect, $cor_fb),
        'inc_fb'    => mysqli_real_escape_string($connect, $inc_fb),
        'points'    => empty($points) ? 'NULL' : $points
    );
    $inc_fb = $data['inc_fb'];
    if ($id == 0) {
        //in case save new question
        // saveQuestion($data);
        $query = "
                    INSERT INTO questions 
                        (quiz_id, que_id, que_type, que_text, 
                        ans_1, ans_2, ans_3, ans_4, ans_5, ans_6, ans_7, ans_8,
                        cor_ans, cor_fb, inc_fb, points) VALUES 
                        (";
        $query .= $data['quiz_id'] . ", ";
        $query .= $data['que_id'] . ", ";
        $query .= "'" . $data['que_type'] . "',";
        $query .= "'" . $data['que_text'] . "',";
        $query .= "'" . $data['ans_1'] . "',";
        $query .= "'" . $data['ans_2'] . "',";
        $query .= "'" . $data['ans_3'] . "',";
        $query .= "'" . $data['ans_4'] . "',";
        $query .= "'" . $data['ans_5'] . "',";
        $query .= "'" . $data['ans_6'] . "',";
        $query .= "'" . $data['ans_7'] . "',";
        $query .= "'" . $data['ans_8'] . "',";
        $query .= "'" . $data['cor_ans'] . "',";
        $query .= "'" . $data['cor_fb'] . "',";
        $query .= "'" . $data['inc_fb'] . "',";
        $query .= $data['points'] . ")";

        $result = mysqli_query($connect, $query);
    } else {
        //in case update question
        // updateQuestion($data);
        $query = "
                    UPDATE questions SET ";
        $query .= "quiz_id = " . $data['quiz_id'] . ", ";
        $query .= "que_id = " . $data['que_id'] . ", ";
        $query .= "que_type = '" . $data['que_type'] . "',";
        $query .= "que_text = '" . $data['que_text'] . "',";
        $query .= "ans_1 = '" . $data['ans_1'] . "',";
        $query .= "ans_2 = '" . $data['ans_2'] . "',";
        $query .= "ans_3 = '" . $data['ans_3'] . "',";
        $query .= "ans_4 = '" . $data['ans_4'] . "',";
        $query .= "ans_5 = '" . $data['ans_5'] . "',";
        $query .= "ans_6 = '" . $data['ans_6'] . "',";
        $query .= "ans_7 = '" . $data['ans_7'] . "',";
        $query .= "ans_8 = '" . $data['ans_8'] . "',";
        $query .= "cor_ans = '" . $data['cor_ans'] . "',";
        $query .= "cor_fb = '" . $data['cor_fb'] . "',";
        $query .= "inc_fb = '" . $data['inc_fb'] . "', ";
        $query .= "points = " . $data['points'] . " ";
        $query .= "WHERE id = " . $data['id'];

        $result = mysqli_query($connect, $query);
    }

    if ($result) {
       $status = true;
       $msg = "Question was saved successfully!"; 
    } else {
       $status = false;
       $msg = "Error: " . $query;
    }
    echo json_encode(array(
        'status'    => $status,
        'msg'       => $msg
    ));
?>