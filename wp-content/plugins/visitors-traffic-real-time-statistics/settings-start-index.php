<?php
ob_start();
if ( ! defined( 'ABSPATH' ) ) {
  // Exit if accessed directly.
  exit;
}
//file_version 2.2

//---------------------------------------------------------------------------------------------
//Register required scripts.
//---------------------------------------------------------------------------------------------
if (!function_exists('cp_plugins_enqueue_scripts')){
	function cp_plugins_enqueue_scripts() {
		$pluginsurl = plugins_url( '', __FILE__ ); // Get the current plugin url
		$admincore = '';
		if (isset($_GET['page'])) $admincore = sanitize_text_field($_GET['page']);
		if( ( current_user_can('editor') || current_user_can('administrator') ) && $admincore == 'cp_plugins_settings_start_page_slug')
		{
			wp_enqueue_script('jquery');
			
			wp_enqueue_style('thickbox'); //include thickbox .css
			wp_enqueue_script('thickbox'); //include Thickbox jQuery plugin
			
			wp_register_style('bootstrap-grid.min', $pluginsurl.'/start-page-assests/css/bootstrap-grid.min.css');
			wp_enqueue_style('bootstrap-grid.min');
		}
	}
}
// Hook into the 'wp_enqueue_scripts' action
add_action('admin_enqueue_scripts', 'cp_plugins_enqueue_scripts');
//---------------------------------------------------------------------------------------------
//start_page_remove_menu_choice
//---------------------------------------------------------------------------------------------
if (!function_exists('start_page_remove_menu_choice')){
	function start_page_remove_menu_choice()
	{
		$start_page_remove_menu_links = get_site_option( 'start_page_remove_menu_links' );
		if(isset($start_page_remove_menu_links) && $start_page_remove_menu_links == "yes")
			return "yes";
		else return "no";
	}
}

if (!function_exists('start_page_hide_suggested_plugins_choice')){
	function start_page_hide_suggested_plugins_choice()
	{
		$start_page_hide_suggested_plugins_boxes = get_site_option( 'start_page_hide_suggested_plugins_boxes' );
		if(isset($start_page_hide_suggested_plugins_boxes) && $start_page_hide_suggested_plugins_boxes == "yes")
			return "yes";
		else return "no";
	}
}
//---------------------------------------------------------------------------------------------
//Add button to the admin top bar
//---------------------------------------------------------------------------------------------
if(start_page_remove_menu_choice() != "yes"){
	add_action('admin_bar_menu', 'cp_plugins_add_items',  40);
	add_action('wp_enqueue_scripts', 'cp_plugins_top_bar_enqueue_style');
	add_action('admin_enqueue_scripts', 'cp_plugins_top_bar_enqueue_style');
}
if (!function_exists('cp_plugins_add_items')){
	function cp_plugins_add_items($admin_bar)
	{
	if ( ! current_user_can( 'manage_options' ) ) { return;	}
	$pluginsurl = plugins_url( '', __FILE__ ); // Get the current plugin url
	$wccpadminurl = get_admin_url();
	//The properties of the new item. Read More about the missing 'parent' parameter below
		$args = array(
				'id'    => 'cp_plugins_top_button',
				'title' => '<span class="ab-icon"></span>' . 'CP Plugins' ,
				'href'  => $wccpadminurl.'admin.php?page=cp_plugins_settings_start_page_slug',
				'meta'  => array('title' => 'CP Plugins')
				);
	 
		//This is where the magic works.
		$admin_bar->add_menu( $args);
	}
}

