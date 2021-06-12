<?php
/**
 * config.php
 *
 * Author: Chris
 *
 * Global configuration file
 *
 */

session_start();
ob_start();

define("ADMIN_PASSWORD", "1q2w3e4r5t");

function authenticate() {
    global $trackPage;
    if ($trackPage === "logout") {
        header('Location: ../admin/login.php');
    } else {
        header('Location: ../admin/login.php?redir=' . urlencode($_SERVER['REQUEST_URI']));
    }
    
    exit;
}

if(!isset($trackPage) || $trackPage != "first") {

    $username = "";

    if (!isset($trackPage)) {
        $trackPage = "";
    }

    $session_timeout = 60 * 20;

    if(!$trackPage == 'login' && !$trackPage == 'register') {
        if(isset($_SESSION['expiretime'])) {
            if($_SESSION['expiretime'] < time()) {
                session_destroy();
                authenticate();
            }
            else {
                $_SESSION['expiretime'] = time() + $session_timeout;
            }
        } else {
            session_destroy();
            authenticate();
        }

        if (!isset($_SESSION['username'])) {
            authenticate();
        } else {
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
        }
        
    }
}

// Include Template class
require 'classes/Template.php';

// Create a new Template Object
$one                               = new Template('Emedica SJT Quiz', 'ESQ', 'V3.0', '../assets'); // Name, short_name, version and assets folder's name

// Global Meta Data
$one->author                       = 'Christopher Horn';
$one->robots                       = 'noindex, nofollow';
$one->title                        = 'Emedica SJT Quiz';
$one->description                  = 'Emedica SJT Quiz created by Chris';

// Global Included Files (eg useful for adding different sidebars or headers per page)
// $one->inc_side_overlay             = 'base_side_overlay.php';
$one->inc_sidebar                  = 'base_sidebar.php';
$one->inc_header                   = 'base_header.php';

// Global Color Theme
$one->theme                        = '';       // '' for default theme or 'amethyst', 'city', 'flat', 'modern', 'smooth'

// Global Cookies
$one->cookies                      = false;    // True: Remembers active color theme between pages (when set through color theme list), False: Disables cookies

// Global Body Background Image
$one->body_bg                      = '';       // eg 'assets/img/photos/photo10@2x.jpg' Useful for login/lockscreen pages

// Global Header Options
$one->l_header_fixed               = true;     // True: Fixed Header, False: Static Header

// Global Sidebar Options
$one->l_sidebar_position           = 'left';   // 'left': Left Sidebar and right Side Overlay, 'right': Flipped position
$one->l_sidebar_mini               = false;    // True: Mini Sidebar Mode (> 991px), False: Disable mini mode
$one->l_sidebar_visible_desktop    = true;     // True: Visible Sidebar (> 991px), False: Hidden Sidebar (> 991px)
$one->l_sidebar_visible_mobile     = false;    // True: Visible Sidebar (< 992px), False: Hidden Sidebar (< 992px)

// Global Side Overlay Options
$one->l_side_overlay_hoverable     = false;    // True: Side Overlay hover mode (> 991px), False: Disable hover mode
$one->l_side_overlay_visible       = false;    // True: Visible Side Overlay, False: Hidden Side Overlay

// Global Sidebar and Side Overlay Custom Scrolling
$one->l_side_scroll                = true;     // True: Enable custom scrolling (> 991px), False: Disable it (native scrolling)

// Global Active Page (it will get compared with the url of each menu link to make the link active and set up main menu accordingly)
$one->main_nav_active              = basename($_SERVER['PHP_SELF']);

// Google Maps API Key (you will have to obtain a Google Maps API key to use Google Maps, for more info please have a look at https://developers.google.com/maps/documentation/javascript/get-api-key#key)
$one->google_maps_api_key          = '';

// Global Main Menu

if(isset($role) && $role == 1) {
    $one->main_nav = array(
        array(
            'name'  => '<span class="sidebar-mini-hide">Import</span>',
            'icon'  => 'fa fa-upload',
            'url'   => 'import_quizzes.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Quizzes</span>',
            'icon'  => 'si si-note',
            'url'   => 'manage_quizzes.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Questions</span>',
            'icon'  => 'fa fa-file',
            'url'   => 'que_list.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Evaluation Setting</span>',
            'icon'  => 'fa fa-cogs',
            'url'   => 'eval_setting.php'
        ),
    );
}

$rolePages[1] = array(
    "admin_index",
    "import_quizzes",
    "manage_quizzes",
    "view_quiz",
    "manage_questions",
    "view_que",
    "eval_setting"
);

function authorizePage($page) {
    global $role;
    global $username;
    global $rolePages;
    
    if(!isset($role) || empty($role) || $role == "") {
        authenticate();
    }

    if(!in_array($page, $rolePages[(int) $role])) {
        header('Location: ../403.php');
        exit;
    }
}