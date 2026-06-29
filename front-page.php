<?php
/**
 * Template: Page d'accueil — reproduction Lovable Pure Juice Hub
 *
 * @package awa-child
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/#produits' );

/* Assets Lovable + locaux */
$img_woman_juice   = awa_lovable_asset_url( '16fef4cd-7299-49e7-ab4d-0dafdffac7df', 'woman-juice.jpg' );
$img_benefit_bio   = awa_lovable_asset_url( '6dc70c66-0a0c-4883-85ab-3fa824134dc5', 'benefit-bio.jpg' );
$img_benefit_press = awa_lovable_asset_url( '93534942-0353-4360-b82b-b618e3415728', 'benefit-press.jpg' );
$img_benefit_body  = awa_theme_asset( 'bon_repos.jpeg' );
$img_benefit_pure  = awa_theme_asset( 'zero_addictif.jpeg' );
$hero_video_url    = awa_theme_asset( 'awa-hero.mp4' );

/* Produits WooCommerce pour hero + grille */
$hero_slides   = array();
$all_products  = new WP_Query(
    array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    )
);

if ( $all_products->have_posts() ) {
    while ( $all_products->have_posts() ) {
        $all_products->the_post();
        global $product;
        $slug    = $product->get_slug();
        $palette = awa_get_product_palette( $slug );
        $thumb   = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $hero_slides[] = array(
            'name'   => get_the_title(),
            'slug'   => $slug,
            'image'  => $thumb ? $thumb : awa_lovable_product_image( $slug ),
            'accent' => $palette['accent'],
            'tint'   => $palette['tint'],
            'price'  => $product->get_price(),
            'link'   => get_the_permalink(),
        );
    }
    wp_reset_postdata();
}

/* Fallback hero si aucun produit WC */
if ( empty( $hero_slides ) ) {
    $fallbacks = array(
        array( 'name' => 'Fruits Rouges', 'slug' => 'fruits-rouges' ),
        array( 'name' => 'Agrumes', 'slug' => 'agrumes' ),
        array( 'name' => 'Gingembre', 'slug' => 'gingembre' ),
        array( 'name' => 'Mangue', 'slug' => 'mangue' ),
        array( 'name' => 'Soump', 'slug' => 'soump' ),
        array( 'name' => 'Fruits Blancs', 'slug' => 'fruits-blancs' ),
    );
    foreach ( $fallbacks as $fb ) {
        $palette       = awa_get_product_palette( $fb['slug'] );
        $hero_slides[] = array(
            'name'   => $fb['name'],
            'slug'   => $fb['slug'],
            'image'  => awa_lovable_product_image( $fb['slug'] ),
            'accent' => $palette['accent'],
            'tint'   => $palette['tint'],
            'price'  => '',
            'link'   => $shop_url,
        );
    }
}

$hero_first    = $hero_slides[0];
$total_products = count( $hero_slides );

/* Gammes */
$gammes = awa_get_gammes();

/* ── Données dynamiques via options admin (Apparence → Contenu Accueil) ── */

$benefits = array(
    array(
        'title' => awa_opt( 'benefit_1_title', '100% Bio & local' ),
        'text'  => awa_opt( 'benefit_1_text', 'Fruits cultivés au Sénégal, sans pesticides ni traitements chimiques.' ),
        'image' => awa_opt( 'benefit_1_image', $img_benefit_bio ),
        'icon'  => 'leaf',
    ),
    array(
        'title' => awa_opt( 'benefit_2_title', 'Pressé à froid' ),
        'text'  => awa_opt( 'benefit_2_text', 'Une méthode douce qui préserve vitamines, enzymes et saveurs.' ),
        'image' => awa_opt( 'benefit_2_image', $img_benefit_press ),
        'icon'  => 'sparkles',
    ),
    array(
        'title' => awa_opt( 'benefit_3_title', 'Bon pour le corps' ),
        'text'  => awa_opt( 'benefit_3_text', 'Tonifiant, digestif, antioxydant. Un rituel santé au quotidien.' ),
        'image' => awa_opt( 'benefit_3_image', $img_benefit_body ),
        'icon'  => 'heart',
    ),
    array(
        'title' => awa_opt( 'benefit_4_title', 'Zéro additif' ),
        'text'  => awa_opt( 'benefit_4_text', "Aucun conservateur, ni colorant, ni arôme. Juste le fruit, rien d'autre." ),
        'image' => awa_opt( 'benefit_4_image', $img_benefit_pure ),
        'icon'  => 'shield',
    ),
);

$reviews = awa_get_testimonials();

