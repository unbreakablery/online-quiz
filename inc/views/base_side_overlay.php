<?php
/**
 * base_side_overlay.php
 *
 * Author: pixelcave
 *
 * The side overlay of each page (Backend)
 *
 */
?>
<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="side-header side-content">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default pull-right" type="button" data-toggle="layout" data-action="side_overlay_close">
                <i class="fa fa-times"></i>
            </button>
            <span>
                <?php $one->get_avatar('10', '', 32); ?>
                <span class="font-w600 push-10-l"><?php $one->get_name('male'); ?></span>
            </span>
        </div>
        <!-- END Side Header -->

        <!-- Side Content -->
        <div class="side-content remove-padding-t">
            <!-- Side Overlay Tabs -->
            <div class="block pull-r-l border-t">
                <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs">
                    <li class="active">
                        <a href="#tabs-side-overlay-overview"><i class="fa fa-fw fa-coffee"></i> Overview</a>
                    </li>
                    <li>
                        <a href="#tabs-side-overlay-sales"><i class="fa fa-fw fa-line-chart"></i> Sales</a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade fade-right in active" id="tabs-side-overlay-overview">
                        <!-- Activity -->
                        <div class="block pull-r-l">
                            <div class="block-header bg-gray-lighter">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Recent Activity</h3>
                            </div>
                            <div class="block-content">
                                <!-- Activity List -->
                                <ul class="list list-activity">
                                    <li>
                                        <i class="si si-wallet text-success"></i>
                                        <div class="font-w600">New sale ($15)</div>
                                        <div><a href="javascript:void(0)">Admin Template</a></div>
                                        <div><small class="text-muted">3 min ago</small></div>
                                    </li>
                                    <li>
                                        <i class="si si-pencil text-info"></i>
                                        <div class="font-w600">You edited the file</div>
                                        <div><a href="javascript:void(0)"><i class="fa fa-file-text-o"></i> Documentation.doc</a></div>
                                        <div><small class="text-muted">15 min ago</small></div>
                                    </li>
                                    <li>
                                        <i class="si si-close text-danger"></i>
                                        <div class="font-w600">Project deleted</div>
                                        <div><a href="javascript:void(0)">Line Icon Set</a></div>
                                        <div><small class="text-muted">4 hours ago</small></div>
                                    </li>
                                </ul>
                                <!-- END Activity List -->
                            </div>
                        </div>
                        <!-- END Activity -->

                        <!-- Online Friends -->
                        <div class="block pull-r-l">
                            <div class="block-header bg-gray-lighter">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Online Friends</h3>
                            </div>
                            <div class="block-content block-content-full">
                                <!-- Users Navigation -->
                                <ul class="nav-users remove-margin-b">
                                    <li>
                                        <a href="base_pages_profile.php">
                                            <?php $one->get_avatar('', 'female'); ?>
                                            <i class="fa fa-circle text-success"></i> <?php $one->get_name('female'); echo "\n"; ?>
                                            <div class="font-w400 text-muted"><small>Copywriter</small></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="base_pages_profile.php">
                                            <?php $one->get_avatar('', 'male'); ?>
                                            <i class="fa fa-circle text-success"></i> <?php $one->get_name('male'); echo "\n"; ?>
                                            <div class="font-w400 text-muted"><small>Web Developer</small></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="base_pages_profile.php">
                                            <?php $one->get_avatar('', 'female'); ?>
                                            <i class="fa fa-circle text-success"></i> <?php $one->get_name('female'); echo "\n"; ?>
                                            <div class="font-w400 text-muted"><small>Web Designer</small></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="base_pages_profile.php">
                                            <?php $one->get_avatar('', 'female'); ?>
                                            <i class="fa fa-circle text-warning"></i> <?php $one->get_name('female'); echo "\n"; ?>
                                            <div class="font-w400 text-muted"><small>Photographer</small></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="base_pages_profile.php">
                                            <?php $one->get_avatar('', 'male'); ?>
                                            <i class="fa fa-circle text-warning"></i> <?php $one->get_name('male'); echo "\n"; ?>
                                            <div class="font-w400 text-muted"><small>Graphic Designer</small></div>
                                        </a>
                                    </li>
                                </ul>
                                <!-- END Users Navigation -->
                            </div>
                        </div>
                        <!-- END Online Friends -->

                        <!-- Quick Settings -->
                        <div class="block pull-r-l">
                            <div class="block-header bg-gray-lighter">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Quick Settings</h3>
                            </div>
                            <div class="block-content">
                                <!-- Quick Settings Form -->
                                <form class="form-bordered" action="base_pages_dashboard.php" method="post" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <div class="font-s13 font-w600">Online Status</div>
                                                <div class="font-s13 font-w400 text-muted">Show your status to all</div>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label class="css-input switch switch-sm switch-primary push-10-t">
                                                    <input type="checkbox"><span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <div class="font-s13 font-w600">Auto Updates</div>
                                                <div class="font-s13 font-w400 text-muted">Keep up to date</div>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label class="css-input switch switch-sm switch-primary push-10-t">
                                                    <input type="checkbox"><span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <div class="font-s13 font-w600">Notifications</div>
                                                <div class="font-s13 font-w400 text-muted">Do you need them?</div>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label class="css-input switch switch-sm switch-primary push-10-t">
                                                    <input type="checkbox" checked><span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <div class="font-s13 font-w600">API Access</div>
                                                <div class="font-s13 font-w400 text-muted">Enable/Disable access</div>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label class="css-input switch switch-sm switch-primary push-10-t">
                                                    <input type="checkbox" checked><span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Quick Settings Form -->
                            </div>
                        </div>
                        <!-- END Quick Settings -->
                    </div>
                    <!-- END Overview Tab -->

                    <!-- Sales Tab -->
                    <div class="tab-pane fade fade-left" id="tabs-side-overlay-sales">
                        <div class="block pull-r-l">
                            <!-- Stats -->
                            <div class="block-content pull-t">
                                <div class="row items-push">
                                    <div class="col-xs-6">
                                        <div class="font-w700 text-gray-darker text-uppercase">Sales</div>
                                        <a class="h3 font-w300 text-primary" href="javascript:void(0)">22030</a>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="font-w700 text-gray-darker text-uppercase">Balance</div>
                                        <a class="h3 font-w300 text-primary" href="javascript:void(0)">$ 4.589,00</a>
                                    </div>
                                </div>
                            </div>
                            <!-- END Stats -->

                            <!-- Today -->
                            <div class="block-content block-content-full block-content-mini bg-gray-lighter">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="font-w600 font-s13 text-gray-darker text-uppercase">Today</span>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <span class="font-s13 text-muted">$996</span>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content">
                                <ul class="list list-activity pull-r-l">
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $249</div>
                                        <div><small class="text-muted">3 min ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $129</div>
                                        <div><small class="text-muted">50 min ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $119</div>
                                        <div><small class="text-muted">2 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $499</div>
                                        <div><small class="text-muted">3 hours ago</small></div>
                                    </li>
                                </ul>
                            </div>
                            <!-- END Today -->

                            <!-- Yesterday -->
                            <div class="block-content block-content-full block-content-mini bg-gray-lighter">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="font-w600 font-s13 text-gray-darker text-uppercase">Yesterday</span>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <span class="font-s13 text-muted">$765</span>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content">
                                <ul class="list list-activity pull-r-l">
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $249</div>
                                        <div><small class="text-muted">26 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-danger"></i>
                                        <div class="font-w600">Product Purchase - $50</div>
                                        <div><small class="text-muted">28 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $119</div>
                                        <div><small class="text-muted">29 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-danger"></i>
                                        <div class="font-w600">Paypal Withdrawal - $300</div>
                                        <div><small class="text-muted">37 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $129</div>
                                        <div><small class="text-muted">39 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $119</div>
                                        <div><small class="text-muted">45 hours ago</small></div>
                                    </li>
                                    <li>
                                        <i class="fa fa-circle text-success"></i>
                                        <div class="font-w600">New sale! + $499</div>
                                        <div><small class="text-muted">46 hours ago</small></div>
                                    </li>
                                </ul>
                            </div>
                            <!-- END Yesterday -->

                            <!-- More -->
                            <div class="text-center">
                                <small><a href="javascript:void(0)">Load More..</a></small>
                            </div>
                            <!-- END More -->
                        </div>
                    </div>
                    <!-- END Sales Tab -->
                </div>
            </div>
            <!-- END Side Overlay Tabs -->
        </div>
        <!-- END Side Content -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
<!-- END Side Overlay -->
