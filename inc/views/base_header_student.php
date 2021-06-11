<?php
/**
 * base_header.php
 *
 * Author: Chris
 *
 * The header of each page (Backend)
 *
 */
?>
<!-- Header -->
<header id="header-navbar" class="content-mini content-mini-full">
    <div class="navbar navbar-inverse col-md-offset-3" style="background-color: #fff; border-color: #fff;">
        <!-- Header logo -->
        <div class="navbar-header col-md-6 col-sm-6 col-xs-12">
            <a class="navbar-brand nav-top-link" href="#" style="padding: 0;">
                <img class="quiz-logo" src="<?php echo $one->assets_folder; ?>/img/photos/emedica_online_logo.jpg" title="logo" alt="logo" />
            </a>
        </div>
        <!-- Navbar Links -->
        <div class="collapse navbar-collapse col-md-6 col-sm-6 col-xs-12 text-center" style="float: right">
            <ul class="nav navbar-nav">
                <li class="dropdown full-width" style="margin: 0 5px;">
                    <a href="index.php" 
                        class="dropdown-toggle nav-top-link" 
                        data-toggle="dropdown"
                        style="background-color: #000">
                        <strong>SJT Section Homepage <i class="si si-home push-5-l"></i></strong>
                    </a>
                </li>
                <li class="dropdown full-width" style="margin: 0 5px;">
                    <a href="http://www.emedica.co.uk/amember/logout.php" 
                        class="dropdown-toggle nav-top-link" 
                        data-toggle="dropdown"
                        style="background-color: #46c37b">
                        <strong>Log out <i class="si si-logout push-5-l"></i></strong>
                    </a>
                </li>  
            </ul>
        </div>
    </div>
</header>
<!-- END Header -->