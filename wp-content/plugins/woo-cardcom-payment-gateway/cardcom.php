<?php
/*
Plugin Name: CardCom Payment Gateway
Plugin URI: http://kb.cardcom.co.il/article/AA-00359/0/
Description: CardCom Payment gateway for Woocommerce
Version: 3.3.2.15
Changes: Coin
Author: CardCom LTD
Author URI: http://www.cardcom.co.il
*/

add_action('plugins_loaded', 'woocommerce_cardcom_init', 0);

/**
 * Load plugin textdomain.
 */
function cardcom_load_textdomain()
{
    load_plugin_textdomain('cardcom', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

add_action('init', 'cardcom_load_textdomain');


//main
function woocommerce_cardcom_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    DEFINE('PLUGIN_DIRECTORY', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)) . '/');

    /**
     * Gateway class
     **/
    class WC_Gateway_Cardcom extends WC_Payment_Gateway
    {
        var $terminalnumber;
        var $username;
        var $operation;
        var $cerPCI;
        var $operationToPerform; //in case operation 2 but user didnt choose save account
        var $isML;
        var $CardComURL;
        static $trm;
        static $cvv_free_trm;
        static $must_cvv;
        static $user;
        static $CoinID;
        static $sendByEmail;
        static $language;
        static $InvVATFREE;
        static $IsActivateInvoiceForPaypal;
        static $SendToEmailInvoiceForPaypal;
        static $plugin = "WOO-3.3.2.15";

        function __construct()
        {
            //$this->CardComURL = 'http://localhost:61240'; // For testing
            $this->CardComURL = 'https://secure.cardcom.solutions'; // Production URL
            $this->id = 'cardcom';
            $this->method_title = __('CardCom', 'cardcom');
            $this->has_fields = false;
            $this->url = $this->CardComURL . "/external/LowProfileClearing2.aspx";
            $this->supports = array('tokenization',);
            // Load the form fields
            $this->init_form_fields();

            // Load the settings.
            $this->init_settings();

            // Load plugin checkout icon; Currently the icon we have is obsolete and should not be used
            // $this->icon = PLUGIN_DIRECTORY.'images/cards.png';

            //Load Language by Define if WPML ACTIVE //https://wpml.org/forums/topic/how-to-check-if-wpml-is-installed-and-active/
            global $sitepress;


            // Set Language dynamically according to "PolyLang", Ref to code:
            // - https://polylang.pro/doc/function-reference/#pll_current_language
            if (function_exists("pll_current_language")) {
                $this->lang = pll_current_language('slug');
                $this->isML = true;
            }
            // Set Language dynamically according to "WordPress Multilingual Plugin", ref to code:
            // - https://wpml.org/forums/topic/get-current-language-in-functions-php/
            // - https://wpml.org/forums/topic/how-to-define-redirect-url-that-automatically-represent-current-language/
            elseif (function_exists('icl_object_id') && defined('ICL_LANGUAGE_CODE') && isset($sitepress)) {
                $this->lang = ICL_LANGUAGE_CODE;
                $this->isML = true;
            } else {
                $this->lang = $this->settings['lang'];
                $this->isML = false;
            }

            // Get setting values
            $this->title = $this->settings['title'];
            $this->description = $this->settings['description'];
            $this->enabled = $this->settings['enabled'];
            $this->terminalnumber = $this->settings['terminalnumber'];
            $this->adminEmail = $this->settings['adminEmail'];
            $this->username = $this->settings['username'];
            $this->currency = $this->settings['currency'];
            if (isset($this->settings['CerPCI'])) {
                $this->cerPCI = $this->settings['CerPCI'];
            } else {
                $this->cerPCI = '2';
            }
            $this->operation = $this->settings['operation'];
            if ($this->operation != '2' && $this->operation != '3' && $this->operation != '4' && $this->operation != '5') {
                $this->operation = '1';
            }
            $this->operationToPerform = $this->operation;
            $this->invoice = $this->settings['invoice'];
            $this->maxpayment = $this->settings['maxpayment'];
            $this->UseIframe = $this->settings['UseIframe'];
            $this->OrderStatus = $this->settings['OrderStatus'];
            $this->InvoiceVATFREE = $this->settings['InvoiceVATFREE'];
            $this->failedUrl = $this->settings['failedUrl'];
            $this->successUrl = $this->settings['successUrl'];
            self::$trm = $this->settings['terminalnumber'];
            self::$cvv_free_trm = $this->settings['cvvFreeTerminal'];
            self::$must_cvv = $this->settings['must_cvv'];
            self::$user = $this->settings['username'];
            self::$CoinID = $this->settings['currency'];
            if (isset($this->settings['SendByEmail'])) {
                self::$sendByEmail = $this->settings['SendByEmail'];
            } else {
                self::$sendByEmail = '1';
            }
            self::$language = $this->lang;
            self::$InvVATFREE = $this->settings['InvoiceVATFREE'];
            self::$IsActivateInvoiceForPaypal = $this->settings['IsActivateInvoiceForPaypal'];
            if (isset($this->settings['SendToEmailInvoiceForPaypal'])) {
                self::$SendToEmailInvoiceForPaypal = $this->settings['SendToEmailInvoiceForPaypal'];
            } else {
                self::$SendToEmailInvoiceForPaypal = "1";
            }
            add_action('woocommerce_api_wc_gateway_cardcom', array($this, 'check_ipn_response'));
            add_action('valid-cardcom-ipn-request', array(&$this, 'ipn_request'));
            add_action('valid-cardcom-successful-request', array(&$this, 'successful_request'));
            add_action('valid-cardcom-cancel-request', array(&$this, 'cancel_request'));
            add_action('valid-cardcom-failed-request', array(&$this, 'failed_request'));
            add_action('woocommerce_receipt_cardcom', array(&$this, 'receipt_page'));

            // Hooks
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            //Update order status
            add_filter('woocommerce_payment_complete_order_status', array($this, 'change_payment_complete_order_status'), 10, 3);
        }

        public static function init()
        {
            //add_action( 'woocommerce_order_status_completed', array( get_called_class(), 'CreateinvoiceForPayPal' ) );
            //add_action( 'woocommerce_order_status_processing', array( get_called_class(), 'CreateinvoiceForPayPal' ) );
            // add_action( 'paypal_ipn_for_wordpress_payment_status_completed', array( get_called_class(), 'CreateinvoiceForPayPal' ) );
            add_action('valid-paypal-standard-ipn-request', array(get_called_class(), 'ValidatePaypalRequest')); // For "PayPal Standard" gateway
            //  add_action( 'woocommerce_paypal_express_checkout_valid_ipn_request', array(get_called_class(), 'CreateinvoiceForPayPal' ) ); // For "Paypal Express Checkout"

        }

        public static function ValidatePaypalRequest($posted)
        {

            if (self::$IsActivateInvoiceForPaypal != '1') {
                return;
            }
            $order = !empty($posted['custom']) ? self::get_paypal_order($posted['custom']) : false;

            if ($order) {
                // Lowercase returned variables.
                $posted['payment_status'] = strtolower($posted['payment_status']);
                if ('completed' === $posted['payment_status']) {
                    if ($order->has_status('cancelled')) {
                        error_log("paypal status complite but order has beed canceled");
                    } else {

                    }
                    $transaction_id = !empty($posted['txn_id']) ? wc_clean($posted['txn_id']) : '';
                    $order->payment_complete($transaction_id);
                    if (!empty($posted['mc_fee'])) {
                        // Log paypal transaction fee.
                        update_post_meta($order->get_id(), 'PayPal Transaction Fee', wc_clean($posted['mc_fee']));
                    }

                    self::CreateinvoiceForPayPal($order->get_id());
                }
            }
        }

        public static function get_paypal_order($raw_custom)
        {
            // We have the data in the correct format, so get the order.
            $custom = json_decode($raw_custom);
            if ($custom && is_object($custom)) {
                $order_id = $custom->order_id;
                $order_key = $custom->order_key;
            } else {
                // Nothing was found.
                error_log('Order ID and key were not found in "custom".', 'error');
                return false;
            }
            $order = wc_get_order($order_id);
            if (!$order) {
                // We have an invalid $order_id, probably because invoice_prefix has changed.
                $order_id = wc_get_order_id_by_order_key($order_key);
                $order = wc_get_order($order_id);
            }
            if (!$order || $order->get_order_key() !== $order_key) {
                error_log('Order Keys do not match.', 'error');
                return false;
            }
            return $order;
        }

        public static function CreateinvoiceForPayPal($order_id)
        {


            if (self::$IsActivateInvoiceForPaypal != '1') {
                return;
            }

            wc_delete_order_item_meta((int)$order_id, 'InvoiceNumber');
            wc_delete_order_item_meta((int)$order_id, 'InvoiceType');
            $order = new WC_Order($order_id);
            // error_log( "Payment has been received for order $order_id ->> is active:".self::$IsActivateInvoiceForPaypal ." Order Payment method : ".$order->get_payment_method());
            // Web service
            // https://secure.cardcom.co.il/Interface/CreateInvoice.aspx
            if (strpos($order->get_payment_method(), 'paypal') !== false) {
                //Paypal Case
                // error_log( "Payment has been received from". $order->get_payment_method() );
                $initParams = self::initInvoice($order_id);
                $initParams['InvoiceHead.CoinISOName'] = $order->get_currency();
                $initParams['InvoiceHead.SendByEmail'] = self::$SendToEmailInvoiceForPaypal;
                $initParams["Plugin"] = self::$plugin;
                //$initParams["InvoiceType"] = "1";

                $key_1_value = get_post_meta((int)$order_id, 'InvoiceNumber', true);
                $key_2_value = get_post_meta((int)$order_id, 'InvoiceType', true);
                if (!empty($key_1_value) && !empty($key_2_value)) {
                    error_log("Order has invoice: " . $key_1_value);
                    return;
                }
                update_post_meta((int)$order_id, 'InvoiceNumber', 0);
                update_post_meta((int)$order_id, 'InvoiceType', 0);
                $initParams["CustomPay.TransactionID"] = '32';
                $initParams["CustomPay.TranDate"] = date('d/m/Y');
                $initParams["CustomPay.Description"] = 'PayPal Payments';
                $initParams["CustomPay.Asmacta"] = $order->get_transaction_id();
                $initParams["CustomPay.Sum"] = number_format($order->get_total(), 2, '.', '');

                $urlencoded = http_build_query($initParams);
                $args = array('body' => $urlencoded,
                    'timeout' => '5',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(),
                    'cookies' => array());
                $response = wp_remote_post('https://secure.cardcom.co.il/Interface/CreateInvoice.aspx', $args);
                $body = wp_remote_retrieve_body($response);
                $responseArray = array();
                parse_str($body, $responseArray);
                if (isset($responseArray['ResponseCode'])) {
                    if ($responseArray['ResponseCode'] == 0) {
                        if (isset($responseArray['InvoiceNumber'])) {
                            $invNumber = $responseArray['InvoiceNumber'];
                            $invType = $responseArray['InvoiceType'];
                            update_post_meta((int)$order_id, 'InvoiceNumber', $invNumber);
                            update_post_meta((int)$order_id, 'InvoiceType', $invType);
                        }
                    }
                }
            }
        }

        /**
         * Change payment complete order status to completed for COD orders.
         *
         * @since  3.2.0.0
         * @param  string $status Current order status.
         * @param  int $order_id Order ID.
         * @param  WC_Order|false $order Order object.
         * @return string
         */
        public function change_payment_complete_order_status($status, $order_id = 0, $order = false)
        {
            if ($this->id === $order->get_payment_method()) {
                $status = $this->OrderStatus;
            }
            return $status;
        }

        public static function initTerminal($order, $OverTerminal = "")
        {
            $params = array();
            $SumToBill = number_format($order->get_total(), 2, '.', '');
            $params["terminalnumber"] = self::$trm;
            if ($OverTerminal != "") {
                $params["terminalnumber"] = $OverTerminal;
            }

            $params["username"] = self::$user;
            $params["CodePage"] = "65001";
            $params["SumToBill"] = number_format($SumToBill, 2, '.', '');
            $params["Languge"] = self::$language;
            $params["CoinISOName"] = $order->get_currency();
            return $params;
        }

        public static function initInvoice($order_id, $OverTerminal = "")
        {
            $order = new WC_Order($order_id);
            $params = array();
            $SumToBill = number_format($order->get_total(), 2, '.', '');
            $params["terminalnumber"] = self::$trm;
            if (self::IsStringSet($OverTerminal)) {
                $params["terminalnumber"] = $OverTerminal;
            }
            $params["username"] = self::$user;
            $params["CodePage"] = "65001";
            $params["SumToBill"] = number_format($SumToBill, 2, '.', '');
            $params["Languge"] = self::$language;
            $params["CoinISOName"] = $order->get_currency();
            $compName = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_company())), 0, 200);
            $lastName = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_last_name())), 0, 200);
            $firstName = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_first_name())), 0, 200);
            $customerName = $firstName . " " . $lastName;
            if ($compName != '') {
                $customerName = $compName;
            }
            $params['InvoiceHead.CustName'] = $customerName;
            $params['InvoiceHead.CustAddresLine1'] = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_address_1())), 0, 200);
            $params['InvoiceHead.CustCity'] = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_city())), 0, 200);
            $params['InvoiceHead.CustAddresLine2'] = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_address_2())), 0, 200);
            $zip = wc_format_postcode($order->get_shipping_postcode(), $order->get_shipping_country());
            if (!empty($zip)) {
                $params['InvoiceHead.CustAddresLine2'] .= __('Postcode / ZIP', 'woocommerce') . ': ' . $zip;
            }
            $params['InvoiceHead.CustMobilePH'] = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_billing_phone())), 0, 200);
            if (strtolower(self::$language) == 'he' || strtolower(self::$language) == 'en') {
                $params['InvoiceHead.Language'] = self::$language;
            } else {
                $params['InvoiceHead.Language'] = 'en';
            }
            $params['InvoiceHead.Email'] = $order->get_billing_email();
            if (self::$sendByEmail === '1') {
                $params['InvoiceHead.SendByEmail'] = 'true';
            } else {
                $params['InvoiceHead.SendByEmail'] = 'false';
            }
            if ($order->get_billing_country() != 'IL' && self::$InvVATFREE == 4) {
                $params['InvoiceHead.ExtIsVatFree'] = 'true';
            } else {
                $params['InvoiceHead.ExtIsVatFree'] = self::$InvVATFREE == '1' ? 'true' : 'false';
            }
            if (strtolower(self::$language) == 'he') {
                $params['InvoiceHead.Comments'] = 'מספר הזמנה: ' . $order->get_id();
            } else {
                $params['InvoiceHead.Comments'] = 'Order ID: ' . $order->get_id();
            }
            $ItemsCount = 0;
            $AddToString = "";
            $TotalLineCost = 0;
            $ItemShipping = $order->get_shipping_total() + $order->get_shipping_tax();
            // ============= Regardless of version: Set Shipping details in invoice ============= //
            if (version_compare(WOOCOMMERCE_VERSION, '2.7', '<')) {
                // ------- Set item/products to invoice ------- //
                foreach ($order->get_items() as $item) {
                    $ItemTotal = $order->get_item_total($item, false, false) + $order->get_item_tax($item, false, false);
                    $itmdesk = substr(strip_tags(preg_replace("/&#\d*;/", " ", $item['name'])), 0, 200);
                    $params['InvoiceLines' . $AddToString . '.Description'] = $itmdesk;
                    $params['InvoiceLines' . $AddToString . '.Price'] = $ItemTotal;
                    $params['InvoiceLines' . $AddToString . '.Quantity'] = $item['qty'];
                    $params['InvoiceLines' . $AddToString . '.ProductID'] = $item["product_id"];
                    $TotalLineCost += ($ItemTotal * $item['qty']);
                    $ItemsCount++;
                    $AddToString = $ItemsCount;
                }
                // ------- Set Shipping description (if there is one) ------- //
                if ($ItemShipping != 0) {
                    $ShippingDesk = substr(strip_tags(preg_replace("/&#\d*;/", " ", ucwords(self::get_shipping_method_fixed($order)))), 0, 200);
                }
                // ------- Set Discount amount total ------- //
                $order_discount = $order->get_order_discount();
                $order_discount += $order->get_discount_tax();
            } else {
                // ------- Set item/products to invoice ------- //
                foreach ($order->get_items(array('line_item', 'fee')) as $item_id => $item) {

                    $itmdesk = substr(strip_tags(preg_replace("/&#\d*;/", " ", $item->get_name())), 0, 200);
                    if ('fee' === $item['type']) {
                        $item_line_total = $item['line_total'];
                        $TotalLineCost += $item_line_total;
                        $ItemsCount++;
                    } else {
                        $product = $item->get_product();
                        $item_line_total = number_format($order->get_item_subtotal($item, true), 2, '.', '');
                        $SKU = '';
                        try {
                            $product_variation_id = $item['variation_id'];
                            // Check if product has variation.
                            if ($product_variation_id) {
                                $product = new WC_Product($item['variation_id']);
                            } else {
                                $product = new WC_Product($item['product_id']);
                            }
                            $SKU = $product->get_sku();
                        } catch (Exception $ex) {
                            error_log('Line 263 get SKU' . $ex->getMessage());
                        }
                        if (self::$InvVATFREE == '3') {
                            $params['InvoiceLines' . $AddToString . '.IsVatFree'] = (bool)$product->is_taxable() == false ? 'true' : 'false';
                            $item_line_total = number_format($order->get_item_subtotal($item, $product->is_taxable()), 2, '.', '');
                        }
                        $params['InvoiceLines' . $AddToString . '.Quantity'] = $item->get_quantity();
                        $params['InvoiceLines' . $AddToString . '.ProductID'] = $SKU;
                        $TotalLineCost += ($item_line_total * $item->get_quantity());
                        $ItemsCount++;
                    }
                    $params['InvoiceLines' . $AddToString . '.Description'] = $itmdesk;
                    $params['InvoiceLines' . $AddToString . '.Price'] = $item_line_total;
                    $AddToString = $ItemsCount;
                }
                // ------- Set Shipping description (if there is one) ------- //
                if ($ItemShipping != 0) {
                    $ShippingDesk = substr(strip_tags(preg_replace("/&#\d*;/", " ", $order->get_shipping_method())), 0, 200);
                }
                // ------- Set Discount amount total ------- //
                $order_discount = $order->get_discount_total();
                $order_discount += $order->get_discount_tax();
            }
            // ============= Set Shipping details in invoice ============= //
            if ($ItemShipping != 0) {
                $params['InvoiceLines' . $AddToString . '.Description'] = $ShippingDesk;
                $params['InvoiceLines' . $AddToString . '.Price'] = $ItemShipping;
                $params['InvoiceLines' . $AddToString . '.Quantity'] = 1;
                $params['InvoiceLines' . $AddToString . '.ProductID'] = "Shipping";
                $TotalLineCost += $ItemShipping;
                $ItemsCount++;
                $AddToString = $ItemsCount;
            }
            // ============= Set Coupon discount details in invoice ============= //
            if ($order_discount > 0) {
                $coupon_codes = $order->get_used_coupons();
                if (!empty($coupon_codes)) {
                    $params['InvoiceLines' . $AddToString . '.Description'] = __("Coupon code", "woocommerce") . ": " . implode(", ", $coupon_codes);
                } else {
                    $params['InvoiceLines' . $AddToString . '.Description'] = "Discount";
                }
                $params['InvoiceLines' . $AddToString . '.Price'] = -1 * $order_discount;
                $params['InvoiceLines' . $AddToString . '.Quantity'] = 1;
                $TotalLineCost -= $order_discount;
                $ItemsCount++;
                $AddToString = $ItemsCount;
            }
            // ============= Set invoice balance in case needed ============= //
            // ======= Note! Shouldn't usually be set, but jus in case ======= //
            if (number_format($SumToBill - $TotalLineCost, 2, '.', '') != 0) {
                if (strtolower(self::$language) == 'he') {
                    $params['InvoiceLines' . $AddToString . '.Description'] = "שורת איזון עבור חשבונית בלבד";
                } else {
                    $params['InvoiceLines' . $AddToString . '.Description'] = "Balance row for invoice";
                }
                $params['InvoiceLines' . $AddToString . '.Price'] = number_format($SumToBill - $TotalLineCost, 2, '.', '');
                $params['InvoiceLines' . $AddToString . '.Quantity'] = '1';
                $params['InvoiceLines' . $AddToString . '.ProductID'] = 'Diff';
                $ItemsCount++;
                $AddToString = $ItemsCount;
            }
            return $params;
        }

        //fix shipping by Or
        public static function get_shipping_method_fixed($order)
        {

            $labels = array();

            // Backwards compat < 2.1 - get shipping title stored in meta
            if ($order->shipping_method_title) {

                $labels[] = $order->shipping_method_title;
            } else {

                // 2.1+ get line items for shipping
                $shipping_methods = $order->get_shipping_methods();

                foreach ($shipping_methods as $shipping) {
                    $labels[] = $shipping['name'];
                }
            }

            return implode(',', $labels);
        }

        /**
         * Initialize Gateway Settings Form Fields
         * admin panel
         */
        function init_form_fields()
        {

            $this->form_fields = array(
                'title' => array(
                    'title' => __('Title', 'cardcom'),
                    'type' => 'text',
                    'description' => __('The title which the user sees during the checkout.', 'cardcom'),
                    'default' => __('Cardcom', 'cardcom')
                ),
                'enabled' => array(
                    'title' => __('Enable/Disable', 'cardcom'),
                    'description' => __('Enable Cardcom', 'cardcom'),
                    'type' => 'select',
                    'options' => array('yes' => 'Yes', 'no' => 'No'),
                    'default' => 'yes'
                ),
                'description' => array(
                    'title' => __('Description', 'cardcom'),
                    'type' => 'text',
                    'description' => __('The description which the user sees during the checkout.', 'cardcom'),
                    'default' => 'Pay with Cardcom.'
                ),
                'operation' => array(
                    'title' => __('Operation', 'cardcom'),
                    'label' => __('Operation', 'cardcom'),
                    'type' => 'select',
                    'options' => array('1' => 'Charge Only', '2' => 'Charge and save TOKEN', '3' => 'Save Token', '4' => 'Suspended Deal J2', '5' => 'Suspended Deal J5'),
                    'description' => __('J2-Chack Only, J5-Create a Deposite ', 'cardcom'),
                    'default' => '1'
                ),
                'CerPCI' => array(
                    'title' => __('PCI certification', 'cardcom'),
                    'label' => __('PCI certification', 'cardcom'),
                    'type' => 'select',
                    'description' => __('Check this if your website is PCI compliant, and credit card numbers can be passed through your servers.<br />If you are not sure, keep this unchecked.', 'cardcom'),
                    'options' => array('1' => 'Yes', '2' => 'No'),
                    'default' => '2',
                ),
                'SendByEmail' => array(
                    'title' => __('Send By Email', 'cardcom'),
                    'type' => 'select',
                    'options' => array('0' => 'No', '1' => 'Yes'),
                    'description' => __('Send Invoice via Email', 'cardcom'),
                    'default' => '1'
                ),
                'invoice' => array(
                    'title' => __('Invoice', 'cardcom'),
                    'label' => __('Invoice', 'cardcom'),
                    'type' => 'select',
                    'options' => array('1' => 'Yes', '2' => 'Display only'),
                    'description' => 'Select Yes only if accout have docuemnts module',
                    'default' => '1'
                ),
                'terminalnumber' => array(
                    'title' => __('Terminal Number', 'cardcom'),
                    'type' => 'text',
                    'description' => __('the company Terminal Number', 'cardcom'),
                    'default' => '1000'
                ),
                'must_cvv' => array(
                    'title' => __('Must CVV', 'cardcom'),
                    'label' => __('Must CVV', 'cardcom'),
                    'type' => 'select',
                    'options' => array('0' => 'No', '1' => 'Yes'),
                    'description' => '',
                    'default' => '0'
                ),
                'cvvFreeTerminal' => array(
                    'title' => __('CVV free Terminal Number', 'cardcom'),
                    'type' => 'text',
                    'description' => __('CVV free Terminal', 'cardcom'),
                    'default' => ''
                ),
                'username' => array(
                    'title' => __('API User Name', 'cardcom'),
                    'type' => 'text',
                    'description' => __('the company API User Name', 'cardcom'),
                    'default' => 'barak9611'
                ),
                'maxpayment' => array(
                    'title' => __('Max Payment', 'cardcom'),
                    'type' => 'text',
                    'description' => __('Limit the amount of payments', 'cardcom'),
                    'default' => '1'
                ),
                'currency' => array(
                    'title' => __('Currency', 'cardcom'),
                    'type' => 'text',
                    'description' => __('Currency: 0- Auto Detect,  1 - NIS , 2 - USD , else ISO Currency', 'cardcom'),
                    'default' => '1'
                ),
                'lang' => array(
                    'title' => __('Lang', 'cardcom'),
                    'type' => 'text',
                    'description' => __('Lang', 'cardcom'),
                    'default' => 'en'
                ),
                'adminEmail' => array(
                    'title' => __('Admin Email', 'cardcom'),
                    'type' => 'text',
                    'description' => __('Admin Email', 'cardcom'),
                    'default' => ''
                ),
                'UseIframe' => array(
                    'title' => __('Use Iframe', 'cardcom'),
                    'label' => __('Use Iframe', 'cardcom'),
                    'type' => 'select',
                    'options' => array('1' => 'Yes', '0' => 'No'),
                    'description' => '',
                    'default' => '0'
                ),
                'InvoiceVATFREE' => array(
                    'title' => __('invoice VAT free', 'cardcom'),
                    'label' => __('invoice VAT free', 'cardcom'),
                    'type' => 'select',
                    'options' => array('1' => 'Invoice VAT free', '2' => 'Invoice will include Vat', '3' => 'Invoice include Tax per product', '4' => 'Invoice include VAT by country'),
                    'description' => __('For third option  "Tax per product" please see <a href="http://kb.cardcom.co.il/article/AA-00359">help</a>', 'cardcom'),
                    'default' => '2'
                ),
                'OrderStatus' => array(
                    'title' => __('Order Status', 'cardcom'),
                    'label' => __('Order Status', 'cardcom'),
                    'type' => 'select',
                    'options' => array('processing' => 'processing', 'completed' => 'completed', 'on-hold' => 'on-hold'),
                    'description' => 'what will the order status will be',
                    'default' => 'completed'
                ),
                'failedUrl' => array(
                    'title' => __('failed Url', 'cardcom'),
                    'type' => 'text',
                    'description' => __('failed Url', 'cardcom'),
                    'default' => ''
                ),
                'successUrl' => array(
                    'title' => __('success Url', 'cardcom'),
                    'type' => 'text',
                    'description' => __('success Url', 'cardcom'),
                    'default' => ''
                ),
                'IsActivateInvoiceForPaypal' => array(
                    'title' => __('Invoice for Paypal', 'cardcom'),
                    'label' => __('Invoice for Paypal', 'cardcom'),
                    'type' => 'select',
                    'description' => __('Activate invoice creation for Paypal', 'cardcom'),
                    'options' => array('1' => 'Yes', '2' => 'No'),
                    'default' => '2'
                ),
                'SendToEmailInvoiceForPaypal' => array(
                    'title' => __('Invoice for Paypal - Send to email', 'cardcom'),
                    'label' => __('Invoice for Paypal - Send to email'),
                    'type' => 'select',
                    'description' => __('Send Paypal Invoice to Email', 'cardcom'),
                    'options' => array('1' => 'Yes', '0' => 'No'),
                    'default' => '1'
                ),
            );
        }

        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         */

        function admin_options()
        {
            ?>
            <h3><?php _e('CardCom', 'cardcom'); ?></h3>
            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
            </table><!--/.form-table-->
            <?php
        }

        /**
         * Check if this gateway is enabled and available in the user's country
         */
        function is_available()
        {
            if ($this->enabled == "yes") :
                return true;
            endif;

            return false;
        }

        /** Process the payment */
        function process_payment($order_id)
        {
            // ============ Check 1st to see if Cart is not empty (This is to prevent "הזמנה כפולה") ============ //
            // This causes problem when charging again in a page that gets items from another list
            //if (WC()->cart->is_empty()) {
            //    return array(
            //        'result' => 'fail',
            //        'redirect' => 'Cart is Empty');
            //}
            global $woocommerce;
            $order = new WC_Order($order_id);
            $this->operationToPerform = $this->operation;
            $paymentTokenValue = $_POST['wc-cardcom-payment-token'];
            // ================= If PCI certification is TRUE && New payment Method || Charge Only ================= //
            if ($this->cerPCI == '1' && // Is PCI
                ($this->operation == '2' || $this->operation == '1') && // Charge || Change And save token
                (!isset($paymentTokenValue) || $paymentTokenValue === 'new')) {
                if ($this->charge_token($paymentTokenValue, $order_id)) {
                    // ----  From this point the Order has completed the process successfully ---- //
                    // Remove Cart Manually (This is to prevent "הזמנה כפולה")
                    WC()->cart->empty_cart();
                    if ($this->successUrl != '') {
                        $redirectTo = $this->successUrl;
                    } else {
                        $redirectTo = $this->get_return_url($order);
                    }
                    $order->payment_complete();
                    return array(
                        'result' => 'success',
                        'redirect' => $redirectTo);
                } else {
                    if ($this->failedUrl != '') {
                        $redirectTo = $this->failedUrl;
                    } else {
                        $redirectTo = $this->get_return_url($order);
                    }
                    return array(
                        'result' => 'fail',
                        'redirect' => $redirectTo);
                }
            }
            // ================= Charge and Save Token ================= //
            try {
                if ($this->operation == '2') {
                    // ================= Validate Token: validate that user selected a saved payment method ================= //
                    if (isset($paymentTokenValue) && $paymentTokenValue !== 'new') {
                        // ------- Validate CVV field (i.e. if CVV is a must) ------- //
                        if (self::$must_cvv == 1 && isset($_POST['cardcom-card-cvc']) && empty($_POST['cardcom-card-cvc'])) {
                            return array(
                                'result' => 'failure',
                                'messages' => 'not valid cvv'
                            );
                        }
                        // ------- From here we process Token ------- //
                        if ($this->charge_token($_POST['wc-cardcom-payment-token'], $order_id)) {
                            // ----  From this point the Order has completed the process successfully ----
                            WC()->cart->empty_cart(); // Remove Cart Manually (This is to prevent "הזמנה כפולה")
                            if ($this->successUrl != '') {
                                $redirectTo = $this->successUrl;
                            } else {
                                $redirectTo = $this->get_return_url($order);
                            }
                            $order->payment_complete();
                            return array(
                                'result' => 'success',
                                'redirect' => $redirectTo);
                        } else {
                            if ($this->failedUrl != '') {
                                $redirectTo = $this->failedUrl;
                            } else {
                                $redirectTo = $this->get_return_url($order);
                            }
                            return array(
                                'result' => 'fail',
                                'redirect' => $redirectTo);
                        }
                    }

                    if (isset($_POST['wc-cardcom-new-payment-method']) && 'true' === $_POST['wc-cardcom-new-payment-method']) {
                    } else {
                        $this->operationToPerform = '1';
                    }
                }
            } catch (Exception $ex) {
                error_log($ex . get_error_message());
            }
            // ================= Other operations... ================= //
            if ($this->UseIframe == 1) {
                if (version_compare(WOOCOMMERCE_VERSION, '2.2', '<')) {
                    return array(
                        'result' => 'success',
                        'redirect' => add_query_arg('order', $order_id, add_query_arg('key', $order->get_order_key(), get_permalink(woocommerce_get_page_id('pay')))));
                } else {
                    $arr_params = array('order-pay' => $order_id, 'operation' => $this->operationToPerform);
                    return array(
                        'result' => 'success',
                        'redirect' => add_query_arg($arr_params, add_query_arg('key', $order->get_order_key(), $order->get_checkout_payment_url(true))));
                }
            } else {
                return array(
                    'result' => 'success',
                    'redirect' => $this->GetRedirectURL($order_id)
                );
            }
        }

        public static function GetCurrency($order, $currency)
        {

            if ($currency != 0)
                return $currency;

            // if woo graeter then 3.0 use get_currency
            if (version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) {
                $cur = $order->get_order_currency();
            } else {
                $cur = $order->get_currency();
            }

            if ($cur == "ILS")
                return 1;
            else if ($cur == "NIS")
                return 1;
            else if ($cur == "AUD")
                return 36;
            else if ($cur == "USD")
                return 2;
            else if ($cur == "CAD")
                return 124;
            else if ($cur == "DKK")
                return 208;
            else if ($cur == "JPY")
                return 392;
            else if ($cur == "CHF")
                return 756;
            else if ($cur == "GBP")
                return 826;
            else if ($cur == "USD")
                return 2;
            else if ($cur == "EUR")
                return 978;
            else if ($cur == "RUB")
                return 643;
            else if ($cur == "SEK")
                return 752;
            else if ($cur == "NOK")
                return 578;
            return $cur;
        }

        /***
         * @param $order_id
         * @return string
         *
         * Gets order parameter to sent to cardcom
         */
        function GetRedirectURL($order_id)
        {
            global $woocommerce;
            $order = new WC_Order($order_id);
            $params = array();
            wc_delete_order_item_meta((int)$order_id, 'CardcomInternalDealNumber');
            wc_delete_order_item_meta((int)$order_id, 'IsIpnRecieved');
            wc_delete_order_item_meta((int)$order_id, 'InvoiceNumber');
            wc_delete_order_item_meta((int)$order_id, 'InvoiceType');


            $params = self::initInvoice($order_id);

            $params["APILevel"] = "9";

            $params["Plugin"] = self::$plugin;

            // https://github.com/UnifiedPaymentSolutions/woocommerce-payment-gateway-everypay/blob/master/includes/class-wc-gateway-everypay.php


            // Redirect
            // if($this->isML == 'TRUE'){
            if (strpos(home_url(), '?') !== false) {

                $params["ErrorRedirectUrl"] = untrailingslashit(home_url()) . '&wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_failed&order_id=' . $order_id);
                $params["IndicatorUrl"] = untrailingslashit(home_url()) . '&wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_IPN&order_id=' . $order_id);
                $params["SuccessRedirectUrl"] = untrailingslashit(home_url()) . '&wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_successful&order_id=' . $order_id);
                $params["CancelUrl"] = untrailingslashit(home_url()) . '&wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_cancel&order_id=' . $order_id);

            } else {
                $params["ErrorRedirectUrl"] = untrailingslashit(home_url()) . '?wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_failed&order_id=' . $order_id);
                $params["IndicatorUrl"] = untrailingslashit(home_url()) . '?wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_IPN&order_id=' . $order_id);
                $params["SuccessRedirectUrl"] = untrailingslashit(home_url()) . '?wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_successful&order_id=' . $order_id);
                $params["CancelUrl"] = untrailingslashit(home_url()) . '?wc-api=WC_Gateway_Cardcom&' . ('cardcomListener=cardcom_cancel&order_id=' . $order_id);
            }

            $params["CancelType"] = "2";
            $params["ProductName"] = "Order Id:" . $order_id;
            $params["ReturnValue"] = $order_id;

            if ($this->operation == '4' || $this->operation == '5') // Req Params for Suspend Deal
            {
                $this->operationToPerform = '4';
                if ($this->terminalnumber == 1000 || $this->operation == '4') {
                    $params['SuspendedDealJValidateType'] = "2";
                } else {
                    $params['SuspendedDealJValidateType'] = "5";
                }

                $params['SuspendedDealGroup'] = "1";

            }

            if (!empty($this->maxpayment) && $this->maxpayment >= "1") {
                $params['MaxNumOfPayments'] = $this->maxpayment;
            }

            $params["Operation"] = $this->operationToPerform;
            $params["ClientIP"] = $this->GetClientIP();

            if ($this->invoice == '1' && $this->operation != '3') {
                $params['InvoiceHeadOperation'] = "1"; // Create Invoice
            } else {
                $params['InvoiceHeadOperation'] = "2"; // Show Only
            }
            $params = apply_filters('cardcom_redirect_url_params', $params, $order_id);
            $urlencoded = http_build_query($this->senitize($params));
            $args = array('body' => $urlencoded,
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array());
            $response = $this->cardcom_post($this->CardComURL . '/BillGoldLowProfile.aspx', $args);
            if (is_wp_error($response)) {
                $IsOk = false;
                //$this->HandleError("wp is error","wp is error 2 ", $urlencoded);
                return;
            }

            $body = wp_remote_retrieve_body($response);
            $exp = explode(';', $body);
            //error_log(implode(" ",$exp ));
            $data = array();
            $IsOk = true;
            if ($exp[0] == "0") {
                $IsOk = true;
                $data['profile'] = $exp[1];
                //wc_add_order_item_meta((int)$order->id, 'Profile', 0 );
                update_post_meta((int)$order_id, 'Profile', $data['profile']);
            } else {
                $IsOk = false;
                $this->HandleError($exp[0], $body, $urlencoded);
            }

            $requestVars = array();
            $requestVars["terminalnumber"] = self::$trm;
            $requestVars["Rcode"] = $exp[0];
            $requestVars["lowprofilecode"] = $exp[1];

            if ($IsOk) {
                return $this->url . "?" . http_build_query($this->senitize($requestVars));
            } else {
                return $this->urlError . "?massage=" . $this->senitize("Code : " . $exp[0] . " Description:" . $exp[2]);
            }
        }

        //Handle Post to Cardcom
        function cardcom_post($url, $args)
        {
            // 1st try
            $response = wp_remote_post($url, $args); // SERVER CALL
            // Try 3 more times if fails
            if (is_wp_error($response)) {
                $counter = 3;
                while ($counter > 0) {
                    $response = wp_remote_post($url, $args); // SERVER CALL
                    $counter--;
                    // ======= Success ======= //
                    if (is_wp_error($response) == false) {
                        break;
                    }
                    // ======= Fails (Write error message ONCE if after the other 3 tries ALSO failed) ======= //
                    if (counter == 0) {
                        error_log('post failed! url : ' . $url);
                        $error = $response->get_error_message();
                        $this->HandleError('999', $error, $args);
                    }
                }
            }
            return $response;
        }

        function HandleError($Error, $msg, $info)
        {
            if ($this->adminEmail != '') {
                wp_mail($this->adminEmail, 'Cardcom payment gateway something went wrong',
                    "Wordpress Transcation Faild!\n
 						==== XML Response ====\n
 						Terminal Number:" . $this->terminalnumber . "\n
 						Error Code:			  " . $Error . "\n
 						==== Transaction Details ====\n
 						Full Response :  " . $msg . "
 						Info:		  " . $info . "\n
 						Please contact Cardcom support with this information"

                );
            }
            error_log("Wordpress Transcation Faild!\n
 						==== XML Response ====\n
 						Terminal Number:" . $this->terminalnumber . "\n
 						Error Code:			  " . $Error . "\n
 						==== Transaction Details ====\n
 						Full Response :  " . $msg . "
 						Info:		  " . $info . "\n
 						Please contact Cardcom support with this information");
        }

        function generate_cardcom_form($order_id)
        {
            $URL = $this->GetRedirectURL($order_id);
            $formstring = '<iframe width="100%" height="1000" frameborder="0" src="' . $URL . '" ></iframe>';
            return $formstring;
        }

        function receipt_page($order)
        {
            $this->operationToPerform = $_GET['operation'];
            if ($this->operationToPerform != '2' && $this->operationToPerform != '3' && $this->operationToPerform != '4' && $this->operationToPerform != '5') {
                $this->operationToPerform = '1';
                error_log("operationToPerform was not set, setting it to default");
            }
            //echo '<p>'.__('Thank you for your order, please click the button below to pay with Cardcom.', 'woocommerce').'</p>';
            echo $this->generate_cardcom_form($order);
        }

        function check_ipn_response()
        {
            if (isset($_GET['cardcomListener']) && $_GET['cardcomListener'] == 'cardcom_IPN'):
                @ob_clean();
                $_POST = stripslashes_deep($_REQUEST);
                header('HTTP/1.1 200 OK');
                header('User-Agent: Cardcom');
                do_action("valid-cardcom-ipn-request", $_REQUEST);
            endif;

            if (isset($_GET['cardcomListener']) && $_GET['cardcomListener'] == 'cardcom_successful'):
                @ob_clean();
                $_POST = stripslashes_deep($_REQUEST);
                header('HTTP/1.1 200 OK');
                header('User-Agent: Cardcom');
                do_action("valid-cardcom-successful-request", $_REQUEST);
            endif;

            if (isset($_GET['cardcomListener']) && $_GET['cardcomListener'] == 'cardcom_cancel'):
                @ob_clean();
                $_GET = stripslashes_deep($_REQUEST);
                header('HTTP/1.1 200 OK');
                header('User-Agent: Cardcom');
                do_action("valid-cardcom-cancel-request", $_REQUEST);
            endif;


            if (isset($_GET['cardcomListener']) && $_GET['cardcomListener'] == 'cardcom_failed'):
                @ob_clean();
                $_GET = stripslashes_deep($_REQUEST);
                header('HTTP/1.1 200 OK');
                header('User-Agent: Cardcom');
                do_action("valid-cardcom-failed-request", $_REQUEST);
            endif;

        }

        function cancel_request($get)
        {

            $order_id = intval($get["order_id"]);
            global $woocommerce;

            $order = new WC_Order($order_id);

            if (!empty($order_id)) {
                $cancelUrl = $order->get_cancel_order_url();
                if ($this->UseIframe == 1) {
                    // wp_redirect($cancelUrl);
                    echo "<script>window.top.location.href = \"$cancelUrl\";</script>";
                    exit();
                } else {
                    wp_redirect($cancelUrl);
                    die();
                }
            }

        }

        function failed_request($get)
        {
            if ($this->failedUrl != '') {
                if ($this->UseIframe == 1) {
                    echo "<script>window.top.location.href = \"$this->failedUrl\";</script>";
                    exit();
                } else {
                    wp_redirect($this->failedUrl);
                }
            } else
                $this->cancel_request($get);

        }

        //http://ipnadress/wp?wc-api=WC_Gateway_Cardcom&cardcomListener=cardcom_IPN&order_id=158&terminalnumber=1000&lowprofilecode=d7aa9b2d-e97f-4c13-8f66-2131dd252618&Operation=2&OperationResponse=5116&OperationResponseText=NOTOK
        function ipn_request($posted)
        {

            //if($posted["DealRespone"] == 0)
            //{
            //error_log( implode(" ", $posted ));
            $lowprofilecode = $posted["lowprofilecode"];
            $orderid = htmlentities($posted["order_id"]);

            $key_1_value = get_post_meta((int)$orderid, 'IsIpnRecieved', true);
            if (!empty($key_1_value) && $key_1_value == 'true') {
                //error_log("Order has been processed: ".$key_1_value);
                return;
            }

            return $this->updateOrder($lowprofilecode, $orderid);
            //}
        }

        function updateOrder($lowprofilecode, $orderid)
        {
            $order = new WC_Order($orderid);
            if ($this->IsLowProfileCodeDealOneOK($lowprofilecode, $this->terminalnumber, $this->username, $order) == '0') {

                if (!empty($orderid)) {
                    // wc_add_order_item_meta((int)$orderid, 'CardcomInternalDealNumber', 0 );
                    update_post_meta((int)$orderid, 'CardcomInternalDealNumber', $this->InternalDealNumberPro);

                    $order->add_order_note(__('IPN payment completed OK! Deal Number:' . $this->InternalDealNumberPro, 'woocommerce'));

                    $order->payment_complete();
                    if ($this->OrderStatus != 'on-hold') {
                        $order->payment_complete();
                    }
                    //wc_add_order_item_meta((int)$orderid, 'IsIpnRecieved', 0 );
                    update_post_meta((int)$orderid, 'IsIpnRecieved', 'true');
                    return true;
                }
            } else {

                if (!empty($orderid)) {
                    if ($order->get_status() == "completed" ||
                        $order->get_status() == "on-hold" ||
                        $order->get_status() == "processing") {
                        return true;
                    }
                    $order->add_order_note(__('IPN payment completed Not OK', 'woocommerce'));
                    $order->update_status("failed");
                    return false;
                }
            }
        }

        function successful_request($posted)
        {

            $orderid = htmlentities($posted["order_id"]);
            $order = new WC_Order($orderid);
            if (!empty($orderid)) {
                WC()->cart->empty_cart();
                if ($this->successUrl != '') {
                    $redirectTo = $this->successUrl;
                } else {
                    $redirectTo = $this->get_return_url($order);

                }

                if ($this->UseIframe) {
                    echo "<script>window.top.location.href =\"$redirectTo\";</script>";
                    exit();
                } else {
                    wp_redirect($redirectTo);
                }
                return true;
            }
            wp_redirect("/");
            return false;
        }

        protected $InternalDealNumberPro;
        protected $DealResponePro;

        /**
         * @param $lpc
         * @param $terminal
         * @param $username
         * @param $orderid
         * @return string
         *  Validate low profile code
         */
        function IsLowProfileCodeDealOneOK($lpc, $terminal, $username, $order)
        {
            $orderid = $order->id;
            $vars = array(
                'TerminalNumber' => $terminal,
                'LowProfileCode' => $lpc,
                'UserName' => $username
            );

            # encode information
            $urlencoded = http_build_query($this->senitize($vars));

            $args = array('body' => $urlencoded,
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array());
            $response = $this->cardcom_post($this->CardComURL . '/Interface/BillGoldGetLowProfileIndicator.aspx', $args);
            if (is_wp_error($response)) {
            }

            $body = wp_remote_retrieve_body($response);

            $responseArray = array();
            $returnvalue = '1';
            parse_str($body, $responseArray);
            //error_log(implode(" ",$responseArray));
            $this->InternalDealNumberPro = 0;
            $this->DealResponePro = -1;


            if (isset($responseArray['InternalDealNumber'])) {
                $this->InternalDealNumberPro = $responseArray['InternalDealNumber'];
            }

            if (isset($responseArray['DealResponse'])) #  OK!
            {
                $this->DealResponePro = $responseArray['DealResponse'];
            } else if (isset($responseArray['SuspendedDealResponseCode'])) #  Suspend Deal
            {
                $this->DealResponePro = $responseArray['SuspendedDealResponseCode'];
            }


            if (isset($responseArray['OperationResponse'])
                && $responseArray['OperationResponse'] == '0'
                && $responseArray['ReturnValue'] == $orderid) #  Normal Deal
            {
                $returnvalue = '0';
            }


            if ($returnvalue == '0') {
                try {

                    if ($responseArray['Operation'] == '2') {
                        $this->process_token($responseArray);
                    }

                    if ($responseArray['Operation'] == '3') {
                        add_post_meta($orderid, 'CardcomToken', $responseArray['Token']);
                        add_post_meta($orderid, 'CardcomTokenExDate', $responseArray['TokenExDate']);
                    }
                } catch (Exception $ex) {
                    error_log($ex->getMessage());
                }


                // http://kb.cardcom.co.il/article/AA-00241/0
                add_post_meta($orderid, 'Payment Gateway', 'CardCom');
                add_post_meta($orderid, 'cc_number', $responseArray['ExtShvaParams_CardNumber5']);
                add_post_meta($orderid, 'cc_holdername', $responseArray['ExtShvaParams_CardOwnerName']);

                add_post_meta($orderid, 'cc_numofpayments', 1 + $responseArray['ExtShvaParams_NumberOfPayments94']);
                if (1 + $responseArray['ExtShvaParams_NumberOfPayments94'] == 1) {
                    add_post_meta($orderid, 'cc_firstpayment', $responseArray['ExtShvaParams_Sum36']);
                    add_post_meta($orderid, 'cc_paymenttype', '1');
                } else {
                    add_post_meta($orderid, 'cc_firstpayment', $responseArray['ExtShvaParams_FirstPaymentSum78']);
                    add_post_meta($orderid, 'cc_paymenttype', '2');
                }

                add_post_meta($orderid, 'cc_total', $responseArray['ExtShvaParams_Sum36']);
                add_post_meta($orderid, 'cc_cardtype', $responseArray['ExtShvaParams_Sulac25']);
            }
            do_action('cardcom_IsLowProfileCodeDealOneOK', $returnvalue, $responseArray, $orderid);
            return $returnvalue;
        }

        /** Payment form on checkout page */
        function payment_fields()
        {
            $RenderCVV = false;
            // -------------- Load saved payment methods (via Token) -------------- //
            if ($this->supports('tokenization') && is_checkout() && $this->operation == '2') {
                $this->cardcom_checkout_script();
                $this->saved_payment_methods();
                $this->save_payment_method_checkbox();
                $RenderCVV = true;
            }
            // ------- Render Credit Card fields for the user to input ------- //
            if ($this->cerPCI == '1' && ($this->operation == '1' || $this->operation == '2')) {
                $this->cardcom_CreditCard_fields();
                $RenderCVV = true;
            }
            // -------------- Render CVV Field -------------- //
            if (self::$must_cvv == '1' && $RenderCVV) {
                $this->cardcom_token_validation_form();
            }
            // -------------- Render Description if set -------------- //
            if ($this->description) : ?><p><?php echo $this->description; ?></p> <?php endif; ?>
            <?php
        }

        /** Render Javascript scripts */
        function cardcom_checkout_script()
        {
            wp_enqueue_script('cardcom_chackout_script',
                plugins_url('/woo-cardcom-payment-gateway/frontend/cardcom.js'),
                //plugins_url('/woocommerce-cardcom-gateway/frontend/cardcom.js'),
                array('jquery'),
                WC()->version);
        }

        /** Render CVV Field in checkout page */
        function cardcom_token_validation_form()
        {
            $alwaysDisplayCVV = $this->cerPCI == '1' ? true : false;
            printf('<p class="form-row ' . ($alwaysDisplayCVV ? "" : "payment_method_cardcom_validation") . '">
			            <label for="%1$s-card-cvc">' . esc_html__('Security Digits (CVV)', 'cardcom') . ' <span class="required">*</span></label>
			            <input id="%1$s-card-cvc" name="%1$s-card-cvc"  class="input-text " 
			            inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" 
			            type="tel" maxlength="4" placeholder="' . esc_attr__('CVC', 'woocommerce') . '" style="width:150px" />
		                </p>',
                esc_attr($this->id));
        }

        /** Render Credit-Card Fields in checkout page */
        function cardcom_CreditCard_fields()
        {
            // ==================== Render CC Number field ==================== //
            printf('
                    <p class="form-row wc-payment-form">
                      <label for="cardcom-card-number">' . esc_html__("Credit Card Number", "cardcom") . '<span class="required">*</span></label>
                      <input id="cardcom-card-number" name="cardcom-card-number" type="text" 
                      class="input-text" maxlength="20" autocomplete="off" style="width:200px"/>
                    </p>
            ', esc_attr($this->id));

            // ==================== Expire Date + Citizen Id ==================== //
            ?>
            <label for="cardcom-expire-date"><?php _e("Expiration date", "cardcom") ?><span
                        class="required">*</span></label><br>
            <div style="display: flex; flex-direction: row" class="wc-payment-form">
                <!-- ==================== Render Expire Date ==================== -->
                <div style="margin: 0 0.11em 0 0.11em;">
                    <select name="cardcom-expire-month" id="cardcom-expire-month"
                            class="woocommerce-select woocommerce-cc-month input-text">
                        <option value=""><?php _e('Month', 'cardcom') ?></option>
                        <?php for ($i = 1; $i <= 12; $i++) {
                            printf('<option value="%u">%s</option>', $i, $i);
                        } ?>
                    </select>
                </div>
                <div style="margin: 0 0.11em 0 0.11em;">
                    <select name="cardcom-expire-year" id="cardcom-expire-year"
                            class="woocommerce-select woocommerce-cc-year input-text">
                        <option value=""><?php _e('Year', 'cardcom') ?></option>
                        <?php for ($i = date('y'); $i <= date('y') + 15; $i++) {
                            printf('<option value="20%u">%u</option>', $i, $i);
                        } ?>
                    </select>
                </div>
            </div>

            <!-- ==================== Id Field (Only render if site is in Hebrew) ==================== -->
            <?php if (get_locale() === 'he_IL'): ?>
            <div style="display: block; margin-right: 0.5em; margin-left: 0.5em;">
                <label for="cardcom-citizen-id">תעודת זהות<span class="required">*</span></label><br>
                <input id="cardcom-citizen-id" name="cardcom-citizen-id" type="text" class="input-text"
                       maxlength="10" autocomplete="off" style="width:150px"/>
            </div>
        <?php endif; ?>

            <!----------------------  Render num of payments if needed ---------------------->
            <?php
            // ============== Don't render if max payment is equal or below 1 ============== //
            if ($this->maxpayment <= 1) return;
            // Else .....
            // ==================== Render Number of Payments (If max payment is above 1) ==================== //
            ?>
            <p class="form-row wc-payment-form">
                <label for="cardcom-num-payments"><?php _e("Number of Payments", "cardcom") ?><span
                            class="required">*</span></label>
                <select name="cardcom-num-payments" id="cardcom-num-payments"
                        class="woocommerce-select input-text" style="width: 60px;">
                    <?php for ($i = 1; $i <= $this->maxpayment; $i++) {
                        printf('<option value="%u">%s</option>', $i, $i);
                    } ?>
                </select>
            </p>
            <?php
        }

        /** TOKENIZATION */
        function charge_token($cc_token, $order_id, $cvv = '')
        {
            // ========= Get Data from Input ========= //
            $numOfPayments = $this->get_post("cardcom-num-payments");
            $paymentTokenValue = $this->get_post('wc-cardcom-payment-token');
            $isCreateToken = $this->get_post('wc-cardcom-new-payment-method');
            $order = new WC_Order($order_id);
            $params = array();
            $params = self::initInvoice($order_id, self::$cvv_free_trm);
            $params['TokenToCharge.APILevel'] = '10';
            $coin = self::GetCurrency($order, self::$CoinID);
            $cvv = $this->get_post("cardcom-card-cvc");
            if ($cvv == null) {
                $cvv = '';
            }
            // ============= If PCI certification is checked AND new Payment is selected ============= //
            if ($this->cerPCI == '1') {
                $params['TokenToCharge.CardNumber'] = $this->get_post("cardcom-card-number");
                $params['TokenToCharge.CardValidityMonth'] = $this->get_post("cardcom-expire-month");
                $params['TokenToCharge.CardValidityYear'] = $this->get_post("cardcom-expire-year");
                $params['TokenToCharge.IdentityNumber'] = $this->get_post("cardcom-citizen-id");
                if ($isCreateToken != null && $isCreateToken == 'true') {
                    $params['TokenToCharge.IsCreateToken'] = "true";
                }
            }
            // ============= If user selected saved payment (i.e. Token)  ============= //
            if ($paymentTokenValue !== null && $paymentTokenValue !== 'new') {
                $token_id = wc_clean($cc_token);
                $token = WC_Payment_Tokens::get($token_id);
                if ($token->get_user_id() !== get_current_user_id()) {
                    return;
                }
                $params['TokenToCharge.Token'] = $token->get_token();
                $params['TokenToCharge.CardValidityMonth'] = $token->get_expiry_month();
                $params['TokenToCharge.CardValidityYear'] = $token->get_expiry_year();
            }
            // ============= Input the common fields ============= //
            $params['TokenToCharge.Salt'] = ''; #User ID or a Cost var.
            $params['TokenToCharge.SumToBill'] = number_format($order->get_total(), 2, '.', '');
            $coin = self::GetCurrency($order, self::$CoinID);
            $params["TokenToCharge.CardOwnerName"] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            $params["TokenToCharge.CardOwnerEmail"] = $order->get_billing_email();
            $params["TokenToCharge.CardOwnerPhone"] = $order->get_billing_phone();
            $params["TokenToCharge.CoinISOName"] = $order->get_currency();
            $params['TokenToCharge.UniqAsmachta'] = $order_id . $this->GetCurrentURL();
            $params['TokenToCharge.CVV2'] = $cvv;
            $params['TokenToCharge.NumOfPayments'] = $numOfPayments === null ? '1' : $numOfPayments;
            $params['CustomeFields.Field1'] = 'Cardcom Woo Token charge';
            $params['CustomeFields.Field2'] = "order_id:" . $order_id;
            $urlencoded = http_build_query($this->senitize($params));
            $args = array('body' => $urlencoded,
                'timeout' => '10',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array());
            $response = $this->cardcom_post($this->CardComURL . '/interface/ChargeToken.aspx', $args);
            $body = wp_remote_retrieve_body($response);
            $responseArray = array();
            $returnvalue = '1';
            parse_str($body, $responseArray);
            $this->InternalDealNumberPro = 0;
            if (isset($responseArray['ResponseCode']) && ($responseArray['ResponseCode'] == '0' || $responseArray['ResponseCode'] == '608')) {
                if (isset($responseArray['InternalDealNumber'])) {
                    $this->InternalDealNumberPro = $responseArray['InternalDealNumber'];
                } else {
                    $this->InternalDealNumberPro = "9";
                }
                update_post_meta((int)$order_id, 'CardcomInternalDealNumber', $this->InternalDealNumberPro);
                // ==================== if Create Token , add Token to User ==================== //
                if (($isCreateToken != null && $isCreateToken === 'true') && $paymentTokenValue !== null && $paymentTokenValue === 'new') {
                    // ==================== Prepare array for the function ==================== //
                    $responseArray["ReturnValue"] = $order_id;
                    $responseArray['ExtShvaParams_Tokef30'] = $responseArray["Tokef_30"];
                    $responseArray['ExtShvaParams_Mutag24'] = $responseArray["Mutag_24"];
                    $responseArray['ExtShvaParams_CardNumber5'] = $responseArray["CardNumEnd"];
                    $responseArray['Token'] = $responseArray["Token"];
                    $this->process_token($responseArray);
                }
                $order->add_order_note(__('Token charge successfully completed! Deal Number:' . $this->InternalDealNumberPro, 'woocommerce'));
                return true;
            }
            return false;
        }

        function GetCurrentURL()
        {
            $link = "";
            $link .= $_SERVER['HTTP_HOST'];
            return $link;
        }

        function GetClientIP()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
            {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }

        public static function IsStringSet($str)
        {
            if (!isset($str)) return false;
            $trimString = trim($str);
            if (trim($trimString) === '') return false;
            return true;
        }

        private function get_post($name)
        {
            if (isset($_POST[$name]))
                return $_POST[$name];
            return null;
        }

        function senitize($params)
        {
            foreach ($params as &$p) {
                $p = substr(strip_tags(preg_replace("/&#x\d*;/", " ", $p)), 0, 200);
            }
            return $params;
        }

        // save token
        function process_token($responseArray)
        {
            $order = new WC_Order($responseArray['ReturnValue']);
            $user_id = $order->user_id;
            $exDate = str_split($responseArray['ExtShvaParams_Tokef30'], 2);
            if (!empty($exDate)) {
                $ExYaer = 2000 + (int)$exDate[1];
                $ExMonth = $exDate[0];
                if (!empty($ExYaer) && !empty($ExMonth)) {
                    $brandId = $responseArray['ExtShvaParams_Mutag24'];
                    switch ($brandId) {
                        case 0:
                            $brand = 'other';
                            break;
                        case 1:
                            $brand = 'mastercard';
                            break;
                        case 2:
                            $brand = 'visa';
                            break;
                        default:
                            $brand = $brandId;
                            break;
                    }
                    $token = new WC_Payment_Token_CC();
                    $token->set_gateway_id($this->id);
                    $token->set_token($responseArray['Token']);
                    $token->set_last4($responseArray['ExtShvaParams_CardNumber5']);
                    $token->set_expiry_year($ExYaer);
                    $token->set_expiry_month($ExMonth); // incorrect length
                    $token->set_card_type($brand);
                    //$token->set_props('CardOwnerID',$responseArray['CardOwnerID']);
                    //$token->set_customet_id($responseArray['CardOwnerID']);
                    $token->set_user_id($user_id);
                    $token->save();
                    if ($token->get_id() > 0) {
                        add_post_meta($order->get_id(), 'CardcomTokenId', $token->get_id());
                        add_post_meta($order->get_id(), 'CardcomToken_expiry_year', $token->get_expiry_year());
                        add_post_meta($order->get_id(), 'CardcomToken_expiry_month', $token->get_expiry_month());

                    }
                }
            }
        }

    } // end woocommerce_sc

    /**
     * Add the Gateway to WooCommerce
     **/
    function add_cardcom_gateway($methods)
    {
        $methods[] = 'WC_Gateway_Cardcom';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_cardcom_gateway');
    WC_Gateway_Cardcom::init(); // add listner to paypal payments
}