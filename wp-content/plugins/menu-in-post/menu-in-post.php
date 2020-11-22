<?php
/*
Plugin Name: Menu In Post
Description: A simple but flexible plugin to add menus to a post or page.
Author: linux4me
Author URI: https://profiles.wordpress.org/linux4me
Text Domain: menu-in-post
Version: 1.1.4
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0-standalone.html
*/

defined('ABSPATH') or die('No direct access.');

define('MENUINPOST_PLUGIN', __FILE__);
define('MENUINPOST_PLUGIN_DIR', untrailingslashit(dirname(MENUINPOST_PLUGIN)));

if (is_admin()) {
	require_once MENUINPOST_PLUGIN_DIR . '/admin/help-tabs.php';
	require_once MENUINPOST_PLUGIN_DIR . '/admin/admin.php';
}

add_shortcode('menu_in_post_menu', 'menu_in_post_output_menu');
function menu_in_post_output_menu($atts = array(), $content, $shortcode_tag) {
	if (isset($atts['menu'])) {
		$menu = absint($atts['menu']);
	} else {
		$menu = 0;
	}
	if ($menu == 0) {
		return menu_in_post_fallback_fxn();
	} else {
		$args = array('menu'=>$menu, 'fallback_cb'=>'menu_in_post_fallback_fxn', 'echo'=>false);
	}
	// If menu_id is empty, don't pass a value, and the menu slug with an incremented value added will be used. 
	// If container_class is empty, don't pass a value and 'menu-{menu slug}-container' will be used.
	$defaults = array(
			'menu_class'=>'menu',
			'menu_id'=>'',	
			'container'=>'div', 
			'container_class'=>'', 
			'container_id'=>'', 
			'style'=>'list', 
			'placeholder_text'=>esc_html(__('Select...', 'menu-in-post')), 
			'depth'=>0 
		);
	foreach ($defaults as $att=>$default) {
		switch($att) {
			case 'depth':
				if (isset($atts[$att])) {
					$passed_depth = absint($atts[$att]);
					if ($passed_depth > 0) {
						$args['depth'] = $passed_depth;
					}
				} else {
					$atts['depth'] = $default;
				}
			break;
			// These should be only strings.
			default:
				if (isset($atts[$att])) {
					$passed_att = filter_var($atts[$att], FILTER_SANITIZE_STRING);
					if ($passed_att != '') {
						$args[$att] = $passed_att;
					}
				} else {
					$atts[$att] = $default;
				}
		}	
	}
	if ($atts['style'] == 'dropdown') {
		$select = '<select class="mip-drop-nav"';
		if ($atts['menu_id'] != '') {
			$select .= ' id="' . $args['menu_id'] . '"';
		}
		$select .= '>';
		$args['items_wrap'] = $select . '<option value="#">' . $atts['placeholder_text'] . '</option>%3$s</select>';
      $args['walker'] = new MIP_Walker_Nav_Menu_Dropdown();
   }
	return wp_nav_menu($args);
}

function menu_in_post_fallback_fxn() {
	return;	
}

add_action( 'wp_enqueue_scripts', 'menu_in_post_frontend_enqueue_scripts' );
function menu_in_post_frontend_enqueue_scripts() {
	//wp_enqueue_script('menu_in_post_frontend_script', plugins_url('js/main.js', __FILE__), array('jquery'));
	wp_enqueue_script('menu_in_post_frontend_script', plugins_url('js/main-min.js', __FILE__), array('jquery'));
}

class MIP_Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = null) {}

    function end_lvl(&$output, $depth = 0, $args = null) {}

    function start_el(&$output, $item, $depth = 0, $args = NULL, $id = 0) {
        // Create each option.
        $item_output = '';

        // Add spacing to the title based on the depth.
        $item->title = str_repeat(' - ', $depth * 1) . $item->title;

        // Get the link.
        $attributes = ! empty( $item->url) ? ' value="'   . esc_attr( $item->url) .'"' : '';

        // Add the HTML.
        $item_output .= '<option'. $attributes .'>';
        $item_output .= apply_filters( 'the_title_attribute', $item->title);

        // Add the new item to the output string.
        $output .= $item_output;
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        // Close the item.
        $output .= "</option>\n";

    }

}
?>