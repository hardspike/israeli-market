<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

/** @class WC_Pelecard_API */
class WC_Pelecard_API {
	
	const ENDPOINT = 'https://gateway20.pelecard.biz/';
	
	/**
	 * Pelecard API request.
	 *
	 * @param  array  $request
	 * @param  string $scheme
	 * @param  string $method
	 * @return array|void
	 */
	public static function request( $request, $scheme = 'PaymentGW/init', $method = 'POST' ) {
		$api_response = wp_remote_post(
			self::ENDPOINT . $scheme,
			apply_filters( 'wc_pelecard_gateway_request_args', array(
				'method' 		=> $method,
				'body' 			=> apply_filters( 'wc_pelecard_gateway_request_body', json_encode( $request ), $scheme ),
				'user-agent' 	=> 'WooCommerce ' . WC()->version
			) )
		);
		
		if ( is_wp_error( $api_response ) ) {
			$error_message = $api_response->get_error_message();
			WC_Pelecard::log( $error_message );
			
			// Enable Debug logging to the /wp-content/debug.log file
			if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
				error_log( $error_message );
			}
			
			// Enable display of errors and warnings 
			if ( defined( 'WP_DEBUG' ) && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG && WP_DEBUG_DISPLAY ) {
				wc_add_notice( $error_message, 'error' );
			}
			
			return;
		}

		return json_decode( wp_remote_retrieve_body( $api_response ), true );
	}
	
	/**
	 * Retrieve the raw request entity (body)
	 *
	 * @since 1.2.0
	 * @return string
	 */
	public static function get_raw_data() {
		// $HTTP_RAW_POST_DATA is deprecated on PHP 5.6
		if ( function_exists( 'phpversion' ) && version_compare( phpversion(), '5.6', '>=' ) ) {
			return file_get_contents( 'php://input' );
		}

		global $HTTP_RAW_POST_DATA;

		// A bug in PHP < 5.2.2 makes $HTTP_RAW_POST_DATA not set by default,
		// but we can do it ourself.
		if ( ! isset( $HTTP_RAW_POST_DATA ) ) {
			$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
		}

		return $HTTP_RAW_POST_DATA;
	}
	
}
