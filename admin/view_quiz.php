<?php require '../inc/config.php'; ?>
<?php require '../inc/connect_db.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>

<?php

    authorizePage("view_quiz");
    
    if (!isset($_GET['action'])) {
        header('Location: index.php');
    }

    switch ($_GET['action']) {
        case "add":
            $action = "save";
            break;
        case "view":
            $action = "update";
            break;
    }

    if ($action == "save" && !isset($_GET['id'])) {
        //in case add new quiz
        $quiz_id = 0;
        $quiz_code = "";
        $quiz_type = "";
        $limit_time = 0;
        $quiz_kind = "ratings";
    } else if ($action == "update" && isset($_GET['id'])) {
        //in case update quiz
        $quiz_id = $_GET['id'];
        $quiz = getQuiz($quiz_id);
        $quiz_code = $quiz['quiz_code'];
        $quiz_type = $quiz['quiz_type'];
        $limit_time = $quiz['limit_time'];
        $quiz_kind = $quiz['quiz_kind'];
    } else {
        header("Location: index.php");
    }
?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">

<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading font-w700 text-modern">
                View Quiz
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header bg-modern">
                    <h3 class="block-title text-white">Quiz Information</h3>
                </div>
                <div class="block-content block-content-narrow">
                    <form class="form-horizontal" name="quiz-form" id="quiz-form" action="save_quiz.php" method="post" autocomplete="off">
                        <input type="hidden" name="quiz-id" value="<?php echo $quiz_id; ?>" />
                        <input type="hidden" name="old-quiz-code" value="<?php echo $quiz_code; ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="quiz-code">Quiz Code<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" id="quiz-code" name="quiz-code" value="<?php echo $quiz_code; ?>" placeholder="Enter Quiz Code.." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="quiz-type">Quiz Type<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-control" name="quiz-type" id="quiz-type" require>
                                    <option value="untimed" <?php if ($quiz_type == "untimed") { ?>selected<?php } ?>>Untimed</option>
                                    <option value="timed" <?php if ($quiz_type == "timed") { ?>selected<?php } ?>>Timed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="limit-time">Limit Time<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-control" name="limit-time" id="limit-time" require>
                                    <option value="0" <?php if ($limit_time == 0) { ?>selected<?php } ?>>None</option>
                                    <option value="600" <?php if ($limit_time == 600) { ?>selected<?php } ?>>10 minutes</option>
                                    <option value="1200" <?php if ($limit_time == 1200) { ?>selected<?php } ?>>20 minutes</option>
                                    <option value="1800" <?php if ($limit_time == 1800) { ?>selected<?php } ?>>30 minutes</option>
                                    <option value="5400" <?php if ($limit_time == 5400) { ?>selected<?php } ?>>90 minutes</option>
                                    <option value="5700" <?php if ($limit_time == 5700) { ?>selected<?php } ?>>95 minutes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="quiz-kind">Quiz Kind<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-control" name="quiz-kind" id="quiz-kind" require>
                                    <option value="ratings" <?php if (empty($quiz_kind) || $quiz_kind == 'ratings') { ?>selected<?php } ?>>Ratings</option>
                                    <option value="selection" <?php if ($quiz_kind == 'selection') { ?>selected<?php } ?>>Selection</option>
                                    <option value="ranking" <?php if ($quiz_kind == 'ranking') { ?>selected<?php } ?>>Ranking</option>
                                    <option value="mini-mock" <?php if ($quiz_kind == 'mini-mock') { ?>selected<?php } ?>>Mini Mock</option>
                                    <option value="mock" <?php if ($quiz_kind == 'mock') { ?>selected<?php } ?>>Mock</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#confirm-save-modal">Save Quiz</button>
                                <button class="btn btn-sm btn-default" type="button" onclick="javascript:window.history.go(-1)">Back</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

<!-- Confirm Save Modal -->
<div class="modal fade" id="confirm-save-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Confirm</h3>
                </div>
                <div class="block-content">
                    <div class="text-center" style="margin-bottom: 20px;">
                        <i class="fa fa-question-circle"></i> Would you save this quiz really?
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">No</button>
                <button class="btn btn-sm btn-success" type="button" data-dismiss="modal" id="save-quiz"><i class="fa fa-check"></i> Yes, Save</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Save Modal -->

<?php require '../inc/views/base_footer.php'; ?>
<?php require '../inc/views/template_footer_start_admin.php'; ?>

<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        function checkQuizType() {
            let quiz_type = $("select#quiz-type").val();
            if (quiz_type == "untimed") {
                $("select#limit-time").val("0");
                $("select#limit-time").attr("disabled", true);
            } else {
                $("select#limit-time").attr("disabled", false);
            }
        }

        checkQuizType();

        $("select#quiz-type").change(function() {
            checkQuizType();
        });

        $("button#save-quiz").click(function() {
            $.ajax({
				url: "/apis/admin/save_quiz.php",
				dataType: "json",
				type: "post",
                data: new FormData(document.getElementById("quiz-form")),
                contentType: false,
                cache: false,
                processData:false,
				success: function( data ) {
                    if (!data.status) {
                        $.notify({
                            icon: 'fa fa-times' || '',
                            message: data.msg,
                            url: ''
                        },
                        {
                            element: 'body',
                            type: 'danger',
                            allow_dismiss: true,
                            newest_on_top: true,
                            showProgressbar: false,
                            placement: {
                                from: 'top',
                                align: 'center'
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1033,
                            delay: 5000,
                            timer: 1000,
                            animate: {
                                enter: 'animated fadeIn',
                                exit: 'animated fadeOutDown'
                            }
                        });
					} else {
                        $.notify({
                            icon: 'fa fa-check',
                            message: data.msg,
                            url: ''
                        },
                        {
                            element: 'body',
                            type: 'success',
                            allow_dismiss: true,
                            newest_on_top: true,
                            showProgressbar: false,
                            placement: {
                                from: 'top',
                                align: 'center' || 'right'
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1033,
                            delay: 5000,
                            timer: 1000,
                            animate: {
                                enter: 'animated fadeIn',
                                exit: 'animated fadeOutDown'
                            }
                        });
					}
				}
			});
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>