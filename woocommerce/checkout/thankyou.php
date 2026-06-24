<?php
/**
 * Order received — AwA Bio Foods
 *
 * @package awa-child
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
?>

<style>
.awa-thankyou__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.5rem; }
.awa-thankyou__list li { display: flex; align-items: baseline; gap: 0.75rem; font-size: 0.875rem; }
.awa-thankyou__list li strong { flex-shrink: 0; min-width: 80px; color: var(--awa-muted-fg, #6b8c78); font-weight: 500; }
.awa-thankyou__list li span {
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
}
.awa-thankyou__list li:first-child span,
.awa-thankyou__list .order-number span,
.awa-thankyou__list .email span {
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
<div class="awa-thankyou">
    <div class="awa-container">
        <header class="awa-thankyou__header">
            <span class="awa-label"><?php esc_html_e( 'Confirmation', 'awa-child' ); ?></span>
            <h1 class="awa-thankyou__title"><?php esc_html_e( 'Merci pour votre commande !', 'awa-child' ); ?></h1>
            <?php if ( $order ) : ?>
                <p class="awa-thankyou__lead">
                    <?php
                    printf(
                        /* translators: %s: order number */
                        esc_html__( 'Votre commande n°%s a bien été enregistrée.', 'awa-child' ),
                        esc_html( $order->get_order_number() )
                    );
                    ?>
                </p>
            <?php else : ?>
                <p class="awa-thankyou__lead"><?php esc_html_e( 'Votre commande a bien été enregistrée.', 'awa-child' ); ?></p>
            <?php endif; ?>
        </header>

        <div class="woocommerce-order awa-thankyou__card">
            <?php
            if ( $order ) :
                do_action( 'woocommerce_before_thankyou', $order->get_id() );

                if ( $order->has_status( 'failed' ) ) :
                    ?>
                    <div class="awa-thankyou__section">
                        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                            <?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?>
                        </p>
                        <p class="woocommerce-thankyou-order-failed-actions">
                            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay awa-btn awa-btn--primary">
                                <?php esc_html_e( 'Pay', 'woocommerce' ); ?>
                            </a>
                            <?php if ( is_user_logged_in() ) : ?>
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay awa-btn awa-btn--light">
                                    <?php esc_html_e( 'My account', 'woocommerce' ); ?>
                                </a>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php
                else :
                    ?>
                    <div class="awa-thankyou__section">
                        <h2 class="awa-thankyou__section-title"><?php esc_html_e( 'Récapitulatif', 'awa-child' ); ?></h2>
                        <ul class="awa-thankyou__list woocommerce-thankyou-order-details">
                            <li>
                                <strong><?php esc_html_e( 'Numéro de commande', 'awa-child' ); ?></strong>
                                <span><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            </li>
                            <li>
                                <strong><?php esc_html_e( 'Date', 'awa-child' ); ?></strong>
                                <span><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            </li>
                            <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                                <li>
                                    <strong><?php esc_html_e( 'Email', 'awa-child' ); ?></strong>
                                    <span><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                </li>
                            <?php endif; ?>
                            <li>
                                <strong><?php esc_html_e( 'Total', 'awa-child' ); ?></strong>
                                <span><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            </li>
                            <?php if ( $order->get_payment_method_title() ) : ?>
                                <li>
                                    <strong><?php esc_html_e( 'Mode de paiement', 'awa-child' ); ?></strong>
                                    <span><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                    <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

                    <?php if ( $order->get_status() !== 'failed' ) : ?>
                        <div class="awa-thankyou__section">
                            <h2 class="awa-thankyou__section-title"><?php esc_html_e( 'Détails de la commande', 'awa-child' ); ?></h2>
                            <table class="awa-thankyou__table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Produit', 'awa-child' ); ?></th>
                                        <th><?php esc_html_e( 'Total', 'awa-child' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $order->get_items() as $item ) : ?>
                                        <tr>
                                            <td>
                                                <?php
                                                echo esc_html( $item->get_name() );
                                                $quantity = $item->get_quantity();
                                                if ( $quantity > 1 ) {
                                                    echo ' <span class="product-quantity">x' . esc_html( $quantity ) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                                        <tr>
                                            <th><?php echo esc_html( $total['label'] ); ?></th>
                                            <td><?php echo $total['value']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tfoot>
                            </table>
                        </div>

                        <?php if ( $order->get_billing_phone() || $order->get_billing_address_1() ) : ?>
                            <div class="awa-thankyou__section">
                                <h2 class="awa-thankyou__section-title"><?php esc_html_e( 'Livraison / Contact', 'awa-child' ); ?></h2>
                                <ul class="awa-thankyou__list">
                                    <?php if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) : ?>
                                        <li>
                                            <strong><?php esc_html_e( 'Nom', 'awa-child' ); ?></strong>
                                            <span><?php echo esc_html( trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ) ); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ( $order->get_billing_phone() ) : ?>
                                        <li>
                                            <strong><?php esc_html_e( 'Téléphone', 'awa-child' ); ?></strong>
                                            <span><?php echo esc_html( $order->get_billing_phone() ); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ( $order->get_billing_address_1() ) : ?>
                                        <li>
                                            <strong><?php esc_html_e( 'Adresse', 'awa-child' ); ?></strong>
                                            <span>
                                                <?php
                                                echo esc_html( $order->get_billing_address_1() );
                                                if ( $order->get_billing_city() ) {
                                                    echo ', ' . esc_html( $order->get_billing_city() );
                                                }
                                                ?>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php
                endif;
            else :
                ?>
                <div class="awa-thankyou__section">
                    <?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
                </div>
                <?php
            endif;
            ?>

            <div class="awa-thankyou__actions">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="awa-btn awa-btn--primary">
                    <?php esc_html_e( 'Continuer mes achats', 'awa-child' ); ?>
                </a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="awa-btn awa-btn--light">
                        <?php esc_html_e( 'Mon compte', 'awa-child' ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