$marquee_raw   = awa_opt( 'marquee_items', "Sans conservateurs\nSans additifs\nSans colorants\nSans arômes\nPressé à froid\n100% Bio" );
$marquee_items = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $marquee_raw ) ) );

$awards = array(
    array( 'icon' => 'trophy', 'title' => awa_opt( 'award_1_title', '2e Prix Meilleur produit Made in Sénégal' ), 'sub' => awa_opt( 'award_1_sub', 'Jus Moringa · 2026' ) ),
    array( 'icon' => 'award',  'title' => awa_opt( 'award_2_title', 'Meilleure Entrepreneure' ), 'sub' => awa_opt( 'award_2_sub', '4× lauréate · 2022, 2024, 2026' ) ),
    array( 'icon' => 'shield', 'title' => awa_opt( 'award_3_title', 'Supervision QHSE' ), 'sub' => awa_opt( 'award_3_sub', "15 ans d'expérience industrielle" ) ),
    array( 'icon' => 'heart',  'title' => awa_opt( 'award_4_title', '91% de satisfaction client' ), 'sub' => awa_opt( 'award_4_sub', 'Avis vérifiés · 2024-2026' ) ),
);

$engagement = array(
    array( 'icon' => 'target',     'label' => awa_opt( 'engagement_mission_label', 'Mission' ), 'text' => awa_opt( 'engagement_mission_text', "Rendre accessibles des jus naturels et biologiques à base d'ingrédients locaux, pour une meilleure santé et longévité." ) ),
    array( 'icon' => 'eye',        'label' => awa_opt( 'engagement_vision_label', 'Vision' ),   'text' => awa_opt( 'engagement_vision_text', "Devenir d'ici 10 ans une marque leader des boissons naturelles en Afrique, reconnue pour son impact et son innovation." ) ),
    array( 'icon' => 'hand-heart', 'label' => awa_opt( 'engagement_values_label', 'Valeurs' ),  'text' => awa_opt( 'engagement_values_text', "Satisfaction client, intégrité, respect de l'environnement, responsabilité sociale, humanité et impact positif." ) ),
);

/* Image lifestyle best sellers */
$img_woman_juice_opt = awa_opt( 'bs_image', '' );
if ( $img_woman_juice_opt ) {
    $img_woman_juice = $img_woman_juice_opt;
}

/* Vidéo fondatrice */
$founder_video = awa_opt( 'founder_video', $hero_video_url );
?>

