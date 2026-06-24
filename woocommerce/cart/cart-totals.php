<?php
/**
 * Cart totals — AwA Bio Foods
 *
 * @package awa-child
 * @see     woocommerce/cart/cart-totals.php (v2.3.6)
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Sous-total', 'awa-child' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Sous-total', 'awa-child' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
			<tr class="shipping">
				<th><?php esc_html_e( 'Livraison', 'awa-child' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Livraison', 'awa-child' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'awa-child' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Total', 'awa-child' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<div class="wc-proceed-to-checkout awa-cart__checkout-wrap">
		<?php do_action( 'woocommerce_before_proceed_to_checkout' ); ?>

		<a
			href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
			class="awa-cart__checkout-btn checkout-button"
			aria-label="<?php esc_attr_e( 'Valider la commande', 'awa-child' ); ?>"
		>
			<span class="awa-cart__checkout-btn__left" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			</span>
			<span class="awa-cart__checkout-btn__body">
				<span class="awa-cart__checkout-btn__label"><?php esc_html_e( 'Valider la commande', 'awa-child' ); ?></span>
				<span class="awa-cart__checkout-btn__total">
					<?php echo wp_kses_post( WC()->cart->get_total() ); ?>
				</span>
			</span>
			<span class="awa-cart__checkout-btn__arrow" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</span>
		</a>

		<p class="awa-cart__checkout-secure">
			<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
			<?php esc_html_e( 'Paiement 100% sécurisé', 'awa-child' ); ?>
		</p>

		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
		<?php do_action( 'woocommerce_after_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
