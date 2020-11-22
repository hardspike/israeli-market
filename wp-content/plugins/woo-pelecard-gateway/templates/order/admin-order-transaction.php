<?php
/**
 * Add transactions metabox
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/admin-order-transaction.php.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

$invoice_link = false;
?>

<table id="pelecard-transaction-data-tables" style="width:100%;">
	<tbody>
		<?php foreach ( $transactions as $transaction_data ) : $transaction = new WC_Pelecard_Transaction( null, $transaction_data ); ?>
		<tr>
			<td>
				<h3><?php
				if ( isset( $transaction->TransactionId ) ) {
					printf( '%s: %s', __( 'Transaction ID', 'woo-pelecard-gateway' ), $transaction->TransactionId );
				} elseif ( isset( $transaction->PelecardTransactionId ) ) {
					printf( '%s: %s', __( 'Transaction ID', 'woo-pelecard-gateway' ), $transaction->PelecardTransactionId );
				} elseif ( ! empty( $transaction->ErrorMessage ) ) {
					echo $transaction->ErrorMessage;
				} else {
					echo '—';
				}
				?></h3>
				<?php if ( ! empty( $transaction ) ) : ?>
				<div>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th scope="col" class="manage-column"><?php _e( 'Parameter', 'woo-pelecard-gateway' ); ?></th>
								<th scope="col" class="manage-column"><?php _e( 'Value', 'woo-pelecard-gateway' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $transaction as $index => $value ) : ?>
							<tr>
								<td><span><?php _e( $index ); ?></span></td>
								<td><span><?php echo ( ! empty( $value ) ) ? $value : '—'; ?></span></td>
							</tr>
							<?php endforeach;
							
							if ( ! $invoice_link && isset( $transaction->InvoiceLink ) && ! filter_var( $transaction->InvoiceLink, FILTER_VALIDATE_URL ) === false ) {
								$invoice_link = $transaction->InvoiceLink;	
							}
							?>
						</tbody>
					</table>
				</div>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach;
						
		if ( ( $http_headers = wp_get_http_headers( $invoice_link ) ) && ! empty( $http_headers['location'] ) ) :
		?>
		<tr>
			<td class="widefat">
				<h3><?php _e( 'Tamal', 'woo-pelecard-gateway' ); ?></h3>
				<div class="num">
					<p><?php 
					printf(
						/* translators: %s$1: tamal invoice link, %s$2: link text */
						'<input type="button" onclick="location.href=\'%s\'" class="button button-primary" name="save" value="%s">',
						esc_url( $transaction->InvoiceLink ),
						__( 'Download Document', 'woo-pelecard-gateway' )
					);
					?></p>
				</div>
			</td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>
