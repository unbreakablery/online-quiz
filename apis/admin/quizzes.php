<?php
    require '../../inc/connect_db.php';
    require '../../inc/classes/SimpleXLSX.php';

    $status = true;
    $msg = "Excel file was imported into database successfully !";

    $filename = $_FILES["source_file"]["tmp_name"];

    if($_FILES["source_file"]["size"] > 0) {

        //When you import data from excel, all quizzes data will be removed.
        $query = "TRUNCATE TABLE `quizzes`";
        $result = mysqli_query($connect, $query);

        $query = "TRUNCATE TABLE `questions`";
        $result = mysqli_query($connect, $query);

        $query = "TRUNCATE TABLE `exams`";
        $result = mysqli_query($connect, $query);

        $query = "TRUNCATE TABLE `exam_detail`";
        $result = mysqli_query($connect, $query);

        //Read data from uploaded excel file.
        if ( $xlsx = SimpleXLSX::parse($filename) ) {
            // Produce array keys from the array values of 1st array element
            $table_header = ['#', 'Quiz Code', 'Count of Questions', 'Quiz Type', 'Limit Time'];
            $table_data = [];
            foreach ( $xlsx->rows() as $k => $r ) {
                if ( $k === 0 ) {
                    continue;
                }
                $quiz_code = trim(mysqli_real_escape_string($connect, $r[0]));
                $quiz_type = strtolower(trim(mysqli_real_escape_string($connect, $r[15])));
                $limit_time = explode(" ", trim(mysqli_real_escape_string($connect, $r[16])));
                $limit_time = is_numeric($limit_time[0]) ? $limit_time[0] * 60 : 0;

                $query = "
                            SELECT *
                            FROM `quizzes`
                            WHERE
                                `quiz_code` = '$quiz_code' AND
                                `quiz_type` = '$quiz_type' 
                        ";
                $result = mysqli_query($connect, $query);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $quiz_id = $row['id'];
                } else {
                    $query = "INSERT INTO `quizzes`";
                    $query .= "(`quiz_code`, `quiz_type`, `limit_time`)";
                    $query .= " VALUES ";
                    $query .= "('$quiz_code', '$quiz_type', $limit_time)";
                    
                    $result = mysqli_query($connect, $query);
                    $quiz_id = mysqli_insert_id($connect);
                }

                $query = "INSERT INTO `questions` ";
                $query .= "(`quiz_id`, `que_id`, `que_type`, `que_text`, `ans_1`, `ans_2`, `ans_3`, `ans_4`, `ans_5`, `ans_6`, `ans_7`, `ans_8`, `cor_ans`, `cor_fb`, `inc_fb`, `points`)";
                $query .= " VALUES ";
                $query .= "(";
                $query .= $quiz_id . ", ";
                $query .= $r[1] . ", ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[2]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[3]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[4]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[5]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[6]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[7]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[8]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[9]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[10]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[11]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, trim($r[14])) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[12]) . "', ";
                $query .= "'" . mysqli_real_escape_string($connect, $r[13]) . "', ";
                $query .= (empty($r[17])) ? 'NULL' : $r[17];
                $query .= ")";
                $result = mysqli_query($connect, $query);
            }
            
            $query = "
                        SELECT
                            `q1`.`quiz_code`,
                            COUNT(`q1`.`quiz_code`) AS `cnt_que`,
                            `q1`.`quiz_type`,
                            `q1`.`limit_time`
                        FROM 
                            `quizzes` AS `q1`
                        LEFT JOIN `questions` AS `q2` ON `q1`.`id` = `q2`.`quiz_id`
                        GROUP BY `q1`.`id`
                        ORDER BY `q1`.`id`
                    ";
            $result = mysqli_query($connect, $query);
            $idx = 0;
            while ($row = mysqli_fetch_array($result)) {
                $row[0] = ++$idx;
                $row['idx'] = $idx;
                $row[1] = $row['quiz_code'];
                $row[2] = $row['cnt_que'];
                $row[3] = ucfirst($row['quiz_type']);
                $row[4] = ($row['limit_time'] > 0) ? ($row['limit_time'] / 60) . ' minutes' : '';
                $table_data[] = $row;
            }
        }
        
        echo json_encode(
            array(
                "status" => $status, 
                "msg" => $msg,
                "table_header" => $table_header,
                "table_data" => $table_data
            )
        );
    } else {
        $status = false;
        $msg = "File size should be greater than 0 !";
        
        echo json_encode(
            array(
                "status" => $status, 
                "msg" => $msg
            )
        );
    }
  
?>