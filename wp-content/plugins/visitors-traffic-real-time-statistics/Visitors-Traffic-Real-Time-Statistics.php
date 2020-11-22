<?php
/*
Plugin Name: Visitor Traffic Real Time Statistics
Description: Hits counter that shows analytical numbers of your WordPress site visitors and hits.
Author: wp-buy
Author URI: https://www.wp-buy.com/
Version: 2.8.1
Text Domain: visitors-traffic-real-time-statistics
Domain Path: /languages
*/

define('AHCFREE_PLUGIN_MAIN_FILE', __FILE__);
define('AHCFREE_PLUGIN_ROOT_DIR', dirname(__FILE__));

require_once(AHCFREE_PLUGIN_ROOT_DIR. "/functions.php");
require_once(AHCFREE_PLUGIN_ROOT_DIR."/init.php");

if( !function_exists('get_plugin_data') or !function_exists('wp_get_current_user')){
	include_once(ABSPATH . 'wp-includes/pluggable.php');
}


include_once("notifications.php");
include_once('settings-start-index.php');
include_once('deactivation.php');

if ( function_exists('get_plugin_data') ) {
	$woodhl_detail = get_plugin_data( __FILE__ );
	$installed_version = get_option( 'visitors-traffic-real-time-statistics-pro-version' );
	if( $installed_version != $woodhl_detail['Version'] ){
		add_action( 'plugins_loaded', 'ahcfree_init' );
		update_option( 'visitors-traffic-real-time-statistics-pro-version', $woodhl_detail['Version'] );
	}
}

if(ahcfree_GetWPTimezoneString() !='')
{
	date_default_timezone_set('UTC');
	//date_default_timezone_set(ahcfree_GetWPTimezoneString());
}


function ahcfree_HideMessageAjaxFunction()
	{  
		add_option( 'ahcfree_upgrade_message','yes');
	}  

	
function ahcfree_after_plugin_row($plugin_file, $plugin_data, $status) {
	        
			if(get_option('ahcfree_upgrade_message') !='yes')
			{
				$class_name = $plugin_data['slug'];
				
				$upgradeMsg = '<tr id="' .$class_name. '-plugin-update-tr" class="plugin-update-tr active">';
		        $upgradeMsg .= '<td  colspan="4" class="plugin-update">';
		        $upgradeMsg .= '<div id="' .$class_name. '-upgradeMsg" class="update-message" style="background:#FFF8E5; padding-left:10px; border-left:#FFB900 solid 4px" >';

				$upgradeMsg .= 'You are running visitor traffic free. To get more features (<b style="color:red">Online users, GEO locations and visitors on the map</b>), you can <a href="https://www.wp-buy.com/product/visitors-traffic-real-time-statistics-pro/#plugins-page" target="_blank"><strong>upgrade now</strong></a> or ';
				        
				$upgradeMsg .= '<span id="HideMe" style="cursor:pointer" ><a href="javascript:void(0)"><strong>dismiss</strong></a> this message</span>';
				$upgradeMsg .= '</div>';
		        $upgradeMsg .= '</td>';
		        $upgradeMsg .= '</tr>';
		        
				
		        echo $upgradeMsg;
		        ?>
		        
		        <script type="text/javascript">
			    jQuery(document).ready(function() {
				    var row = jQuery('#<?php echo $class_name;?>-plugin-update-tr').closest('tr').prev();
				    jQuery(row).addClass('update');
					
					
					jQuery("#HideMe").click(function(){ 
					  
					jQuery("#<?php echo $class_name;?>-upgradeMsg").empty(); 
					jQuery("#<?php echo $class_name;?>-upgradeMsg").removeAttr("style"); 
					
					localStorage.setItem("vtrts_hide_upgrade_message", "hide_msg");

					  
				  });
				  
				  if(localStorage.getItem("vtrts_hide_upgrade_message") == "hide_msg")
				  {
					 
					  jQuery("#<?php echo $class_name;?>-upgradeMsg").empty(); 
					  jQuery("#<?php echo $class_name;?>-upgradeMsg").removeAttr("style"); 
				  }
  
			    });
			    </script>
		        
		        
		        <?php
				
			}
	    }

function ahcfree_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page=cp_plugins_settings_start_page_slug' ) ) . '">' . __( 'Dashboard', 'textdomain' ) . '</a>',
		'<a href="' . esc_url( admin_url( '/admin.php?page=ahc_hits_counter_settings' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
	), $links );
	return $links;
}

function ahcfree_row_meta( $meta_fields, $file ) {

      if ( strpos($file,'Visitors-Traffic-Real-Time-Statistics.php') == false) {

        return $meta_fields;
      }

      echo "<style>.pluginrows-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.pluginrows-rate-stars svg{ fill:#ffb900; } .pluginrows-rate-stars svg:hover{ fill:#ffb900 } .pluginrows-rate-stars svg:hover ~ svg{ fill:none; } </style>";

      $plugin_rate   = "https://wordpress.org/support/plugin/visitors-traffic-real-time-statistics/reviews/?rate=5#new-post";
      $plugin_filter = "https://wordpress.org/support/plugin/visitors-traffic-real-time-statistics/reviews/?filter=5";
      $svg_xmlns     = "https://www.w3.org/2000/svg";
      $svg_icon      = '';

      for ( $i = 0; $i < 5; $i++ ) {
        $svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
      }

      $meta_fields[] = '<a href="' . esc_url( $plugin_filter ) . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __( 'Vote!', 'pluginrows' ) . '</a>';
      $meta_fields[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'pluginrows' ) . "'><i class='pluginrows-rate-stars'>" . $svg_icon . "</i></a>";

      return $meta_fields;
}


$path = plugin_basename( __FILE__ );
add_action("after_plugin_row_{$path}", 'ahcfree_after_plugin_row', 10, 3);
add_action( 'wp_ajax_ahcfree_HideMessageAjaxFunction', 'ahcfree_HideMessageAjaxFunction' ); 
add_filter( 'plugin_row_meta', 'ahcfree_row_meta', 10, 4 );
add_action( 'plugin_action_links_' . $path, 'ahcfree_action_links' );
add_option( 'ahcfree_new_updates', date('Y-m-d', time()));


if(get_option('ahcfree_hide_top_bar_icon') != '1')
{
add_action('admin_bar_menu', 'vtrts_free_add_items',  40);
add_action('wp_enqueue_scripts', 'vtrts_free_top_bar_enqueue_style');
add_action('admin_enqueue_scripts', 'vtrts_free_top_bar_enqueue_style');
}
		
//--------------------------------------------
?>
