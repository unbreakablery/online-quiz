<?php require '../inc/config.php'; ?>
<?php require '../inc/connect_db.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>

<?php

    authorizePage("eval_setting");

    if (isset($_POST['eval-id']) && $_POST['eval-id'] == 0) {
        //add new evaluation setting
        $from_value     = $_POST['from-value'];
        $to_value       = $_POST['to-value'];
        $feedback_text  = htmlspecialchars($_POST['feedback-text']);
        $feedback_class = $_POST['feedback-class'];
        $chart_color    = $_POST['chart-color'];
        $chart_class    = $_POST['chart-class'];
        
        $query = "
                    INSERT INTO eval_setting
                        (from_value, to_value, feedback_text, feedback_class, chart_color, chart_class)
                    VALUES
                        ($from_value, $to_value, '$feedback_text', '$feedback_class', '$chart_color', '$chart_class')
                ";
        $result = mysqli_query($connect, $query);

        if ($result) {
            $alert = array();
            $alert['type'] = "alert-success";
            $alert['msg'] = "Evaluation was added successfully!";
        } else {
            $alert = array();
            $alert['type'] = "alert-danger";
            $alert['msg'] = "Error occurs while query running!";
        }
    } else if (isset($_POST['eval-id']) && $_POST['eval-id'] != 0) {
        //update evaluation setting
        $id             = $_POST['eval-id'];
        $from_value     = $_POST['from-value'];
        $to_value       = $_POST['to-value'];
        $feedback_text  = htmlspecialchars($_POST['feedback-text']);
        $feedback_class = $_POST['feedback-class'];
        $chart_color    = $_POST['chart-color'];
        $chart_class    = $_POST['chart-class'];

        $query = "
                    UPDATE eval_setting
                    SET
                        from_value = $from_value,
                        to_value = $to_value,
                        feedback_text = '$feedback_text',
                        feedback_class = '$feedback_class',
                        chart_color = '$chart_color',
                        chart_class = '$chart_class'
                    WHERE
                        id = $id
                ";
        $result = mysqli_query($connect, $query);

        if ($result) {
            $alert = array();
            $alert['type'] = "alert-success";
            $alert['msg'] = "Evaluation was updated successfully!";
        } else {
            $alert = array();
            $alert['type'] = "alert-danger";
            $alert['msg'] = "Error occurs while query running!";
        }
    }
    
    //Get setting data from db.
    $evaluations = getEvaluations();
