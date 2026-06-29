<?php
/**
 * Fiche produit — reproduction Lovable produit.$slug
 *
 * @package awa-child
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

while ( have_posts() ) :
    the_post();

    global $product;

    if ( ! $product instanceof WC_Product ) {
        $product = wc_get_product( get_the_ID() );
    }

    if ( ! $product ) {
        continue;
    }

    $product_id = $product->get_id();
    $slug       = $product->get_slug();
    $palette    = awa_get_product_palette( $slug );
    $accent     = $palette['accent'];
    $tint       = $palette['tint'];
    $defaults   = awa_get_product_content_defaults( $slug );

    $benefits = array();
    $benefits_meta = get_post_meta( $product_id, '_awa_benefits', true );
    if ( is_array( $benefits_meta ) ) {
        $benefits = array_filter( array_map( 'trim', $benefits_meta ) );
    } elseif ( is_string( $benefits_meta ) && '' !== $benefits_meta ) {
        $benefits = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $benefits_meta ) ) );
    } else {
        $benefits_attr = $product->get_attribute( 'benefices' );
        if ( $benefits_attr ) {
            $benefits = array_filter( array_map( 'trim', preg_split( '/\||,/', $benefits_attr ) ) );
        }
    }
    if ( empty( $benefits ) ) {
        $benefits = $defaults['benefits'];
    }

    $ingredients = get_post_meta( $product_id, '_awa_ingredients', true );
    if ( ! is_string( $ingredients ) || '' === $ingredients ) {
        $ingredients = $product->get_attribute( 'ingredients' );
    }
    if ( ! $ingredients ) {
        $ingredients = $defaults['ingredients'];
    }

    $tagline = $product->get_short_description();
    if ( ! $tagline ) {
        $tagline = $defaults['tagline'];
    } else {
        $tagline = wp_strip_all_tags( $tagline );
    }

    $description = wp_strip_all_tags( $product->get_description() );
    if ( ! $description ) {
        $description = $defaults['description'];
    }

    $image_url = get_the_post_thumbnail_url( $product_id, 'large' );
    if ( ! $image_url ) {
        $image_url = awa_lovable_product_image( $slug );
    }

    $related_ids = wc_get_related_products( $product_id, 3 );
    if ( empty( $related_ids ) ) {
        $fallback = new WP_Query(
            array(
                'post_type'      => 'product',
                'posts_per_page' => 3,
                'post__not_in'   => array( $product_id ),
                'orderby'        => 'rand',
                'post_status'    => 'publish',
            )
        );
        $related_ids = wp_list_pluck( $fallback->posts, 'ID' );
        wp_reset_postdata();
    }

    $can_purchase = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock();
    $form_action  = esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) );
    ?>

    <main class="awa-pdetail" id="awa-pdetail-<?php echo esc_attr( $product_id ); ?>" style="--awa-pdetail-accent: <?php echo esc_attr( $accent ); ?>; --awa-pdetail-tint: <?php echo esc_attr( $tint ); ?>;">

        <div class="awa-container awa-pdetail__back">
            <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-pdetail__back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                <?php esc_html_e( 'Tous les jus', 'awa-child' ); ?>
            </a>
        </div>


        <section class="awa-container awa-pdetail__main">
            <div
                class="awa-pdetail__visual"
                style="--awa-pdetail-accent: <?php echo esc_attr( $accent ); ?>; --awa-pdetail-tint: <?php echo esc_attr( $tint ); ?>;"
            >
                <div class="awa-pdetail__visual-glow" aria-hidden="true"></div>
                <span class="awa-pdetail__dot awa-pdetail__dot--1" aria-hidden="true"></span>
                <span class="awa-pdetail__dot awa-pdetail__dot--2" aria-hidden="true"></span>
                <span class="awa-pdetail__dot awa-pdetail__dot--3" aria-hidden="true"></span>
                <?php if ( $image_url ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>" class="awa-pdetail__image">
                <?php else : ?>
                    <div class="awa-pdetail__no-image"><?php the_title(); ?></div>
                <?php endif; ?>
            </div>

            <div class="awa-pdetail__info">
                <span class="awa-pdetail__badge" style="color: <?php echo esc_attr( $accent ); ?>;">
                    <?php esc_html_e( 'Jus naturel', 'awa-child' ); ?>
                </span>
                <h1 class="awa-pdetail__title"><?php the_title(); ?></h1>

                <?php if ( $tagline ) : ?>
                    <p class="awa-pdetail__tagline"><?php echo esc_html( $tagline ); ?></p>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <p class="awa-pdetail__desc"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>

                <?php if ( $can_purchase ) : ?>
                    <div class="awa-pdetail__buy awa-pdetail__buy--desktop">
                        <form class="awa-pdetail__form cart" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
                            <div class="awa-pdetail__qty">
                                <button type="button" class="awa-pdetail__qty-btn" data-action="minus" aria-label="<?php esc_attr_e( 'Diminuer', 'awa-child' ); ?>">−</button>
                                <?php
                                woocommerce_quantity_input(
                                    array(
                                        'min_value'   => $product->get_min_purchase_quantity(),
                                        'max_value'   => $product->get_max_purchase_quantity(),
                                        'input_value' => 1,
                                        'classes'     => array( 'awa-pdetail__qty-input', 'qty' ),
                                    )
                                );
                                ?>
                                <button type="button" class="awa-pdetail__qty-btn" data-action="plus" aria-label="<?php esc_attr_e( 'Augmenter', 'awa-child' ); ?>">+</button>
                            </div>
                            <button
                                type="submit"
                                name="add-to-cart"
                                value="<?php echo esc_attr( $product_id ); ?>"
                                class="awa-pdetail__add-btn single_add_to_cart_button"
                                style="background-color: <?php echo esc_attr( $accent ); ?>; box-shadow: 0 8px 24px -8px <?php echo esc_attr( $accent ); ?>;"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                <?php esc_html_e( 'Ajouter au panier', 'awa-child' ); ?>
                            </button>
                        </form>
                        <div class="woocommerce-notices-wrapper awa-pdetail__notice-inline"></div>
                    </div>
                <?php else : ?>
                    <div class="awa-pdetail__buy awa-pdetail__buy--desktop">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                        <div class="woocommerce-notices-wrapper awa-pdetail__notice-inline"></div>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $benefits ) ) : ?>
                    <div class="awa-pdetail__benefits">
                        <h2 class="awa-pdetail__section-title"><?php esc_html_e( 'Bienfaits', 'awa-child' ); ?></h2>
                        <ul class="awa-pdetail__benefits-list">
                            <?php foreach ( $benefits as $benefit ) : ?>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr( $accent ); ?>" stroke-width="2.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                    <?php echo esc_html( $benefit ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( $ingredients ) : ?>
                    <div class="awa-pdetail__ingredients">
                        <h3><?php esc_html_e( 'Ingrédients', 'awa-child' ); ?></h3>
                        <p><?php echo esc_html( $ingredients ); ?></p>
                    </div>
                <?php endif; ?>

                <div class="awa-pdetail__trust">
                    <div class="awa-pdetail__trust-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                        <?php esc_html_e( 'Livré frais', 'awa-child' ); ?>
                    </div>
                    <div class="awa-pdetail__trust-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                        <?php esc_html_e( '100% bio', 'awa-child' ); ?>
                    </div>
                    <div class="awa-pdetail__trust-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                        <?php esc_html_e( 'Pressé à froid', 'awa-child' ); ?>
                    </div>
                </div>
            </div>
        </section>

        <?php if ( ! empty( $related_ids ) ) : ?>
            <section class="awa-pdetail__related">
                <div class="awa-container">
                    <h2 class="awa-pdetail__related-title"><?php esc_html_e( 'Vous aimerez aussi', 'awa-child' ); ?></h2>
                    <div class="awa-pdetail__related-grid">
                        <?php
                        foreach ( $related_ids as $related_id ) :
                            $related      = wc_get_product( $related_id );
                            if ( ! $related ) {
                                continue;
                            }
                            $rel_slug     = $related->get_slug();
                            $rel_palette  = awa_get_product_palette( $rel_slug );
                            $rel_defaults = awa_get_product_content_defaults( $rel_slug );
                            $rel_img      = get_the_post_thumbnail_url( $related_id, 'woocommerce_thumbnail' );
                            if ( ! $rel_img ) {
                                $rel_img = awa_lovable_product_image( $rel_slug );
                            }
                            $rel_tagline = $related->get_short_description();
                            if ( ! $rel_tagline ) {
                                $rel_tagline = $rel_defaults['tagline'];
                            } else {
                                $rel_tagline = wp_strip_all_tags( $rel_tagline );
                            }
                            ?>
                            <a
                                href="<?php echo esc_url( get_permalink( $related_id ) ); ?>"
                                class="awa-pdetail__related-card"
                                style="background-color: <?php echo esc_attr( $rel_palette['tint'] ); ?>;"
                            >
                                <div class="awa-pdetail__related-img">
                                    <?php if ( $rel_img ) : ?>
                                        <img src="<?php echo esc_url( $rel_img ); ?>" alt="<?php echo esc_attr( $related->get_name() ); ?>" loading="lazy">
                                    <?php endif; ?>
                                </div>
                                <div class="awa-pdetail__related-body">
                                    <h3 style="color: <?php echo esc_attr( $rel_palette['accent'] ); ?>;"><?php echo esc_html( $related->get_name() ); ?></h3>
                                    <?php if ( $rel_tagline ) : ?>
                                        <p><?php echo esc_html( $rel_tagline ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( $can_purchase ) : ?>
            <div class="awa-pdetail__sticky-bar">
                <form class="awa-pdetail__form awa-pdetail__form--sticky cart" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
                    <div class="awa-pdetail__qty awa-pdetail__qty--compact">
                        <button type="button" class="awa-pdetail__qty-btn" data-action="minus" aria-label="<?php esc_attr_e( 'Diminuer', 'awa-child' ); ?>">−</button>
                        <input type="number" class="awa-pdetail__qty-input qty" name="quantity" value="1" min="1" max="<?php echo esc_attr( $product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : '' ); ?>" step="1">
                        <button type="button" class="awa-pdetail__qty-btn" data-action="plus" aria-label="<?php esc_attr_e( 'Augmenter', 'awa-child' ); ?>">+</button>
                    </div>
                    <button
                        type="submit"
                        name="add-to-cart"
                        value="<?php echo esc_attr( $product_id ); ?>"
                        class="awa-pdetail__add-btn awa-pdetail__add-btn--full single_add_to_cart_button"
                        style="background-color: <?php echo esc_attr( $accent ); ?>; box-shadow: 0 8px 24px -8px <?php echo esc_attr( $accent ); ?>;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <?php esc_html_e( 'Ajouter au panier', 'awa-child' ); ?>
                    </button>
                </form>
                <div class="woocommerce-notices-wrapper awa-pdetail__notice-inline"></div>
            </div>
        <?php endif; ?>

    </main>

    <script>
    (function () {
        /* ── Rediriger les notices WooCommerce vers le wrapper inline visible sous le bouton Ajouter ── */
        function getVisibleInlineWrapper() {
            var wrappers = document.querySelectorAll('.awa-pdetail__notice-inline');
            for (var i = 0; i < wrappers.length; i++) {
                if (wrappers[i].offsetParent !== null) return wrappers[i];
            }
            return wrappers.length ? wrappers[0] : null;
        }
        function moveNoticesToInline() {
            var inlineWrapper = getVisibleInlineWrapper();
            if (!inlineWrapper) return;
            var moved = false;
            document.querySelectorAll('.woocommerce-notices-wrapper').forEach(function (w) {
                if (!w.classList.contains('awa-pdetail__notice-inline') && w.innerHTML.trim() !== '') {
                    inlineWrapper.innerHTML = w.innerHTML;
                    w.innerHTML = '';
                    moved = true;
                }
            });
            if (moved && inlineWrapper.innerHTML.trim() !== '') {
                inlineWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
        /* Dès le chargement + après chaque ajout AJAX */
        moveNoticesToInline();
        if (window.jQuery) {
            jQuery(document.body).on('added_to_cart wc_fragments_refreshed', function () {
                setTimeout(moveNoticesToInline, 50);
            });
        }

        document.querySelectorAll('.awa-pdetail__qty').forEach(function (wrap) {
            var input = wrap.querySelector('.awa-pdetail__qty-input, input.qty');
            if (!input) return;
            wrap.querySelectorAll('.awa-pdetail__qty-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var min = parseInt(input.min, 10) || 1;
                    var max = parseInt(input.max, 10) || 9999;
                    var val = parseInt(input.value, 10) || 1;
                    if (btn.getAttribute('data-action') === 'minus') val = Math.max(min, val - 1);
                    else val = Math.min(max, val + 1);
                    input.value = val;
                });
            });
        });
    })();
    </script>

<?php endwhile; ?>

<?php
get_footer();
