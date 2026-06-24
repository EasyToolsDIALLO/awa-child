<?php
/**
 * Variable product add to cart — boutons radios
 *
 * Remplace les selects WooCommerce par des boutons radios pour les attributs de variation.
 *
 * @package awa-child
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$palette         = awa_get_product_palette( $product->get_slug() );
$attribute_keys  = array_keys( $product->get_variation_attributes() );
$attributes      = $product->get_variation_attributes();
$available_variations = $product->get_available_variations();
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="value awa-variation-value-left">
							<?php
							$attribute_name_safe = sanitize_title( $attribute_name );
							$selected            = isset( $_REQUEST[ 'attribute_' . $attribute_name_safe ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'attribute_' . $attribute_name_safe ] ) ) : $product->get_variation_default_attribute( $attribute_name );
							?>
							<div class="awa-variation-radios" data-attribute="<?php echo esc_attr( $attribute_name_safe ); ?>" style="--awa-variation-bg: <?php echo esc_attr( $palette['tint'] ); ?>; --awa-variation-accent: <?php echo esc_attr( $palette['accent'] ); ?>;">
								<?php foreach ( $options as $option ) :
									$option_label = $option;
									if ( taxonomy_exists( $attribute_name ) ) {
										$term = get_term_by( 'slug', $option, $attribute_name );
										if ( $term && ! is_wp_error( $term ) ) {
											$option_label = $term->name;
										}
									}
									$option_id = $attribute_name_safe . '-' . sanitize_title( $option );
									?>
									<label class="awa-variation-radio" for="<?php echo esc_attr( $option_id ); ?>">
										<input type="radio" id="<?php echo esc_attr( $option_id ); ?>" name="attribute_<?php echo esc_attr( $attribute_name_safe ); ?>" value="<?php echo esc_attr( $option ); ?>" <?php checked( $selected, $option ); ?>>
										<span><?php echo esc_html( $option_label ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
							<?php
							// Select caché pour que la logique JS WooCommerce continue de fonctionner.
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
									'selected'  => $selected,
									'class'     => 'awa-variation-select-hidden',
									'id'        => '',
								)
							);
							?>
						</td>
						<td class="label awa-variation-label-empty">
							<?php echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__( 'Clear options', 'woocommerce' ) . '">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : ''; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap">
			<?php
				do_action( 'woocommerce_before_single_variation' );
				do_action( 'woocommerce_single_variation' );
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<script>
(function () {
    document.querySelectorAll('.awa-variation-radios').forEach(function (radios) {
        var firstRadio = radios.querySelector('input[type="radio"]');
        if (!firstRadio) return;
        var name = firstRadio.name;
        var select = document.querySelector('select[name="' + name + '"]');
        if (!select) return;

        radios.querySelectorAll('input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (radio.checked) {
                    select.value = radio.value;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    if (window.jQuery) {
                        jQuery(select).trigger('change');
                    }
                }
            });
        });
    });

    document.querySelectorAll('select.awa-variation-select-hidden').forEach(function (select) {
        select.addEventListener('change', function () {
            var radios = document.querySelectorAll('input[type="radio"][name="' + select.name + '"]');
            radios.forEach(function (radio) {
                radio.checked = radio.value === select.value;
            });
        });
    });
})();
</script>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
