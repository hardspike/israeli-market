<?php
ob_start();
if ( ! defined( 'ABSPATH' ) ) {
  // Exit if accessed directly.
  exit;
}
//file_version 2.2

$wp_buy_plugins_list = array(
    "captchinoo-captcha-for-login-form-protection/Captcha.php" => Array(
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Captchinoo Captcha for Login Form Protection",
            "PluginURI" => "",
            "Version" => 1.8,
            "Description" => "This is the best security solution to protect your WordPress login form from spam entries.",
            "Author" => "wp-buy",
            "AuthorURI" => "https://profiles.wordpress.org/wp-buy/#content-plugins",
            "TextDomain" => "captchinoo-captcha-for-login-form-protection",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Captcha Login Form Protection",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "red",
			"version_type" => "free",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=logincform"
        ),

    "login-as-customer-or-user/loginas.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Login As Customer or User",
            "PluginURI" => "",
            "Version" => 1.3,
            "Description" => "It's very helpful for admins or customer support users to access any user account in one click.",
            "Author" => "codepressplugins",
            "AuthorURI" => "https://codepress.pro/",
            "TextDomain" => "login-as-customer-or-user",
            "DomainPath" => "/languages/",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Login As Customer or User",
            "AuthorName" => "codepressplugins",
			"icon>" => "",
			"color" => "green",
			"version_type" => "free",
			"pro_version_link" => "https://www.wp-buy.com/product/login-as-customer-or-user-pro/",
			"pro_version_file" => "login-as-customer-or-user-pro/loginasPro.php",
			"settings_url" => "admin.php?page=loginas"
        ),
		
	"login-as-customer-or-user-pro/loginasPro.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Login as user or customer pro",
            "PluginURI" => "https://www.wp-buy.com/product/login-as-customer-or-user-pro/",
            "Version" => 1.3,
            "Description" => "Login as User or Customer is very helpful for admins or customer support users to access any user account in one click.",
            "Author" => "wp-buy",
            "AuthorURI" => "http://www.wp-buy.com/",
            "TextDomain" => "codepressloginas",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Login as user or customer (PRO)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "green",
			"version_type" => "pro",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=loginas"
        ),

    "conditional-marketing-mailer/woo-conditional-marketing-mailer.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Woocommerce Conditional Marketing Mailer",
            "PluginURI" => "",
            "Version" => 1.3,
            "Description" => "Create professional emailed notifications based on custom conditions. Fully customizable.",
            "Author" => "wp-buy",
            "AuthorURI" => "https://profiles.wordpress.org/wp-buy/#content-plugins",
            "TextDomain" => "conditional-marketing-mailer",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Woo Conditional Marketing Mailer",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "yellow",
			"version_type" => "free",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "edit.php?post_type=wcmm"
        ),

    "wp-content-copy-protector/preventer-index.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP Content Copy Protection & No Right Click",
            "PluginURI" => "http://wordpress.org/plugins/w-p-content-copy-protector/",
            "Version" => 2.6,
            "Description" => "This plugin protects the content of the post from being copied, Just basic protection provided with it!!",
            "Author" => "wp-buy",
            "AuthorURI" => "http://www.wp-buy.com/",
            "TextDomain" => "wp-content-copy-protector",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "WP Content Copy Protection (Free)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "gray",
			"version_type" => "free",
			"pro_version_link" => "https://www.wp-buy.com/product/wp-content-copy-protection-pro/",
			"pro_version_file" => "wccp-pro/preventer-index.php",
			"settings_url" => "admin.php?page=wccpoptionspro"
        ),

    "wccp-pro/preventer-index.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP Content Copy Protection & No Right Click (premium)",
            "PluginURI" => "https://www.wp-buy.com/product/wp-content-copy-protection-pro/",
            "Version" => 9.6,
            "Description" => "This plugin protects the content of the post from being copied, Premium protection provided with it!!",
            "Author" => "wp-buy",
            "AuthorURI" => "http://www.wp-buy.com/",
            "TextDomain" => "wccp-pro",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "WP Content Copy Protection (PRO)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "gray",
			"version_type" => "pro",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "options-general.php?page=wccp-options-pro"
        ),

    "tree-website-map/tree-website-map.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP Tree Page View",
            "PluginURI" => "",
            "Version" => 2.4,
            "Description" => "Vertical & Horizontal Tree Builder is an awesome WordPress plug-in to add a tree view of all pages.",
            "Author" => "wp-buy",
            "AuthorURI" => "https://www.wp-buy.com/",
            "TextDomain" => "tree-website-map",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "WP Tree Page View (Free)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "red",
			"version_type" => "free",
			"pro_version_link" => "https://www.wp-buy.com/product/wp-tree-pro/",
			"pro_version_file" => "wp-tree-pro/wp-tree-pro.php",
			"settings_url" => "admin.php?page=wm_website_maps"
        ),
		
		"wp-tree-pro/wp-tree-pro.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP Tree Page View PRO",
            "PluginURI" => "",
            "Version" => 2.4,
            "Description" => "PRO Vertical & Horizontal Tree Builder is an awesome WordPress plug-in to add a tree view of all pages.",
            "Author" => "wp-buy",
            "AuthorURI" => "https://www.wp-buy.com/",
            "TextDomain" => "tree-website-map-pro",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "WP Tree Page View (PRO)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "red",
			"version_type" => "pro",
			"pro_version_link" => "https://www.wp-buy.com/product/wp-tree-pro/",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=wm_website_maps"
        ),
		"visitors-traffic-real-time-statistics/Visitors-Traffic-Real-Time-Statistics.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Visitor Traffic Real Time Statistics",
            "PluginURI" => "",
            "Version" => "2.6",
            "Description" => "Hits counter that shows analytical numbers & data of your WordPress site visitors and hits (Basic version)",
            "Author" => "wp-buy",
            "AuthorURI" => "https://www.wp-buy.com/",
            "TextDomain" => "visitors-traffic-real-time-statistics",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Visitor Traffic Statistics (Free)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "green",
			"version_type" => "free",
			"pro_version_link" => "https://www.wp-buy.com/product/visitors-traffic-real-time-statistics-pro/",
			"pro_version_file" => "visitors-traffic-real-time-statistics-pro/visitors-traffic-real-time-statistics-pro.php",
			"settings_url" => "admin.php?page=ahc_hits_counter_menu_free"
        ),
		"visitors-traffic-real-time-statistics-pro/visitors-traffic-real-time-statistics-pro.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Visitor Traffic Real Time Statistics pro",
            "PluginURI" => "",
            "Version" => "6.6",
            "Description" => "Hits counter that shows analytical numbers & data of your WordPress site visitors and hits (PRO version)",
            "Author" => "wp-buy",
            "AuthorURI" => "https://www.wp-buy.com/",
            "TextDomain" => "visitors-traffic-real-time-statistics-pro",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Visitor Traffic Statistics (PRO)",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "green",
			"version_type" => "pro",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=ahc_hits_counter_menu_pro"
        ),
		"ultimate-content-views/ultimate-content-views.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "Custom Post List",
            "PluginURI" => "https://preview.codepress.pro",
            "Version" => "2.5",
            "Description" => "Easy-to-use plugin to make lists of posts, list posts by category in a post or page using the short-code.",
            "Author" => "wp-buy",
            "AuthorURI" => "https://www.codepress.pro",
            "TextDomain" => "ultimate-content-views",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Custom Post List Builder",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "yellow",
			"version_type" => "free",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "edit.php?post_type=wpucv_list"
        ),
		"wp-limit-failed-login-attempts/failed.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP limit failed login attempts",
            "PluginURI" => "",
            "Version" => "1.7",
            "Description" => "Limit the number of retry attempts when logging in per IP. Fully customizable and easy to use.",
            "Author" => "wp-buy",
            "AuthorURI" => "https://wp-buy.com",
            "TextDomain" => "wp-limit-failed-login-attempts",
            "DomainPath" => "/languages/",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Limit failed login attempts",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "gray",
			"version_type" => "free",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=WPLFLA"
        ),
		"wp-maintenance-mode-site-under-construction/maintenance.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP Maintenance Mode & Site Under Construction",
            "PluginURI" => "https://wordpress.org/plugins/wp-maintenance-mode-site-under-construction",
            "Version" => "1.6",
            "Description" => "Let's tell your visitors about the maintenance time you want, by showing a very beautiful counter",
            "Author" => "wp-buy",
            "AuthorURI" => "https://wordpress.org/plugins/wp-maintenance-mode-site-under-construction",
            "TextDomain" => "wp-maintenance-mode-site-under-construction",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "Maintenance Mode Plugin",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "red",
			"version_type" => "free",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=SUM"
        ),
		"easy-popup-lightbox-maker/easy_popup_lightbox_maker.php" => Array
        (
            "WC requires at least" => "",
            "WC tested up to" => "",
            "Woo" => "",
            "Name" => "WP Popup Window Maker",
            "PluginURI" => "https://codepress.pro",
            "Version" => "1.6",
            "Description" => "Looking for the best lightbox plugin for your WordPress website؟ Activate this and enjoy it!",
            "Author" => "wp-buy",
            "AuthorURI" => "https://codepress.pro",
            "TextDomain" => "easy-popup-lightbox-maker",
            "DomainPath" => "/languages",
            "Network" => "",
            "RequiresWP" => "",
            "RequiresPHP" => "",
            "Title" => "WP Popup Window Maker",
            "AuthorName" => "wp-buy",
			"icon>" => "",
			"color" => "green",
			"version_type" => "free",
			"pro_version_link" => "no_pro_link",
			"pro_version_file" => "no_pro_file",
			"settings_url" => "admin.php?page=eplm_popups"
        )
);

