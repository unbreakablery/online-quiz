<?php
/**
 * Basic class used for storing template options and providing
 * various helper functions for populating the template with
 * random content
 *
 * @author pixelcave
 *
 */
class Template {
    // Template Variables
    public  $name               = '',
            $short_name         = '',
            $version            = '',
            $author             = '',
            $robots             = '',
            $title              = '',
            $description        = '',
            $assets_folder      = '',
            $body_bg            = '',
            $main_nav           = array(),
            $main_nav_active    = '',
            $theme              = '',
            $cookies,
            $google_maps_api_key,
            $l_sidebar_position,
            $l_sidebar_mini,
            $l_sidebar_visible_desktop,
            $l_sidebar_visible_mobile,
            $l_side_overlay_hoverable,
            $l_side_overlay_visible,
            $l_side_scroll,
            $l_header_fixed,
            $l_header_transparent,
            $inc_side_overlay,
            $inc_sidebar,
            $inc_header;

    private $nav_html           = '',
            $page_classes       = '';

    /**
     * Class constructor
     */
    public function __construct($name = '', $short_name = '', $version = '', $assets_folder = '') {
        // Set Template's name, version and assets folder
        $this->name                 = $name;
        $this->short_name           = $short_name;
        $this->version              = $version;
        $this->assets_folder        = $assets_folder;
    }

    /**
     * Builds #page-container classes
     *
     * @param   boolean $print True to print the classes and False to return them
     *
     * @return  string  Returns the classes if $print is set to false
     */
    public function page_classes($print = true) {
        // Build page classes
        if ($this->cookies) {
            $this->page_classes .= ' enable-cookies';
        }

        if ($this->l_sidebar_position == 'left') {
            $this->page_classes .= ' sidebar-l';
        } else if ($this->l_sidebar_position == 'right') {
            $this->page_classes .= ' sidebar-r';
        }

        if ($this->l_sidebar_mini) {
            $this->page_classes .= ' sidebar-mini';
        }

        if ($this->l_sidebar_visible_desktop) {
            $this->page_classes .= ' sidebar-o';
        }

        if ($this->l_sidebar_visible_mobile) {
            $this->page_classes .= ' sidebar-o-xs';
        }

        if ($this->l_side_overlay_hoverable) {
            $this->page_classes .= ' side-overlay-hover';
        }

        if ($this->l_side_overlay_visible) {
            $this->page_classes .= ' side-overlay-o';
        }

        if ($this->l_side_scroll) {
            $this->page_classes .= ' side-scroll';
        }

        if ($this->l_header_fixed) {
            $this->page_classes .= ' header-navbar-fixed';
        }

        if ($this->l_header_transparent) {
            $this->page_classes .= ' header-navbar-transparent';
        }

        // Print or return page classes
        if ($this->page_classes) {
            if ($print) {
                echo ' class="'. trim($this->page_classes) .'"';
            } else {
                return trim($this->page_classes);
            }
        } else {
            return false;
        }
    }

    /**
     * Builds main navigation
     *
     * @param   boolean     $print True to print the navigation and False to return it
     *
     * @return  string      Returns the navigation if $print is set to false
     */
    public function build_nav($print = true) {
        // Build navigation
        $this->build_nav_array($this->main_nav);

        // Print or return navigation
        if ($print) {
            echo $this->nav_html;
        } else {
            return $this->nav_html;
        }
    }

    /**
     * Build navigation helper - Builds main navigation one level at a time
     *
     * @param string    $nav_array A multi dimensional array with menu/submenus links
     */
    private function build_nav_array($nav_array) {
        foreach ($nav_array as $node) {
            // Get all vital link info
            $link_name      = isset($node['name']) ? $node['name'] : '';
            $link_icon      = isset($node['icon']) ? '<i class="' . $node['icon'] . '"></i>' : '';
            $link_url       = isset($node['url']) ? $node['url'] : '#';
            $link_sub       = isset($node['sub']) && is_array($node['sub']) ? true : false;
            $link_type      = isset($node['type']) ? isset($node['type']) : '';
            $sub_active     = false;
            $link_active    = $link_url == $this->main_nav_active ? true : false;

            // If link type is a header
            if ($link_type == 'heading') {
                $this->nav_html .= "<li class=\"nav-main-heading\">$link_name</li>\n";
            } else {
                // If it is a submenu search for an active link in all sub links
                if ($link_sub) {
                    $sub_active = $this->build_nav_array_search($node['sub']) ? true : false;
                }

                // Set menu properties
                $li_prop        = $sub_active ? ' class="open"' : '';
                $link_prop      = $link_sub ? ' class="nav-submenu' . ($link_active ? ' active' : '') . '" data-toggle="nav-submenu"' : ($link_active ? ' class="active"' : '');

                // Add the link
                $this->nav_html .= "<li$li_prop>\n";
                $this->nav_html .= "<a$link_prop href=\"$link_url\">$link_icon$link_name</a>\n";

                // If it is a submenu, call the function again
                if ($link_sub) {
                    $this->nav_html .= "<ul>\n";
                    $this->build_nav_array($node['sub']);
                    $this->nav_html .= "</ul>\n";
                }

                $this->nav_html .= "</li>\n";
            }
        }
    }

    /**
     * Build navigation helper - Search navigation array for active menu links
     *
     * @param   string      $nav_array A multi dimensional array with menu/submenus links
     *
     * @return  boolean     Returns true if an active link is found
     */
    private function build_nav_array_search($nav_array) {
        foreach ($nav_array as $node) {
            if (isset($node['url']) && ($node['url'] == $this->main_nav_active)) {
                return true;
            } else if (isset($node['sub']) && is_array($node['sub'])) {
                if ($this->build_nav_array_search($node['sub'])) {
                    return true;
                }
            }
        }
    }
}