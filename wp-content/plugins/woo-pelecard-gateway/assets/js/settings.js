( function( $ ) {
	$( function() {
		$(document).ready(function(){
			$(document).on('click', '#pelecard-settings-block .nav-tab-wrapper a', function(){
				if (!is_tab_valid($('#pelecard-settings-block tr.' + $('#pelecard-settings-block .nav-tab-active').attr('data-tab')))){
					return false;
				}
				
				var tab = $('#pelecard-settings-block tr.' + $(this).attr('data-tab'));
				$('#pelecard-settings-block tr.pelecard-tab').each(function(){
					if ($(this).is(tab)) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
				
				$('#pelecard-settings-block .nav-tab-active').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
			});
			
			var hash = $( location ).attr( 'hash' ).replace( /^#/, '' );
			var osek = $( '#woocommerce_pelecard_tamalosek' );
			
			$('.pelecard-tab').each( function() {
				var field = $( this );
				var classes = $(field).attr('class').match(/[\w-]*pelecard-tab[\w-]*/g);
				
				$.each(classes, function(index, value){
					$(field).closest('tr').addClass( value );
				});
			});
			
			if (hash === '' || !$('#pelecard-settings-block a.nav-tab[data-tab="' + hash + '"]').length){
				$('#pelecard-settings-block a.nav-tab:first-of-type').click();
			} else {
				$('#pelecard-settings-block a.nav-tab[data-tab="' + hash + '"]').click();
			}
			
			tamal_doc_type(osek);
			
			osek.on("change", function(e){
				tamal_doc_type(osek);
			});
			
			function is_tab_valid(tab){
				var valid = true;
				tab.find('input, select').each( function(){
					if ($(this)[0].checkValidity() === false){
						$(this).focus();
						valid = false;
						return false;
					}
				});
				
				return valid;
			}
			
			function tamal_doc_type(osek){
				var doc_types = {};
				doc_types.mursh = [100, 10100, 10301, 305, 320, 330, 400]; 
				doc_types.patur = [100, 10100, 10301, 300, 400]; 
				doc_types.amuta = [100, 10100, 10301, 300, 400, 405]; 
				
				$('#woocommerce_pelecard_tamaldoctype > option').each( function(){
					if ($.inArray(parseInt($(this).val()), doc_types[osek.val()]) !== -1){
						$(this).prop('disabled', false);
					} else {
						$(this).prop('disabled', true);
						if ($(this).is(':selected')){
							$( '#woocommerce_pelecard_tamaldoctype' ).val(100).trigger("change");
						}
					}
				});
			}
			
			$('#pelecard_payment_range').on( 'click', 'a.add', function(){
				var $symbol = $('.ranges').data('symbol');
				var $size = $('#pelecard_payment_range').find('tbody .range').length;

				$('<tr class="range">' +
						'<td class="sort"></td>' +
						'<td>' +
							'<div>' +
								'<div class="price-container">' +
									'<span class="currency-symbol">' + $symbol + '</span>' +
									'<input class="payment-range-min" type="number" name="paymentrange_cart[' + $size + '][min]" value="1" readonly>' +
								'</div>' +
								'<div class="slider-container">' +
									'<div class="slider-range" data-id="' + $size + '"></div>' +
								'</div>' +
								'<div class="price-container">' +
									'<span class="currency-symbol">' + $symbol + '</span>' +
									'<input class="payment-range-max" type="number" name="paymentrange_cart[' + $size + '][max]" value="10000" readonly>' +
								'</div>' +
							'</div>' +
						'</td>' +
						'<td><input type="number" name="paymentrange_min[' + $size + ']" /></td>' +
						'<td><input type="number" name="paymentrange_max[' + $size + ']" /></td>' +
					'</tr>').appendTo('#pelecard_payment_range table tbody');
				
				init_slider( $('.slider-range[data-id="' + $size + '"]') );
				
				return false;
			});
			
			function init_slider(selector) {
				selector = selector || ".slider-range";
				$( selector ).each( function( index, el ) {
					var $min = $(this).parentsUntil('tr').find("input[name*='min']");
					var $max = $(this).parentsUntil('tr').find("input[name*='max']");
					$(this).slider({
						range: true,
						min: 1,
						max: 10000,
						values: [ $min.val(), $max.val() ],
						slide: function( event, ui ) {
							$min.val(ui.values[0]);
							$max.val(ui.values[1]);
						},
					});
				});
			}
			
			init_slider();
		});
	});
})( jQuery );