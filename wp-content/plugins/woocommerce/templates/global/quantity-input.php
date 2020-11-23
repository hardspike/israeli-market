<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	</div>
	<?php
} else {
	/* translators: %s: Quantity. */
	$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );
	?>

	<?php

global $wpdb;
global $product;

echo "<style>
.varRadios {
	position: absolute; left: 15px; top: 10px;
	text-align: right;
}
.varRadios label {
	line-height: 0.9;
	display: block;
	color: white;
}
input[name='productVariation'] {
	font-size: 8px;
	width: 10px;
	height: 10px;
}
</style>";

if ($product->is_type('variable')) {

	global $pVars;
	global $initialVar;
	echo "<div class='quantityBox' style='text-align: right; padding-right: 10px;'>";

	if (isset($pVars)) {
		echo "<div class='varRadios'>";
		foreach ($pVars as $pv) {
			echo "<label><input type='radio' name='productVariation' data-vartype='{$pv['varName']}' data-varprice='{$pv['varPrice']}' data-varid='{$pv['varId']}'";
			echo (isset($initialVar) && $initialVar == $pv['varId']) ? " checked='checked'> " : ">";
			echo " {$pv['varName']}</label>";
		}
		echo "</div>";
	} else {

	$pName = $product->get_name();
	$sql = "SELECT `ID`, `post_title` FROM `wp_posts` WHERE `post_type` = 'product_variation' AND `post_title` LIKE '%{$pName} - %'";
	$varRows = $wpdb->get_results($sql);
	if ($varRows && count($varRows)) {
		$vars = [];
		foreach ($varRows as $v) {
			$vTitle = explode(' - ', $v->post_title);
			$vars[$vTitle[1]] = $v->ID;
		}

		echo "<div class='varRadios'>";
		foreach ($vars as $vName => $vId) {
			echo "<label><input type='radio' name='productVariation' data-vartype='{$vName}' data-varid='{$vId}'" . (isset($varId) && $vId == $varId) ? "checked" : "" . "> {$vName}</label>";
		}
		echo "</div>";
	}

	unset($GLOBALS['initialVar']);
	unset($GLOBALS['pVars']);
}
} else {
	echo "<div class='quantityBox'>";

}

	?>

	<div class="plus-minus-layout">
	<button type="button" class="plus">+</button>	
	<div class="quantity">
		<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
		<input
			type="number"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
			step="<?php echo esc_attr( $step ); ?>"
			min="<?php echo esc_attr( $min_value ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ); ?>"
			size="4"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			inputmode="<?php echo esc_attr( $inputmode ); ?>" />
		<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
	</div>
	<button type="button" class="minus">-</button>
	</div>
	</div>
	<?php
}
