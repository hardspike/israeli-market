<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

/*
Plugin Name: Woo Pelecard Gateway
Plugin URI:  https://wordpress.org/plugins/woo-pelecard-gateway/
Description: Extends WooCommerce with Pelecard payment gateway.
Version:     1.2.2
Author:      Ido Friedlander
Author URI:  https://profiles.wordpress.org/idofri/
Text Domain: woo-pelecard-gateway
*/

/** @class WC_Pelecard */
final class WC_Pelecard {
	
	/** @var WC_Logger Logger instance */
	public static $log = false;
	
	/** The single instance of the class. */
	protected static $_instance = null;
	
	/**
	 * Notices (array)
	 * @var array
	 */
	public $notices = array();
	
	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woo-pelecard-gateway' ), '1.2.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woo-pelecard-gateway' ), '1.2.0' );
	}
	
	/**
	 * __get function.
	 *
	 * @param mixed $key
	 * @return mixed
	 */
	public function __get( $key ) {
		$method = "get_{$key}";
		if ( method_exists( $this, $method ) ) {
			return $this->$method();
		}
	}
	
	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Logging method.
	 *
	 * @param string $message
	 */
	public static function log( $message ) {
		if ( empty( self::$log ) ) {
			self::$log = new WC_Logger();
		}
		self::$log->add( 'wc_pelecard_gateway', $message );
	}
	
	protected function __construct() {
		if ( ! defined( 'WC_PELECARD_PLUGIN_FILE' ) ) {
			define( 'WC_PELECARD_PLUGIN_FILE', __FILE__ );
		}
		
		add_action( 'admin_init', array( $this, 'check_environment' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
	}
	
	/**
	 * Init the plugin after plugins_loaded so environment variables are set.
	 *
	 * @return void
	 */
	public function init() {
		// bail
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}
		
		// Includes
		include_once( dirname( __FILE__ ) . '/includes/class-wc-pelecard-api.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-pelecard-gateway.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-pelecard-metabox.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-pelecard-transaction.php' );
		
		// Init metabox
		WC_Pelecard_Metabox::instance();
		
		// Init the gateway itself
		$this->init_gateways();
		
		// Check gateways response
		add_action( 'wp', array( $this, 'handle_api_requests' ), 10 );
	}
	
	/**
	 * Checks the environment for compatibility problems.
	 *
	 * @return void
	 */
	public function check_environment() {
		if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$class = 'notice notice-error is-dismissible';
			$message = __( 'This plugin requires <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> to be active.', 'woo-pelecard-gateway' );
			$this->add_admin_notice( $class, $message );
			// Deactivate the plugin
			deactivate_plugins( __FILE__ );
			return;
		}
		
		$php_version = phpversion();
		$required_php_version = '5.3';
		if ( version_compare( $required_php_version, $php_version, '>' ) ) {
			$class = 'notice notice-warning is-dismissible';
			$message = sprintf( __( 'Your server is running PHP version %1$s but some features requires at least %2$s.', 'woo-pelecard-gateway' ), $php_version, $required_php_version );
			$this->add_admin_notice( $class, $message );
		}
		
		if ( ! in_array( get_woocommerce_currency(), apply_filters( 'wc_pelecard_gateway_allowed_currencies', array( 'ILS', 'USD', 'EUR' ) ) ) ) {
			$class = 'notice notice-error is-dismissible';
			$message = __( 'No support for your store currency.', 'woo-pelecard-gateway' );
			$this->add_admin_notice( $class, $message );
		}
	}
	
	/**
	 * Add admin notices.
	 *
	 * @param string $class
	 * @param string $message
	 */
	public function add_admin_notice( $class, $message ) {
		$this->notices[] = array(
			'class'   => $class,
			'message' => $message
		);
	}
	
	/**
	 * Display any notices collected.
	 *
	 * @return void
	 */
	public function admin_notices() {
		foreach ( (array) $this->notices as $notice ) {
			echo '<div class="' . esc_attr( $notice['class'] ) . '"><p><b>' . __( 'Woo Pelecard Gateway', 'woo-pelecard-gateway' ) . ': </b>';
			echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) );
			echo '</p></div>';
		}
	}
	
	/**
	 * Returns current plugin version.
	 *
	 * @return string Plugin version
	 */
	protected function get_version() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
		$plugin_file = basename( ( __FILE__ ) );
		return $plugin_folder[ $plugin_file ]['Version'];
	}
	
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=pelecard' ) . '" aria-label="' . esc_attr__( 'View Pelecard settings', 'woo-pelecard-gateway' ) . '">' . esc_html__( 'Settings', 'woo-pelecard-gateway' ) . '</a>'
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Handle API requests.
	 *
	 * @return void
	 */
	public function handle_api_requests() {
		// bail
		if ( empty( $_POST['PelecardTransactionId'] ) ) {
			return;
		}
		
		// Transaction
		$transaction = new WC_Pelecard_Transaction( null, $_POST );
		
		// Timeout
		if ( 301 == $transaction->PelecardStatusCode ) {
			$order_id = $transaction->get_order_id();
			if ( ! $order = wc_get_order( $order_id ) ) {
				return;
			}
			$this->gateway()->do_payment( $transaction, $order );
			return;
		}
		
		// Add method
		if ( is_user_logged_in() && is_add_payment_method_page() ) {
			$this->gateway()->add_payment_method( $transaction );
		}
	}
	
	/**
	 * Initialize the gateway.
	 *
	 * @return void
	 */
	public function init_gateways() {
		load_plugin_textdomain( 'woo-pelecard-gateway' );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'woocommerce_add_pelecard_gateway' ) );
	}
	
	/**
	 * Add the gateways to WooCommerce.
	 *
	 * @param array $methods
	 */
	public function woocommerce_add_pelecard_gateway( $methods ) {
		$methods[] = $this->gateway();
		return $methods;
	}
	
	/**
	 * Gateway instance.
	 *
	 * @return WC_Pelecard_Gateway
	 */
	public function gateway() {
		return WC_Pelecard_Gateway::instance();
	}
	
}

/**
 * @return WC_Pelecard
 */
function WC_Pelecard() {
	return WC_Pelecard::instance();
}

// Global for backwards compatibility.
$GLOBALS['wc_pelecard'] = WC_Pelecard();