?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-8">
            <h1 class="page-heading font-w700 text-default">
                Evaluation Setting
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <?php if (isset($alert)) { ?>
    <div class="row" id="alert-section">
        <div class="col-sm-12 col-lg-12">
            <div class="alert <?php echo $alert['type']; ?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p><strong><?php echo $alert['msg']; ?></strong></p>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="block block-themed">
        <div class="block-header bg-primary">
            <ul class="block-options">
                <li>
                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                </li>
            </ul>
            <h3 class="block-title">Add Evaluation Setting</h3>
        </div>
        <div class="block-content block-content-narrow">
            <!-- Input Form -->
            <form class="form-horizontal" method="post" id="evaluation-form" name="evaluation-form" autocomplete="off">
                <input type="hidden" id="eval-id" name="eval-id" value="" />
                <div class="form-group">
                    <label class="col-md-5 control-label" for="from-value">From Value <span class="text-danger">*</span> : </label>
                    <div class="col-md-5">
                        <input type="number" class="form-control" id="from-value" name="from-value" min="0" max="100" value="0" require>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5 control-label" for="to-value">To Value <span class="text-danger">*</span> : </label>
                    <div class="col-md-5">
                        <input type="number" class="form-control" id="to-value" name="to-value" min="0" max="100" value="0" require>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5 control-label" for="feedback-text">Feedback Text <span class="text-danger">*</span> : </label>
                    <div class="col-md-5">
                        <textarea class="form-control" id="feedback-text" name="feedback-text" rows="2" placeholder="Content.." spellcheck="false"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5 control-label" for="feedback-class">Feedback Class <span class="text-danger">*</span> : </label>
                    <div class="col-md-5">
                        <select class="form-control" name="feedback-class" id="feedback-class" require>
                            <option value="alert-success">Success</option>
                            <option value="alert-info">Info</option>
                            <option value="alert-warning">Warning</option>
                            <option value="alert-danger">Danger</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5 control-label" for="chart-color">Chart Color <span class="text-danger">*</span> : </label>
                    <div class="col-md-5">
                        <div class="js-colorpicker input-group colorpicker-element">
                            <input class="form-control" type="text" id="chart-color" name="chart-color" value="#5c90d2">
                            <span class="input-group-addon"><i style="background-color: rgb(85, 204, 153);" id="i-chart-color"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5 control-label" for="chart-class">Chart Class <span class="text-danger">*</span> : </label>
                    <div class="col-md-5">
                        <select class="form-control" name="chart-class" id="chart-class" require>
                            <option value="text-success">Success</option>
                            <option value="text-info">Info</option>
                            <option value="text-warning">Warning</option>
                            <option value="text-danger">Danger</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-5">
                        <button class="btn btn-primary save-eval" type="submit"><i class="fa fa-save"></i> Save</button>
                        <button class="btn btn-default reset-eval" type="button"><i class="fa fa-undo"></i> Reset</button>
                    </div>
                </div>
            </form>
            <!-- END Input Form -->
        </div>
    </div>

    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-content">
            <table class="table table-striped table-vcenter" id="eval-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 10%;">From Value</th>
                        <th class="text-center" style="width: 10%;">To Value</th>
                        <th class="text-center">Feedback Text</th>
                        <th class="text-center hidden-xs hidden-sm" style="width: 15%">Feedback Class</th>
                        <th class="text-center hidden-xs hidden-sm" style="width: 15%;">Chart Color</th>
                        <th class="text-center hidden-xs hidden-sm" style="width: 15%;">Chart Class</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($evaluations as $eval) { ?>
                    <tr>
                        <td class="text-right">
                            <?php echo $eval['from_value']; ?>
                        </td>
                        <td class="text-right">
                            <?php echo $eval['to_value']; ?>
                        </td>
                        <td class="text-left <?php echo $eval['feedback_class']; ?>">
                            <?php echo $eval['feedback_text']; ?>
                        </td>
                        <td class="hidden-xs hidden-sm">
                            <?php echo $eval['feedback_class']; ?>
                        </td>
                        <td class="text-center hidden-xs hidden-sm" style="color: <?php echo $eval['chart_color']; ?>;">
                            <?php echo $eval['chart_color']; ?>
                        </td>
                        <td class="hidden-xs hidden-sm">
                            <?php echo $eval['chart_class']; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-xs btn-default edit-eval" type="button" data-toggle="tooltip" title="Edit Client" data-id="<?php echo $eval['id']; ?>"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-xs btn-default remove-eval" type="button" data-toggle="tooltip" title="Remove Client" data-id="<?php echo $eval['id']; ?>"><i class="fa fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Dynamic Table Full -->

</div>
<!-- END Page Content -->

<?php require '../inc/views/base_footer.php'; ?>
<?php require '../inc/views/template_footer_start_admin.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- Page JS Code -->
<script type="text/javascript">
    $(document).ready(function() {
        App.initHelpers(['colorpicker']);

        setTimeout(() => {
            $("#alert-section").fadeOut(1000, function(){ $(this).remove();});
        }, 2000);
        
        $(document).on("click", ".edit-eval", function() {
            let id = $(this).data("id");
            let obj = $(this).closest("tr");
            $("input#eval-id").val(id);
            $("#from-value").val($(obj).find("td").eq(0).text().trim());
            $("#to-value").val($(obj).find("td").eq(1).text().trim());
            $("#feedback-text").val($(obj).find("td").eq(2).text().trim());
            $("#feedback-class").val($(obj).find("td").eq(3).text().trim());
            $("#chart-color").val($(obj).find("td").eq(4).text().trim());
            $("#i-chart-color").css("background-color", $(obj).find("td").eq(4).text().trim());
            $("#chart-class").val($(obj).find("td").eq(5).text().trim());
        });

        $(document).on("click", ".remove-eval", function() {
            let id = $(this).data("id");
            let target = $(this).closest("tr");
            $.ajax({
				url: "/apis/admin/remove_eval.php",
				dataType: "json",
				type: "post",
                data: {
                    'id': id
                },
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
                        $(target).remove();
					}
				}
			});
        });

        $("button.reset-eval").click(function() {
            $("input#eval-id").val("");
            $("#from-value").val(0);
            $("#to-value").val(0);
            $("#feedback-text").val("");
            $("#feedback-class").val("alert-success");
            $("#chart-color").val("#5c90d2");
            $("#i-chart-color").css("background-color", "rgb(92, 144, 210)");
            $("#chart-class").val("text-success");
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>