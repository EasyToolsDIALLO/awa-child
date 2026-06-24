<?php
/**
 * Single variation cart button — design AwA
 *
 * @package awa-child
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$palette = awa_get_product_palette( $product->get_slug() );
$accent  = $palette['accent'];
?>
<div class="woocommerce-variation-add-to-cart variations_button awa-pdetail__buy">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<div class="awa-pdetail__form cart">
		<div class="awa-pdetail__qty">
			<button type="button" class="awa-pdetail__qty-btn" data-action="minus" aria-label="<?php esc_attr_e( 'Diminuer', 'awa-child' ); ?>">−</button>
			<?php
			do_action( 'woocommerce_before_add_to_cart_quantity' );

			woocommerce_quantity_input(
				array(
					'min_value'   => $product->get_min_purchase_quantity(),
					'max_value'   => $product->get_max_purchase_quantity(),
					'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
					'classes'     => array( 'awa-pdetail__qty-input', 'qty' ),
				)
			);

			do_action( 'woocommerce_after_add_to_cart_quantity' );
			?>
			<button type="button" class="awa-pdetail__qty-btn" data-action="plus" aria-label="<?php esc_attr_e( 'Augmenter', 'awa-child' ); ?>">+</button>
		</div>

		<button
			type="submit"
			class="single_add_to_cart_button button alt awa-pdetail__add-btn"
			style="background-color: <?php echo esc_attr( $accent ); ?>; box-shadow: 0 8px 24px -8px <?php echo esc_attr( $accent ); ?>;"
		>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		</button>
	</div>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
