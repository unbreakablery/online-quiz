<?php require '../inc/config_student.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>
<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<?php
    require '../inc/connect_db.php';

    //Unset session data for quiz
    if (isset($_SESSION)) {
        unset($_SESSION['user_id']);
        unset($_SESSION['quiz_id']);
        unset($_SESSION['quiz_code']);
        unset($_SESSION['exam_id']);
        unset($_SESSION['quiz_type']);
    }

    //default set user id
    $_SESSION['user_id'] = session_id();
        
    //Get quizzes data from db.
    $quizzes = getQuizzes();
?>
<!-- Page Content -->
<div class="content">
    <div class="row push">
        <div class="col-lg-3 col-md-3"></div>
        <div class="col-lg-6 col-md-6">
            <h3 class="block-header bg-primary-darker text-white">
                Situational Judgement Test Questions
            </h3>
        </div>
        <div class="col-lg-3 col-md-3"></div>
    </div>
    <div class="row font-s13">
        <div class="col-lg-3 col-md-3"></div>
        <div class="col-lg-6 col-md-6">
            <div class="col-lg-12 col-md-12 push">
                <h5 class="text-black">
                    We have separated the SJT questions by type (as in the real exam). 
                    Once you have worked through these, try the full SJT mock at the bottom of the page.
                </h5>
            </div>
            <div class="col-lg-12 col-md-12">
            <?php foreach($quizzes as $quiz) { ?>
                <?php if (strpos(strtolower($quiz['quiz_code']), "rating") !== false) { ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="block block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                            <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/photos/ratings-sjt.jpg" alt="">
                            <div class="block-content text-center">
                                <h4 class="push-10"><?php echo $quiz['quiz_code']; ?></h4>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
            <div class="col-lg-12 col-md-12">
            <?php foreach($quizzes as $quiz) { ?>
                <?php if (strpos(strtolower($quiz['quiz_code']), "pick") !== false) { ?>
                    <div class="col-lg-4 col-md-4  col-sm-6 col-xs-12">
                        <a class="block block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                            <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/photos/selection-sjt.jpg" alt="">
                            <div class="block-content text-center">
                                <h4 class="push-10"><?php echo $quiz['quiz_code']; ?></h4>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
            <div class="col-lg-12 col-md-12">
            <?php foreach($quizzes as $quiz) { ?>
                <?php if (strpos(strtolower($quiz['quiz_code']), "rank") !== false) { ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="block block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                            <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/photos/ranking-sjt.jpg" alt="">
                            <div class="block-content text-center">
                                <h4 class="push-10"><?php echo $quiz['quiz_code']; ?></h4>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 push">
                <h5 class="text-black">
                    The minimocks below have 16 questions each - 8 from each section - and give you the opportunity to practise questions with exam timings. 
                    The timed versions last 30 minutes and explanations are available after you complete the exam. 
                    In the untimed version you can mark questions as you go along.
                </h5>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php foreach($quizzes as $quiz) { ?>
                            <?php if (strpos(strtolower($quiz['quiz_code']), "minimock") !== false && $quiz['quiz_type'] == "timed") { ?>
                                <a class="block block-rounded block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                                    <div class="block-content block-content-full clearfix">
                                        <i class="fa fa-2x fa-clock-o" style="padding-right:5px;"></i>
                                        <span class="quiz-code"><?php echo ucfirst($quiz['quiz_type']); ?> <?php echo $quiz['quiz_code']; ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php foreach($quizzes as $quiz) { ?>
                            <?php if (strpos(strtolower($quiz['quiz_code']), "minimock") !== false && $quiz['quiz_type'] == "untimed") { ?>
                                <a class="block block-rounded block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                                    <div class="block-content block-content-full clearfix">
                                        <span class="quiz-code"><?php echo ucfirst($quiz['quiz_type']); ?> <?php echo $quiz['quiz_code']; ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
  
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 push">
                <img class="quiz-logo" src="<?php echo $one->assets_folder; ?>/img/photos/sjtmock.jpg" title="logo" alt="logo" />
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 push">
                <h5 class="text-black">
                    We have a full professional dilemma mock paper. 
                    This consists of 50 SJT questions. 
                    The timed version lasts 95 minutes - explanations are available after you complete the whole exam. 
                    The untimed version contains the same questions, and can be marked as you go.
                </h5>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php foreach($quizzes as $quiz) { ?>
                            <?php if (strpos(strtolower($quiz['quiz_code']), " mock") !== false && $quiz['quiz_type'] == "timed") { ?>
                                <a class="block block-rounded block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                                    <div class="block-content block-content-full clearfix">
                                        <i class="fa fa-2x fa-clock-o" style="padding-right:5px;"></i>
                                        <span class="quiz-code"><?php echo ucfirst($quiz['quiz_type']); ?> <?php echo $quiz['quiz_code']; ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php foreach($quizzes as $quiz) { ?>
                            <?php if (strpos(strtolower($quiz['quiz_code']), " mock") !== false && $quiz['quiz_type'] == "untimed") { ?>
                                <a class="block block-rounded block-link-hover3 submit-link" href="#" data-id="<?php echo $quiz['id']; ?>">
                                    <div class="block-content block-content-full clearfix">
                                        <span class="quiz-code"><?php echo ucfirst($quiz['quiz_type']); ?> <?php echo $quiz['quiz_code']; ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="quiz-form" name="quiz-form" action="welcome.php" method="post">
        <input type="hidden" name="quiz-id" id="quiz-id" value="" />
    </form>
</div>
<!-- END Page Content -->

<?php require '../inc/views/base_footer.php'; ?>

<?php require '../inc/views/template_footer_start.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $(".submit-link").click(function() {
            let quiz_id = $(this).data('id');
            if (!quiz_id) {
                return;
            }
            $("form#quiz-form input#quiz-id").val(quiz_id);
            $("form#quiz-form").submit();
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>