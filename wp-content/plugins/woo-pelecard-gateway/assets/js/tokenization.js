( function( $ ) {
	$( function() {
		$( document.body ).on( 'updated_checkout wc-credit-card-form-init', function() {
			var $formWrap = $( '.payment_method_pelecard' );
			var $target = $formWrap.find( 'ul.woocommerce-SavedPaymentMethods' );
			
			$( ':input.woocommerce-SavedPaymentMethods-tokenInput', $target ).change( function() {
				$formWrap = $target.closest( '.payment_box' );
				if ( 'new' === $( this ).val() ) {
					$( '.woocommerce-NumOfPayments', $formWrap ).hide();
				} else {
					$( '.woocommerce-NumOfPayments', $formWrap ).show();
				}
			} );
			
			// Trigger change event
			$( ':input.woocommerce-SavedPaymentMethods-tokenInput:checked', $target ).trigger( 'change' );
		} );
	});
})( jQuery );