if (!function_exists('cp_plugins_top_bar_enqueue_style')){
	function cp_plugins_top_bar_enqueue_style() {
	?>
		<style>
		#wpadminbar #wp-admin-bar-cp_plugins_top_button .ab-icon:before {
			content: "\f533";
			top: 3px;
		}
		#wpadminbar #wp-admin-bar-cp_plugins_top_button .ab-icon {
			transform: rotate(45deg);
		}
		</style>
	<?php
	}
}
//---------------------------------------------------------------------------------------------
//Our plugins array
//---------------------------------------------------------------------------------------------
$wp_buy_plugins_list = array(
    "captchinoo-captcha-for-login-form-protection/Captcha.php" => Array
		(
            "TextDomain" => "captchinoo-captcha-for-login-form-protection",
            "Title" => "Captcha Login Form Protection",
			"settings_url" => "admin.php?page=logincform"
        ),

    "login-as-customer-or-user/loginas.php" => Array
        (
            "TextDomain" => "login-as-customer-or-user",
            "Title" => "Login As Customer or User",
			"settings_url" => "admin.php?page=loginas"
        ),
	
	"login-as-customer-or-user-pro/loginasPro.php" => Array
        (
            "TextDomain" => "codepressloginas",
            "Title" => "Login as user or customer (PRO)",
			"settings_url" => "admin.php?page=loginas"
        ),

    "conditional-marketing-mailer/woo-conditional-marketing-mailer.php" => Array
        (
            "TextDomain" => "conditional-marketing-mailer",
            "Title" => "Woo Conditional Marketing Mailer",
			"settings_url" => "edit.php?post_type=wcmm"
        ),

    "wp-content-copy-protector/preventer-index.php" => Array
        (
            "TextDomain" => "wp-content-copy-protector",
            "Title" => "WP Content Copy Protection (Free)",
			"settings_url" => "admin.php?page=wccpoptionspro"
        ),

    "wccp-pro/preventer-index.php" => Array
        (
            "TextDomain" => "wccp_pro_translation_slug",
            "Title" => "WP Content Copy Protection (PRO)",
			"settings_url" => "options-general.php?page=wccp-options-pro"
        ),

    "tree-website-map/tree-website-map.php" => Array
        (
            "TextDomain" => "tree-website-map",
            "Title" => "WP Tree Page View (Free)",
			"settings_url" => "admin.php?page=wm_website_maps"
        ),
	"wp-tree-pro/wp-tree-pro.php" => Array
		(
			"TextDomain" => "tree-website-map-pro",
			"Title" => "WP Tree Page View (PRO)",
			"settings_url" => "admin.php?page=wm_website_maps"
		),
	"visitors-traffic-real-time-statistics/Visitors-Traffic-Real-Time-Statistics.php" => Array
		(
			"TextDomain" => "visitors-traffic-real-time-statistics",
			"Title" => "Visitor Traffic Statistics (Free)",
			"settings_url" => "admin.php?page=ahc_hits_counter_menu_free"
		),
	"visitors-traffic-real-time-statistics-pro/visitors-traffic-real-time-statistics-pro.php" => Array
		(
			"TextDomain" => "visitors-traffic-real-time-statistics-pro",
			"Title" => "Visitor Traffic Statistics (PRO)",
			"settings_url" => "admin.php?page=ahc_hits_counter_menu_pro"
		),
	"ultimate-content-views/ultimate-content-views.php" => Array
		(
			"TextDomain" => "ultimate-content-views",
			"Title" => "Custom Post List Builder",
			"settings_url" => "edit.php?post_type=wpucv_list"
		),
	"wp-limit-failed-login-attempts/failed.php" => Array
		(
			"TextDomain" => "wp-limit-failed-login-attempts",
			"Title" => "Limit failed login attempts",
			"settings_url" => "admin.php?page=WPLFLA"
		),
	"wp-maintenance-mode-site-under-construction/maintenance.php" => Array
		(
			"TextDomain" => "wp-maintenance-mode-site-under-construction",
			"Title" => "Maintenance Mode Plugin",
			"settings_url" => "admin.php?page=SUM"
		),
	"easy-popup-lightbox-maker/easy_popup_lightbox_maker.php" => Array
		(
			"TextDomain" => "easy-popup-lightbox-maker",
			"Title" => "WP Popup Window Maker",
			"settings_url" => "admin.php?page=eplm_popups"
		)
);
//---------------------------------------------------------------------------------------------
//Register a custom menu page.
//---------------------------------------------------------------------------------------------
 if (!function_exists('cp_plugins_settings_start_page')){
	 function cp_plugins_settings_start_page() {
		include 'start-page-assests/settings-start.php';
	}
}
// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
if (!function_exists('cp_plugins_register_my_custom_menu_page')){
	function cp_plugins_register_my_custom_menu_page() {
		if(start_page_remove_menu_choice() != "yes")
		{//show menu in normal
			add_menu_page(
				'CP Plugins start page',
				'CP Plugins',
				'manage_options',
				'cp_plugins_settings_start_page_slug', // slug
				'cp_plugins_settings_start_page', // callback
				'dashicons-image-filter', //icon
				6
			);
			add_submenu_page //First sub-item
			(
				'cp_plugins_settings_start_page_slug',       // parent slug
				'Start Page',    // page title
				'Start Page',             // menu title
				'manage_options',           // capability
				'cp_plugins_settings_start_page_slug', // slug
				'' // callback
			);
		}
		else
		{//just activate the starting page without menu link
			add_submenu_page //First sub-item
			(
				'',       // parent slug is null to hide menu link
				'Start Page',    // page title
				'Start Page',             // menu title
				'manage_options',           // capability
				'cp_plugins_settings_start_page_slug', // slug
				'cp_plugins_settings_start_page' // callback
			);
		}
		
		global $wp_buy_plugins_list;
		$admincore = '';
		if (isset($_GET['page'])) $admincore = sanitize_text_field($_GET['page']);
		if ($admincore == 'cp_plugins_settings_start_page_slug')
		{
			foreach ($wp_buy_plugins_list as $plugin_file => $plugin_data) {
				if (is_plugin_active($plugin_file) || is_plugin_active_for_network($plugin_file)) {
					add_submenu_page
					(
						'cp_plugins_settings_start_page_slug',       // parent slug
						$plugin_data["Title"],    // page title
						$plugin_data["Title"],             // menu title
						'manage_options',           // capability
						$plugin_data["settings_url"], // slug
						'' // callback
					);
				}
			}
		}
		
	}
}

add_action( 'admin_menu', 'cp_plugins_register_my_custom_menu_page' );

//---------------------------------------------------------------------------------------------
//Setup Ajax action hook
//---------------------------------------------------------------------------------------------
add_action( 'wp_ajax_do_button_job_later', 'cp_plugins_do_button_job_later_callback' );

if (!function_exists('cp_plugins_do_button_job_later_callback')){
function cp_plugins_do_button_job_later_callback() {
	
	$result = "";
	
	if(isset($_POST['plugin_file']))
	{
		$result = $_POST['plugin_file'];
		
		activate_plugin( $result );
	}
	
	if(isset($_POST['slug']))
	{
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		
		wp_cache_flush();

		$upgrader = new Plugin_Upgrader();
		
		$installed = $upgrader->install( "https://downloads.wordpress.org/plugin/" . $_POST["slug"] . ".zip" );

		return $installed;
	}
	
	if ( is_wp_error( $result ) ) {
		echo "wp_error happened";
	}
	
    wp_die();
}
}
?>