<main id="awa-home" class="awa-home">

    <!-- ═══════════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════════ -->
    <section
        class="awa-hero"
        id="awa-hero"
        style="background-color: <?php echo esc_attr( $hero_first['tint'] ); ?>;"
        data-slides="<?php echo esc_attr( wp_json_encode( $hero_slides ) ); ?>"
    >
        <div class="awa-hero__bg" aria-hidden="true"></div>
        <div class="awa-hero__blob awa-hero__blob--1" aria-hidden="true"></div>
        <div class="awa-hero__blob awa-hero__blob--2" aria-hidden="true"></div>
        <div class="awa-hero__blob awa-hero__blob--3" aria-hidden="true"></div>

        <div class="awa-container awa-hero__inner">
            <div class="awa-hero__content">
                <span class="awa-hero__badge">
                    <svg class="awa-hero__badge-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    <?php echo esc_html( awa_opt( 'hero_badge', 'Certifiés bio · Sénégal' ) ); ?>
                </span>
                <h1 class="awa-hero__title">
                    <?php echo esc_html( awa_opt( 'hero_title_1', 'La nature,' ) ); ?><br>
                    <em class="awa-hero__word" style="color: <?php echo esc_attr( $hero_first['accent'] ); ?>;"><?php echo esc_html( mb_strtolower( $hero_first['name'] ) ); ?></em><br>
                    <?php echo esc_html( awa_opt( 'hero_title_3', 'embouteillée.' ) ); ?>
                </h1>
                <p class="awa-hero__sub">
                    <?php echo esc_html( awa_opt( 'hero_sub', "Pressés à froid, livrés frais. Aucun conservateur, juste la promesse d'un fruit cueilli ce matin." ) ); ?>
                </p>
                <div class="awa-hero__btns">
                    <a href="#produits" class="awa-btn awa-btn--dark">
                        <?php echo esc_html( awa_opt( 'hero_cta1', 'Commander maintenant' ) ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="#histoire" class="awa-btn awa-btn--outline"><?php echo esc_html( awa_opt( 'hero_cta2', 'Notre histoire' ) ); ?></a>
                </div>
                <div class="awa-hero__stats">
                    <div class="awa-stat"><span class="awa-stat__num"><?php echo esc_html( $total_products ); ?></span><span class="awa-stat__lbl">recettes</span></div>
                    <div class="awa-stat"><span class="awa-stat__num"><?php echo esc_html( awa_opt( 'hero_stat_gammes', '3' ) ); ?></span><span class="awa-stat__lbl">gammes</span></div>
                    <div class="awa-stat"><span class="awa-stat__num"><?php echo esc_html( awa_opt( 'hero_stat_additif', '0' ) ); ?></span><span class="awa-stat__lbl">additif</span></div>
                </div>
            </div>

            <div class="awa-hero__visual">
                <div class="awa-hero__carousel">
                    <?php foreach ( $hero_slides as $i => $slide ) : ?>
                        <div class="awa-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>">
                            <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['name'] ); ?>" width="360" height="480" loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="awa-hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choisir un jus', 'awa-child' ); ?>">
                    <?php foreach ( $hero_slides as $i => $slide ) : ?>
                        <button
                            type="button"
                            class="awa-hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
                            role="tab"
                            aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
                            data-index="<?php echo esc_attr( $i ); ?>"
                            style="<?php echo 0 === $i ? 'background-color:' . esc_attr( $slide['accent'] ) . ';width:18px;' : ''; ?>"
                        ></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="awa-hero__scroll" aria-hidden="true">
            <span>Découvrir</span>
            <span class="awa-hero__scroll-line"></span>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         MARQUEE
    ═══════════════════════════════════════════════════════ -->
    <div class="awa-marquee" aria-hidden="true">
        <div class="awa-marquee__track">
            <?php
            $marquee_loop = array_merge( $marquee_items, $marquee_items, $marquee_items );
            foreach ( $marquee_loop as $item ) :
                ?>
                <span class="awa-marquee__item"><?php echo esc_html( $item ); ?><span class="awa-marquee__sep">✦</span></span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         GAMMES (3 gammes, 1 philosophie)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-gammes" id="gammes">
        <div class="awa-container">
            <p class="awa-label"><?php echo esc_html( awa_opt( 'gammes_label', '3 gammes, 1 philosophie' ) ); ?></p>
            <h2 class="awa-gammes__title"><?php echo esc_html( awa_opt( 'gammes_title', 'Choisissez la gamme qui vous ressemble.' ) ); ?></h2>
            <p class="awa-gammes__intro"><?php echo esc_html( awa_opt( 'gammes_intro', 'Mêmes jus pressés à froid, trois écrins pour trois moments de vie.' ) ); ?></p>

            <div class="awa-gammes__grid">
                <?php foreach ( $gammes as $g ) : ?>
                    <article class="awa-gamme-card" style="background-color: <?php echo esc_attr( $g['tint'] ); ?>;">
                        <div class="awa-gamme-card__img">
                            <img src="<?php echo esc_url( $g['image'] ); ?>" alt="Gamme <?php echo esc_attr( $g['name'] ); ?>" loading="lazy">
                        </div>
                        <span class="awa-gamme-card__badge" style="color: <?php echo esc_attr( $g['accent'] ); ?>;">
                            <?php echo esc_html( $g['badge'] ); ?>
                        </span>
                        <div class="awa-gamme-card__body">
                            <div class="awa-gamme-card__icon" style="background-color: <?php echo esc_attr( $g['accent'] ); ?>;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.5 2.474a6.247 6.247 0 0 0-5.5 3.69 6.247 6.247 0 0 0-5.5-3.69C3.47 2.474 1 4.976 1 8.04c0 4.088 5.094 8.2 11 13.46 5.906-5.26 11-9.372 11-13.46 0-3.064-2.47-5.566-5.5-5.566z"/></svg>
                            </div>
                            <h3 class="awa-gamme-card__name" style="color: <?php echo esc_attr( $g['accent'] ); ?>;"><?php echo esc_html( $g['name'] ); ?></h3>
                            <p class="awa-gamme-card__tagline"><?php echo esc_html( $g['tagline'] ); ?></p>
                            <p class="awa-gamme-card__desc"><?php echo esc_html( $g['description'] ); ?></p>
                            <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-gamme-card__link" style="color: <?php echo esc_attr( $g['accent'] ); ?>;">
                                Explorer <?php echo esc_html( $g['name'] ); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         BEST SELLERS (WooCommerce featured/top products)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-bestsellers" id="best-sellers">
        <div class="awa-container">
            <div class="awa-bestsellers__layout">
                <!-- Lifestyle image -->
                <div class="awa-bestsellers__image">
                    <img src="<?php echo esc_url( $img_woman_juice ); ?>" alt="Femme savourant un jus AwA Bio Foods" loading="lazy">
                    <div class="awa-bestsellers__image-badge">
                        <div class="awa-bestsellers__stars" aria-hidden="true">★★★★★</div>
                        <span><?php echo esc_html( awa_opt( 'bs_rating', '4.9/5 · 240+ avis' ) ); ?></span>
                        <p><?php echo esc_html( awa_opt( 'bs_quote', '« Une dose quotidienne de soleil sénégalais. »' ) ); ?></p>
                    </div>
                </div>

                <!-- Best sellers list -->
                <div class="awa-bestsellers__content">
                    <p class="awa-label"><?php echo esc_html( awa_opt( 'bs_label', '★ Best sellers' ) ); ?></p>
                    <h2 class="awa-bestsellers__title"><?php echo esc_html( awa_opt( 'bs_title', 'Les chouchous de nos clients.' ) ); ?></h2>
                    <p class="awa-bestsellers__sub"><?php echo esc_html( awa_opt( 'bs_sub', 'Quatre recettes plébiscitées, pressées à froid, à savourer fraîches du jour.' ) ); ?></p>

                    <div class="awa-bestsellers__grid">
                        <?php
                        $best = new WP_Query(
                            array(
                                'post_type'      => 'product',
                                'posts_per_page' => 4,
                                'post_status'    => 'publish',
                                'meta_key'       => 'total_sales',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC',
                            )
                        );

                        if ( $best->have_posts() ) :
                            while ( $best->have_posts() ) :
                                $best->the_post();
                                global $product;
                                $slug    = $product->get_slug();
                                $palette = awa_get_product_palette( $slug );
                                $thumb   = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' );
                                $img     = $thumb ? $thumb : awa_lovable_product_image( $slug );
                                $price   = $product->get_price();
                                $price_f = ( '' !== $price && null !== $price ) ? number_format( (float) $price, 0, ',', ' ' ) . ' F' : '';
                                $defaults = awa_get_product_content_defaults( $slug );
                                $tagline  = $defaults['tagline'] ? $defaults['tagline'] : $product->get_short_description();
                                ?>
                                <a href="<?php the_permalink(); ?>" class="awa-bestseller-card" style="background-color: <?php echo esc_attr( $palette['tint'] ); ?>;">
                                    <div class="awa-bestseller-card__img">
                                        <?php if ( $img ) : ?>
                                            <img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                        <?php endif; ?>
                                        <span class="awa-bestseller-card__badge">★ Top vente</span>
                                    </div>
                                    <div class="awa-bestseller-card__body">
                                        <h3 style="color: <?php echo esc_attr( $palette['accent'] ); ?>;"><?php the_title(); ?></h3>
                                        <p class="awa-bestseller-card__tagline"><?php echo esc_html( wp_strip_all_tags( $tagline ) ); ?></p>
                                        <div class="awa-bestseller-card__footer">
                                            <span class="awa-bestseller-card__price"><?php echo esc_html( $price_f ); ?></span>
                                            <span class="awa-bestseller-card__cta">Voir →</span>
                                        </div>
                                    </div>
                                </a>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>

                    <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-btn awa-btn--dark" style="margin-top: 1.25rem;">
                        <?php echo esc_html( awa_opt( 'bs_cta', 'Voir tout le catalogue' ) ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         PRODUITS (WooCommerce - grille complète)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-products" id="produits">
        <div class="awa-container">
            <div class="awa-products__head">
                <div>
                    <p class="awa-label"><?php echo esc_html( awa_opt( 'products_label', 'Boutique' ) ); ?></p>
                    <h2 class="awa-products__title"><?php echo esc_html( awa_opt( 'products_title', 'Nos jus à commander.' ) ); ?></h2>
                    <p class="awa-products__sub"><?php echo esc_html( awa_opt( 'products_sub', 'Livrés frais en 1h à Dakar · 48h en régions.' ) ); ?></p>
                </div>
                <div class="awa-products__controls">
                    <div class="awa-format-toggle" id="awa-format-toggle">
                        <button type="button" class="awa-format-toggle__btn is-active" data-format="250ml">250ml</button>
                        <button type="button" class="awa-format-toggle__btn" data-format="1L">1L</button>
                    </div>
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-link awa-link--desktop">Voir tout →</a>
                </div>
            </div>

            <?php
            $best_seller_slugs = array( 'fruits-rouges', 'gingembre', 'mangue', 'bissap-blanc' );
            $new_slugs         = array( 'moringa', 'the-bio' );
            ?>

            <div class="awa-products__grid awa-products__grid--4col">
                <?php
                $shop_products = new WP_Query(
                    array(
                        'post_type'      => 'product',
                        'posts_per_page' => 8,
                        'post_status'    => 'publish',
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC',
                    )
                );

                if ( $shop_products->have_posts() ) :
                    while ( $shop_products->have_posts() ) :
                        $shop_products->the_post();
                        global $product;
                        $slug    = $product->get_slug();
                        $palette = awa_get_product_palette( $slug );
                        $thumb   = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' );
                        $img     = $thumb ? $thumb : awa_lovable_product_image( $slug );
                        $price   = $product->get_price();
                        $price_f = ( '' !== $price && null !== $price ) ? number_format( (float) $price, 0, ',', ' ' ) . ' F' : '';

                        $cat_name = '';
                        $cats     = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'number' => 1 ) );
                        if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                            $cat_name = $cats[0]->name;
                        }

                        $is_best  = in_array( $slug, $best_seller_slugs, true );
                        $is_new   = in_array( $slug, $new_slugs, true );
                        $pid      = $product->get_id();
                        ?>
                        <a
                            href="<?php the_permalink(); ?>"
                            class="awa-product-card"
                            style="background-color: <?php echo esc_attr( $palette['tint'] ); ?>;"
                            data-product-id="<?php echo esc_attr( $pid ); ?>"
                            data-price="<?php echo esc_attr( $price ); ?>"
                        >
                            <div class="awa-product-card__img">
                                <?php if ( $img ) : ?>
                                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                <?php endif; ?>

                                <!-- Badges -->
                                <div class="awa-product-card__badges">
                                    <?php if ( $is_best ) : ?>
                                        <span class="awa-product-card__badge awa-product-card__badge--best">★ Top vente</span>
                                    <?php endif; ?>
                                    <?php if ( $is_new ) : ?>
                                        <span class="awa-product-card__badge awa-product-card__badge--new">Nouveau</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Quick add-to-cart -->
                                <button
                                    type="button"
                                    class="awa-product-card__add"
                                    data-product-id="<?php echo esc_attr( $pid ); ?>"
                                    aria-label="Ajouter <?php the_title_attribute(); ?> au panier"
                                    onclick="event.preventDefault();event.stopPropagation();awaAddToCart(this,<?php echo esc_attr( $pid ); ?>);"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                </button>
                            </div>
                            <div class="awa-product-card__body">
                                <div class="awa-product-card__stars" aria-hidden="true">★★★★★ <span class="awa-product-card__reviews-count">(48)</span></div>
                                <h3 class="awa-product-card__name" style="color: <?php echo esc_attr( $palette['accent'] ); ?>;"><?php the_title(); ?></h3>
                                <p class="awa-product-card__cat"><?php echo esc_html( $cat_name ? $cat_name : '' ); ?> · <span class="awa-product-card__format">250ml</span></p>
                                <div class="awa-product-card__pricing">
                                    <span class="awa-product-card__price"><?php echo esc_html( $price_f ); ?></span>
                                </div>
                            </div>

                            <!-- Added flash -->
                            <div class="awa-product-card__flash" aria-hidden="true">✓ Ajouté au panier</div>
                        </a>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p class="awa-products__empty">
                        <?php esc_html_e( 'Ajoutez vos produits dans WooCommerce → Produits.', 'awa-child' ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Trust bar -->
            <div class="awa-trust-bar">
                <div class="awa-trust-bar__item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                    <div><strong>Livraison 1h</strong><small>À Dakar</small></div>
                </div>
                <div class="awa-trust-bar__item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <div><strong>Paiement à la livraison</strong><small>Wave · OM · cash</small></div>
                </div>
                <div class="awa-trust-bar__item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                    <div><strong>Certifiés bio</strong><small>Sans additifs</small></div>
                </div>
                <div class="awa-trust-bar__item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    <div><strong>Pressés à froid</strong><small>Frais du jour</small></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         BIENFAITS
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-benefits" id="bienfaits">
        <div class="awa-container">
            <p class="awa-label"><?php echo esc_html( awa_opt( 'benefits_label', 'Bienfaits' ) ); ?></p>
            <h2 class="awa-benefits__title"><?php echo esc_html( awa_opt( 'benefits_title_1', 'Pourquoi vous allez' ) ); ?><br><em><?php echo esc_html( awa_opt( 'benefits_title_2', 'les adorer.' ) ); ?></em></h2>

            <div class="awa-benefits__grid">
                <?php foreach ( $benefits as $b ) : ?>
                    <article class="awa-benefit-card">
                        <div class="awa-benefit-card__img">
                            <img src="<?php echo esc_url( $b['image'] ); ?>" alt="<?php echo esc_attr( $b['title'] ); ?>" loading="lazy">
                        </div>
                        <div class="awa-benefit-card__body">
                            <h3><?php echo esc_html( $b['title'] ); ?></h3>
                            <p><?php echo esc_html( $b['text'] ); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="awa-benefits__perks">
                <div class="awa-benefits__perk">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                    Livré en 1h à Dakar · 48h en régions
                </div>
                <div class="awa-benefits__perk">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    Made in Sénégal, fierté locale
                </div>
                <div class="awa-benefits__perk">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                    Formats 250ml & 1L recyclables
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         AWARDS (Reconnaissance)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-awards" id="awards">
        <div class="awa-container">
            <p class="awa-label"><?php echo esc_html( awa_opt( 'awards_label', 'Reconnaissance' ) ); ?></p>
            <h2 class="awa-awards__title"><?php echo esc_html( awa_opt( 'awards_title', 'Une qualité primée.' ) ); ?></h2>

            <div class="awa-awards__grid">
                <?php foreach ( $awards as $a ) : ?>
                    <article class="awa-award-card">
                        <div class="awa-award-card__icon">
                            <?php if ( 'trophy' === $a['icon'] ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            <?php elseif ( 'award' === $a['icon'] ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                            <?php elseif ( 'shield' === $a['icon'] ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            <?php else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            <?php endif; ?>
                        </div>
                        <p class="awa-award-card__title"><?php echo esc_html( $a['title'] ); ?></p>
                        <p class="awa-award-card__sub"><?php echo esc_html( $a['sub'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         TÉMOIGNAGES
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-reviews" id="temoignages">
        <div class="awa-container">
            <p class="awa-label"><?php echo esc_html( awa_opt( 'reviews_label', 'Témoignages' ) ); ?></p>
            <h2 class="awa-reviews__title"><?php echo esc_html( awa_opt( 'reviews_title', 'Ils en redemandent.' ) ); ?></h2>
            <div class="awa-reviews__rating">
                <span class="awa-reviews__stars" aria-hidden="true">★★★★★</span>
                <span><?php echo esc_html( awa_opt( 'reviews_rating', '4.9/5 · 240+ avis vérifiés' ) ); ?></span>
            </div>

            <div class="awa-reviews__slider" data-autoplay="true" data-delay="4000">
                <div class="awa-reviews__track">
                    <?php foreach ( $reviews as $r ) : ?>
                        <article class="awa-review-card">
                            <span class="awa-review-card__quote" aria-hidden="true">"</span>
                            <div class="awa-review-card__stars" aria-hidden="true">★★★★★</div>
                            <p class="awa-review-card__text">« <?php echo esc_html( wp_strip_all_tags( $r['text'] ) ); ?> »</p>
                            <div class="awa-review-card__author">
                                <span class="awa-review-card__avatar" style="background-color: <?php echo esc_attr( $r['color'] ); ?>;"><?php echo esc_html( $r['initial'] ); ?></span>
                                <div>
                                    <strong><?php echo esc_html( $r['name'] ); ?></strong>
                                    <small><?php echo esc_html( $r['city'] ); ?> · Client vérifié</small>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="awa-reviews__dots" aria-label="Navigation du slider"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         FONDATRICE (Lovable Founder section)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-founder" id="histoire">
        <div class="awa-container awa-founder__inner">
            <div class="awa-founder__media" id="awa-founder-media">
                <video class="awa-founder__video" id="awa-founder-video" src="<?php echo esc_url( $founder_video ); ?>" autoplay loop muted playsinline></video>
                <div class="awa-founder__overlay"></div>
                <span class="awa-founder__label">La fondatrice</span>
                <button type="button" class="awa-founder__sound-hint" id="awa-founder-sound-hint" aria-label="Activer le son">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5 6 9H2v6h4l5 4V5Z"/><line x1="23" x2="17" y1="9" y2="15"/><line x1="17" x2="23" y1="9" y2="15"/></svg>
                    <span>Activer le son</span>
                </button>
                <div class="awa-founder__controls">
                    <button type="button" class="awa-founder__ctrl" id="awa-founder-mute" aria-label="Couper/activer le son">
                        <svg class="awa-founder__icon-muted" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5 6 9H2v6h4l5 4V5Z"/><line x1="23" x2="17" y1="9" y2="15"/><line x1="17" x2="23" y1="9" y2="15"/></svg>
                        <svg class="awa-founder__icon-unmuted" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path d="M11 5 6 9H2v6h4l5 4V5Z"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                    </button>
                    <button type="button" class="awa-founder__ctrl" id="awa-founder-fullscreen" aria-label="Plein écran">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/></svg>
                    </button>
                    <button type="button" class="awa-founder__ctrl awa-founder__ctrl--play" id="awa-founder-playpause" aria-label="Lecture/Pause">
                        <svg class="awa-founder__icon-pause" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="4" height="16" x="6" y="4"/><rect width="4" height="16" x="14" y="4"/></svg>
                        <svg class="awa-founder__icon-play" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </button>
                </div>
                <div class="awa-founder__quote-bottom">
                    <p><?php echo esc_html( awa_opt( 'founder_quote', "« Le naturel n'est pas une option, c'est une nécessité. »" ) ); ?></p>
                    <small><?php echo esc_html( awa_opt( 'founder_name', 'Awa Mbengue · Fondatrice & Ingénieure QHSE' ) ); ?></small>
                </div>
            </div>

            <div class="awa-founder__text">
                <p class="awa-label"><?php echo esc_html( awa_opt( 'founder_label', 'Mot de la fondatrice' ) ); ?></p>
                <h2 class="awa-founder__title"><?php echo esc_html( awa_opt( 'founder_title', 'Tout a commencé par' ) ); ?><br><em><?php echo esc_html( awa_opt( 'founder_title_em', 'une histoire simple.' ) ); ?></em></h2>
                <div class="awa-founder__desc">
                    <p><?php echo esc_html( awa_opt( 'founder_p1', "Je me souviens encore des saveurs simples et authentiques de mon enfance. Avec ma mère, j'ai appris très tôt que bien manger, ce n'était pas seulement se nourrir… c'était se préserver." ) ); ?></p>
                    <p><?php echo esc_html( awa_opt( 'founder_p2', "Un soir de mi-Sha'ban, le 20 avril 2019, entourée de mes proches, j'ai pris une décision : créer une entreprise engagée, porteuse de sens. AwA Bio Foods venait de naître." ) ); ?></p>
                    <p class="awa-founder__desc--fade"><?php echo esc_html( awa_opt( 'founder_p3', "Aujourd'hui, cette histoire continue. Avec vous." ) ); ?></p>
                </div>
                <div class="awa-founder__btns">
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="awa-btn awa-btn--primary">
                        <?php echo esc_html( awa_opt( 'founder_cta1', 'Découvrir nos jus' ) ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo esc_url( awa_opt( 'founder_cta2_url', 'https://wa.me/221783793197' ) ); ?>" target="_blank" rel="noopener noreferrer" class="awa-btn awa-btn--outline"><?php echo esc_html( awa_opt( 'founder_cta2', 'Échanger sur WhatsApp' ) ); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         ENGAGEMENT (Mission, Vision, Valeurs)
    ═══════════════════════════════════════════════════════ -->
    <section class="awa-engagement" id="engagement">
        <div class="awa-container">
            <p class="awa-label"><?php echo esc_html( awa_opt( 'engagement_label', 'Notre engagement' ) ); ?></p>
            <h2 class="awa-engagement__title"><?php echo esc_html( awa_opt( 'engagement_title', 'Ce qui nous guide.' ) ); ?></h2>

            <div class="awa-engagement__grid">
                <?php foreach ( $engagement as $e ) : ?>
                    <article class="awa-engagement-card">
                        <div class="awa-engagement-card__bar"></div>
                        <div class="awa-engagement-card__icon">
                            <?php if ( 'target' === $e['icon'] ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            <?php elseif ( 'eye' === $e['icon'] ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <?php else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08c.82.82 2.13.85 3 .07l2.07-1.9a2.82 2.82 0 0 1 3.79 0l2.96 2.66"/><path d="m18 15-2-2"/><path d="m15 18-2-2"/></svg>
                            <?php endif; ?>
                        </div>
                        <p class="awa-engagement-card__label"><?php echo esc_html( $e['label'] ); ?></p>
                        <p class="awa-engagement-card__text"><?php echo esc_html( $e['text'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<script>
(function () {
    /* ── Hero carousel ─────────────────────────────────────── */
    var hero = document.getElementById('awa-hero');
    if (!hero) return;
    var slides = hero.querySelectorAll('.awa-hero__slide');
    var dots = hero.querySelectorAll('.awa-hero__dot');
    var word = hero.querySelector('.awa-hero__word');
    var blobs = hero.querySelectorAll('.awa-hero__blob');
    var data = [];
    try { data = JSON.parse(hero.getAttribute('data-slides') || '[]'); } catch (e) {}
    var idx = 0, timer;

    function go(i) {
        if (!data.length) return;
        idx = (i + data.length) % data.length;
        var prevIdx = (idx - 1 + data.length) % data.length;
        var nextIdx = (idx + 1) % data.length;
        slides.forEach(function (s, j) {
            s.classList.toggle('is-active', j === idx);
            s.classList.toggle('is-prev', j === prevIdx);
            s.classList.toggle('is-next', j === nextIdx);
        });
        dots.forEach(function (d, j) {
            var on = j === idx;
            d.classList.toggle('is-active', on);
            d.setAttribute('aria-selected', on ? 'true' : 'false');
            d.style.width = on ? '18px' : '6px';
            d.style.backgroundColor = on ? data[idx].accent : 'rgba(0,0,0,0.2)';
        });
        hero.style.backgroundColor = data[idx].tint;
        if (word) {
            word.textContent = data[idx].name.toLowerCase();
            word.style.color = data[idx].accent;
        }
        blobs.forEach(function (b) { b.style.backgroundColor = data[idx].accent; });
    }

    function start() { timer = setInterval(function () { go(idx + 1); }, 3600); }
    function reset() { clearInterval(timer); start(); }

    dots.forEach(function (d) {
        d.addEventListener('click', function () { go(parseInt(d.getAttribute('data-index'), 10)); reset(); });
    });
    if (data.length) { go(0); start(); }

    /* ── Format toggle ─────────────────────────────────────── */
    var toggle = document.getElementById('awa-format-toggle');
    if (toggle) {
        toggle.addEventListener('click', function (e) {
            var btn = e.target.closest('.awa-format-toggle__btn');
            if (!btn) return;
            var fmt = btn.getAttribute('data-format');
            toggle.querySelectorAll('.awa-format-toggle__btn').forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            document.querySelectorAll('.awa-product-card__format').forEach(function (el) { el.textContent = fmt; });
        });
    }

    /* ── Add-to-cart (WooCommerce AJAX) ────────────────────── */
    window.awaAddToCart = function (btn, pid) {
        var card = btn.closest('.awa-product-card');
        var flash = card ? card.querySelector('.awa-product-card__flash') : null;
        var url = '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>';
        var fd = new FormData();
        fd.append('action', 'woocommerce_ajax_add_to_cart');
        fd.append('product_id', pid);
        fd.append('quantity', 1);

        /* Fallback: add via ?add-to-cart */
        fetch('/?add-to-cart=' + pid, { method: 'POST', credentials: 'same-origin' });

        if (flash) {
            flash.style.display = 'block';
            setTimeout(function () { flash.style.display = 'none'; }, 1400);
        }

        /* Update cart count in header */
        var counter = document.querySelector('.awa-header__cart-count');
        if (counter) {
            var c = parseInt(counter.textContent, 10) || 0;
            counter.textContent = c + 1;
        } else {
            var cartLink = document.querySelector('.awa-header__cart');
            if (cartLink) {
                var span = document.createElement('span');
                span.className = 'awa-header__cart-count';
                span.textContent = '1';
                cartLink.appendChild(span);
            }
        }
    };

    /* ── Founder video controls ────────────────────────────── */
    var fv = document.getElementById('awa-founder-video');
    if (fv) {
        var muteBtn = document.getElementById('awa-founder-mute');
        var fsBtn = document.getElementById('awa-founder-fullscreen');
        var ppBtn = document.getElementById('awa-founder-playpause');

        var soundHint = document.getElementById('awa-founder-sound-hint');

        function updateMuteUI() {
            muteBtn.querySelector('.awa-founder__icon-muted').style.display = fv.muted ? '' : 'none';
            muteBtn.querySelector('.awa-founder__icon-unmuted').style.display = fv.muted ? 'none' : '';
            if (soundHint) soundHint.style.display = fv.muted ? '' : 'none';
        }

        if (soundHint) soundHint.addEventListener('click', function () {
            fv.muted = false;
            updateMuteUI();
        });

        if (muteBtn) muteBtn.addEventListener('click', function () {
            fv.muted = !fv.muted;
            updateMuteUI();
        });

        if (fsBtn) fsBtn.addEventListener('click', function () {
            fv.muted = false;
            if (muteBtn) {
                muteBtn.querySelector('.awa-founder__icon-muted').style.display = 'none';
                muteBtn.querySelector('.awa-founder__icon-unmuted').style.display = '';
            }
            if (fv.requestFullscreen) fv.requestFullscreen();
            else if (fv.webkitEnterFullscreen) fv.webkitEnterFullscreen();
        });

        if (ppBtn) ppBtn.addEventListener('click', function () {
            if (fv.paused) { fv.play(); } else { fv.pause(); }
            ppBtn.querySelector('.awa-founder__icon-pause').style.display = fv.paused ? 'none' : '';
            ppBtn.querySelector('.awa-founder__icon-play').style.display = fv.paused ? '' : 'none';
        });
    }
})();
</script>

<?php get_footer(); ?>
