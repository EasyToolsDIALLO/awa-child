<?php
/**
 * Panier — AwA Bio Foods (redesign)
 *
 * @package awa-child
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action(
    'woocommerce_proceed_to_checkout',
    function () {
        echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="checkout-button awa-btn awa-btn--primary awa-cart__checkout">';
        echo esc_html__( 'Commander', 'awa-child' );
        echo '</a>';
    },
    20
);

do_action( 'woocommerce_before_cart' );
?>

<div class="awa-cart">
    <div class="awa-container">
        <style>
            /* Masquer bouton Rechercher (toutes variantes) */
            .woocommerce-cart .widget_search,
            .woocommerce-cart .search-form,
            .woocommerce-cart form[role="search"],
            .woocommerce-cart .wp-block-search,
            .woocommerce-cart #searchform,
            .woocommerce-cart button[name="s"],
            .woocommerce-cart input[name="s"],
            .woocommerce-cart .search-submit,
            .woocommerce-cart button[type="submit"][value*="search" i],
            .woocommerce-cart .woocommerce-cart-form button[value="Rechercher"],
            .woocommerce-cart .woocommerce-cart-form input[type="submit"][value="Rechercher"] { display: none !important; }

            /* Masquer bouton Modifier / Mettre à jour le panier */
            .woocommerce-cart button[name="update_cart"],
            .woocommerce-cart input[name="update_cart"][type="submit"],
            .woocommerce-cart .awa-cart__update,
            .woocommerce-cart .cart-contents button.update_cart { display: none !important; }

            /* Boutons */
            .woocommerce-cart .button,
            .woocommerce-cart a.button,
            .woocommerce-cart input[type=submit] {
                background: oklch(0.45 0.16 145) !important;
                background-color: oklch(0.45 0.16 145) !important;
                color: oklch(0.985 0.012 95) !important;
                border: none !important;
                border-radius: 9999px !important;
                font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
                font-size: 0.8125rem !important;
                font-weight: 600 !important;
                padding: 0.75rem 1.5rem !important;
                box-shadow: none !important;
                text-shadow: none !important;
                letter-spacing: 0 !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
            }
            .woocommerce-cart .awa-btn--light {
                background: oklch(0.985 0.012 95) !important;
                background-color: oklch(0.985 0.012 95) !important;
                color: oklch(0.22 0.04 145) !important;
                border: 1px solid oklch(0.9 0.02 95) !important;
            }
            /* Supprimer boutons qty natifs WooCommerce (fond vert parasite) */
            .woocommerce-cart .quantity .plus,
            .woocommerce-cart .quantity .minus,
            .woocommerce-cart .quantity button.button,
            .woocommerce-cart div.quantity > .button { display: none !important; visibility: hidden !important; width: 0 !important; height: 0 !important; padding: 0 !important; }
            .woocommerce-cart .quantity,
            .woocommerce-cart div.quantity { background: transparent !important; border: none !important; display: contents !important; }

            /* Contrôle quantité — horizontal, valeur centrée entre - et + */
            .woocommerce-cart .awa-pdetail__qty {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 0.25rem !important;
                border: none !important;
                background: transparent !important;
                flex-wrap: nowrap !important;
            }
            .woocommerce-cart .awa-pdetail__qty-btn {
                background: rgba(56,84,63,0.08) !important;
                background-color: rgba(56,84,63,0.08) !important;
                color: #38543f !important;
                border: none !important;
                border-radius: 50% !important;
                padding: 0 !important;
                width: 2rem !important;
                height: 2rem !important;
                font-size: 1rem !important;
                font-weight: 400 !important;
                line-height: 1 !important;
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                box-shadow: none !important;
            }
            .woocommerce-cart .awa-pdetail__qty-input,
            .woocommerce-cart .awa-pdetail__qty input.qty {
                width: 1.5rem !important;
                font-size: 0.75rem !important;
                border: none !important;
                background: transparent !important;
                padding: 0 !important;
                text-align: center !important;
                box-shadow: none !important;
            }
            /* Bouton Valider la commande — redesign */
            .woocommerce-cart .awa-cart__checkout-btn {
                display: grid !important;
                grid-template-columns: auto 1fr auto !important;
                align-items: center !important;
                gap: 0.875rem !important;
                width: 100% !important;
                padding: 1rem 1.25rem !important;
                background: linear-gradient(135deg, oklch(0.38 0.18 148), oklch(0.50 0.17 142)) !important;
                color: #fff !important;
                border: none !important;
                border-radius: 1rem !important;
                text-decoration: none !important;
                cursor: pointer !important;
                box-shadow: 0 4px 6px -1px oklch(0.38 0.18 148 / 0.3), 0 12px 28px -8px oklch(0.38 0.18 148 / 0.45) !important;
                transition: transform 0.18s ease, box-shadow 0.18s ease !important;
            }
            .woocommerce-cart .awa-cart__checkout-btn:hover {
                transform: translateY(-2px) !important;
                color: #fff !important;
                text-decoration: none !important;
            }
            .woocommerce-cart .awa-cart__checkout-btn__left,
            .woocommerce-cart .awa-cart__checkout-btn__arrow {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                background: rgba(255,255,255,0.15) !important;
                flex-shrink: 0 !important;
            }
            .woocommerce-cart .awa-cart__checkout-btn__left {
                width: 2.25rem !important; height: 2.25rem !important;
                border-radius: 0.625rem !important;
            }
            .woocommerce-cart .awa-cart__checkout-btn__arrow {
                width: 2rem !important; height: 2rem !important;
                border-radius: 50% !important;
            }
            .woocommerce-cart .awa-cart__checkout-btn__label {
                display: block !important;
                font-size: 0.9375rem !important; font-weight: 700 !important;
                color: #fff !important; line-height: 1.2 !important;
            }
            .woocommerce-cart .awa-cart__checkout-btn__total {
                display: block !important;
                font-size: 0.75rem !important; font-weight: 500 !important;
                color: #fff !important; opacity: 0.8 !important; line-height: 1 !important;
            }
            .woocommerce-cart .awa-cart__checkout-secure {
                display: flex !important;
                align-items: center !important; justify-content: center !important;
                gap: 0.3rem !important;
                margin: 0.5rem 0 0 !important;
                font-size: 0.6875rem !important; color: oklch(0.45 0.03 145) !important;
            }
            /* Masquer le fallback WooCommerce */
            .woocommerce-cart .wc-proceed-to-checkout .checkout-button:not(.awa-cart__checkout-btn) { display: none !important; }
            /* Marges horizontales */
            .woocommerce-cart .awa-container {
                padding-left: 1.25rem !important;
                padding-right: 1.25rem !important;
            }
            @media (min-width: 768px) {
                .woocommerce-cart .awa-container {
                    padding-left: 2rem !important;
                    padding-right: 2rem !important;
                }
            }
            /* Alignement desktop */
            @media (min-width: 1024px) {
                .woocommerce-cart .awa-cart .awa-cart__item,
                .woocommerce-cart .awa-cart .woocommerce-cart-form__cart-item,
                .woocommerce-cart .awa-cart .cart_item,
                .woocommerce-cart .awa-cart__item { padding-left: 0 !important; }
                .woocommerce-cart .cart-collaterals.awa-cart__collaterals,
                .woocommerce-cart .awa-cart__collaterals { padding-right: 0 !important; }
            }
        </style>
        <header class="awa-cart__header">
            <span class="awa-label"><?php esc_html_e( 'Panier', 'awa-child' ); ?></span>
            <h1 class="awa-cart__title"><?php esc_html_e( 'Votre sélection', 'awa-child' ); ?></h1>
        </header>

        <?php if ( WC()->cart->is_empty() ) : ?>
            <div class="awa-cart__empty">
                <div class="awa-cart__empty-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <p><?php esc_html_e( 'Votre panier est vide.', 'awa-child' ); ?></p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="awa-btn awa-btn--primary">
                    <?php esc_html_e( 'Découvrir nos jus', 'awa-child' ); ?>
                </a>
            </div>
        <?php else : ?>
            <div class="awa-cart__layout">
                <div class="awa-cart__main">
                    <form class="woocommerce-cart-form awa-cart__form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                        <?php do_action( 'woocommerce_before_cart_table' ); ?>

                        <ul class="awa-cart__list shop_table shop_table_responsive woocommerce-cart-form__contents">
                            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                            <?php
                            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                $visible    = apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key );

                                if ( ! $_product instanceof WC_Product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! $visible ) {
                                    continue;
                                }

                                $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                                $item_price        = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                $item_subtotal     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                $palette           = awa_get_product_palette( $_product->get_slug() );

                                if ( $_product->is_sold_individually() ) {
                                    $min_quantity = 1;
                                    $max_quantity = 1;
                                } else {
                                    $min_quantity = 0;
                                    $max_quantity = $_product->get_max_purchase_quantity();
                                }

                                $product_quantity = woocommerce_quantity_input(
                                    array(
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $max_quantity,
                                        'min_value'    => $min_quantity,
                                        'product_name' => $product_name,
                                        'classes'      => array( 'awa-pdetail__qty-input', 'qty' ),
                                    ),
                                    $_product,
                                    false
                                );
                                ?>
                                <li class="awa-cart__item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" style="--awa-item-accent:<?php echo esc_attr( $palette['accent'] ); ?>;">
                                    <div class="awa-cart__item-media product-thumbnail">
                                        <?php
                                        if ( $product_permalink ) {
                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        } else {
                                            echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        }
                                        ?>
                                    </div>

                                    <div class="awa-cart__item-info product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                        <h2 class="awa-cart__item-name">
                                            <?php
                                            if ( $product_permalink ) {
                                                echo wp_kses_post(
                                                    apply_filters(
                                                        'woocommerce_cart_item_name',
                                                        sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ),
                                                        $cart_item,
                                                        $cart_item_key
                                                    )
                                                );
                                            } else {
                                                echo wp_kses_post( $product_name . '&nbsp;' );
                                            }
                                            ?>
                                        </h2>

                                        <?php
                                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                                        echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                            echo wp_kses_post(
                                                apply_filters(
                                                    'woocommerce_cart_item_backorder_notification',
                                                    '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>',
                                                    $product_id
                                                )
                                            );
                                        }
                                        ?>

                                        <div class="awa-cart__item-unit-price product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                            <?php echo $item_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </div>
                                    </div>

                                    <div class="awa-cart__item-actions">
                                        <div class="awa-pdetail__qty product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                            <button type="button" class="awa-pdetail__qty-btn" data-action="minus" aria-label="<?php esc_attr_e( 'Diminuer', 'awa-child' ); ?>">−</button>
                                            <?php echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            <button type="button" class="awa-pdetail__qty-btn" data-action="plus" aria-label="<?php esc_attr_e( 'Augmenter', 'awa-child' ); ?>">+</button>
                                        </div>

                                        <div class="awa-cart__item-subtotal product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                            <?php echo $item_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </div>

                                        <div class="awa-cart__item-remove product-remove">
                                            <?php
                                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a role="button" href="%s" class="remove awa-cart__remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    esc_attr(
                                                        sprintf(
                                                            /* translators: %s: product name */
                                                            __( 'Remove %s from cart', 'woocommerce' ),
                                                            wp_strip_all_tags( $product_name )
                                                        )
                                                    ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() ),
                                                    esc_html__( 'Supprimer', 'awa-child' )
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                            ?>

                            <?php do_action( 'woocommerce_cart_contents' ); ?>
                        </ul>

                        <div class="awa-cart__actions actions">
                            <?php if ( wc_coupons_enabled() ) : ?>
                                <div class="coupon awa-cart__coupon">
                                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
                                    <input
                                        type="text"
                                        name="coupon_code"
                                        class="input-text awa-cart__coupon-input"
                                        id="coupon_code"
                                        value=""
                                        placeholder="<?php esc_attr_e( 'Code promo', 'awa-child' ); ?>"
                                    >
                                    <button
                                        type="submit"
                                        class="button awa-btn awa-btn--light awa-cart__coupon-btn<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                                        name="apply_coupon"
                                        value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"
                                    >
                                        <?php esc_html_e( 'Appliquer', 'awa-child' ); ?>
                                    </button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            <?php endif; ?>

                            <?php do_action( 'woocommerce_cart_actions' ); ?>
                            <input type="hidden" name="update_cart" value="1">
                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_after_cart_table' ); ?>
                    </form>
                </div>

                <aside class="awa-cart__sidebar">
                    <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

                    <div class="cart-collaterals awa-cart__collaterals">
                        <h2 class="awa-cart__totals-title"><?php esc_html_e( 'Récapitulatif', 'awa-child' ); ?></h2>
                        <?php do_action( 'woocommerce_cart_collaterals' ); ?>

                        <div class="awa-cart__trust">
                            <div class="awa-cart__trust-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span><?php esc_html_e( 'Paiement sécurisé', 'awa-child' ); ?></span>
                            </div>
                            <div class="awa-cart__trust-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                                <span><?php esc_html_e( 'Livraison 1h à Dakar', 'awa-child' ); ?></span>
                            </div>
                            <div class="awa-cart__trust-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                <span><?php esc_html_e( 'Sans additifs', 'awa-child' ); ?></span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    document.querySelectorAll('.awa-pdetail__qty').forEach(function (wrap) {
        var input = wrap.querySelector('.awa-pdetail__qty-input, input.qty');
        if (!input) return;
        wrap.querySelectorAll('.awa-pdetail__qty-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var min = parseInt(input.min, 10) || 0;
                var max = parseInt(input.max, 10) || 9999;
                var val = parseInt(input.value, 10) || 0;
                if (btn.getAttribute('data-action') === 'minus') val = Math.max(min, val - 1);
                else val = Math.min(max, val + 1);
                input.value = val;
                input.dispatchEvent(new Event('change', { bubbles: true }));
                if (window.jQuery) jQuery(input).trigger('change');
            });
        });
    });
})();
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>
