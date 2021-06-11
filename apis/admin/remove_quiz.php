<?php
    require '../../inc/connect_db.php';
    
    $quiz_id    = $_POST['quiz-id'];

    if (removeQuiz($quiz_id)) {
        echo json_encode(
            array(
                "status"    => true,
                "msg"       => "Quiz was removed successfully!"
            )
        );
    } else {
        echo json_encode(
            array(
                "status"    => false,
                "msg"       => "Error occurs while query running!"
            )
        );
    }
?>