<?php require '../inc/config.php'; ?>
<?php require '../inc/connect_db.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>

<?php

    authorizePage("import_quizzes");
    
    //Get quizzes data from db.
    $quizzes = getQuizzes();

?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">

<?php require '../inc/views/template_head_end.php'; ?>

<!-- Page loader (functionality is initialized in App() -> uiLoader()) -->
<!-- If markup is added, the loading screen will be enabled and auto hide once the page loads -->
<div id="page-loader"></div>

<?php require '../inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-8">
            <h1 class="page-heading font-w700 text-default">
                Import Quizzes
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <!-- Warning Alert -->
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><i class='fa fa-warning'></i> When you import, all quiz data will be updated.</p>
        </div>
        <!-- END Warning Alert -->
    </div>
    <div class="block block-themed">
        <div class="block-header bg-primary">
            <ul class="block-options">
                <li>
                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                </li>
            </ul>
            <h3 class="block-title">Import quizzes data from excel file</h3>
        </div>
        <div class="block-content block-content-narrow">
            <!-- Upload Form -->
            <form class="form-horizontal" method="post" id="import_form" name="import_form" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-md-5 control-label" for="">Choose File: </label>
                    <div class="col-md-5">
                        <input type="file" id="source_file" name="source_file" style="margin: 5px 0;" accept=".xlsx,.xls">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-5">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-upload"></i> Import Quizzes</button>
                        <button class="btn btn-danger" type="button" id="remove-quizzes" data-toggle="tooltip" data-placement="bottom" data-original-title="All quizzes and questions will be removed."><i class="fa fa-trash-o"></i> Remove Quizzes</button>
                    </div>
                </div>
            </form>
            <!-- END Upload Form -->
        </div>
    </div>

    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-content">
            <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                <table class="table table-bordered table-striped table-header-bg" id="quizzesTable">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Quiz Code</th>
                            <th class="text-center">Count Of Questions</th>
                            <th class="text-center">Quiz Type</th>
                            <th class="text-center">Limit Time</th>
                            <th class="text-center">Quiz Kind</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($quizzes as $idx => $quiz) { ?>
                            <tr>
                                <td class="text-center"><?php echo $idx + 1; ?></td>
                                <td class="text-center"><?php echo $quiz['quiz_code']; ?></td>
                                <td class="text-center"><?php echo $quiz['cnt_que']; ?></td>
                                <td class="text-center"><?php echo ucfirst($quiz['quiz_type']); ?></td>
                                <td class="text-center"><?php echo ($quiz['limit_time'] > 0) ? ($quiz['limit_time'] / 60) . ' minutes' : ''; ?></td>
                                <td class="text-center"><?php echo $quiz['quiz_kind']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->

</div>
<!-- END Page Content -->

<!-- Require File Modal -->
<div class="modal fade" id="require-file-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Note</h3>
                </div>
                <div class="block-content">
                    <div class="text-center" style="margin-bottom: 20px;">
                        You must select an excel source file to import !
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- END Require File Modal -->

<!-- Confirm Import Modal -->
<div class="modal fade" id="confirm-import-modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        Would you import data from this excel file really?
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">No</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" id="import-yes-button"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Import Modal -->

<?php require '../inc/views/base_footer.php'; ?>
<?php require '../inc/views/template_footer_start_admin.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_tables_datatables.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#quizzesTable').DataTable({
                responsive: true,
                ordering:  false,
                columnDefs: [
                    { targets: [0], "width": "10%"},
                    { targets: [1], "width": "20%"},
                    { targets: [2], "width": "20%"},
                    { targets: [3], "width": "15%"},
                    { targets: [4], "width": "20%"},
                    { targets: [5], "width": "15%"},
                    { targets: '_all', className: 'text-center' }
                    ]
            });

        $("#import_form").submit(function(e) {
            if(document.getElementById("source_file").files.length == 0) {
                $("#require-file-modal").modal('show');
                return false;
            }
            
            $("#confirm-import-modal").modal('show');
            e.preventDefault();

        });

        $('#import-yes-button').click(function() {
            App.loader('show');
            $.ajax({
				url: "/apis/admin/quizzes.php",
				dataType: "json",
				type: "post",
                data: new FormData(document.getElementById("import_form")),
                contentType: false,
                cache: false,
                processData:false,
				success: function( data ) {
                    App.loader('hide');
                    if (!data.status) {
                        $.notify({
                            icon: 'fa fa-times' || '',
                            message: data.msg + "<br/> SQL: " + data.error_query,
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

                        var header_data = [];
                        for(i = 0; i < data.table_header.length; i++) {
                            header_data.push({title: data.table_header[i]});
                        }
                        
                        table.destroy();
                        $('#quizzesTable').empty();
                
                        table = $('#quizzesTable').DataTable( {
                            responsive: true,
                            columns: header_data,
                            data: data.table_data,
                            ordering:  false,
                            columnDefs: [
                                { targets: [0], "width": "10%"},
                                { targets: [1], "width": "20%"},
                                { targets: [2], "width": "20%"},
                                { targets: [3], "width": "15%"},
                                { targets: [4], "width": "20%"},
                                { targets: [5], "width": "15%"},
                                { targets: '_all', className: 'text-center' }
                                ]
                        } );
					}
				}
			});
        });

        $("#remove-quizzes").click(function() {
            window.location.href = "remove_all.php";
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>