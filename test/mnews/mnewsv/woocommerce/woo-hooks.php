<?php

// Update cart contents when added via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
    $cart_count = WC()->cart->get_cart_contents_count(); ?>
		<span class="cart-contents"><?php echo $cart_count; ?></span>
	<?php $fragments['.cart-contents'] = ob_get_clean();

	return $fragments;
}
