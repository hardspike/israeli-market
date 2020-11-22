<?php
if ( ! defined( 'ABSPATH' ) ) {
  // Exit if accessed directly.
  exit;
}

//////////CLASS NAME MUST BE UNIQUE RELATED TO PLUGIN NAME//////////
if ( ! class_exists( 'wp_visitors_traffic_statistics' ) ) :

  class wp_visitors_traffic_statistics {//////////USE YOUR OWN CLASS NAME//////////
	
	public $notify_array_data = array();
	public $development_mode = false;// Put yes to allow development mode, you will see the rating notice without timers
	public $file_version = 3.3;//
	/* * * * * * * * * *
    * Class constructor
    * * * * * * * * * */
    public function __construct() {
		///////////////////THIS MUST BE CHANGED FOR EACH PLUGIN BEGIN////////////////
		$this->notify_array_data["plugin_real_slug"] = "visitors-traffic-real-time-statistics";
		$this->notify_array_data["variable_slug"] = "wp_visitors_traffic_statistics_";// Use your own prefix
		$this->notify_array_data["message_header"] = __( 'Leave A Review?', $this->notify_array_data["plugin_real_slug"]);// Use your own prefix
		$this->notify_array_data["message_body"] = __( 'We hope you\'ve enjoyed using visitor traffic real time statistics plugin :), Would you mind taking a few minutes to write a review on WordPress.org?<br>Just writing simple "thank you" will make us happy!', $this->notify_array_data["plugin_real_slug"]);// Use your own prefix
		///////////////////THIS MUST BE CHANGED FOR EACH PLUGIN END////////////////
		
		$this->notify_array_data["icon_path"] = plugins_url( '/notifications/icon-128x128.png' , __FILE__);
		$this->notify_array_data["rating_url"] = "https://wordpress.org/support/plugin/".$this->notify_array_data["plugin_real_slug"]."/reviews/#new-post";
		$this->notify_array_data["activation_period"] = 604800; // 7 DAYS IN SECONDS
		if ($this->development_mode)
		{
			delete_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_active_time' );
			delete_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_review_dismiss' );
		}
		$this->_hooks();
    }

    /**
    * Hook into actions and filters
    * @since  1.0.0
    * @version 1.2.1
    */
    private function _hooks() {
      add_action( 'admin_init', array( $this, "notification_bar_review_notice3" ) );
    }
	
	/**
  	 * Ask users to review our plugin on wordpress.org
  	 *
  	 * @since 1.0.11
  	 * @return boolean false
  	 * @version 1.1.3
  	 */

  	public function notification_bar_review_notice3() {
		
		$this->notification_bar_review_dismissal();
		
  		$this->notification_bar_review_pending();
		
		$activation_time 	= get_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_active_time' );
		
  		$review_dismissal	= get_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_review_dismiss' );
		
		if ($review_dismissal == 'yes' && !$this->development_mode) return;
		
		if ( !$activation_time && !$this->development_mode ) :

  			$activation_time = time(); // Reset Time to current time.
  			add_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_active_time', $activation_time );
			
  		endif;
		if ($this->development_mode) $this->notify_array_data["activation_period"] = 0; //This variable used to show the message always for testing purposes only

  		if ( time() - $activation_time > $this->notify_array_data["activation_period"] ) :

			wp_enqueue_style( $this->notify_array_data["variable_slug"] . 'notification_bar_review_stlye', plugins_url( '/notifications/style-review.css', __FILE__ ), array(), $this->file_version );
			add_action( 'admin_notices' , array( $this, 'notification_bar_review_notice_message' ) );
		
		endif;
  	}

    /**
  	 *	Check and Dismiss review message.
  	 *
  	 *	@since 1.9
  	 */
  	private function notification_bar_review_dismissal() {
  		if ( ! is_admin() ||
  			! current_user_can( 'manage_options' ) ||
  			! isset( $_GET['_wpnonce'] ) ||
  			! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), "notification_bar_review_nonce" ) ||
  			! isset( $_GET[$this->notify_array_data["variable_slug"].'notification_bar_review_dismiss'] ) ) :

  			return;
  		endif;

  		add_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_review_dismiss', 'yes' );
  	}

    /**
  	 * Set time to current so review notice will popup after 14 days
  	 *
  	 * @since 1.9
  	 */
  	private function notification_bar_review_pending() {
  		if ( ! is_admin() ||
  			! current_user_can( 'manage_options' ) ||
  			! isset( $_GET['_wpnonce'] ) ||
  			! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), "notification_bar_review_nonce" ) ||
  			! isset( $_GET[$this->notify_array_data["variable_slug"] . 'notification_bar_review_later'] ) ) :

  			return;
  		endif;

  		// Reset Time to current time.
  		update_site_option( $this->notify_array_data["variable_slug"] . 'notification_bar_active_time', time() );
  	}

    /**
  	 * Review notice message
  	 *
  	 * @since  1.0.11
  	 */
  	public function notification_bar_review_notice_message() {
		
		$scheme      = ( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ) ) ? '&' : '?';
  		$url         = $_SERVER['REQUEST_URI'] . $scheme . $this->notify_array_data["variable_slug"] . 'notification_bar_review_dismiss=yes';
  		$dismiss_url = wp_nonce_url( $url, "notification_bar_review_nonce" );

  		$_later_link = $_SERVER['REQUEST_URI'] . $scheme . $this->notify_array_data["variable_slug"] . 'notification_bar_review_later=yes';
  		$later_url   = wp_nonce_url( $_later_link, "notification_bar_review_nonce" );
      ?>

  		<div class="notification_bar_review-notice">
  			<div class="notification_bar_review-thumbnail">
  				<img src="<?php echo $this->notify_array_data["icon_path"]; ?>" alt="">
  			</div>
  			<div class="notification_bar_review-text">
  				<h3><?php echo $this->notify_array_data["message_header"]; ?></h3>
  				<p><?php echo $this->notify_array_data["message_body"]; ?></p>
  				<ul class="notification_bar_review-ul">
            <li><a href="<?php echo $this->notify_array_data["rating_url"]; ?>" target="_blank"><span class="dashicons dashicons-external"></span><?php _e( 'Sure! I\'d love to!', $this->notify_array_data["plugin_real_slug"] ) ?></a></li>
            <li><a href="<?php echo $dismiss_url ?>"><span class="dashicons dashicons-smiley"></span><?php _e( 'I\'ve already left a review', $this->notify_array_data["plugin_real_slug"] ) ?></a></li>
            <li><a href="<?php echo $later_url ?>"><span class="dashicons dashicons-calendar-alt"></span><?php _e( 'Will Rate Later', $this->notify_array_data["plugin_real_slug"] ) ?></a></li>
            <li><a href="<?php echo $dismiss_url ?>"><span class="dashicons dashicons-dismiss"></span><?php _e( 'Hide Forever', $this->notify_array_data["plugin_real_slug"] ) ?></a></li></ul>
  			</div>
  		</div>
  	<?php
  	}
}

endif;

//Call all that work
new wp_visitors_traffic_statistics(); //////////MUST BE RIGHT OBJECT SAME AS CLASS NAME//////////
?>