<?php
/**
 * Checkout — AwA Bio Foods
 *
 * @package awa-child
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_filter(
    'woocommerce_checkout_fields',
    function ( $fields ) {
        $hidden = array( 'billing_country', 'billing_state', 'billing_company', 'billing_address_2' );

        foreach ( $hidden as $key ) {
            if ( ! isset( $fields['billing'][ $key ] ) ) {
                continue;
            }
            $fields['billing'][ $key ]['required'] = false;
            $fields['billing'][ $key ]['class']    = array( 'form-row-wide', 'awa-checkout__field--hidden' );
        }

        if ( isset( $fields['billing']['billing_country'] ) ) {
            $fields['billing']['billing_country']['default'] = 'SN';
        }

        if ( isset( $fields['billing']['billing_first_name'] ) ) {
            $fields['billing']['billing_first_name']['label']    = __( 'Prénom', 'awa-child' );
            $fields['billing']['billing_first_name']['priority'] = 10;
            $fields['billing']['billing_first_name']['class']    = array( 'form-row-first' );
        }

        if ( isset( $fields['billing']['billing_last_name'] ) ) {
            $fields['billing']['billing_last_name']['label']    = __( 'Nom', 'awa-child' );
            $fields['billing']['billing_last_name']['priority'] = 20;
            $fields['billing']['billing_last_name']['class']    = array( 'form-row-last' );
        }

        if ( isset( $fields['billing']['billing_email'] ) ) {
            $fields['billing']['billing_email']['label']       = __( 'E-mail', 'awa-child' );
            $fields['billing']['billing_email']['priority']    = 25;
            $fields['billing']['billing_email']['required']    = true;
            $fields['billing']['billing_email']['class']       = array( 'form-row-wide' );
            $fields['billing']['billing_email']['placeholder'] = 'votre@email.com';
        }

        if ( isset( $fields['billing']['billing_phone'] ) ) {
            $fields['billing']['billing_phone']['label']       = __( 'Téléphone', 'awa-child' );
            $fields['billing']['billing_phone']['priority']    = 30;
            $fields['billing']['billing_phone']['required']    = true;
            $fields['billing']['billing_phone']['class']       = array( 'form-row-wide' );
            $fields['billing']['billing_phone']['placeholder'] = '+221 77 000 00 00';
        }

        if ( isset( $fields['billing']['billing_address_1'] ) ) {
            $fields['billing']['billing_address_1']['label']       = __( 'Adresse', 'awa-child' );
            $fields['billing']['billing_address_1']['priority']    = 40;
            $fields['billing']['billing_address_1']['class']       = array( 'form-row-wide' );
            $fields['billing']['billing_address_1']['placeholder'] = __( 'Rue, quartier, point de repère…', 'awa-child' );
        }

        if ( isset( $fields['billing']['billing_postcode'] ) ) {
            $fields['billing']['billing_postcode']['label']       = __( 'Code postal', 'awa-child' );
            $fields['billing']['billing_postcode']['priority']    = 45;
            $fields['billing']['billing_postcode']['required']    = true;
            $fields['billing']['billing_postcode']['class']       = array( 'form-row-wide' );
            $fields['billing']['billing_postcode']['placeholder'] = '10000';
        }

        if ( isset( $fields['billing']['billing_city'] ) ) {
            $fields['billing']['billing_city']['label']       = __( 'Ville', 'awa-child' );
            $fields['billing']['billing_city']['priority']    = 50;
            $fields['billing']['billing_city']['class']       = array( 'form-row-wide' );
            $fields['billing']['billing_city']['placeholder'] = 'Dakar';
        }

        if ( isset( $fields['order']['order_comments'] ) ) {
            $fields['order']['order_comments']['label']       = __( 'Note de commande', 'awa-child' );
            $fields['order']['order_comments']['placeholder'] = __( 'Instructions de livraison, préférences…', 'awa-child' );
            $fields['order']['order_comments']['type']        = 'textarea';
            $fields['order']['order_comments']['class']       = array( 'form-row-wide' );
        }

        unset( $fields['shipping'] );

        return $fields;
    },
    9999
);

add_filter(
    'woocommerce_checkout_posted_data',
    function ( $data ) {
        if ( empty( $data['billing_country'] ) ) {
            $data['billing_country'] = 'SN';
        }

        return $data;
    }
);

add_filter(
    'woocommerce_order_button_text',
    function () {
        return __( 'Passer la commande', 'awa-child' );
    }
);

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<form
    name="checkout"
    method="post"
    class="checkout woocommerce-checkout awa-checkout__form"
    action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
    enctype="multipart/form-data"
    aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>"
>

    <div class="awa-checkout">
        <div class="awa-container">
            <style>
                .woocommerce-checkout .button,
                .woocommerce-checkout a.button,
                .woocommerce-checkout input[type=submit] {
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
                .woocommerce-checkout #place_order {
                    width: 100% !important;
                    padding: 0.875rem !important;
                    font-size: 0.875rem !important;
                }
                .woocommerce-checkout .awa-container {
                    padding-left: 1rem !important;
                    padding-right: 1rem !important;
                }
            </style>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="awa-pdetail__back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                <?php esc_html_e( 'Retour au panier', 'awa-child' ); ?>
            </a>
            <header class="awa-checkout__header">
                <span class="awa-label"><?php esc_html_e( 'Commande', 'awa-child' ); ?></span>
                <h1 class="awa-checkout__title"><?php esc_html_e( 'Finaliser votre commande', 'awa-child' ); ?></h1>
            </header>

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <div class="awa-checkout__layout">
                    <div class="awa-checkout__customer" id="customer_details">
                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                        <div class="awa-checkout__card">
                            <h2 class="awa-checkout__section-title"><?php esc_html_e( 'Vos informations', 'awa-child' ); ?></h2>

                            <div class="awa-checkout__billing">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>

                            <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                                <div class="awa-checkout__notes woocommerce-additional-fields">
                                    <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
                                    <div class="woocommerce-additional-fields__field-wrapper">
                                        <?php
                                        foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) {
                                            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                        }
                                        ?>
                                    </div>
                                    <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                    </div>

                    <aside class="awa-checkout__sidebar">
                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

                        <div class="awa-checkout__card awa-checkout__review">
                            <h2 class="awa-checkout__section-title awa-checkout__section-title--review" id="order_review_heading">
                                <?php esc_html_e( 'Récapitulatif', 'awa-child' ); ?>
                            </h2>

                            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        </div>

                        <div class="awa-cart__trust awa-checkout__trust">
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
                    </aside>
                </div>

            <?php endif; ?>
        </div>
    </div>

</form>

<?php
do_action( 'woocommerce_after_checkout_form', $checkout );
