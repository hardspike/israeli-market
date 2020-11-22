( function( $ ) {
	$( function() {
		$(document).ready(function(){
			var icons = {
				header: "ui-icon-mail-closed",
				activeHeader: "ui-icon-mail-open"
			};
			
			$( '#pelecard-transaction-data-tables' ).accordion({
				header: "h3",
				collapsible: true,
				active: false,
				animate: false,
				heightStyle: 'content',
				icons: icons
			});
		});
	});
})( jQuery );