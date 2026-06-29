<?php
/**
 * Mon Compte — AwA Bio Foods
 * Override de woocommerce/myaccount/my-account.php
 *
 * Ce template remplace la mise en page WC/Storefront par défaut.
 * Il affiche l'en-tête du compte, la navigation latérale et le contenu.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package awa-child
 */

defined( 'ABSPATH' ) || exit;

$current_user    = wp_get_current_user();
$display_name    = $current_user->display_name ?: $current_user->user_login;
$user_email      = $current_user->user_email;
$avatar_initial  = strtoupper( mb_substr( $display_name, 0, 1 ) );
$shop_url        = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>

<main class="awa-myaccount">

    <!-- ═══ En-tête du compte ═══════════════════════════════════ -->
    <div class="awa-myaccount__hero">
        <div class="awa-container awa-myaccount__hero-inner">
            <div class="awa-myaccount__avatar" aria-hidden="true"><?php echo esc_html( $avatar_initial ); ?></div>
            <div class="awa-myaccount__hero-text">
                <p class="awa-label">Mon compte</p>
                <h1 class="awa-myaccount__name">Bonjour, <?php echo esc_html( $display_name ); ?>&nbsp;👋</h1>
                <p class="awa-myaccount__email"><?php echo esc_html( $user_email ); ?></p>
            </div>
            <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-myaccount__shop-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Commander
            </a>
        </div>
    </div>

    <!-- ═══ Layout : navigation + contenu ═══════════════════════ -->
    <div class="awa-container awa-myaccount__body">

        <!-- Navigation latérale (rendue par navigation.php override) -->
        <?php do_action( 'woocommerce_account_navigation' ); ?>

        <!-- Contenu dynamique (tableau de bord, commandes, adresses…) -->
        <div class="awa-myaccount__content woocommerce-MyAccount-content">
            <?php do_action( 'woocommerce_account_content' ); ?>
        </div>

    </div>

</main>
