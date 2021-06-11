<?php
    require '../../inc/connect_db.php';
    
    $id = $_POST['id'];

    $query = "
                DELETE
                FROM
                    eval_setting
                WHERE
                    id = $id
            ";
    $result = mysqli_query($connect, $query);

    if ($result) {
        echo json_encode(
            array(
                "status"    => true,
                "msg"       => "Evaluation was removed successfully! (id = $id)"
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