<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

/** @class WC_Pelecard_Transaction */
class WC_Pelecard_Transaction {
	
	public function __construct( $transaction_id = '', $data = array() ) {
		if ( ! $data ) {
			$data = $this->get_transaction( $transaction_id );
		}
		
		$this->populate( $data );
	}
	
	/**
	 * __get function.
	 *
	 * @param mixed $key
	 * @return mixed
	 */
	public function __get( $key ) {
		return isset( $this->{$key} ) ? $this->{$key} : null;
	}
	
	/**
	 * Getting all the details of the transaction.
	 */
	protected function get_transaction( $transaction_id ) {
		$options = get_option( 'woocommerce_pelecard_settings' );
		$args = array(
			'terminal' 		=> $options['terminal'],
			'user' 			=> $options['username'],
			'password' 		=> $options['password'],
			'TransactionId' => $transaction_id,
		);
		
		// API request
		return WC_Pelecard_API::request( $args, 'PaymentGW/GetTransaction' );
	}
	
	/**
	 * Populates a transaction from the loaded data.
	 */
	public function populate( $data ) {
		if ( ! is_array( $data ) ) {
			$data = (array) $data;
		}
		array_walk_recursive( $data, array( $this, 'sanitize' ) );
	}
	
	// PHP < 5.4
	protected function sanitize( &$value, $key ) {
		if ( 'InvoiceLink' === $key ) {
			$this->{$key} = wc_sanitize_permalink( $value );
		} else {
			$this->{$key} = wc_clean( $value );
		}
	}
	
	/**
	 * Validate transaction by unique key.
	 */
	public function validate( $unique_key ) {
		$args = array(
			'ConfirmationKey' 	=> $this->ConfirmationKey,
			'TotalX100'			=> $this->DebitTotal,
			'UniqueKey'			=> $unique_key
		);
		
		// API request
		return (bool) WC_Pelecard_API::request( $args, 'PaymentGW/ValidateByUniqueKey' );
	}
	
	/**
	 * Check if transaction was successful.
	 */
	public function is_success() {
		return '000' === "{$this->StatusCode}";
	}
	
	/**
	 * Get the order ID from transaction.
	 */
	public function get_order_id() {
		$order_id = 0;
		if ( isset( $this->AdditionalDetailsParamX ) ) {
			$order_id = $this->AdditionalDetailsParamX;
		} elseif ( isset( $this->ParamX ) ) {
			$order_id = $this->ParamX;
		}
		return absint( $order_id );
	}
	
}
