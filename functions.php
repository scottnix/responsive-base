<?php

//
//  Responsive Base Child Theme Functions
//


// recreates the doctype section, html5boilerplate.com style with conditional classes
// http://scottnix.com/html5-header-with-thematic/
function childtheme_create_doctype() {
    $content = "<!doctype html>" . "\n";
    $content .= '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= '<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->'. "\n";
    $content .= '<!--[if IE 8]> <html class="no-js lt-ie9" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= "<!--[if gt IE 8]><!-->" . "\n";
    $content .= "<html class=\"no-js\"";
    return $content;
}
add_filter('thematic_create_doctype', 'childtheme_create_doctype', 11);

// creates the head, meta charset, and viewport tags
function childtheme_head_profile() {
    $content = "<!--<![endif]-->";
    $content .= "\n" . "<head>" . "\n";
    $content .= "<meta charset=\"utf-8\" />" . "\n";
    $content .= "<meta name=\"viewport\" content=\"width=device-width\" />" . "\n";
    return $content;
}
add_filter('thematic_head_profile', 'childtheme_head_profile', 11);

// remove meta charset tag, now in the above function
function childtheme_create_contenttype() {
    // silence
}
add_filter('thematic_create_contenttype', 'childtheme_create_contenttype', 11);



// clear useless garbage for a polished head
// remove really simple discovery
remove_action('wp_head', 'rsd_link');
// remove windows live writer xml
remove_action('wp_head', 'wlwmanifest_link');
// remove index relational link
remove_action('wp_head', 'index_rel_link');
// remove parent relational link
remove_action('wp_head', 'parent_post_rel_link');
// remove start relational link
remove_action('wp_head', 'start_post_rel_link');
// remove prev/next relational link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');



// remove built in drop down theme javascripts
// thematictheme.com/forums/topic/correct-way-to-prevent-loading-thematic-scripts/
function childtheme_remove_superfish() {
    remove_theme_support('thematic_superfish');
}
add_action('wp_enqueue_scripts', 'childtheme_remove_superfish', 9);



// script manager template for registering and enqueuing files
// http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes
function childtheme_script_manager() {
    // wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );
    // registers modernizr script, stylesheet local path, no dependency, no version, loads in header
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/js/modernizr.js', false, false, false);
    // registers fitvids script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('fitvids-js', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), false, true);
    // registers misc custom script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), false, true);

    // enqueue the scripts for use in theme
    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script ('fitvids-js');

        // placeholder for example of conditional script loading
        if (is_front_page() ) {

        }

    //always enqueue this last, helps with conflicts
    wp_enqueue_script ('custom-js');

}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');



// add favicon to site, add 16x16 or 32x32 "favicon.ico" or .png image to child themes main folder
function childtheme_add_favicon() { ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<?php }
add_action('wp_head', 'childtheme_add_favicon');



// register two additional custom menu slots
function childtheme_register_menus() {
    if (function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('thematic_child_init', 'childtheme_register_menus');



// remove user agent sniffing from thematic theme
// this is what applies classes to the browser type and version body classes
function childtheme_show_bc_browser() {
    return FALSE;
}
add_filter('thematic_show_bc_browser', 'childtheme_show_bc_browser');



// completely remove nav above functionality
function childtheme_override_nav_above() {
    // silence
}



// featured image thumbnail sizing, default is 100x100 set by Thematic
function childtheme_post_thumb_size($size) {
    $size = array(300,300);
    return $size;
}
add_filter('thematic_post_thumb_size', 'childtheme_post_thumb_size');



// add 4th subsidiary aside, currently set up to be a footer widget (#footer-widget) underneath the 3 subs
function childtheme_add_subsidiary($content) {
    $content['Footer Widget Aside'] = array(
            'admin_menu_order' => 550,
            'args' => array (
            'name' => 'Footer Aside',
            'id' => '4th-subsidiary-aside',
            'description' => __('The 4th bottom widget area in the footer.', 'thematic'),
            'before_widget' => thematic_before_widget(),
            'after_widget' => thematic_after_widget(),
            'before_title' => thematic_before_title(),
            'after_title' => thematic_after_title(),
                ),
            'action_hook'   => 'widget_area_subsidiaries',
            'function'      => 'childtheme_4th_subsidiary_aside',
            'priority'      => 90
        );
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_add_subsidiary');

// set structure for the 4th subsidiary aside
function childtheme_4th_subsidiary_aside() {
    if ( is_active_sidebar('4th-subsidiary-aside') ) {
        echo thematic_before_widget_area('footer-widget');
        dynamic_sidebar('4th-subsidiary-aside');
        echo thematic_after_widget_area('footer-widget');
    }
}



// just because, wrap the site info in a p tag automatically
function childtheme_override_siteinfo() {
    echo "\t\t<p>" . do_shortcode( thematic_get_theme_opt( 'footer_txt' ) ) . "</p>\n";
}



/*
// example for hiding unused widget areas inside the WordPress admin
function childtheme_hide_areas($content) {
    unset($content['Index Top']);
    unset($content['Index Insert']);
    unset($content['Index Bottom']);
    unset($content['Single Top']);
    unset($content['Single Insert']);
    unset($content['Single Bottom']);
    unset($content['Page Top']);
    unset($content['Page Bottom']);
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_hide_areas');
*/



/*
// load google analytics
// optimized version http://mathiasbynens.be/notes/async-analytics-snippet
function snix_google_analytics(){ ?>
<script>var _gaq=[['_setAccount','UA-xxxxxxx-x'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src='//www.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s)}(document,'script'))</script>
<?php }
add_action('wp_footer', 'snix_google_analytics');
*/