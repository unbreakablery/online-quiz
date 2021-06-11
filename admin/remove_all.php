<?php
    require '../inc/connect_db.php';

    $query = "TRUNCATE TABLE `quizzes`";
    $result = mysqli_query($connect, $query);

    $query = "TRUNCATE TABLE `questions`";
    $result = mysqli_query($connect, $query);

    $query = "TRUNCATE TABLE `exams`";
    $result = mysqli_query($connect, $query);

    $query = "TRUNCATE TABLE `exam_detail`";
    $result = mysqli_query($connect, $query);

    header('Location: import_quizzes.php');
?>