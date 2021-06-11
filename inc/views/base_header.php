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
    <!-- Header Navigation Right -->
    <ul class="nav-header pull-right">
        <li>
            <div class="btn-group">
                
                <ul class="nav nav-pills push">
                    <li class="active">
                        <a tabindex="-1" href="logout.php" id="logout">
                            Log out <i class="si si-logout push-5-l"></i>
                        </a>
                    </li>
                </ul>
                
            </div>
        </li>
    </ul>

    <ul class="nav-header pull-right">
        <li>
            <div class="btn-group">
                
                <ul class="nav nav-pills push">
                    <li class="active">
                        <a tabindex="-1" href="index.php" id="index" style="background-color: #46c37b;">
                            Home <i class="si si-home push-5-l"></i>
                        </a>
                    </li>
                </ul>
                
            </div>
        </li>
    </ul>
    <!-- END Header Navigation Right -->

    <!-- Header Navigation Left -->
    <ul class="nav-header pull-left">
        <li class="hidden-md hidden-lg">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                <i class="fa fa-navicon"></i>
            </button>
        </li>
        <li class="hidden-xs hidden-sm">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                <i class="fa fa-ellipsis-v"></i>
            </button>
        </li>
        <li class="visible-xs">
            <!-- Toggle class helper (for .js-header-search below), functionality initialized in App() -> uiToggleClass() -->
            <button class="btn btn-default" data-toggle="class-toggle" data-target=".js-header-search" data-class="header-search-xs-visible" type="button">
                <i class="fa fa-search"></i>
            </button>
        </li>
    </ul>
    <!-- END Header Navigation Left -->
</header>
<!-- END Header -->