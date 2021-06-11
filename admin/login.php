<?php 
    //ob_start();
    
    $trackPage = "login";

    require '../inc/config.php';
    require '../inc/views/template_head_start.php';
    require '../inc/views/template_head_end.php';

    if (!empty($_POST)) {
        
        $password = $_POST['login-password'];

        if ($password == ADMIN_PASSWORD) {

            $_SESSION['user_id'] = 'admin';
            $_SESSION['username'] = 'admin'; //userid
            $_SESSION['role'] = 1;
            $_SESSION['user_f_name'] = '';
            $_SESSION['user_l_name'] = '';
            $tempNow = time();
            $_SESSION['expiretime'] = $tempNow + $session_timeout;

            //Remember Me
            if(isset($_POST['login-remember-me']) && $_POST['login-remember-me'] == "on") {
                setcookie("member_password", $password, $tempNow + (10 * 365 * 24 * 60 * 60));
            } else {
                if(isset($_COOKIE["member_password"])) {
                    setcookie("member_password", "");
                }
            }

            if (isset($_SESSION['redir']) && !empty($_SESSION['redir'])) {
                $tmpRedir = $_SESSION['redir'];
                unset($_SESSION['redir']);
                header('Location: ' . $tmpRedir);
                exit;
            } else {
                header('Location: ../admin/index.php');
                exit;
            }
        } else {
            $_SESSION['msg'] = "The credentials you entered are invalid.";
            header('Location: ../admin/login.php');
            exit;
        }
        
    } else {

        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            header('Location: ../admin/index.php');
            exit;
        }

        if (isset($_GET['redir'])) {
            $redir = urldecode($_GET['redir']);
        } else {
            $redir = '/admin/index.php';
        }

        $_SESSION['redir'] = $redir;
    }
?>

<!-- Login Content -->
<div class="content overflow-hidden">

    <!-- Notification Section -->
    <?php
        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
    ?>
    <div class="row">
        <!-- Info Alert -->
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><a class="alert-link" href="javascript:void(0)"><?php echo $_SESSION['msg']; ?></a></p>
            </div>
        </div>
        <!-- END Info Alert -->
    </div>
    <?php 
            unset($_SESSION['msg']);
        } 
    ?>
    <!-- End Notification Section -->
    
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <!-- Login Block -->
            <div class="block block-themed animated fadeIn">
                <div class="block-header bg-gray-darker">
                    <h3 class="block-title">Authenticate</h3>
                </div>
                <div class="block-content block-content-full block-content-narrow">
                    <!-- Login Title -->
                    <h1 class="h2 font-w700 push-30-t push-5"><?php echo $one->short_name; ?> Administrator</h1>
                    <p>Welcome, please enter admin password.</p>
                    <!-- END Login Title -->

                    <!-- Login Form -->
                    <!-- jQuery Validation (.js-validation-login class is initialized in js/pages/base_pages_login.js) -->
                    <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                    <form class="js-validation-login form-horizontal push-30-t push-50" action="" method="post">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" type="password" id="login-password" name="login-password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>">
                                    <label for="login-password">Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="css-input switch switch-sm switch-primary">
                                    <input type="checkbox" id="login-remember-me" name="login-remember-me" <?php if(isset($_COOKIE["member_password"])) { ?> checked <?php } ?>><span></span> Remember Me?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <button class="btn btn-block btn-primary" type="submit"><i class="si si-login pull-right"></i> Log in</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-8">
                                <button class="btn btn-block btn-success" type="button" id="btn-switch-pages"><i class="si si-directions pull-right"></i> Switch Pages</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Login Form -->
                </div>
            </div>
            <!-- END Login Block -->
        </div>
    </div>
</div>
<!-- END Login Content -->

<!-- Login Footer -->
<?php $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); ?>
<div class="push-10-t text-center animated fadeInUp">
    <small class="text-muted font-w600"><span class="js-year-copy"></span> &copy; <?php echo $one->name . ' ' . $one->version; ?></small>
</div>
<!-- END Login Footer -->

<?php require '../inc/views/template_footer_start.php'; ?>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/jquery-validation/jquery.validate.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_pages_login.js"></script>
<script>
    $(document).ready(function() {
        $("#btn-switch-pages").click(function() {
            window.location.href = "../index.php";
        });
    });
</script>

<?php require '../inc/views/template_footer_end.php'; ?>