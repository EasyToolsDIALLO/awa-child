<?php
/**
 * Header — reproduction Lovable SiteHeader
 *
 * @package awa-child
 */

$awa_shop_url = function_exists( 'wc_get_page_permalink' )
    ? wc_get_page_permalink( 'shop' )
    : home_url( '/#produits' );

$awa_cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' );
$awa_home     = home_url( '/' );
$awa_logo_url = awa_lovable_asset_url( '1ac4f2f0-5bc5-49cc-8e92-a516c0212208', 'logo.png' );
$awa_custom_logo_id  = get_theme_mod( 'custom_logo' );
$awa_custom_logo_img = $awa_custom_logo_id ? wp_get_attachment_image( $awa_custom_logo_id, 'full', false, array( 'class' => 'awa-header__logo-img' ) ) : '';

$awa_cart_count = 0;
if ( function_exists( 'WC' ) && WC()->cart ) {
    $awa_cart_count = WC()->cart->get_cart_contents_count();
}

/* URL page Mon Compte et état de connexion */
$awa_account_url  = function_exists( 'wc_get_page_permalink' )
    ? wc_get_page_permalink( 'myaccount' )
    : home_url( '/mon-compte/' );
$awa_is_logged_in = is_user_logged_in();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'awa-body' ); ?>>

<?php wp_body_open(); ?>

<div id="page" class="hfeed site">

    <header class="awa-header" id="awa-header" role="banner">
        <div class="awa-container awa-header__inner">
            <a class="awa-header__logo" href="<?php echo esc_url( $awa_home ); ?>">
                <?php if ( $awa_custom_logo_img ) : ?>
                    <?php echo $awa_custom_logo_img; ?>
                <?php else : ?>
                    <img src="<?php echo esc_url( $awa_logo_url ); ?>" alt="AwA Bio Foods" class="awa-header__logo-img" width="32" height="32">
                    <span class="awa-header__logo-text">AwA Bio</span>
                <?php endif; ?>
            </a>

            <nav class="awa-header__nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'awa-child' ); ?>">
                <a href="<?php echo esc_url( $awa_shop_url ); ?>">Catalogue</a>
                <a href="<?php echo esc_url( $awa_home . '#gammes' ); ?>">Nos gammes</a>
                <a href="<?php echo esc_url( $awa_home . '#bienfaits' ); ?>">Bienfaits</a>
                <a href="<?php echo esc_url( $awa_home . '#temoignages' ); ?>">Témoignages</a>
                <a href="<?php echo esc_url( $awa_home . '#histoire' ); ?>">Notre histoire</a>
            </nav>

            <!-- Icônes compte + panier regroupées -->
            <div class="awa-header__actions">

                <?php if ( $awa_is_logged_in ) : ?>
                    <!-- Connecté : lien direct vers Mon Compte -->
                    <a href="<?php echo esc_url( $awa_account_url ); ?>" class="awa-header__account" aria-label="<?php esc_attr_e( 'Mon compte', 'awa-child' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                <?php else : ?>
                    <!-- Non connecté : href = fallback no-JS, JS intercepte le clic pour ouvrir la modal -->
                    <a href="<?php echo esc_url( $awa_account_url ); ?>" class="awa-header__account" id="awa-account-btn" aria-label="<?php esc_attr_e( 'Se connecter', 'awa-child' ); ?>" aria-haspopup="dialog">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url( $awa_cart_url ); ?>" class="awa-header__cart" aria-label="<?php esc_attr_e( 'Panier', 'awa-child' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <?php if ( $awa_cart_count > 0 ) : ?>
                        <span class="awa-header__cart-count"><?php echo esc_html( $awa_cart_count ); ?></span>
                    <?php endif; ?>
                </a>

            </div><!-- /.awa-header__actions -->
        </div>
    </header>

    <nav class="awa-tabbar" aria-label="<?php esc_attr_e( 'Navigation mobile', 'awa-child' ); ?>">
        <a href="<?php echo esc_url( $awa_home ); ?>" class="awa-tabbar__item<?php echo is_front_page() ? ' is-active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Accueil</span>
        </a>
        <a href="<?php echo esc_url( $awa_shop_url ); ?>" class="awa-tabbar__item<?php echo ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product() ) ) ? ' is-active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
            <span>Catalogue</span>
        </a>
        <a href="<?php echo esc_url( $awa_home . '#bienfaits' ); ?>" class="awa-tabbar__item">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
            <span>Bienfaits</span>
        </a>
        <a href="<?php echo esc_url( $awa_home . '#temoignages' ); ?>" class="awa-tabbar__item">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
            <span>Avis</span>
        </a>
        <a href="<?php echo esc_url( $awa_home . '#contact' ); ?>" class="awa-tabbar__item">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span>Contact</span>
        </a>
    </nav>

    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">

<script>
(function () {
    var header = document.getElementById('awa-header');
    if (!header) return;
    function onScroll() {
        header.classList.toggle('is-scrolled', window.scrollY > 8);
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>
