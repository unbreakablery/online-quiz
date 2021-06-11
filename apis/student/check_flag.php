<?php
    require '../../inc/connect_db.php';

    $exam_id    = $_POST['exam_id'];
    $que_id     = $_POST['que_id'];
    
    $f_que_ids = getExamQueFlagged($exam_id);

    if (in_array($que_id, $f_que_ids)) {
        $flag = 1;
    } else {
        $flag = 0;
    }

    echo json_encode(
        array(
            "status"    => true,
            "flag"      => $flag
        )
    );
?>