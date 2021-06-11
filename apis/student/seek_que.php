<?php
    require '../../inc/connect_db.php';

    $exam_id        = $_POST['exam_id'];
    $cur_que_idx    = $_POST['cur_que_idx'];

    $query = "
                UPDATE `exams`
                SET
                    `cur_que_idx` = IF(`cnt_que` = `cur_que_idx`, `cur_que_idx`, $cur_que_idx)
                WHERE
                    `id` = $exam_id
            ";
    $result = mysqli_query($connect, $query);
    
    echo json_encode(
        array(
            "status"    => true, 
            "msg"       => "Seeked successfully."
        )
    );
?>