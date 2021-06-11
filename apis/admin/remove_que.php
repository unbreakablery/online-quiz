<?php
    require '../../inc/connect_db.php';
    
    $id    = $_POST['que-id'];

    if (removeQuestion($id)) {
        echo json_encode(
            array(
                "status"    => true,
                "msg"       => "Question was removed successfully!"
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