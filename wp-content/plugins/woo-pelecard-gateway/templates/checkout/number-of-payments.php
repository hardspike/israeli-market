<?php
/**
 * Add payments fieldset
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/number-of-payments.php.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly
?>

<fieldset class="form-row woocommerce-NumOfPayments">
	<label class="screen-reader-text" for="wc-pelecard-number-of-payments"><?php _e( 'Select number of payments', 'woo-pelecard-gateway' ); ?></label>
	<select name="num_of_payments" id="wc-pelecard-number-of-payments">
		<option value=""><?php _e( 'Select number of payments', 'woo-pelecard-gateway' ); ?></option>
		<?php foreach ( range( $payments['MinPayments'], $payments['MaxPayments'] ) as $payment ) : ?>
		<option value="<?php echo $payment; ?>"><?php echo ( $payments['MinPaymentsForCredit'] <= $payment ) ? sprintf( __( '%s (Credit)', 'woo-pelecard-gateway' ), $payment ) : $payment ; ?></option>
		<?php endforeach; ?>
	</select>
</fieldset>
