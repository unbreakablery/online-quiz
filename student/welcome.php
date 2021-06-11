<?php require '../inc/config_student.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>
<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<?php
    require '../inc/connect_db.php';

    //Unset session data for quiz
    if (isset($_SESSION)) {
        unset($_SESSION['quiz_id']);
        unset($_SESSION['exam_id']);
    }
        
    if (!isset($_POST['quiz-id'])) {
        header("Location: index.php");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $quiz_id = $_POST['quiz-id'];

    $query = "
                SELECT
                    *
                FROM
                    `quizzes` as q
                WHERE
                    q.id = $quiz_id
            ";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_array($result);
    $quiz_code = $row['quiz_code'];
    $quiz_type = $row['quiz_type'];

    $query = "
                SELECT
                    e.id,
                    e.user_id,
                    e.quiz_id
                FROM
                    `exams` AS e
                WHERE
                    e.user_id = '$user_id' AND
                    e.quiz_id = $quiz_id AND
                    e.state = 0
            ";
    if ($result = mysqli_query($connect, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $exam = mysqli_fetch_array($result);
            $exam_id = $exam['id'];
        } else {
            $exam_id = 0;
        }
    } else {
        header('Location: index.php');
        exit;
    }

    $quiz = getQuiz($quiz_id);
    
    //set session
    $_SESSION['quiz_id'] = $quiz_id;
    $_SESSION['quiz_code'] = $quiz_code;
    $_SESSION['quiz_type'] = $quiz_type;
?>

<!-- Page Content -->
<div class="content">
    <div class="col-lg-3 col-md-3"></div>
    <div class="col-lg-6 col-md-6">
        <div class="block block-themed">
            <!-- Quiz Form -->
            <form class="form-horizontal" method="post" id="quiz-form" name="quiz-form" action="<?php echo ($quiz_type === 'untimed') ? 'quiz.php' : 'tquiz.php'; ?>">
                <div class="block-header bg-quiz-main push-5-quiz">
                    <h3 class="block-title"></h3>
                </div>
                <div class="block-content block-content-narrow">
                    <input type="hidden" name="exam-id" id="exam-id" value="<?php echo $exam_id; ?>" />
                    <div class="form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <img class="quiz-logo" src="<?php echo $one->assets_folder; ?>/img/photos/emedica_online_logo.jpg" title="logo" alt="logo" />
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h2 class="text-center"><strong>Welcome to the Emedica Specialty Recruitment</strong></h2>
                            <h2 class="text-center"><strong>Assessment Questions</strong></h2>
                            <h2 class="text-center push-10-t"><strong>Situational Judgement Section - <span class="text-danger"><?php echo $quiz_code; ?></span></strong></h2>
                            <?php if ($quiz['limit_time'] > 0) { ?>
                                <?php if (strpos(strtolower($quiz['quiz_code']), "minimock") !== false) { ?>
                                    <h3 class="text-center push-50-t text-black">
                                        <strong>This is a Timed minimock exam - you have <?php echo round($quiz['limit_time'] / 60); ?> minutes to complete <?php echo $quiz['cnt_que']; ?> questions</strong>
                                    </h3>
                                <?php } else if (strpos(strtolower($quiz['quiz_code']), " mock") !== false) { ?>
                                    <h3 class="text-center push-50-t text-black">
                                        <strong>This is a full length timed SJT exam - you have <?php echo round($quiz['limit_time'] / 60); ?> minutes to complete <?php echo $quiz['cnt_que']; ?> questions</strong>
                                    </h3>
                                <?php } ?>
                            
                            <?php } else if (strpos(strtolower($quiz['quiz_code']), "minimock") !== false) { ?>
                            <h3 class="text-center push-50-t text-black">
                                <strong>This is a Untimed minimock exam</strong>
                            </h3>
                            <?php } else if (strpos(strtolower($quiz['quiz_code']), " mock") !== false) { ?>
                            <h3 class="text-center push-50-t text-black">
                                <strong>This is a full length untimed SJT exam</strong>
                            </h3>
                            <?php } ?>
                            <h3 class="text-center push-50-t">Click the "Start Quiz" button to proceed</h3>
                        </div>
                    </div>
                </div>
                <div class="block-header bg-quiz-main push-5-t-quiz">
                    <h3 class="block-title text-right"><button class="btn btn-default start-quiz" type="submit">Start Quiz <i class="fa fa-play-circle-o"></i></button></h3>
                </div>
            </form>
            <!-- END Quiz Form -->
        </div>
    </div>
    <div class="col-lg-3 col-md-3"></div>
</div>
<!-- END Page Content -->
<!-- Pop Out Modal -->
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Confirm</h3>
                </div>
                <div class="block-content">
                    <h3><i class="fa fa-question-circle"></i> Would you like to resume where you left off?</h3>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" id="new-exam" data-dismiss="modal">No, New exam</button>
                <button class="btn btn-sm btn-dark" type="button" id="load-exam" data-dismiss="modal">Yes, Load exam</button>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->

<?php require '../inc/views/base_footer.php'; ?>

<?php require '../inc/views/template_footer_start.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        let exam_id = <?php echo $exam_id; ?>;
        if (exam_id != undefined && exam_id != 0) {
            $("#modal-popout").modal({'backdrop': 'static'});
        }
        $("#modal-popout button#load-exam").click(function() {
            $("form#quiz-form").submit();
        });
        $("#modal-popout button#new-exam").click(function() {
            $("form#quiz-form #exam-id").val('0');
            $("form#quiz-form").submit();
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>