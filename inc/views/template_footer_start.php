<?php
/**
 * template_footer_start.php
 *
 * Author: pixelcave
 *
 * All vital JS scripts are included here
 *
 */
?>

<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/bootstrap.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.slimscroll.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.scrollLock.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.appear.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.countTo.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.placeholder.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery-ui.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/core/js.cookie.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/app.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("header .nav-top-link").click(function() {
            window.location.href = $(this).attr("href");
        });

        $("#logout").click(function() {
            window.localStorage.removeItem('');
        });
    });
</script>
