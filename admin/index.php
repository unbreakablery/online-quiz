<?php require '../inc/config.php'; ?>
<?php require '../inc/views/template_head_start.php'; ?>
<?php require '../inc/views/template_head_end.php'; ?>
<?php require '../inc/views/base_head.php'; ?>

<?php

    authorizePage("admin_index");

    if(!isset($role) || empty($role) || $role == "") {
        authenticate();
    }

?>

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo17@2x.jpg');">
    <div class="row items-push">
        <div class="push-25-t push-15">
            <h1 class="h2 text-white animated zoomIn font-w700">Dashboard</h1>
            <h2 class="h5 text-white-op animated zoomIn font-w700">Welcome Administrator !</h2>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-boxed">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <a class="block block-rounded block-link-hover3 text-center" href="import_quizzes.php">
                <div class="block-content block-content-full bg-default">
                    <div class="item item-circle bg-crystal-op">
                        <i class="fa fa-upload text-white-op"></i>
                    </div>
                    <div class="h3 font-w700 text-white push-5 push-10-t">Import</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <a class="block block-rounded block-link-hover3 text-center" href="manage_quizzes.php">
                <div class="block-content block-content-full bg-smooth">
                    <div class="item item-circle bg-crystal-op">
                        <i class="fa fa-edit text-white-op"></i>
                    </div>
                    <div class="h3 font-w700 text-white push-5 push-10-t">Quizzes</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <a class="block block-rounded block-link-hover3 text-center" href="que_list.php">
                <div class="block-content block-content-full bg-modern">
                    <div class="item item-circle bg-crystal-op">
                        <i class="fa fa-file text-white-op"></i>
                    </div>
                    <div class="h3 font-w700 text-white push-5 push-10-t">Questions</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <a class="block block-rounded block-link-hover3 text-center" href="eval_setting.php">
                <div class="block-content block-content-full bg-success">
                    <div class="item item-circle bg-crystal-op">
                        <i class="fa fa-cogs text-white-op"></i>
                    </div>
                    <div class="h3 font-w700 text-white push-5 push-10-t">Evaluation Setting</div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require '../inc/views/base_footer.php'; ?>
<?php require '../inc/views/template_footer_start.php'; ?>
<?php require '../inc/views/template_footer_end.php'; ?>