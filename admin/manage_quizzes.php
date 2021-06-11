<?php require '../inc/config.php'; ?>
<?php require '../inc/connect_db.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>

<?php

    authorizePage("manage_quizzes");
    
    //Get quizzes data from db.
    $quizzes = getQuizzes();

?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">

<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading font-w700 text-smooth">
                Manage Quizzes
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Simple -->
    <div class="block">
        <div class="block-header bg-smooth">
            <h3 class="block-title text-white">Quizzes List</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive push text-right">
                <button type="button" class="btn btn-dark" id="add-quiz">
                    <i class="fa fa-plus"></i> Add Quiz
                </button>
            </div>
            <!-- DataTables init on table by adding .js-dataTable-simple class, functionality initialized in js/pages/base_tables_datatables.js -->
            <table class="table table-bordered table-striped js-dataTable-simple">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th>Quiz Code</th>
                        <th class="hidden-xs">Number Of Questions</th>
                        <th class="hidden-xs">Quiz Type</th>
                        <th class="hidden-xs" style="width: 15%;">Limit Time</th>
                        <th class="text-center" style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($quizzes); $i++) { ?>
                    <tr>
                        <td class="text-center"><?php echo $i + 1; ?></td>
                        <td class="font-w600"><?php echo $quizzes[$i]['quiz_code']; ?></td>
                        <td class="hidden-xs">Questions: <?php echo $quizzes[$i]['cnt_que']; ?></td>
                        <td class="hidden-xs">
                            <?php if ($quizzes[$i]['quiz_type'] == "timed") { ?>
                                <span class="label label-danger">
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo $quizzes[$i]['quiz_type']; ?>
                                </span>
                            <?php } else { ?>
                                <span class="label label-success">
                                    <?php echo $quizzes[$i]['quiz_type']; ?>
                                </span>
                            <?php } ?>
                        </td>
                        <td class="hidden-xs"><?php echo getYourTime($quizzes[$i]['limit_time']); ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-xs btn-default edit-quiz" type="button" data-toggle="tooltip" title="Edit Quiz" data-id="<?php echo $quizzes[$i]['id']; ?>"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-xs btn-default remove-quiz" type="button" data-toggle="tooltip" title="Remove Quiz"  data-id="<?php echo $quizzes[$i]['id']; ?>"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Dynamic Table Simple -->
</div>
<!-- END Page Content -->

<!-- Confirm Remove Modal -->
<div class="modal fade" id="confirm-remove-modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <div class="text-center text-danger" style="margin-bottom: 20px;">
                        <strong>
                            <i class="fa fa-warning"></i> Would you remove this quiz really? All questions belong to this quiz will be removed!
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">No</button>
                <button class="btn btn-sm btn-success" type="button" data-dismiss="modal" id="remove-quiz"><i class="fa fa-check"></i> Yes, Remove</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Remove Modal -->

<?php require '../inc/views/base_footer.php'; ?>
<?php require '../inc/views/template_footer_start_admin.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // DataTables Bootstrap integration
        var bsDataTables = function() {
            var $DataTable = jQuery.fn.dataTable;

            // Set the defaults for DataTables init
            jQuery.extend( true, $DataTable.defaults, {
                dom:
                    "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-6'i><'col-sm-6'p>>",
                renderer: 'bootstrap',
                oLanguage: {
                    sLengthMenu: "_MENU_",
                    sInfo: "Showing <strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left"></i>',
                        sNext: '<i class="fa fa-angle-right"></i>'
                    }
                }
            });

            // Default class modification
            jQuery.extend($DataTable.ext.classes, {
                sWrapper: "dataTables_wrapper form-inline dt-bootstrap",
                sFilterInput: "form-control",
                sLengthSelect: "form-control"
            });

            // Bootstrap paging button renderer
            $DataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
                var api     = new $DataTable.Api(settings);
                var classes = settings.oClasses;
                var lang    = settings.oLanguage.oPaginate;
                var btnDisplay, btnClass;

                var attach = function (container, buttons) {
                    var i, ien, node, button;
                    var clickHandler = function (e) {
                        e.preventDefault();
                        if (!jQuery(e.currentTarget).hasClass('disabled')) {
                            api.page(e.data.action).draw(false);
                        }
                    };

                    for (i = 0, ien = buttons.length; i < ien; i++) {
                        button = buttons[i];

                        if (jQuery.isArray(button)) {
                            attach(container, button);
                        }
                        else {
                            btnDisplay = '';
                            btnClass = '';

                            switch (button) {
                                case 'ellipsis':
                                    btnDisplay = '&hellip;';
                                    btnClass = 'disabled';
                                    break;

                                case 'first':
                                    btnDisplay = lang.sFirst;
                                    btnClass = button + (page > 0 ? '' : ' disabled');
                                    break;

                                case 'previous':
                                    btnDisplay = lang.sPrevious;
                                    btnClass = button + (page > 0 ? '' : ' disabled');
                                    break;

                                case 'next':
                                    btnDisplay = lang.sNext;
                                    btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                    break;

                                case 'last':
                                    btnDisplay = lang.sLast;
                                    btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                    break;

                                default:
                                    btnDisplay = button + 1;
                                    btnClass = page === button ?
                                            'active' : '';
                                    break;
                            }

                            if (btnDisplay) {
                                node = jQuery('<li>', {
                                    'class': classes.sPageButton + ' ' + btnClass,
                                    'aria-controls': settings.sTableId,
                                    'tabindex': settings.iTabIndex,
                                    'id': idx === 0 && typeof button === 'string' ?
                                            settings.sTableId + '_' + button :
                                            null
                                })
                                .append(jQuery('<a>', {
                                        'href': '#'
                                    })
                                    .html(btnDisplay)
                                )
                                .appendTo(container);

                                settings.oApi._fnBindAction(
                                    node, {action: button}, clickHandler
                                );
                            }
                        }
                    }
                };

                attach(
                    jQuery(host).empty().html('<ul class="pagination"/>').children('ul'),
                    buttons
                );
            };

            // TableTools Bootstrap compatibility - Required TableTools 2.1+
            if ($DataTable.TableTools) {
                // Set the classes that TableTools uses to something suitable for Bootstrap
                jQuery.extend(true, $DataTable.TableTools.classes, {
                    "container": "DTTT btn-group",
                    "buttons": {
                        "normal": "btn btn-default",
                        "disabled": "disabled"
                    },
                    "collection": {
                        "container": "DTTT_dropdown dropdown-menu",
                        "buttons": {
                            "normal": "",
                            "disabled": "disabled"
                        }
                    },
                    "print": {
                        "info": "DTTT_print_info"
                    },
                    "select": {
                        "row": "active"
                    }
                });

                // Have the collection use a bootstrap compatible drop down
                jQuery.extend(true, $DataTable.TableTools.DEFAULTS.oTags, {
                    "collection": {
                        "container": "ul",
                        "button": "li",
                        "liner": "a"
                    }
                });
            }
        };

        bsDataTables();
        
        let oTable = $('.js-dataTable-simple').dataTable({
            columnDefs: [ { orderable: false, targets: [ 5 ] } ],
            pageLength: 10,
            lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
            // searching: false,
            // oLanguage: {
            //     sLengthMenu: ""
            // },
            // dom:
            //     "<'row'<'col-sm-12'tr>>" +
            //     "<'row'<'col-sm-6'i><'col-sm-6'p>>"
        });

        let remove_quiz_id = 0;
        
        $(document).on("click", "button.edit-quiz", function() {
            let quiz_id = $(this).data('id');
            window.location.href = "view_quiz.php?id=" + quiz_id + "&action=view";
        });

        $(document).on("click", "button.remove-quiz", function() {
            remove_quiz_id = $(this).data('id');
            $("#confirm-remove-modal").modal('show');
        });

        $("button#add-quiz").click(function() {
            window.location.href = "view_quiz.php?action=add";
        });

        $("button#remove-quiz").click(function() {
            $.ajax({
				url: "/apis/admin/remove_quiz.php",
				dataType: "json",
				type: "post",
                data: {
                        'quiz-id': remove_quiz_id
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
                            },
                            onClosed: function() {
                                window.location.href = "manage_quizzes.php";
                            }
                        });
					}
				}
			});
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>