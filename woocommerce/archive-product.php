<?php
/**
 * Archive produits — Catalogue AwA Bio Foods
 * Design basé sur le prototype Pure Juice Hub/catalogue.tsx
 *
 * @package awa-child
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$awa_categories = awa_get_filter_categories();
$shop_url       = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$total_products = (int) wp_count_posts( 'product' )->publish;
$visible_count  = $GLOBALS['wp_query']->found_posts;
$is_all_active  = is_shop() && ! is_product_category();
?>

<main class="awa-catalog" id="awa-catalog">

    <!-- ═══════════════════════════════════════════════════════
         HEADER (cream bg, search, category tabs)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-catalog__header">
        <div class="awa-container">
            <span class="awa-label">Catalogue</span>
            <h1 class="awa-catalog__title">
                Nos produits,<br>
                <em>à portée de soif.</em>
            </h1>
            <p class="awa-catalog__intro">
                <?php echo esc_html( $total_products ); ?> jus naturels. Trouvez celui qui vous correspond.
            </p>

            <!-- Search bar -->
            <div class="awa-catalog__search" id="awa-catalog-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="awa-catalog-q" placeholder="Rechercher un jus, une saveur…" autocomplete="off">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H14"/><path d="M10 4H3"/><path d="M21 12H12"/><path d="M8 12H3"/><path d="M21 20H16"/><path d="M12 20H3"/><circle cx="12" cy="4" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="14" cy="20" r="2"/></svg>
            </div>

            <!-- Category tabs -->
            <nav class="awa-catalog__cats" aria-label="Filtrer par catégorie">
                <a
                    href="<?php echo esc_url( $shop_url ); ?>"
                    class="awa-catalog__cat-tab<?php echo $is_all_active ? ' is-active' : ''; ?>"
                >
                    Tous <span class="awa-catalog__tab-count"><?php echo esc_html( $total_products ); ?></span>
                </a>
                <?php foreach ( $awa_categories as $term ) :
                    $url    = get_term_link( $term );
                    $count  = (int) $term->count;
                    $active = is_product_category() && get_queried_object_id() === (int) $term->term_id;
                    ?>
                    <a
                        href="<?php echo esc_url( $url ); ?>"
                        class="awa-catalog__cat-tab<?php echo $active ? ' is-active' : ''; ?>"
                    >
                        <?php echo esc_html( $term->name ); ?> <span class="awa-catalog__tab-count"><?php echo esc_html( $count ); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         PRODUCT GRID
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-catalog__body">
        <div class="awa-container">
            <div class="awa-catalog__meta">
                <span><?php printf( esc_html( _n( '%d produit', '%d produits', $visible_count, 'awa-child' ) ), $visible_count ); ?></span>
                <span>Trié par popularité</span>
            </div>

            <?php if ( woocommerce_product_loop() ) : ?>

                <div class="awa-catalog__grid" id="awa-catalog-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        global $product;

                        if ( ! $product instanceof WC_Product ) {
                            $product = wc_get_product( get_the_ID() );
                        }
                        if ( ! $product ) {
                            continue;
                        }

                        $slug    = $product->get_slug();
                        $palette = awa_get_product_palette( $slug );
                        $defaults = awa_get_product_content_defaults( $slug );
                        $thumb   = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' );
                        $img     = $thumb ? $thumb : awa_lovable_product_image( $slug );

                        $cat_name = '';
                        $cats     = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'number' => 1 ) );
                        if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                            $cat_name = $cats[0]->name;
                        }

                        $format  = awa_get_product_format( $product );
                        $tagline = $defaults['tagline'] ? $defaults['tagline'] : wp_strip_all_tags( $product->get_short_description() );

                        $price_raw = $product->get_price();
                        $price_f   = ( '' !== $price_raw && null !== $price_raw ) ? number_format( (float) $price_raw, 0, ',', ' ' ) . ' F' : '';
                        ?>
                        <a
                            href="<?php the_permalink(); ?>"
                            class="awa-catalog-card"
                            style="background-color: <?php echo esc_attr( $palette['tint'] ); ?>;"
                            data-name="<?php echo esc_attr( mb_strtolower( get_the_title() . ' ' . $tagline . ' ' . $cat_name ) ); ?>"
                        >
                            <div class="awa-catalog-card__img">
                                <?php if ( $img ) : ?>
                                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                <?php else : ?>
                                    <div class="awa-catalog-card__no-img"><?php the_title(); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="awa-catalog-card__body">
                                <h2 class="awa-catalog-card__name" style="color: <?php echo esc_attr( $palette['accent'] ); ?>;"><?php the_title(); ?></h2>
                                <?php if ( $cat_name || $format ) : ?>
                                    <p class="awa-catalog-card__meta">
                                        <?php if ( $cat_name ) : ?>
                                            <span class="awa-catalog-card__cat"><?php echo esc_html( $cat_name ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $cat_name && $format ) : ?>
                                            <span class="awa-catalog-card__sep"> · </span>
                                        <?php endif; ?>
                                        <?php if ( $format ) : ?>
                                            <span class="awa-catalog-card__format"><?php echo esc_html( $format ); ?></span>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                <p class="awa-catalog-card__tagline"><?php echo esc_html( $tagline ); ?></p>
                                <?php if ( $price_f ) : ?>
                                    <div class="awa-catalog-card__pricing">
                                        <span class="awa-catalog-card__price" style="color: <?php echo esc_attr( $palette['accent'] ); ?>;"><?php echo esc_html( $price_f ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>

                <nav class="awa-catalog__pagination" aria-label="Pagination des produits">
                    <?php woocommerce_pagination(); ?>
                </nav>

            <?php else : ?>

                <p class="awa-catalog__empty">
                    Aucun jus ne correspond à votre recherche.
                </p>

            <?php endif; ?>
        </div>
    </section>

</main>

<script>
(function () {
    var input = document.getElementById('awa-catalog-q');
    var grid  = document.getElementById('awa-catalog-grid');
    if (!input || !grid) return;
    var cards = grid.querySelectorAll('.awa-catalog-card');

    input.addEventListener('input', function () {
        var q = input.value.toLowerCase().trim();
        var visibleCount = 0;
        cards.forEach(function (card) {
            var name = card.getAttribute('data-name') || '';
            var show = !q || name.indexOf(q) !== -1;
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
    });
})();
</script>

<?php
get_footer();
