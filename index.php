<?php 
    ob_start();

    $trackPage = "first";
    
    require 'inc/config.php';
    require 'inc/views/template_head_start.php';
    require 'inc/views/template_head_end.php';
?>

<!-- First Page Content -->
<div class="content overflow-hidden">
    
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            
            <div class="block block-themed animated fadeIn">
                <div class="block-header bg-gray-darker">
                    <h3 class="block-title">Who are you?</h3>
                </div>
                <div class="block-content block-content-full block-content-narrow">
                    
                    <h1 class="h2 font-w700 push-30-t push-5"><?php echo $one->name; ?></h1>
                    <p>Welcome, please make your choice.</p>
                    
                    <div class="row">
                        <a class="block block-rounded block-link-hover3" href="student/index.php">
                            <div class="block-content block-content-full">
                                <div class="h3 font-w700 text-default push-5 push-50-l"><i class="si si-user"></i> Student</div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="row">
                        <a class="block block-rounded block-link-hover3" href="admin/index.php">
                            <div class="block-content block-content-full">
                                <div class="h3 font-w700 text-flat push-5 push-50-l"><i class="fa fa-user-circle"></i> Administrator</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- END First Page Content -->

<!-- Footer -->
<?php $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); ?>
<div class="push-10-t text-center animated fadeInUp">
    <small class="text-muted font-w600"><span class="js-year-copy"></span> &copy; <?php echo $one->name . ' ' . $one->version; ?></small>
</div>
<!-- END Footer -->

<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>