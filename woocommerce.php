<?php
/**
 * WooCommerce pages outer template — AwA Child
 *
 * Bypass Storefront UNIQUEMENT pour panier + commande + confirmation.
 * Pour tous les autres pages WooCommerce (boutique, produit, etc.),
 * on délègue à Storefront/page.php qui gère les hooks correctement.
 *
 * @package awa-child
 */

defined( 'ABSPATH' ) || exit;

if ( is_cart() || is_checkout() || is_account_page() ) {
	/* ── Panier / commande / confirmation ──────────────────────────────
	 * Rendu awa-child direct, sans wrappers Storefront (#primary, sidebar…)
	 * Le filtre the_content dans functions.php injecte [woocommerce_cart]
	 * / [woocommerce_checkout] qui active nos templates override.
	 */
	get_header();

	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;

	get_footer();

} else {
	/* ── Toutes les autres pages WooCommerce ────────────────────────────
	 * Reconstruit la hiérarchie naturelle WordPress/Storefront :
	 *   - fiche produit   → single.php (Storefront)
	 *   - boutique/shop   → page.php   (Storefront)
	 *   - catégorie/tag   → archive.php (Storefront)
	 * Cela préserve exactement le comportement d'avant sans woocommerce.php.
	 */
	if ( is_singular( 'product' ) ) {
		/* Fiche produit → awa-child/woocommerce/single-product.php (template complet) */
		locate_template( array( 'woocommerce/single-product.php', 'single.php', 'index.php' ), true );

	} elseif ( is_shop() || is_product_category() || is_product_tag() || is_tax( get_object_taxonomies( 'product' ) ) ) {
		/* Catalogue / catégorie → awa-child/woocommerce/archive-product.php (template complet) */
		locate_template( array( 'woocommerce/archive-product.php', 'archive.php', 'index.php' ), true );

	} else {
		locate_template( array( 'page.php', 'archive.php', 'index.php' ), true );
	}
}