function start_page_remove_menu_links()
{
	if ( ! is_admin() ||
		! current_user_can( 'manage_options' ) ||
		! isset( $_GET['_wpnonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'start_page_menu_links_nonce' ) ||
		! isset( $_GET['start_page_remove_menu_links'] ) ) :

		return;
  	endif;

  	update_site_option( 'start_page_remove_menu_links', sanitize_text_field($_GET['start_page_remove_menu_links']) );
	# redirect
	$new_url = "?page=cp_plugins_settings_start_page_slug";
	header("Location: $new_url");
}

function start_page_hide_suggested_plugins_boxes()
{
	if ( ! is_admin() ||
		! current_user_can( 'manage_options' ) ||
		! isset( $_GET['_wpnonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'suggested_plugins_boxes_nonce' ) ||
		! isset( $_GET['start_page_hide_suggested_plugins_boxes'] ) ) :

		return;
  	endif;
	
  	update_site_option( 'start_page_hide_suggested_plugins_boxes', sanitize_text_field($_GET['start_page_hide_suggested_plugins_boxes']));
	# redirect
	$new_url = "?page=cp_plugins_settings_start_page_slug";
	header("Location: $new_url");
}

start_page_remove_menu_links();
start_page_hide_suggested_plugins_boxes();

?>
<div class="demo">
    <div class="container">
		<div class="row"><div class="col-sm-6 left">
		<!-- logo -->
		<div class="logo">
			<img src="<?php echo plugins_url( '', __FILE__ ) . '/images/wp-buy-new-logo.png' ?>" alt="Premium Wordpress Plugins" style="margin-top: 20px;">
			<p><b>Note:</b> All plugins listed here are coded by us (<a href="https://profiles.wordpress.org/wp-buy/#content-plugins" target="_blank">Profile</a>) and this page does not contain any ADS at all</p>
		</div>
		<!-- end logo -->
		</div>
		<div class="col-sm-6 right" style="margin: auto; text-align: right;">
		<?php
		$suggested_plugins_boxes_href = "";
		$scheme      = ( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ) ) ? '&' : '?';
		$title_hint = "Please make sure that our plugins are all updated to enable this feature";
		/////////
		if(start_page_remove_menu_choice() != "yes"){
			$url = $_SERVER['REQUEST_URI'] . $scheme . 'start_page_remove_menu_links=yes';
			$start_page_remove_menu_links_url = wp_nonce_url( $url, 'start_page_menu_links_nonce' );
			$show_hide_menu_href = '<p><a title="'. $title_hint .'" href="' . $start_page_remove_menu_links_url . '">Hide menu links for this page</a></p>';
		}else
		{
			$url = $_SERVER['REQUEST_URI'] . $scheme . 'start_page_remove_menu_links=no';
			$start_page_remove_menu_links_url = wp_nonce_url( $url, 'start_page_menu_links_nonce' );
			$show_hide_menu_href = '<p><a  title="'. $title_hint .'" href="' . $start_page_remove_menu_links_url . '">Show menu links for this page</a></p>';
		}
		/////////
		if(start_page_hide_suggested_plugins_choice() != "yes"){
			$url = $_SERVER['REQUEST_URI'] . $scheme . 'start_page_hide_suggested_plugins_boxes=yes';
			$hide_suggested_plugins_boxes_url = wp_nonce_url( $url, 'suggested_plugins_boxes_nonce' );
			$suggested_plugins_boxes_href = '<p><a title="'. $title_hint .'"  href="' . $hide_suggested_plugins_boxes_url . '">Dismiss suggested plugins boxes</a></p>';
		}else
		{
			$url = $_SERVER['REQUEST_URI'] . $scheme . 'start_page_hide_suggested_plugins_boxes=no';
			$hide_suggested_plugins_boxes_url = wp_nonce_url( $url, 'suggested_plugins_boxes_nonce' );
			$suggested_plugins_boxes_href = '<p><a title="'. $title_hint .'" href="' . $hide_suggested_plugins_boxes_url . '">Show suggested plugins boxes</a></p>';
		}
		?>
		<?php echo $show_hide_menu_href; ?>
		<?php echo $suggested_plugins_boxes_href; ?>
		</div>
	<div class="row">
<?php
if (!function_exists('get_plugins') || !function_exists('is_plugin_active')) {
         include_once ABSPATH . 'wp-admin/includes/plugin.php';
     }
//print_r(get_plugins());

start_plugins_listing($wp_buy_plugins_list);		

function start_plugins_listing($wp_buy_plugins_list)
{
	$plugins = $wp_buy_plugins_list;
	foreach ($plugins as $plugin_file => $plugin_data) {
		$active_inactive_result = "";
		$pathpluginurl = WP_PLUGIN_DIR . '/' . $plugin_file;
		$isinstalled = file_exists( $pathpluginurl );
		if(!$isinstalled)
		{
			$active_inactive_result = "not_installed";
		}else
		{
			if (is_plugin_active($plugin_file) || is_plugin_active_for_network($plugin_file)) {$active_inactive_result = "active";}
			if (is_plugin_inactive($plugin_file)) {$active_inactive_result = "inactive";}
		}
		$current_plugin_data = get_plugin_data( __FILE__ );
		$current_plugin_data_TextDomain = $current_plugin_data["TextDomain"];
		if(start_page_hide_suggested_plugins_choice() == "yes")
		{//Just show the installed or activated plugins boxes
			if($active_inactive_result == "active" || $active_inactive_result == "inactive" )
			{
				start_page_show_product_box($plugin_file, $plugin_data, $active_inactive_result, $current_plugin_data_TextDomain);
			}
		}else
		{//Show all boxes
			start_page_show_product_box($plugin_file, $plugin_data, $active_inactive_result, $current_plugin_data_TextDomain);
		}
	}
}
?>
<?php
function start_page_show_product_box($plugin_file = '', $plugin_data = array() ,$active_inactive_result = "", $current_plugin_data_TextDomain)
{
	$pro_version_file = $plugin_data["pro_version_file"];
	
	$is_pro_plugin_active_or_installed = false;
	
	$is_pro_plugin_active_or_installed = file_exists( WP_PLUGIN_DIR . '/' . $pro_version_file );
	
	$is_pro_plugin_active_result = false;
	
	$settings_url = "";
	
	$settings_url = $plugin_data["settings_url"];
	
	if ($pro_version_file != "no_pro_file" && is_plugin_active($pro_version_file))
	{
		$is_pro_plugin_active_result = true;
	}
	
	$show_first_box = "";
	
	if($current_plugin_data_TextDomain == $plugin_data["TextDomain"]) $show_first_box = "order-first ";
	
	if($active_inactive_result != "active") $show_first_box = "order-last ";
	
	if($plugin_data["version_type"] == "free" && $is_pro_plugin_active_result) return;
	
	if($plugin_data["version_type"] == "pro" && $active_inactive_result == "not_installed") return; //Hide the PRO box if its not installed
	
	$go_pro_link = '';
	
	// Build the link if its free & not installed & has a pro version & pro in not installed
	if($plugin_data["version_type"] == "free" && $active_inactive_result != "not_installed" && $pro_version_file != "no_pro_file" && !$is_pro_plugin_active_or_installed)
	{
		$pro_version_link = "";
		
		$pro_version_link = $plugin_data["pro_version_link"];
		
		$go_pro_link = '<a class="go_pro_link" target="_blank" href="' . $pro_version_link .'">Go PRO</a>';
	}
	
	$show_details_link = "";
	
	if($plugin_data["version_type"] == "free")
	{
		$pro_version_link = network_admin_url("plugin-install.php?tab=plugin-information&plugin=" . $plugin_data["TextDomain"] . "&TB_iframe=true&width=600&height=550");
		
		$show_details_link = '<a title="show plugin details on wordpress.org" class="thickbox show_details_link" style="" target="_blank" href="' . $pro_version_link .'">Details</a>';
	}
	
	$url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_data["TextDomain"]), 'install-plugin_' . $plugin_data["TextDomain"]);
	
	if($active_inactive_result == "active") $url = self_admin_url($settings_url);
	
	$url = self_admin_url($settings_url);
	
	$plugin_name = $plugin_data["TextDomain"];
	
	$icon_url = plugin_dir_url( __FILE__ ).'/icons/' . $plugin_data["TextDomain"] . '.png';
?>
	<div class="<?php echo $show_first_box; ?>col-md-3 col-sm-6 start-product-box">
		<div class="serviceBox <?php echo $plugin_data["color"]; ?>">
			<div class="service-icon">
				<span><img style="width:90px; height:90px;" src="<?php echo $icon_url ?>"></span>
			</div>
			<h3 class="title"><?php echo $plugin_data["Title"]; ?></h3>
			<p class="description"><?php echo $plugin_data["Description"]; ?></p>
			<?php if($active_inactive_result == "not_installed"){ ?>
				<a class="button-primary install-now button" plugin_file="<?php echo $plugin_file; ?>" plugin_slug="<?php echo $plugin_data["TextDomain"]; ?>" href="<?php echo $url; ?>" aria-label="" data-name="">Try for Free</a>
			<?php }else if($active_inactive_result == "active"){ ?>
				<a class="button-primary dashboard-now button" plugin_file="<?php echo $plugin_file; ?>" href="<?php echo $url; ?>" aria-label="" data-name="">Dashboard</a>
			<?php }else if($active_inactive_result == "inactive"){ ?>
				<a class="button-primary activate-now button" plugin_file="<?php echo $plugin_file; ?>" href="<?php echo $url; ?>" aria-label="" data-name="">Activate</a>
			<?php } ?>
			<?php echo $go_pro_link; ?>
			<?php echo $show_details_link; ?>
			
		</div>
	</div>
<?php
}
?>
        </div>
    </div>
</div>

<?php
add_action('admin_footer', 'cp_start_plugins_js_action');

function cp_start_plugins_js_action() {
?>
<script type="text/javascript" >
jQuery(document).ready( function(){
		jQuery('body').on("click", "a.activate-now", function(e){
			e.preventDefault();
			var target_link = jQuery(this);
			jQuery(this).text("Activating..");
			var data = {
				'action': 'do_button_job_later',
				'plugin_file' : jQuery(this).attr("plugin_file")
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(data) {
				target_link.text("Dashboard");
				target_link.removeClass( "activate-now" ).addClass( "dashboard-now" );
			});
		});
		//Install new plugin with ajax
		jQuery('body').on("click", "a.install-now", function(e){
			e.preventDefault();
			var target_link = jQuery(this);
			jQuery(this).text("Installing..");
			var data = {
				'action': 'do_button_job_later',
				'slug' : jQuery(this).attr("plugin_slug")
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(data) {
				target_link.text("Activate");
				target_link.removeClass( "install-now" ).addClass( "activate-now" );
			});
		});
    });
</script>
<?php
}
?>
<style>
.start-product-box{margin-bottom: 40px; min-width:320px;}
.serviceBox{
    color: #555;
    background: #fff;
    font-family: 'Cabin', sans-serif;
    text-align: center;
    padding: 40px 15px 20px;
    border-radius: 30px;
    overflow: hidden;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease 0s;
}
.serviceBox h3{
min-height: 60px;
}
.serviceBox:hover{ box-shadow: 0 0 15px #999; }
.serviceBox:before{
    content: "";
    background: linear-gradient(45deg,#139627,#4BB85D,#139627);
    width: 125%;
    height: 230px;
    border-radius: 50%;
    position: absolute;
    top: -75px;
    left: -50px;
    z-index: -1;
}
.serviceBox .service-icon{
    color: #fff;
    font-size: 75px;
    line-height: 20px;
    margin: 0 auto 65px;
}
.serviceBox .service-icon i{
    transform: rotateX(0deg) rotateY(0deg);
    transition: all 0.3s ease 0s;
}
.serviceBox:hover .service-icon i{
    text-shadow: 0 0 5px #fff;
    transform: rotateX(360deg) rotateY(360deg);
}
.serviceBox .title{
    font-size: 20px;
    font-weight: 700;
    text-transform: uppercase;
    margin: 0 0 10px;
    transition: all 0.3s;
	line-height: initial;
}
.serviceBox:hover .title{ color: #139627; }
.serviceBox .description{
    font-size: 15px;
    line-height: 25px;
}
.serviceBox.yellow:before{ background: linear-gradient(45deg,#db9e11,#FEC132,#db9e11); }
.serviceBox.yellow:hover .title{ color: #db9e11; }
.serviceBox.red:before{ background: linear-gradient(45deg,#d1082d,#ED2D52,#d1082d); }
.serviceBox.red:hover .title{ color: #d1082d; }
.serviceBox.gray:before{ background: linear-gradient(45deg,#343538,#626367,#343538); }
.serviceBox.gray:hover .title{ color: #343538; }

.go_pro_link{
	width: 55px;height: 22px; position: absolute; top: 22px; left: 10px; transform: rotate(-45deg);color: white; text-decoration: none;
	}
.show_details_link{
	width: 55px;height: 22px;position: absolute;bottom: 22px;right: 15%;color: #007cba;text-decoration: none;
}

@media only screen and (max-width:990px){
    .serviceBox{ margin: 0 0 40px; }
}
</style>