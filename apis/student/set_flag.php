<?php
    require '../../inc/connect_db.php';

    $exam_id    = $_POST['exam_id'];
    $cur_que_id = $_POST['cur_que_id'];
    $flag       = $_POST['flag'];
    
    $f_que_ids = getExamQueFlagged($exam_id);

    if ($flag == 1) {
        if (!in_array($cur_que_id, $f_que_ids)) {
            array_push($f_que_ids, $cur_que_id);
        }
    } else {
        if (in_array($cur_que_id, $f_que_ids)) {
            $pos = array_search($cur_que_id, $f_que_ids);
            unset($f_que_ids[$pos]);
        }
    }

    saveExamQueFlagged($exam_id, implode("|", $f_que_ids));

    echo json_encode(
        array(
            "status" => true
        )
    );
?>