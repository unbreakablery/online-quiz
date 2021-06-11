<?php require '../inc/config.php'; ?>
<?php require '../inc/connect_db.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>

<?php

    authorizePage("view_que");
    
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
        $id         = 0;
        $quiz_id    = 0;
        $que_id     = 0;
        $que_type   = "";
        $que_text   = "";
        $ans_1      = "";
        $ans_2      = "";
        $ans_3      = "";
        $ans_4      = "";
        $ans_5      = "";
        $ans_6      = "";
        $ans_7      = "";
        $ans_8      = "";
        $cor_ans    = "";
        $cor_fb     = "";
        $inc_fb     = "";
        $points     = 0;
       
        $quizzes    = getQuizzes();
    } else if ($action == "update" && isset($_GET['id'])) {
        $id = $_GET['id'];
        $cur_que = getQuestion($id);

        $quiz_id    = $cur_que['quiz_id'];
        $que_id     = $cur_que['que_id'];
        $que_type   = $cur_que['que_type'];
        $que_text   = $cur_que['que_text'];
        $ans_1      = $cur_que['ans_1'];
        $ans_2      = $cur_que['ans_2'];
        $ans_3      = $cur_que['ans_3'];
        $ans_4      = $cur_que['ans_4'];
        $ans_5      = $cur_que['ans_5'];
        $ans_6      = $cur_que['ans_6'];
        $ans_7      = $cur_que['ans_7'];
        $ans_8      = $cur_que['ans_8'];
        $cor_ans    = $cur_que['cor_ans'];
        $cor_fb     = $cur_que['cor_fb'];
        $inc_fb     = $cur_que['inc_fb'];
        $points     = $cur_que['points'];

        $quizzes    = getQuizzes();
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
                View Question
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
                    <h3 class="block-title text-white">Question Information</h3>
                </div>
                <div class="block-content block-content-narrow">
                    <form class="form-horizontal" name="que-form" id="que-form" action="" method="post" autocomplete="off">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="que-id" value="<?php echo $que_id; ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="quiz-id">Quiz Code<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-control" name="quiz-id" id="quiz-id" require>
                                    <?php foreach ($quizzes as $quiz) { ?>
                                        <option value="<?php echo $quiz['id']?>" <?php if ($quiz['id'] == $quiz_id) { ?>selected<?php } ?>><?php echo $quiz['quiz_code']; ?> (<?php echo $quiz['quiz_type']; ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="que-type">Question Type<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-control" name="que-type" id="que-type" require>
                                    <option value="SEQ" <?php if ($que_type == "SEQ") { ?>selected<?php } ?>>SEQ (Ranked Questions)</option>
                                    <option value="MR" <?php if ($que_type == "MR") { ?>selected<?php } ?>>MR (Multiple Chices)</option>
                                    <option value="MC" <?php if ($que_type == "MC") { ?>selected<?php } ?>>MC (Ratings)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="que-text">Question Text<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <textarea class="form-control" id="que-text" name="que-text" rows="6" placeholder="Content.." spellcheck="false"><?php echo trim($que_text); ?></textarea>
                            </div>
                        </div>
                        <?php for ($i = 1; $i <= 8; $i++) { ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="ans-<?php echo $i; ?>">Answer <?php echo $i; ?> - <span class="text-danger">(<?php echo chr(65 + $i - 1); ?>)</span></label>
                            <div class="col-md-7">
                                <textarea class="form-control" id="ans-<?php echo $i; ?>" name="ans-<?php echo $i; ?>" rows="2" placeholder="Content.." spellcheck="false"><?php echo trim(${'ans_' . $i}); ?></textarea>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="cor-ans">Correct Answers<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" id="cor-ans" name="cor-ans" value="<?php echo $cor_ans; ?>" placeholder="Enter Correct Answers like DCBAE .." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="cor-fb">Correct Feedback<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <textarea class="form-control" id="cor-fb" name="cor-fb" rows="6" placeholder="Content.." spellcheck="false"><?php echo trim($cor_fb); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inc-fb">Incorrect Feedback<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <textarea class="form-control" id="inc-fb" name="inc-fb" rows="6" placeholder="Content.." spellcheck="false"><?php echo trim($inc_fb); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="points">Points</label>
                            <div class="col-md-7">
                                <input class="form-control" type="number" id="points" name="points" value="<?php echo $points; ?>" placeholder="Enter Points for marked .." min="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#confirm-save-modal">Save Question</button>
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
                        <i class="fa fa-question-circle"></i> Would you save this question really?
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">No</button>
                <button class="btn btn-sm btn-success" type="button" data-dismiss="modal" id="save-que"><i class="fa fa-check"></i> Yes, Save</button>
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
        $("button#save-que").click(function() {
            $.ajax({
				url: "/apis/admin/save_que.php",
				dataType: "json",
				type: "post",
                data: new FormData(document.getElementById("que-form")),
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