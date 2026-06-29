<?php
/**
 * Mon Compte — Tableau de bord AwA Bio Foods
 * Override de woocommerce/myaccount/dashboard.php
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package awa-child
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$order_count  = wc_get_customer_order_count( get_current_user_id() );
$orders_url   = wc_get_endpoint_url( 'orders' );
$address_url  = wc_get_endpoint_url( 'edit-address' );
$account_url  = wc_get_endpoint_url( 'edit-account' );
$shop_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$logout_url   = wc_logout_url();
?>

<!-- Message de bienvenue -->
<div class="awa-dashboard__welcome">
    <p class="awa-dashboard__welcome-text">
        <?php
        printf(
            wp_kses(
                /* translators: %s : lien de déconnexion */
                __( 'Pas vous ? <a href="%s">Se déconnecter</a>', 'awa-child' ),
                array( 'a' => array( 'href' => array() ) )
            ),
            esc_url( $logout_url )
        );
        ?>
    </p>
</div>

<!-- Cartes d'accès rapide -->
<div class="awa-dashboard__cards">

    <a href="<?php echo esc_url( $orders_url ); ?>" class="awa-dashboard__card">
        <div class="awa-dashboard__card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <div class="awa-dashboard__card-body">
            <strong><?php echo esc_html( $order_count ); ?></strong>
            <span><?php echo 1 === (int) $order_count ? 'commande' : 'commandes'; ?></span>
        </div>
        <svg class="awa-dashboard__card-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>

    <a href="<?php echo esc_url( $address_url ); ?>" class="awa-dashboard__card">
        <div class="awa-dashboard__card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="awa-dashboard__card-body">
            <strong>Adresses</strong>
            <span>Livraison & facturation</span>
        </div>
        <svg class="awa-dashboard__card-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>

    <a href="<?php echo esc_url( $account_url ); ?>" class="awa-dashboard__card">
        <div class="awa-dashboard__card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="awa-dashboard__card-body">
            <strong>Mon profil</strong>
            <span>Mot de passe & e-mail</span>
        </div>
        <svg class="awa-dashboard__card-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>

    <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-dashboard__card awa-dashboard__card--cta">
        <div class="awa-dashboard__card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
        </div>
        <div class="awa-dashboard__card-body">
            <strong>Commander</strong>
            <span>Retour à la boutique</span>
        </div>
        <svg class="awa-dashboard__card-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>

</div>

<?php
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
