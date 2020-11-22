<?php
function menu_in_post_tools_page() {
	$options = add_submenu_page(
			'tools.php', 
			__('Menu In Post Tools', 'menu-in-post'), 
			__('Menu In Post Tools', 'menu-in-post'), 
			'manage_options', 
			'menu-in-post', 
			'menu_in_post_tools_page_html'
		);
	add_action('load-' . $options, 'menu_in_post_tools_load_admin_page');
}
add_action('admin_menu', 'menu_in_post_tools_page');

function menu_in_post_tools_load_admin_page() {
	$help_tabs = new MENUINPOST_Help_Tabs(get_current_screen());
	$help_tabs->set_help_tabs('tools');
}

function menu_in_post_tools_page_html() {
	if (!current_user_can('manage_options')) {
        return;
    }
    $shortcode = '';
    /* ************ Fallback for no javascript. Normally, the form is submitted via javascript, and everything is done client-side. ************* */
    if (isset($_POST['mip_menu'])) {
    	$atts = array(
    		'menu'=>'mip_menu', 
    		'menu_class'=>'mip_menu_class', 
    		'menu_id'=>'mip_menu_id',	
    		'container'=>'mip_container', 
    		'container_class'=>'mip_container_class', 
    		'container_id'=>'mip_container_id', 
    		'depth'=>'mip_depth', 
    		'style'=>'mip_style'
    		);
    	foreach ($atts as $att=>$field) {
    		switch($att) {
    			case 'menu':
    			case 'depth':
    			case 'container':
    				$$att = absint($_POST[$field]);
    			break;
    			break;
				// These should only be strings.
				default:
					$$att = trim(filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING));
    		}
    	}
    	// Build the shortcode.
 		$shortcode = '[menu_in_post_menu';
 		foreach ($atts as $att=>$field) {
 			switch($att) {
 				case 'menu':
 					$shortcode .= ' ' . $att . '=' . $$att;
 				break;
 				case 'depth':
 					if ($depth > 0) {
 						$shortcode .= ' depth=' . $depth;
 					}
 				break;
 				case 'style':
 					if ($style != 'list') {
 						$shortcode .= ' style=' . $style;
 					}
 				break;
 				case 'container':
 					if ($container == 0) {
 						$shortcode .= ' container=&#34;false&#34;';
 					}
 				break;
 				default:
	    			if ($$att != '') {
	    				$shortcode .= ' ' . $att . '=&#34;' . $$att . '&#34;';
	    			}
    		}
 		}
 		$shortcode .= ']';
    }
    /* *************************************************** End of javascript fallback. ********************************************** */
	?>
   <div class="wrap">
   	<h1><?php echo esc_html(__('Menu In Post Tools', 'menu-in-post')); ?></h1>
    	<p><?php echo esc_html(__('Use the form below to create shortcodes to display menus in posts and pages. Paste the shortcodes you create in a Shortcode Block to display them.', 'menu-in-post')); ?></p>
    	<h2><?php echo esc_html(__('Shortcode Builder', 'menu-in-post')); ?></h2>
    	<?php
    	$menus = wp_get_nav_menus();
		if (is_array($menus) && count($menus) > 0) {
 			?>
	    	<form name="mip_shortcode_builder_form" id="mip_shortcode_builder_form" method="post">
	    		<label for="mip_menu"><?php echo esc_html(__('Select Menu', 'menu-in-post')); ?>:</label>
	    		<select name="mip_menu" id="mip_menu">
	    			<?php
	    			foreach ($menus as $menu) {
	    				echo '<option value="' . $menu->term_id . '">' . $menu->name . '</option>';
	    			}
	    			?>
	    		</select><br>
	    		<label for="mip_container"><?php echo esc_html(__('Include Container', 'menu-in-post')); ?>:</label>
	    		<select name="mip_container" id="mip_container">
	    			<option value="1"><?php echo esc_html(__('Yes', 'menu-in-post')); ?></option>
	    			<option value="0"><?php echo esc_html(__('No', 'menu-in-post')); ?></option>
	    		</select><br>
	    		<label for="mip_container_id"><?php echo esc_html(__('Container ID', 'menu-in-post')); ?>:</label>
	    		<input type="text" name="mip_container_id" id="mip_container_id"><br>
	    		<label for="mip_container_class"><?php echo esc_html(__('Container Class(es)', 'menu-in-post')); ?>:</label>
	    		<input type="text" name="mip_container_class" id="mip_container_class"><br>
	    		<label for="mip_menu_id"><?php echo esc_html(__('Menu ID', 'menu-in-post')); ?>:</label>
	    		<input type="text" name="mip_menu_id" id="mip_menu_id"><br>
	    		<label for="mip_menu_class"><?php echo esc_html(__('Menu Class(es)', 'menu-in-post')); ?>:</label>
	    		<input type="text" name="mip_menu_class" id="mip_menu_class"><br>
	    		<label for="mip_depth"><?php echo esc_html(__('Depth', 'menu-in-post')); ?>:</label>
	    		<select name="mip_depth" id="mip_depth">
	    			<?php
	    				$depth_options = array(
	    					0=>esc_html(__('All Levels', 'menu-in-post')), 
	    					1=>esc_html(__('1 Level', 'menu-in-post')), 
	    					2=>esc_html(__('2 Levels', 'menu-in-post')), 
	    					3=>esc_html(__('3 Levels', 'menu-in-post')), 
	    					4=>esc_html(__('4 Levels', 'menu-in-post')), 
	    					5=>esc_html(__('5 Levels', 'menu-in-post'))
	    				);
	    				foreach ($depth_options as $value=>$text) {
	    					if ($value == 0) {
	    						echo '<option value="' . $value . '" selected="selected">' . $text . '</option>';
	    					} else {
	    						echo '<option value="' . $value . '">' . $text . '</option>';
	    					}
	    				}
	    			?>
	    		</select><br>
	    		<label for="mip_style"><?php echo esc_html(__('Style', 'menu-in-post')); ?>:</label>
	    		<select name="mip_style" id="mip_style">
	    			<?php
	    				$style_options = array(
	    					'list'=>esc_html(__('List of Links', 'menu-in-post')), 
	    					'dropdown'=>esc_html(__('Dropdown', 'menu-in-post'))
	    				);
	    				foreach ($style_options as $value=>$text) {
	    					if ($value == 'list') {
	    						echo '<option value="' . $value . '" selected="selected">' . $text . '</option>';
	    					} else {
	    						echo '<option value="' . $value . '">' . $text . '</option>';
	    					}
	    				}
	    			?>
	    		</select><br>
	    		<label for="mip_placeholder_text"><?php echo esc_html(__('Placeholder Text', 'menu-in-post')); ?>:</label>
	    		<input type="text" name="mip_placeholder_text" id="mip_placeholder_text"><br>
	    		<input type="submit" name="mip_build" value="<?php echo esc_attr(__('Build the Shortcode', 'menu-in-post')); ?>">
	    	</form>
	    	<div>
	    		<label for="mip_shortcode_builder_output"><?php echo esc_html(__('Shortcode', 'menu-in-post')); ?>:</label>
	    		<div class="mip_shortcode_output_hightlight">
			    	<input type="text" name="mip_shortcode_builder_output" id="mip_shortcode_builder_output" value="<?php echo $shortcode; ?>" readonly="readonly">
			  </div>
			  <div>
			  	<button type="button" id="mip_shortcode_output_copy_button"><?php echo esc_html(__('Copy Shortcode', 'menu-in-post')); ?></button>&nbsp;<span id="mip_shortcode_copy_success"><?php echo esc_html(__('Copied...', 'menu-in-post')); ?></span>
			  </div>
			</div>
	    </div>
	   <?php
	   } else {
			?>
			<div class="notice notice-warning is-dismissible"><p><?php echo esc_html(__('You must create one or more menus (Appearance > Menus) prior to using Menu In Post&#39;s Shortcode Builder.', 'menu-in-post')); ?></p></div>
			<?php
		}
}

function menu_in_post_admin_enqueue_scripts($hook) {
	if ($hook != 'tools_page_menu-in-post') {
		return;
	}
	//wp_enqueue_style('menu_in_post_admin_style', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('menu_in_post_admin_style', plugins_url('css/style-min.css', __FILE__));
	//wp_enqueue_script('menu_in_post_admin_script', plugins_url('js/main.js', __FILE__), array('jquery'));
	wp_enqueue_script('menu_in_post_admin_script', plugins_url('js/main-min.js', __FILE__), array('jquery'));
}
add_action( 'admin_enqueue_scripts', 'menu_in_post_admin_enqueue_scripts' );
?>