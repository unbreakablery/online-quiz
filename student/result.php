<?php require '../inc/config_student.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>
<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<?php
    require '../inc/connect_db.php';
    
    if ( !isset($_SESSION['user_id']) || (!isset($_SESSION['exam_id']) && !isset($_POST['exam-id']))) {
        header('Location: index.php');
        exit;
    } else {
        if (isset($_POST['exam-id'])) {
            $exam_id = $_POST['exam-id'];
        } else {
            $exam_id = $_SESSION['exam_id'];
        }
        $exam = getExam($exam_id);
        
        if ($exam['state'] === 0) {
            header('Location: index.php');
            exit;
        }

        $quiz = getQuiz($exam['quiz_id']);

        $total_score    = $exam['total_score'];
        $your_score     = $exam['score'];
        
        if ($quiz['quiz_type'] == 'timed') {
            $your_time  = $quiz['limit_time'] - $exam['spent_time'];
        } else {
            $your_time  = $exam['spent_time'];
        }

        $percentage     = round($your_score / $total_score * 100);
        
        //get evaluation
        $evaluation = getEvalFromValue($percentage);
        if (isset($evaluation['id'])) {
            $badge_text     = nl2br($evaluation['feedback_text']);
            $badge_class    = $evaluation['feedback_class'];
            $chart_color    = $evaluation['chart_color'];
            $chart_class    = $evaluation['chart_class'];
        } else {
            $badge_text     = "Unknown Evalution";
            $badge_class    = "alert-danger";
            $chart_color    = "#d26a5c";
            $chart_class    = "text-danger";
        }

        //Unset session data for quiz
        // if (isset($_SESSION)) {
        //     unset($_SESSION['user_id']);
        //     unset($_SESSION['quiz_id']);
        //     unset($_SESSION['quiz_code']);
        //     unset($_SESSION['exam_id']);
        //     unset($_SESSION['tscore']);
        // }
    } 
?>

<!-- Page Content -->
<div class="content">
    <div class="col-lg-3 col-md-3"></div>
    <div class="col-lg-6 col-md-6">
        <div class="block block-themed">
            <div class="block-header bg-gray-darker">
                <h3 class="block-title">Your Exam Result</h3>
            </div>
            <div class="block-content block-content-narrow">
                <div class="row">
                    <div class="block block-themed">
                        <div class="block-content block-content-full text-center">
                            <!-- Pie Chart Container -->
                            <div class="js-pie-chart pie-chart" data-percent="<?php echo $percentage; ?>" data-line-width="10" data-size="150" data-bar-color="<?php echo $chart_color; ?>" data-track-color="#eeeeee">
                                <span class="h4 <?php echo $chart_class; ?>"><strong><?php echo $percentage; ?>%</strong></span>
                            </div>
                        </div>
                        <div class="block-content bg-gray-lighter">
                            <div class="row items-push text-center">
                                <div class="col-xs-12">
                                    <div class="alert <?php echo $badge_class; ?> alert-dismissable">
                                        <h3 class="font-w300 push-15">
                                            <strong><?php echo $badge_text; ?></strong>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row items-push text-center">
                                <div class="col-xs-4">
                                    <div class="push-5"><i class="si si-target fa-2x"></i></div>
                                    <div class="h3 font-w300 text-muted">Total Score: <?php echo $total_score; ?></div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="push-5"><i class="si si-speedometer fa-2x"></i></div>
                                    <div class="h3 font-w300 text-muted">Your Score: <?php echo $your_score; ?></div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="push-5"><i class="si si-clock fa-2x"></i></div>
                                    <div class="h3 font-w300 text-muted">Your Time: <?php echo getYourTime($your_time); ?></div>
                                </div>
                            </div>
                            <div class="row push push-5-t-quiz">
                                <form name="exam-form" action="review.php" method="post">
                                    <input type="hidden" id="exam-id" name="exam-id" value="<?php echo $exam_id; ?>" />
                                    <div class="col-lg-12 col-md-12 text-right push-10-t">
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <button type="submit" class="push-10-t col-xs-12 btn btn-dark review-quiz">Review Answers + Explanations</button>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <button type="button" class="push-10-t col-xs-12 btn btn-default finish-quiz">Finish and Exit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3"></div>
</div>
<!-- END Page Content -->

<?php require '../inc/views/base_footer.php'; ?>

<?php require '../inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>

<script>
    App.initHelpers(['easy-pie-chart']);
    $(document).ready(function() {
        $("button.finish-quiz").click(function() {
            window.location.href = "index.php";
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>