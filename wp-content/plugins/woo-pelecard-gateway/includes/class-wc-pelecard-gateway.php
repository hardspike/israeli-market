<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

// backward compatibility
if ( ! class_exists( 'WC_Payment_Gateway_CC' ) ) {
	class WC_Payment_Gateway_CC extends WC_Payment_Gateway {}
}

/** @class WC_Pelecard_Gateway */
class WC_Pelecard_Gateway extends WC_Payment_Gateway_CC {
	
	/** The single instance of the class. */
	protected static $_instance = null;
	
	/**
	 * Returns the *Singleton* instance of this class.
	 * @return Singleton The *Singleton* instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * @return string
	 */
	public function __toString() {
		return strtolower( get_class( $this ) );
	}
	
	public function __construct() {
		// Global variables
		$this->id 					= 'pelecard';
		$this->icon 				= apply_filters( 'wc_pelecard_gateway_icon', plugins_url( '/assets/images/pelecard.png', WC_PELECARD_PLUGIN_FILE ) );
		$this->order_button_text 	= apply_filters( 'wc_pelecard_gateway_order_button_text', __( 'Proceed to Pelecard', 'woo-pelecard-gateway' ), false );
		$this->method_title 		= apply_filters( 'wc_pelecard_gateway_method_title', __( 'Pelecard', 'woo-pelecard-gateway' ) );
		$this->method_description 	= apply_filters( 'wc_pelecard_gateway_method_description', '' );
		$has_fields 				= false;
		$supports					= array( 'products' );
		
		/** @since 1.2.0 */
		if ( version_compare( WC()->version, '2.6', '>=' ) ) {
			$has_fields = true;
			$supports[] = 'tokenization';
		}
		
		$this->has_fields 			= apply_filters( 'wc_pelecard_gateway_has_fields', $has_fields );
		$this->supports 			= apply_filters( 'wc_pelecard_gateway_supports', $supports );
		
		// Load settings
		$this->init_form_fields();
		$this->init_settings();
		
		// Define user set variables
		$this->title          	= $this->get_option( 'title' );
		$this->description    	= $this->get_option( 'description' );
		$this->terminal    	  	= $this->get_option( 'terminal' );
		$this->username   	  	= $this->get_option( 'username' );
		$this->password 		= $this->get_option( 'password' );
		$this->language 		= $this->get_option( 'language' );
		$this->cardholdername	= $this->get_option( 'cardholdername' );
		$this->customeridfield	= $this->get_option( 'customeridfield' );
		$this->cvvfield			= $this->get_option( 'cvv2field' );
		$this->maxpayments		= $this->get_option( 'maxpayments' );
		$this->minpayments		= $this->get_option( 'minpayments' );
		$this->mincredit		= $this->get_option( 'mincredit' );
		$this->firstpayment		= $this->get_option( 'firstpayment' );
		$this->confirmationcb	= $this->get_option( 'confirmationcb' );
		$this->confirmationtext	= $this->get_option( 'confirmationtext' );
		$this->confirmationurl	= $this->get_option( 'confirmationurl' );
		$this->logourl			= $this->get_option( 'logourl' );
		$this->cssurl			= $this->get_option( 'cssurl' );
		$this->toptext			= $this->get_option( 'toptext' );
		$this->bottomtext		= $this->get_option( 'bottomtext' );
		$this->emailfield		= $this->get_option( 'emailfield' );
		$this->telfield			= $this->get_option( 'telfield' );
		$this->cancelbutton		= $this->get_option( 'cancelbutton' );
		$this->paymentrange 	= $this->get_option( 'paymentrange', array() );
		$this->setfocus 		= $this->get_option( 'setfocus' );
		$this->supportedcards	= $this->supported_cards( $this->get_option( 'supportedcards', array() ) );
		$this->splitccnumber	= 'yes' === $this->get_option( 'splitccnumber' ) ? true : false;
		$this->freetotal		= 'yes' === $this->get_option( 'freetotal' ) ? true : false;
		$this->hiddenpelecard	= 'yes' === $this->get_option( 'hiddenpelecard' ) ? false : true;
		$this->currency			= $this->get_currency();
		
		// Tamal variables
		$this->tamal			= 'yes' === $this->get_option( 'tamal' ) ? true : false;
		$this->tamaluser		= $this->get_option( 'tamaluser' );
		$this->tamalpassword	= $this->get_option( 'tamalpassword' );
		$this->tamaleseknum		= $this->get_option( 'tamaleseknum' );
		$this->tamalosek		= $this->get_option( 'tamalosek' );
		$this->tamaldoctype		= $this->get_option( 'tamaldoctype' );
		$this->tamallanguage	= $this->get_option( 'tamallanguage' );
		$this->tamaldocremark	= $this->get_option( 'tamaldocremark' );
		
		/**
		 * Hook terminal
		 *
		 * @since 1.2.0
		 */
		$this->hook_terminal    = $this->get_option( 'hook_terminal' );
		$this->hook_username   	= $this->get_option( 'hook_username' );
		$this->hook_password 	= $this->get_option( 'hook_password' );
		
		// Save settings
		add_action( "woocommerce_update_options_payment_gateways_{$this->id}", array( &$this, 'process_admin_options' ) );
		add_action( "woocommerce_update_options_payment_gateways_{$this->id}", array( &$this, 'save_payment_range' ) );
		
		// Add hooks
		add_action( 'wp_enqueue_scripts', 				array( $this, 'enqueue_style' ) );
		add_action( 'admin_enqueue_scripts', 			array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', 			array( $this, 'admin_scripts' ) );
		add_action( "woocommerce_receipt_{$this->id}", 	array( $this, 'receipt_page' ) );
		add_action( "woocommerce_api_{$this}", 			array( $this, 'check_ipn_response' ) );
		
		/** @since 1.2.0 */
		add_filter( 'woocommerce_available_payment_gateways', 				array( $this, 'selected_payment_gateways' ) );
		add_filter( 'woocommerce_get_customer_payment_tokens', 				array( $this, 'selected_customer_payment_token' ), 10, 3 );
		add_filter( 'wc_payment_gateway_form_saved_payment_methods_html',  	array( $this, 'payment_methods_html' ) );
		
		/** @since 1.2.1 */
		add_filter( version_compare( WC()->version, '3.0.9', '>=' ) ? 'woocommerce' : 'wocommerce' . '_credit_card_type_labels', array( $this, 'credit_card_type_labels' ) );
	}
	
	/**
	 * Display selected payment token on checkout pay page.
	 *
	 * @param  array $tokens
	 * @param  int $customer_id
	 * @param  string $gateway_id
	 * @return array
	 */
	public function selected_customer_payment_token( $tokens, $customer_id, $gateway_id ) {
		if ( $gateway_id === $this->id && ! empty( WC()->session->selected_token_id ) && is_checkout_pay_page() ) {
			$selected_token_id = WC()->session->selected_token_id;
			// bail
			if ( ! array_key_exists( $selected_token_id, $tokens ) ) {
				return $tokens;
			}
			$selected_token = $tokens[ $selected_token_id ];
			$selected_token->set_default( true );
			$tokens = array( $selected_token_id => $selected_token );
			
			// Remove new payment option
			add_filter( 'woocommerce_payment_gateway_get_new_payment_method_option_html', '__return_empty_string' );
		}
		return $tokens;
	}
	
	/**
	 * Get payments field html.
	 *
	 * @param  array $available_gateways
	 * @return array
	 */
	public function payment_methods_html( $html ) {
		global $wp;
		
		// bail
		if ( ! is_checkout_pay_page() ) {
			return $html;
		}
		
		$order_id = absint( $wp->query_vars['order-pay'] );
		$order = wc_get_order( $order_id );
		$payments = array(
			'MaxPayments'			=> $this->get_range( $order->get_total(), 'max', $this->maxpayments ),
			'MinPayments'			=> $this->get_range( $order->get_total(), 'min', $this->minpayments ),
			'MinPaymentsForCredit'	=> $this->mincredit
		);
		
		// bail
		if ( $payments['MaxPayments'] !== $payments['MinPayments'] || 1 == $payments['MinPayments'] ) {
			$html .= wc_get_template_html( 'checkout/number-of-payments.php', array( 'payments' => $payments ), null, WC_Pelecard()->plugin_path() . '/templates/' );
		}
		
		return $html;
	}
	
	/**
	 * Display selected payment gateway on checkout pay page.
	 *
	 * @param  array $available_gateways
	 * @return array
	 */
	public function selected_payment_gateways( $available_gateways ) {
		$is_checkout_pay_page = is_checkout_pay_page();
		if ( $is_checkout_pay_page ) {
			$gateway = $available_gateways[ $this->id ];
			$gateway->order_button_text = apply_filters( 'wc_pelecard_gateway_order_button_text', __( 'Pay for order', 'woo-pelecard-gateway' ), $is_checkout_pay_page );
			if ( ! empty( WC()->session->selected_token_id ) ) {
				$available_gateways = array( $this->id => $gateway );
			}
		}
		return $available_gateways;
	}
	
	/**
	 * Add credit card types and labels.
	 *
	 * @param  array $labels
	 * @return array
	 */
	public function credit_card_type_labels( $labels ) {
		return array_merge( $labels, apply_filters( 'wc_pelecard_gateway_credit_card_type_labels', array(
			'maestro' 		=> __( 'Maestro', 'woo-pelecard-gateway' ),
			'isracard' 		=> __( 'Isracard', 'woo-pelecard-gateway' ),
			'leumi card' 	=> __( 'Leumi Card', 'woo-pelecard-gateway' )
		) ) );
	}
	
	/**
	 * Get supported cards.
	 *
	 * @param  array $cards
	 * @return array
	 */
	protected function supported_cards( $cards = array() ) {
		$defaults = array(
			'Amex' 		=> false, 
			'Diners' 	=> false, 
			'Isra' 		=> false, 
			'Master' 	=> false, 
			'Visa' 		=> false
		);
		return array_merge( $defaults, array_map( '__return_true', array_flip( $cards ) ) );
	}
	
	/**
	 * Register and enqueue a styles for use.
	 *
	 * @return void
	 */
	public function enqueue_style() {
		wp_enqueue_style( 
			'wc-pelecard-gateway-css', 
			plugins_url( '/assets/css/checkout' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.css', WC_PELECARD_PLUGIN_FILE ), 
			array(), 
			WC_Pelecard()->version 
		);
	}
	
	/**
	 * Register and enqueue admin styles.
	 *
	 * @return void
	 */
	public function admin_styles() {
		wp_enqueue_style( 
			'wc-pelecard-gateway-admin', 
			plugins_url( '/assets/css/admin' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.css', WC_PELECARD_PLUGIN_FILE ), 
			array(), 
			WC_Pelecard()->version 
		);
	}
	
	/**
	 * Register and enqueue admin scripts.
	 *
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script(
			'wc-pelecard-gateway-settings', 
			plugins_url( '/assets/js/settings' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js', WC_PELECARD_PLUGIN_FILE ), 
			array( 'jquery' ), 
			WC_Pelecard()->version 
		);
	}
	
	/**
	 * Get Pelecard currency code.
	 *
	 * @return int
	 */
	protected function get_currency() {
		$store_currency = get_woocommerce_currency();
		$allowed_currencies = array( 'ILS' => 1, 'USD' => 2, 'EUR' => 978 );
		$currency_code = array_key_exists( $store_currency, $allowed_currencies ) ? $allowed_currencies[ $store_currency ] : $store_currency;
		return apply_filters( 'wc_pelecard_gateway_currency_code', $currency_code, $store_currency, $allowed_currencies );
	}
	
	/**
	 * Generate HTML Message.
	 *
	 * @param  mixed $key
	 * @param  mixed $data
	 * @return string
	 */
	public function generate_message_html( $key, $data ) {
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		
		?><tr valign="top">
			<th scope="row" class="titledesc">
				<u class="<?php echo esc_attr( $data['class'] ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></u>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr><?php

		return ob_get_clean();
	}
	
	/**
	 * Admin Panel Options.
	 */
	public function admin_options() {
		printf(
			/* translators: %s: pelecard url. */
			'<h3>%s</h3>
			<p>' . __( '<a href="%s" target="_blank">Pelecard</a> provides clearing solutions for over 20 years, and gives a solution for secure and advanced enterprises both large and small, including websites.', 'woo-pelecard-gateway' ) . '</p>',
			__( 'Pelecard', 'woo-pelecard-gateway' ),
			esc_url( 'http://www.pelecard.com' )
		);
		
		do_action( 'wc_pelecard_gateway_admin_options_before_table' );
		
		?><table class="form-table" id="pelecard-settings-block"><?php
		$this->pelecard_admin_tabs();
		$this->generate_settings_html();
		?></table><?php
		
		do_action( 'wc_pelecard_gateway_admin_options_after_table' );
	}
	
	/**
	 * Outputs settings-page tabs.
	 */
	protected function pelecard_admin_tabs() {
		$tabs = apply_filters( 'wc_pelecard_gateway_admin_tabs', array(
			'pelecard-tab-general' 		=> __( 'General', 'woo-pelecard-gateway' ), 
			'pelecard-tab-terminal' 	=> __( 'Terminal', 'woo-pelecard-gateway' ),
			'pelecard-tab-payment' 		=> __( 'Payment', 'woo-pelecard-gateway' ),
			'pelecard-tab-fields' 		=> __( 'Fields', 'woo-pelecard-gateway' ),
			'pelecard-tab-confirmation' => __( 'Confirmation', 'woo-pelecard-gateway' ),
			'pelecard-tab-appearance' 	=> __( 'Appearance', 'woo-pelecard-gateway' ),
			'pelecard-tab-tamal' 		=> __( 'Tamal', 'woo-pelecard-gateway' ) 
		) ); 
		
		?><tr><td colspan="2"><h2 class="nav-tab-wrapper"><?php
		foreach ( $tabs as $tab => $name ) {
			printf( '<a href="#%s" class="nav-tab" data-tab="%s">%s</a>', $tab, $tab, $name );
		}
		?></h2></td></tr><?php
	}
	
	/**
	 * Initialise Gateway Settings Form Fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = apply_filters( 'wc_pelecard_gateway_admin_fields', include( 'settings-pelecard.php' ) );
	}
	
	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$on_checkout = ( isset( $_POST['wc-pelecard-payment-token'] ) && 'new' !== $_POST['wc-pelecard-payment-token'] ) ? false : true;
		$redirect_to = $order->get_checkout_payment_url( $on_checkout );
		
		// Store in session
		WC()->session->save_payment_method = isset( $_POST['wc-pelecard-new-payment-method'] );
		WC()->session->selected_token_id = ( ! empty( $_POST['wc-pelecard-payment-token'] ) ) ? absint( $_POST['wc-pelecard-payment-token'] ) : null;
		
		if ( is_checkout_pay_page() && ! $on_checkout ) {
			$redirect_to = $this->get_return_url( $order );
			$token_id = wc_clean( $_POST['wc-pelecard-payment-token'] );
			$token = WC_Payment_Tokens::get( $token_id );
			if ( $token->get_user_id() !== get_current_user_id() ) {
				wc_add_notice( __( 'Please make sure your card details have been entered correctly and that your browser supports JavaScript.', 'woo-pelecard-gateway' ), 'error' );
				return;
			}
			
			$args = array(
				'terminalNumber' 	=> ! empty( $this->hook_terminal ) ? $this->hook_terminal : $this->terminal,
				'user' 				=> ! empty( $this->hook_username ) ? $this->hook_username : $this->username,
				'password' 			=> ! empty( $this->hook_password ) ? $this->hook_password : $this->password,
				'shopNumber' 		=> '001',
				'token' 			=> $token->get_token(),
				'total' 			=> $order->get_total() * 100,
				'currency' 			=> $this->currency,
				'paramX' 			=> is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id,
				'UserKey'			=> is_callable( array( $order, 'get_order_key' ) ) ? $order->get_order_key() : $order->order_key
			);
			
			if ( $this->tamal ) {
				$args['TamalInvoice'] = $this->tamal_options( $order );
			}
			
			$scheme = 'DebitRegularType';
			if ( isset( $_POST['num_of_payments'] ) ) {
				$num_of_payments = absint( $_POST['num_of_payments'] );
				if ( 0 === $num_of_payments ) {
					wc_add_notice( __( 'Please select number of payments.', 'woo-pelecard-gateway' ), 'error' );
					return;
				}
				
				$min_payments = $this->get_range( $order->get_total(), 'min', $this->minpayments );
				$max_payments = $this->get_range( $order->get_total(), 'max', $this->maxpayments );
				if ( 1 < $num_of_payments && $min_payments <= $num_of_payments && $num_of_payments <= $max_payments ) {
					$args['paymentsNumber'] = $num_of_payments;
					$scheme = ( $num_of_payments < $this->mincredit ) ? 'DebitPaymentsType' : 'DebitCreditType';
				}
			}
			
			// API request
			if ( $api_response = WC_Pelecard_API::request( apply_filters( 'wc_pelecard_gateway_rest_api_debit_args', $args, $order, $scheme ), "services/$scheme" ) ) {
				$transaction = new WC_Pelecard_Transaction( null, $api_response );
				$this->do_payment( $transaction, $order );
				if ( $transaction->is_success() ) {
					$redirect_to = $order->get_checkout_order_received_url();
				}
			}
		}
		
		return array(
			'result'    => 'success',
			'redirect'  => $redirect_to
		);
	}
	
	/**
	 * Receipt page.
	 *
	 * @param int $order_id
	 */
	public function receipt_page( $order_id ) {
		$order = wc_get_order( $order_id );
		
		$args = apply_filters( 'wc_pelecard_gateway_iframe_args', array(
			'terminal' 						=> $this->terminal,
			'user' 							=> $this->username,
			'password' 						=> $this->password,
			'GoodURL' 						=> $this->get_return_url( $order ),
			'ErrorURL' 						=> $this->get_return_url( $order ),
			'CancelURL'						=> 'yes' === $this->cancelbutton ? $order->get_cancel_order_url() : '',
			'ActionType' 					=> 'J4',
			'Currency' 						=> $this->currency,
			'Total' 						=> $order->get_total() * 100,
			'FreeTotal' 					=> $this->freetotal,
			'resultDataKeyName'				=> null,
			'ServerSideGoodFeedbackURL' 	=> WC()->api_request_url( "{$this}" ),
			'ServerSideErrorFeedbackURL' 	=> WC()->api_request_url( "{$this}" ),
			'NotificationGoodMail'			=> null,
			'NotificationErrorMail'			=> null,
			'NotificationFailMail'			=> null,
			'CreateToken' 					=> WC()->session->save_payment_method,
			'TokenForTerminal'				=> null,
			'Language' 						=> $this->language,
			'Track2Swipe'					=> false,	
			'TokenCreditCardDigits'			=> null,	
			'CardHolderName' 				=> $this->cardholdername,
			'CustomerIdField'				=> $this->customeridfield,
			'Cvv2Field' 					=> $this->cvvfield,
			'EmailField' 					=> 'value' === $this->emailfield ? ( is_callable( array( $order, 'get_billing_email' ) ) ? $order->get_billing_email() : $order->billing_email ) : $this->emailfield,
			'TelField' 						=> 'value' === $this->telfield ? ( is_callable( array( $order, 'get_billing_phone' ) ) ? $order->get_billing_phone() : $order->billing_phone ) : $this->telfield,
			'SplitCCNumber' 				=> $this->splitccnumber,
			'FeedbackOnTop' 				=> true,
			'FeedbackDataTransferMethod' 	=> 'POST',
			'UseBuildInFeedbackPage' 		=> false,
			'MaxPayments' 					=> $this->get_range( $order->get_total(), 'max', $this->maxpayments ),
			'MinPayments' 					=> $this->get_range( $order->get_total(), 'min', $this->minpayments ),
			'MinPaymentsForCredit' 			=> $this->mincredit,
			'DisabledPaymentNumbers'		=> null,
			'FirstPayment' 					=> $this->firstpayment,
			'AuthNum'						=> null,
			'ShopNo' 						=> '001',
			'ParamX' 						=> is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id,
			'ShowXParam' 					=> false,
			'AddHolderNameToXParam' 		=> false,
			'UserKey'						=> is_callable( array( $order, 'get_order_key' ) ) ? $order->get_order_key() : $order->order_key,
			'SetFocus'						=> $this->setfocus,
			'CssURL'						=> $this->cssurl,
			'TopText'						=> $this->toptext,
			'BottomText'					=> $this->bottomtext,
			'LogoURL'						=> $this->logourl,
			'ShowConfirmationCheckbox' 		=> $this->confirmationcb,
			'TextOnConfirmationBox' 		=> $this->confirmationtext,
			'ConfirmationLink'				=> $this->confirmationurl,
			'HiddenPelecardLogo' 			=> $this->hiddenpelecard,
			'AllowedBINs' 					=> null,
			'BlockedBINs' 					=> null,
			'ShowSubmitButton' 				=> true,
			'SupportedCards' 				=> $this->supportedcards,
			'AccessibilityMode' 			=> false,
			'CustomCanRetrayErrors'			=> array()			
		), $order );
		
		if ( $this->tamal ) {
			$args['TamalInvoice'] = $this->tamal_options( $order );
		}
		
		// API request
		if ( ! $api_response = WC_Pelecard_API::request( $args ) ) {
			return;
		}
		
		$this->get_iframe( $api_response, $order_id );
	}
	
	/**
	 * Return handler for IPN.
	 */
	public function check_ipn_response() {
		$raw_data = json_decode( WC_Pelecard_API::get_raw_data(), true );
		$transaction = new WC_Pelecard_Transaction( null, $raw_data );
		$order_id = $transaction->get_order_id();
		if ( ! $order_id && isset( $raw_data['ResultData']['TransactionId'] ) ) {
			$transaction_id = wc_clean( $raw_data['ResultData']['TransactionId'] );
			$transaction = new WC_Pelecard_Transaction( $transaction_id );
			$order_id = $transaction->get_order_id();
		}
		
		// bail
		if ( ! $order = wc_get_order( $order_id ) ) {
			return;
		}
		
		$validated_ipn_response = $transaction->validate( is_callable( array( $order, 'get_order_key' ) ) ? $order->get_order_key() : $order->order_key );
		if ( apply_filters( 'wc_pelecard_gateway_validate_ipn_response', $validated_ipn_response ) ) {
			$this->do_payment( $transaction, $order );
		}
	}
	
	/**
	 * Outputs the payment iframe.
	 *
	 * @param  array $api_response
	 * @param  int   $order_id
	 */
	protected function get_iframe( $api_response, $order_id = 0 ) {
		if ( isset( $api_response['URL'], $api_response['Error']['ErrCode'] ) && 0 === $api_response['Error']['ErrCode'] ) {
			printf( '<div id="pelecard-iframe-container"><iframe src="%s" frameBorder="0"></iframe></div>', $api_response['URL'] );
		} elseif ( isset( $api_response['Error']['ErrCode'], $api_response['Error']['ErrMsg'] ) ) {
			wc_add_notice( sprintf( __( 'Pelecard error: %s', 'woo-pelecard-gateway' ), $api_response['Error']['ErrMsg'] ), 'error' );
			WC_Pelecard()->log( sprintf( 'Error Code %s: %s', $api_response['Error']['ErrCode'], $api_response['Error']['ErrMsg'] ) );
		}
	}
	
	/**
	 * Get Tamal options.
	 *
	 * @return array
	 */
	protected function tamal_options( $order ) {
		$products = array();
		
		$order_subtotal 	= 0;
		$order_total 		= 0;
		$order_subtotal_tax = 0;
		$order_total_tax 	= 0;
		
		foreach ( $order->get_items() as $item ) {
			$order_subtotal     += wc_format_decimal( isset( $item['line_subtotal'] ) ? $item['line_subtotal'] : 0 );
			$order_total        += wc_format_decimal( isset( $item['line_total'] ) ? $item['line_total'] : 0 );
			$order_subtotal_tax += wc_format_decimal( isset( $item['line_subtotal_tax'] ) ? $item['line_subtotal_tax'] : 0 );
			$order_total_tax    += wc_format_decimal( isset( $item['line_tax'] ) ? $item['line_tax'] : 0 );
			
			$products[] = array(
				'Description' 	=> $item['name'],
				'Price'			=> ( $item['subtotal'] / $item['quantity'] ) * 100,
				'Quantity'		=> $item['qty']
			);
		}
		
		if ( 0 < $order->get_total_shipping() ) {
			$shipping_total = ceil( ( $order->get_total_shipping() + $order->get_shipping_tax() ) * 100 );
			$products[] = array(
				'Description' 	=> $order->get_shipping_method(),
				'Price'			=> $shipping_total,
				'Quantity'		=> 1
			);
		}
		
		$args = array(
			'InvoiceUserName' 	=> $this->tamaluser,
			'InvoicePassword' 	=> $this->tamalpassword,
			'EsekNum' 			=> $this->tamaleseknum,
			'TypeCode' 			=> $this->tamaldoctype,
			'PrintLanguage' 	=> $this->tamallanguage,
			'ClientNumber' 		=> 200000,
			'ClientName' 		=> $order->get_formatted_billing_full_name(),
			'ClientAddress' 	=> is_callable( array( $order, 'get_billing_address_1' ) ) ? $order->get_billing_address_1() : $order->billing_address_1,
			'ClientCity' 		=> is_callable( array( $order, 'get_billing_city' ) ) ? $order->get_billing_city() : $order->billing_city,
			'EmailAddress' 		=> is_callable( array( $order, 'get_billing_email' ) ) ? $order->get_billing_email() : $order->billing_email,
			'NikuyBamakorSum' 	=> 0,
			'MaamRate' 			=> 'mursh' === $this->tamalosek ? 999 : 0,
			'DocDetail' 		=> sprintf( __( 'Order #%s', 'woo-pelecard-gateway' ), $order->get_order_number() ),
			'ToSign' 			=> 1,
			'DocRemark' 		=> $this->tamaldocremark,
			'ProductsList' 		=> $products,
			'DiscountAmount' 	=> ''
		);
		
		$order_discount = $order_subtotal - $order_total;
		$order_discount_tax = $order_subtotal_tax - $order_total_tax;
		$order_discount_total = round( $order_discount + $order_discount_tax, wc_get_price_decimals() );
		
		if ( $order_discount_total ) {
			$args['DiscountAmount'] = $order_discount_total * 100;
		}
		
		return apply_filters( 'wc_pelecard_gateway_tamal_args', $args, $order );
	}
	
	/**
	 * Add payment method via account screen.
	 */
	public function add_payment_method( $transaction = '' ) {
		// intercept WC_Form_Handler method calls.
		if ( empty( $transaction ) ) {
			wc_add_notice( __( 'Please use the payment button inside the form.', 'woo-pelecard-gateway' ), 'error' );
			return;
		}
		
		// Transaction
		$transaction = new WC_Pelecard_Transaction( $transaction->PelecardTransactionId );
		
		// Save token
		if ( ! $transaction->is_success() || ! $this->save_token( $transaction ) ) {
			wc_add_notice( __( 'There was a problem adding this card.', 'woo-pelecard-gateway' ), 'error' );
			return;
		}
		
		wc_add_notice( __( 'Payment method added.', 'woo-pelecard-gateway' ) );
		wp_redirect( wc_get_endpoint_url( 'payment-methods' ) );
		exit;
	}
	
	/**
	 * do payment function.
	 */
	public function do_payment( $transaction, $order ) {
		// bail
		if ( ! $order->needs_payment() ) {
			return;
		}
		
		// Save transaction
		add_post_meta( is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id, '_transaction_data', (array) $transaction );
		
		if ( $transaction->is_success() ) {
			// Save token
			if ( ! empty( $transaction->Token ) && $order->get_user_id() ) {
				$this->save_token( $transaction, $order->get_user_id() );
			}
			
			// Mark as completed
			$order->payment_complete( $transaction->TransactionId );
			
			// Empty cart
			WC()->cart->empty_cart();
		} else {
			if ( $order->has_status( 'failed' ) ) {
				$order->add_order_note( sprintf(
					/* translators: %s: status code. */
					__( 'Transaction Failed: %s', 'woo-pelecard-gateway' ), 
					wc_clean( $transaction->StatusCode )
				) );
			} else {
				$order->update_status( 'failed', sprintf(
					/* translators: %s: status code. */
					__( 'Transaction Failed: %s', 'woo-pelecard-gateway' ),
					wc_clean( $transaction->StatusCode )
				) );
			}
		}
		
		// Clear selected
		WC()->session->save_payment_method 	= null;
		WC()->session->selected_token_id 	= null;
	}
	
	/**
	 * Returns the card type (mastercard, visa, ...).
	 */
	protected function get_card_type( $transaction ) {
		$all_types = apply_filters( 'wc_pelecard_gateway_card_types', array(
			'CreditCardBrand' => array(
				1 => 'mastercard',
				2 => 'visa',
				3 => 'maestro',
				5 => 'isracard'
			),
			'CreditCardCompanyClearer' => array(
				1 => 'isracard',
				2 => 'visa',
				3 => 'diners',
				4 => 'american express',
				6 => 'leumi card'
			)
		) );
		
		if ( 0 < $transaction->CreditCardBrand ) {
			$type = $all_types['CreditCardBrand'][ $transaction->CreditCardBrand ];
		} else {
			$type = $all_types['CreditCardCompanyClearer'][ $transaction->CreditCardCompanyClearer ];
		}
		
		return $type;
	}
	
	/**
	 * Saves a customer token to the database.
	 *
	 * @param  WC_Pelecard_Transaction $transaction
	 * @param  integer $user_id
	 */
	public function save_token( $transaction, $user_id = 0 ) {
		$token_number 		= $transaction->Token;
		$token_card_type 	= $this->get_card_type( $transaction );
		$token_last4 		= substr( $transaction->CreditCardNumber, -4 );
		$token_expiry_month = substr( $transaction->CreditCardExpDate, 0, 2 );
		$token_expiry_year 	= substr( date( 'Y' ), 0, 2 ) . substr( $transaction->CreditCardExpDate, -2 );
		
		$token = new WC_Payment_Token_CC();
		$token->set_token( $token_number );
		$token->set_gateway_id( 'pelecard' );
		$token->set_card_type( $token_card_type );
		$token->set_last4( $token_last4 );
		$token->set_expiry_month( $token_expiry_month );
		$token->set_expiry_year( $token_expiry_year );
		$token->set_user_id( 0 < $user_id ? $user_id : get_current_user_id() );
		
		if ( $token->save() ) {
			return $token;
		}
		
		return null;
	}
	
	/**
	 * Enqueues our tokenization script to handle some of the new form options.
	 */
	public function tokenization_script() {
		wp_enqueue_script(
			'woocommerce-tokenization-form',
			plugins_url( '/assets/js/frontend/tokenization-form' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js', WC_PLUGIN_FILE ),
			array( 'jquery' ),
			WC()->version
		);
		
		wp_enqueue_script(
			'wc-pelecard-gateway-tokenization', 
			plugins_url( '/assets/js/tokenization' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js', WC_PELECARD_PLUGIN_FILE ), 
			array( 'woocommerce-tokenization-form' ), 
			WC_Pelecard()->version 
		);
		
		wp_enqueue_script( 'wc-credit-card-form' );
	}
	
	/**
	 * Builds our payment fields area - including tokenization fields for logged
	 * in users, and the actual payment fields.
	 * @since 1.2.0
	 */
	public function payment_fields() {
		$description = $this->get_description();

		if ( $description ) {
			echo wpautop( wptexturize( trim( $description ) ) );
		}

		if ( $this->supports( 'tokenization' ) ) {
			parent::payment_fields();
		}
	}
	
	/**
	 * Outputs fields for entering credit card information.
	 * @since 1.2.0
	 */
	public function form() {
		// bail
		if ( ! is_add_payment_method_page() ) {
			return;
		}
		
		$args = apply_filters( 'wc_pelecard_gateway_my_account_iframe_args', array(
			'terminal' 						=> $this->terminal,
			'user' 							=> $this->username,
			'password' 						=> $this->password,
			'GoodURL' 						=> wc_get_endpoint_url( 'add-payment-method' ),
			'ErrorURL' 						=> wc_get_endpoint_url( 'add-payment-method' ),
			'ActionType' 					=> 'J5',
			'Total'							=> 0,
			'CancelURL'						=> 'yes' === $this->cancelbutton ? wc_get_endpoint_url( 'payment-methods' ) : '',
			'Currency' 						=> $this->currency,
			'ShowSubmitButton' 				=> true,
			'Language' 						=> $this->language,
			'CardHolderName' 				=> $this->cardholdername,
			'CustomerIdField'				=> 'must',
			'Cvv2Field' 					=> 'must',
			'SplitCCNumber' 				=> $this->splitccnumber,
			'FeedbackOnTop' 				=> true,
			'FeedbackDataTransferMethod' 	=> 'POST',
			'UseBuildInFeedbackPage' 		=> false,
			'FirstPayment' 					=> $this->firstpayment,
			'ShopNo' 						=> '001',
			'ShowXParam' 					=> false,
			'AddHolderNameToXParam' 		=> false,
			'CssURL'						=> $this->cssurl,
			'LogoURL'						=> $this->logourl,
			'topText'						=> $this->toptext,
			'BottomText'					=> $this->bottomtext,
			'ShowConfirmationCheckbox' 		=> $this->confirmationcb,
			'TextOnConfirmationBox' 		=> $this->confirmationtext,
			'ConfirmationLink'				=> $this->confirmationurl,
			'HiddenPelecardLogo' 			=> $this->hiddenpelecard,
			'SupportedCards' 				=> $this->supportedcards,
			'AccessibilityMode' 			=> false,
			'CustomCanRetrayErrors'			=> array(),
			'CaptionSet'					=> array(
				'cs_submit'	=> __( 'Add card', 'woo-pelecard-gateway' )
			)
		) );
		
		// API request
		if ( ! $api_response = WC_Pelecard_API::request( $args ) ) {
			return;
		}
		
		$this->get_iframe( $api_response );
	}
	
	/**
	 * Generate payment range HTML.
	 *
	 * @return string
	 */
	public function generate_payment_range_html() {
		$symbol = get_woocommerce_currency_symbol();
		
		ob_start();
		
		?><tr valign="top" class="pelecard-tab pelecard-tab-payment">
			<th scope="row" class="titledesc"><?php _e( 'Custom Payments', 'woo-pelecard-gateway' ); ?>:</th>
			<td class="forminp" id="pelecard_payment_range">
				<table class="widefat wc_input_table sortable" cellspacing="0">
					<thead>
						<tr>
							<th class="sort">&nbsp;</th>
							<th><?php _e( 'Total Cart', 'woo-pelecard-gateway' ); ?></th>
							<th><?php _e( 'Minimum', 'woo-pelecard-gateway' ); ?></th>
							<th><?php _e( 'Maximum', 'woo-pelecard-gateway' ); ?></th>
						</tr>
					</thead>
					<tbody class="ranges" data-symbol="<?php echo $symbol; ?>">
						<?php foreach ( (array) $this->paymentrange as $index => $range ) : ?>
						<tr class="range">
							<td class="sort"></td>
							<td>
								<div>
									<div class="price-container">
										<span class="currency-symbol"><?php echo $symbol; ?></span>
										<input class="payment-range-min" type="number" name="paymentrange_cart[<?php echo $index; ?>][min]" value="<?php echo esc_attr( $range['cart']['min'] ); ?>" readonly>
									</div>
									<div class="slider-container">
										<div class="slider-range"></div>
									</div>
									<div class="price-container">
										<span class="currency-symbol"><?php echo $symbol; ?></span>
										<input class="payment-range-max" type="number" name="paymentrange_cart[<?php echo $index; ?>][max]" value="<?php echo esc_attr( $range['cart']['max'] ); ?>" readonly>
									</div>
								</div>
							</td>
							<td><input type="number" value="<?php echo esc_attr( $range['min'] ); ?>" name="paymentrange_min[<?php echo $index; ?>]" /></td>
							<td><input type="number" value="<?php echo esc_attr( $range['max'] ); ?>" name="paymentrange_max[<?php echo $index; ?>]" /></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="7">
								<a href="#" class="add button"><?php _e( '+ Add Row', 'woo-pelecard-gateway' ); ?></a>
								<a href="#" class="remove_rows button"><?php _e( 'Remove row(s)', 'woo-pelecard-gateway' ); ?></a>
							</th>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr><?php
		
		return ob_get_clean();
	}
	
	/**
	 * Get payment range.
	 *
	 * @param 	int 	$total
	 * @param 	string 	$key
	 * @param 	int 	$default
	 * @return 	int
	 */
	protected function get_range( $total, $key, $default ) {
		foreach ( $this->paymentrange as $range ) {
			if ( isset( $range['cart']['min'], $range['cart']['max'] ) ) {
				if ( $range['cart']['min'] <= $total && $total <= $range['cart']['max'] ) {
					if ( ! empty( $range[ $key ] ) && is_numeric( $range[ $key ] ) ) {
						return $range[ $key ];
					}
				}
			}
		}
		
		return $default;
	}
	
	/**
	 * Save payment range.
	 *
	 * @return void
	 */
	public function save_payment_range() {
		// bail
		if ( empty( $_POST['paymentrange_cart'] ) ) {
			return;
		}
		
		$ranges = array();
		$cart 	= array_map( 'wc_clean', $_POST['paymentrange_cart'] );
		$min 	= array_map( 'wc_clean', $_POST['paymentrange_min'] );
		$max 	= array_map( 'wc_clean', $_POST['paymentrange_max'] );

		foreach ( (array) $cart as $index => $name ) {
			if ( empty( $cart[ $index ]['min'] ) || empty( $cart[ $index ]['max'] ) ) {
				continue;
			}
			
			if ( empty( $min[ $index ] ) || empty( $max[ $index ] ) ) {
				continue;
			}
			
			$ranges[] = array(
				'cart' 	=> $cart[ $index ],
				'min' 	=> $min[ $index ],
				'max' 	=> $max[ $index ]
			);
		}
		
		$this->settings['paymentrange'] = $this->paymentrange = $ranges;
		update_option( $this->get_option_key(), $this->settings );
	}
	
	/**
	 * Return the name of the option in the WP DB.
	 * @since 1.2.0
	 * @return string
	 */
	public function get_option_key() {
		return $this->plugin_id . $this->id . '_settings';
	}
	
}
