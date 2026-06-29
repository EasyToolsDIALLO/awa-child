<?php
/**
 * Page d'options — Contenu Accueil AwA Bio Foods
 * Accessible via : Apparence → Contenu Accueil
 *
 * @package awa-child
 */

defined( 'ABSPATH' ) || exit;

/* ══════════════════════════════════════════════════════════════
   1. Enqueue scripts admin (media uploader)
══════════════════════════════════════════════════════════════ */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'appearance_page_awa-homepage-options' !== $hook ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script(
        'awa-homepage-admin',
        get_stylesheet_directory_uri() . '/inc/homepage-options.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );
    wp_add_inline_style( 'wp-admin', '
        .awa-admin-wrap { max-width: 960px; }
        .awa-admin-tabs { display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 0; border-bottom: 2px solid #1d6640; padding-bottom: 0; }
        .awa-admin-tab { padding: 8px 16px; cursor: pointer; background: #f0f0f1; border: 1px solid #c3c4c7; border-bottom: none; border-radius: 4px 4px 0 0; font-weight: 600; font-size: 13px; color: #1d6640; text-decoration: none; }
        .awa-admin-tab.active { background: #1d6640; color: #fff; border-color: #1d6640; }
        .awa-admin-panel { display: none; background: #fff; border: 1px solid #c3c4c7; border-top: none; padding: 24px; border-radius: 0 4px 4px 4px; }
        .awa-admin-panel.active { display: block; }
        .awa-admin-section { margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #f0f0f1; }
        .awa-admin-section:last-child { border-bottom: none; margin-bottom: 0; }
        .awa-admin-section h3 { margin: 0 0 12px; font-size: 14px; color: #1d1d1d; font-weight: 700; padding-left: 8px; border-left: 3px solid #1d6640; }
        .awa-admin-row { display: grid; grid-template-columns: 200px 1fr; gap: 12px; align-items: start; margin-bottom: 10px; }
        .awa-admin-row label { font-weight: 600; font-size: 13px; padding-top: 6px; color: #444; }
        .awa-admin-row input[type=text], .awa-admin-row textarea, .awa-admin-row input[type=url] { width: 100%; box-sizing: border-box; }
        .awa-admin-row textarea { min-height: 80px; }
        .awa-admin-row .description { font-size: 11px; color: #888; margin-top: 4px; }
        .awa-media-row { display: flex; gap: 10px; align-items: center; }
        .awa-media-preview { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; display: block; }
        .awa-media-preview-video { width: 120px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        .awa-review-card-admin, .awa-benefit-card-admin, .awa-award-card-admin { background: #f8faf8; border: 1px solid #e0e8e0; border-radius: 6px; padding: 16px; margin-bottom: 12px; }
        .awa-review-card-admin h4, .awa-benefit-card-admin h4, .awa-award-card-admin h4 { margin: 0 0 10px; font-size: 13px; color: #1d6640; }
    ' );
} );

/* ══════════════════════════════════════════════════════════════
   2. Enregistrer la page dans le menu Apparence
══════════════════════════════════════════════════════════════ */
add_action( 'admin_menu', function () {
    add_theme_page(
        'Contenu Accueil',
        'Contenu Accueil',
        'edit_theme_options',
        'awa-homepage-options',
        'awa_homepage_options_page'
    );
} );

/* ══════════════════════════════════════════════════════════════
   3. Sauvegarder les options
══════════════════════════════════════════════════════════════ */
add_action( 'admin_init', function () {
    if (
        ! isset( $_POST['awa_home_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['awa_home_nonce'] ) ), 'awa_home_save' )
    ) {
        return;
    }
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    $fields = awa_homepage_fields_list();
    foreach ( $fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_option( $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }

    /* Champs textarea (autorisent les retours à la ligne) */
    $textareas = array(
        'awa_home_marquee_items',
        'awa_home_founder_p1', 'awa_home_founder_p2', 'awa_home_founder_p3',
        'awa_home_engagement_mission_text', 'awa_home_engagement_vision_text', 'awa_home_engagement_values_text',
        'awa_home_review_1_text', 'awa_home_review_2_text', 'awa_home_review_3_text', 'awa_home_review_4_text',
        'awa_home_benefit_1_text', 'awa_home_benefit_2_text', 'awa_home_benefit_3_text', 'awa_home_benefit_4_text',
    );
    foreach ( $textareas as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_option( $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }

    add_action( 'admin_notices', function () {
        echo '<div class="notice notice-success is-dismissible"><p>✅ Contenu de la page d\'accueil mis à jour.</p></div>';
    } );
} );

/* ══════════════════════════════════════════════════════════════
   4. Liste de tous les champs (pour la sauvegarde)
══════════════════════════════════════════════════════════════ */
function awa_homepage_fields_list() {
    return array(
        /* Hero */
        'awa_home_hero_badge', 'awa_home_hero_title_1', 'awa_home_hero_title_3',
        'awa_home_hero_sub', 'awa_home_hero_cta1', 'awa_home_hero_cta2',
        'awa_home_hero_stat_gammes', 'awa_home_hero_stat_additif',
        /* Gammes section */
        'awa_home_gammes_label', 'awa_home_gammes_title', 'awa_home_gammes_intro',
        /* Best sellers */
        'awa_home_bs_label', 'awa_home_bs_title', 'awa_home_bs_sub',
        'awa_home_bs_rating', 'awa_home_bs_quote', 'awa_home_bs_cta',
        'awa_home_bs_image',
        /* Products */
        'awa_home_products_label', 'awa_home_products_title', 'awa_home_products_sub',
        /* Benefits */
        'awa_home_benefits_label', 'awa_home_benefits_title_1', 'awa_home_benefits_title_2',
        'awa_home_benefit_1_title', 'awa_home_benefit_1_text', 'awa_home_benefit_1_image',
        'awa_home_benefit_2_title', 'awa_home_benefit_2_text', 'awa_home_benefit_2_image',
        'awa_home_benefit_3_title', 'awa_home_benefit_3_text', 'awa_home_benefit_3_image',
        'awa_home_benefit_4_title', 'awa_home_benefit_4_text', 'awa_home_benefit_4_image',
        /* Awards */
        'awa_home_awards_label', 'awa_home_awards_title',
        'awa_home_award_1_title', 'awa_home_award_1_sub',
        'awa_home_award_2_title', 'awa_home_award_2_sub',
        'awa_home_award_3_title', 'awa_home_award_3_sub',
        'awa_home_award_4_title', 'awa_home_award_4_sub',
        /* Reviews */
        'awa_home_reviews_label', 'awa_home_reviews_title', 'awa_home_reviews_rating',
        'awa_home_review_1_name', 'awa_home_review_1_city', 'awa_home_review_1_initial', 'awa_home_review_1_color',
        'awa_home_review_2_name', 'awa_home_review_2_city', 'awa_home_review_2_initial', 'awa_home_review_2_color',
        'awa_home_review_3_name', 'awa_home_review_3_city', 'awa_home_review_3_initial', 'awa_home_review_3_color',
        'awa_home_review_4_name', 'awa_home_review_4_city', 'awa_home_review_4_initial', 'awa_home_review_4_color',
        /* Founder */
        'awa_home_founder_label', 'awa_home_founder_title', 'awa_home_founder_title_em',
        'awa_home_founder_quote', 'awa_home_founder_name', 'awa_home_founder_video',
        'awa_home_founder_cta1', 'awa_home_founder_cta2', 'awa_home_founder_cta2_url',
        /* Engagement */
        'awa_home_engagement_label', 'awa_home_engagement_title',
        'awa_home_engagement_mission_label', 'awa_home_engagement_vision_label', 'awa_home_engagement_values_label',
        /* Marquee */
        'awa_home_marquee_items',
    );
}

/* ══════════════════════════════════════════════════════════════
   5. Rendu de la page admin
══════════════════════════════════════════════════════════════ */
function awa_homepage_options_page() {
    $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'hero';
    $tabs = array(
        'hero'       => '🎯 Hero',
        'sections'   => '📌 Sections',
        'benefits'   => '✅ Bienfaits',
        'reviews'    => '⭐ Avis',
        'founder'    => '👤 Notre histoire',
        'engagement' => '💚 Engagement',
    );
    ?>
    <div class="wrap awa-admin-wrap">
        <h1>🌿 Contenu — Page d'accueil AwA Bio Foods</h1>
        <p style="color:#666;font-size:13px;">Modifiez le contenu de votre page d'accueil ici. Les produits sont gérés dans <a href="<?php echo admin_url('edit.php?post_type=product'); ?>">WooCommerce → Produits</a>.</p>

        <div class="awa-admin-tabs">
            <?php foreach ( $tabs as $key => $label ) : ?>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=awa-homepage-options&tab=' . $key ) ); ?>"
                   class="awa-admin-tab<?php echo $tab === $key ? ' active' : ''; ?>">
                    <?php echo esc_html( $label ); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field( 'awa_home_save', 'awa_home_nonce' ); ?>

            <!-- ── TAB HERO ── -->
            <div class="awa-admin-panel<?php echo 'hero' === $tab ? ' active' : ''; ?>">
                <div class="awa-admin-section">
                    <h3>Badge & Sous-titre</h3>
                    <?php awa_field( 'awa_home_hero_badge', 'Badge vert', 'Certifiés bio · Sénégal' ); ?>
                    <?php awa_field( 'awa_home_hero_sub', 'Sous-titre', 'Pressés à froid, livrés frais. Aucun conservateur, juste la promesse d\'un fruit cueilli ce matin.' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Titre principal</h3>
                    <?php awa_field( 'awa_home_hero_title_1', 'Ligne 1', 'La nature,' ); ?>
                    <p style="font-size:12px;color:#888;margin:4px 0 8px 212px;">La ligne 2 (le nom du produit) est automatique depuis WooCommerce.</p>
                    <?php awa_field( 'awa_home_hero_title_3', 'Ligne 3', 'embouteillée.' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Boutons</h3>
                    <?php awa_field( 'awa_home_hero_cta1', 'Bouton principal', 'Commander maintenant' ); ?>
                    <?php awa_field( 'awa_home_hero_cta2', 'Bouton secondaire', 'Notre histoire' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Statistiques</h3>
                    <?php awa_field( 'awa_home_hero_stat_gammes', 'Nombre de gammes', '3' ); ?>
                    <?php awa_field( 'awa_home_hero_stat_additif', 'Additifs', '0' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Marquee (bandeau défilant)</h3>
                    <?php awa_field( 'awa_home_marquee_items', 'Items (1 par ligne)', "Sans conservateurs\nSans additifs\nSans colorants\nSans arômes\nPressé à froid\n100% Bio", 'textarea' ); ?>
                </div>
            </div>

            <!-- ── TAB SECTIONS ── -->
            <div class="awa-admin-panel<?php echo 'sections' === $tab ? ' active' : ''; ?>">
                <div class="awa-admin-section">
                    <h3>Section Gammes</h3>
                    <?php awa_field( 'awa_home_gammes_label', 'Label', '3 gammes, 1 philosophie' ); ?>
                    <?php awa_field( 'awa_home_gammes_title', 'Titre', 'Choisissez la gamme qui vous ressemble.' ); ?>
                    <?php awa_field( 'awa_home_gammes_intro', 'Introduction', 'Mêmes jus pressés à froid, trois écrins pour trois moments de vie.' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Section Best Sellers</h3>
                    <?php awa_field( 'awa_home_bs_label', 'Label', '★ Best sellers' ); ?>
                    <?php awa_field( 'awa_home_bs_title', 'Titre', 'Les chouchous de nos clients.' ); ?>
                    <?php awa_field( 'awa_home_bs_sub', 'Sous-titre', 'Quatre recettes plébiscitées, pressées à froid, à savourer fraîches du jour.' ); ?>
                    <?php awa_field( 'awa_home_bs_rating', 'Note affichée', '4.9/5 · 240+ avis' ); ?>
                    <?php awa_field( 'awa_home_bs_quote', 'Citation lifestyle', '« Une dose quotidienne de soleil sénégalais. »' ); ?>
                    <?php awa_field( 'awa_home_bs_cta', 'Bouton catalogue', 'Voir tout le catalogue' ); ?>
                    <?php awa_media_field( 'awa_home_bs_image', 'Photo lifestyle', 'image' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Section Produits</h3>
                    <?php awa_field( 'awa_home_products_label', 'Label', 'Boutique' ); ?>
                    <?php awa_field( 'awa_home_products_title', 'Titre', 'Nos jus à commander.' ); ?>
                    <?php awa_field( 'awa_home_products_sub', 'Sous-titre', 'Livrés frais en 1h à Dakar · 48h en régions.' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Section Awards</h3>
                    <?php awa_field( 'awa_home_awards_label', 'Label', 'Reconnaissance' ); ?>
                    <?php awa_field( 'awa_home_awards_title', 'Titre', 'Une qualité primée.' ); ?>
                    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                        <div class="awa-award-card-admin">
                            <h4>Prix <?php echo $i; ?></h4>
                            <?php awa_field( "awa_home_award_{$i}_title", 'Titre', awa_award_default( $i, 'title' ) ); ?>
                            <?php awa_field( "awa_home_award_{$i}_sub", 'Sous-titre', awa_award_default( $i, 'sub' ) ); ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- ── TAB BIENFAITS ── -->
            <div class="awa-admin-panel<?php echo 'benefits' === $tab ? ' active' : ''; ?>">
                <div class="awa-admin-section">
                    <h3>En-tête de section</h3>
                    <?php awa_field( 'awa_home_benefits_label', 'Label', 'Bienfaits' ); ?>
                    <?php awa_field( 'awa_home_benefits_title_1', 'Titre ligne 1', 'Pourquoi vous allez' ); ?>
                    <?php awa_field( 'awa_home_benefits_title_2', 'Titre ligne 2 (italique)', 'les adorer.' ); ?>
                </div>
                <?php
                $benefit_defaults = array(
                    1 => array( 'title' => '100% Bio & local', 'text' => 'Fruits cultivés au Sénégal, sans pesticides ni traitements chimiques.' ),
                    2 => array( 'title' => 'Pressé à froid', 'text' => 'Une méthode douce qui préserve vitamines, enzymes et saveurs.' ),
                    3 => array( 'title' => 'Bon pour le corps', 'text' => 'Tonifiant, digestif, antioxydant. Un rituel santé au quotidien.' ),
                    4 => array( 'title' => 'Zéro additif', 'text' => 'Aucun conservateur, ni colorant, ni arôme. Juste le fruit, rien d\'autre.' ),
                );
                for ( $i = 1; $i <= 4; $i++ ) :
                ?>
                <div class="awa-benefit-card-admin">
                    <h4>Bienfait <?php echo $i; ?></h4>
                    <?php awa_field( "awa_home_benefit_{$i}_title", 'Titre', $benefit_defaults[ $i ]['title'] ); ?>
                    <?php awa_field( "awa_home_benefit_{$i}_text", 'Description', $benefit_defaults[ $i ]['text'], 'textarea' ); ?>
                    <?php awa_media_field( "awa_home_benefit_{$i}_image", 'Image', 'image' ); ?>
                </div>
                <?php endfor; ?>
            </div>

            <!-- ── TAB AVIS ── -->
            <div class="awa-admin-panel<?php echo 'reviews' === $tab ? ' active' : ''; ?>">
                <div class="notice notice-info inline" style="margin: 0 0 16px;">
                    <p>
                        <strong>ℹ️ Gestion illimitée :</strong> pour ajouter autant de témoignages que vous voulez, utilisez le menu
                        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=awa_testimonial' ) ); ?>">Témoignages</a>.
                        Les champs ci-dessous servent uniquement de secours si aucun témoignage n'est créé.
                    </p>
                </div>
                <div class="awa-admin-section">
                    <h3>En-tête</h3>
                    <?php awa_field( 'awa_home_reviews_label', 'Label', 'Témoignages' ); ?>
                    <?php awa_field( 'awa_home_reviews_title', 'Titre', 'Ils en redemandent.' ); ?>
                    <?php awa_field( 'awa_home_reviews_rating', 'Note globale', '4.9/5 · 240+ avis vérifiés' ); ?>
                </div>
                <?php
                $review_defaults = array(
                    1 => array( 'name' => 'Aïssatou D.', 'city' => 'Dakar', 'text' => 'Le jus de bissap est divin, un vrai retour aux saveurs de chez nous. Je ne peux plus m\'en passer.', 'initial' => 'A', 'color' => '#b8112b' ),
                    2 => array( 'name' => 'Marc L.', 'city' => 'Saly', 'text' => 'Le gingembre/ananas, ma routine du matin. Énergie garantie pour toute la journée.', 'initial' => 'M', 'color' => '#c8851a' ),
                    3 => array( 'name' => 'Fatou S.', 'city' => 'Thiès', 'text' => 'Livraison rapide, jus frais, packaging top. Mes enfants adorent le jus de mangue !', 'initial' => 'F', 'color' => '#e07a1a' ),
                    4 => array( 'name' => 'Khadija B.', 'city' => 'Dakar', 'text' => 'Le soump m\'a fait redécouvrir un goût d\'enfance. Authentique et tellement bien fait.', 'initial' => 'K', 'color' => '#6b3a18' ),
                );
                for ( $i = 1; $i <= 4; $i++ ) :
                    $d = $review_defaults[ $i ];
                ?>
                <div class="awa-review-card-admin">
                    <h4>Avis <?php echo $i; ?></h4>
                    <?php awa_field( "awa_home_review_{$i}_name", 'Prénom Nom', $d['name'] ); ?>
                    <?php awa_field( "awa_home_review_{$i}_city", 'Ville', $d['city'] ); ?>
                    <?php awa_field( "awa_home_review_{$i}_text", 'Texte de l\'avis', $d['text'], 'textarea' ); ?>
                    <?php awa_field( "awa_home_review_{$i}_initial", 'Initiale (avatar)', $d['initial'] ); ?>
                    <?php awa_field( "awa_home_review_{$i}_color", 'Couleur avatar (hex)', $d['color'] ); ?>
                </div>
                <?php endfor; ?>
            </div>

            <!-- ── TAB NOTRE HISTOIRE ── -->
            <div class="awa-admin-panel<?php echo 'founder' === $tab ? ' active' : ''; ?>">
                <div class="awa-admin-section">
                    <p class="awa-admin-notice" style="background:#f0f6fc;border-left:4px solid #2271b1;padding:0.75rem 1rem;margin:0 0 1rem;color:#333;">
                        Cette section correspond au bloc <strong>« Notre histoire »</strong> (ancre <code>#histoire</code>) sur la page d'accueil. Vous pouvez modifier la vidéo et tout le texte ci-dessous.
                    </p>
                    <h3>Titre & texte</h3>
                    <?php awa_field( 'awa_home_founder_label', 'Label', 'Mot de la fondatrice' ); ?>
                    <?php awa_field( 'awa_home_founder_title', 'Titre ligne 1', 'Tout a commencé par' ); ?>
                    <?php awa_field( 'awa_home_founder_title_em', 'Titre ligne 2 (italique)', 'une histoire simple.' ); ?>
                    <?php awa_field( 'awa_home_founder_p1', 'Paragraphe 1', 'Je me souviens encore des saveurs simples et authentiques de mon enfance. Avec ma mère, j\'ai appris très tôt que bien manger, ce n\'était pas seulement se nourrir… c\'était se préserver.', 'textarea' ); ?>
                    <?php awa_field( 'awa_home_founder_p2', 'Paragraphe 2', 'Un soir de mi-Sha\'ban, le 20 avril 2019, entourée de mes proches, j\'ai pris une décision : créer une entreprise engagée, porteuse de sens. AwA Bio Foods venait de naître.', 'textarea' ); ?>
                    <?php awa_field( 'awa_home_founder_p3', 'Paragraphe 3 (discret)', 'Aujourd\'hui, cette histoire continue. Avec vous.', 'textarea' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Citation & identité</h3>
                    <?php awa_field( 'awa_home_founder_quote', 'Citation (sur la vidéo)', '« Le naturel n\'est pas une option, c\'est une nécessité. »' ); ?>
                    <?php awa_field( 'awa_home_founder_name', 'Nom & titre', 'Awa Mbengue · Fondatrice & Ingénieure QHSE' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Vidéo</h3>
                    <?php awa_media_field( 'awa_home_founder_video', 'Vidéo de la section Notre histoire', 'video' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Boutons</h3>
                    <?php awa_field( 'awa_home_founder_cta1', 'Bouton principal (texte)', 'Découvrir nos jus' ); ?>
                    <?php awa_field( 'awa_home_founder_cta2', 'Bouton secondaire (texte)', 'Échanger sur WhatsApp' ); ?>
                    <?php awa_field( 'awa_home_founder_cta2_url', 'Bouton secondaire (URL)', 'https://wa.me/221783793197' ); ?>
                </div>
            </div>

            <!-- ── TAB ENGAGEMENT ── -->
            <div class="awa-admin-panel<?php echo 'engagement' === $tab ? ' active' : ''; ?>">
                <div class="awa-admin-section">
                    <h3>En-tête</h3>
                    <?php awa_field( 'awa_home_engagement_label', 'Label', 'Notre engagement' ); ?>
                    <?php awa_field( 'awa_home_engagement_title', 'Titre', 'Ce qui nous guide.' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Mission</h3>
                    <?php awa_field( 'awa_home_engagement_mission_label', 'Titre', 'Mission' ); ?>
                    <?php awa_field( 'awa_home_engagement_mission_text', 'Texte', 'Rendre accessibles des jus naturels et biologiques à base d\'ingrédients locaux, pour une meilleure santé et longévité.', 'textarea' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Vision</h3>
                    <?php awa_field( 'awa_home_engagement_vision_label', 'Titre', 'Vision' ); ?>
                    <?php awa_field( 'awa_home_engagement_vision_text', 'Texte', 'Devenir d\'ici 10 ans une marque leader des boissons naturelles en Afrique, reconnue pour son impact et son innovation.', 'textarea' ); ?>
                </div>
                <div class="awa-admin-section">
                    <h3>Valeurs</h3>
                    <?php awa_field( 'awa_home_engagement_values_label', 'Titre', 'Valeurs' ); ?>
                    <?php awa_field( 'awa_home_engagement_values_text', 'Texte', 'Satisfaction client, intégrité, respect de l\'environnement, responsabilité sociale, humanité et impact positif.', 'textarea' ); ?>
                </div>
            </div>

            <p style="padding: 16px 24px; border-top: 1px solid #e0e0e0; margin: 0;">
                <?php submit_button( 'Enregistrer les modifications', 'primary large', 'submit', false ); ?>
            </p>
        </form>
    </div>
    <?php
}

/* ══════════════════════════════════════════════════════════════
   6. Helpers de rendu de champs
══════════════════════════════════════════════════════════════ */
function awa_field( $key, $label, $default = '', $type = 'text' ) {
    $val = get_option( $key, '' );
    if ( '' === $val ) {
        $val = $default;
    }
    echo '<div class="awa-admin-row">';
    echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
    if ( 'textarea' === $type ) {
        echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" rows="3">' . esc_textarea( $val ) . '</textarea>';
    } else {
        echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '">';
    }
    echo '</div>';
}

function awa_media_field( $key, $label, $media_type = 'image' ) {
    $val = get_option( $key, '' );
    echo '<div class="awa-admin-row">';
    echo '<label>' . esc_html( $label ) . '</label>';
    echo '<div>';
    echo '<div class="awa-media-row">';
    if ( $val ) {
        if ( 'video' === $media_type ) {
            echo '<video src="' . esc_url( $val ) . '" class="awa-media-preview-video" muted></video>';
        } else {
            echo '<img src="' . esc_url( $val ) . '" class="awa-media-preview" id="preview-' . esc_attr( $key ) . '">';
        }
    } else {
        if ( 'video' === $media_type ) {
            echo '<span style="width:120px;height:70px;display:flex;align-items:center;justify-content:center;background:#f0f0f1;border-radius:4px;font-size:11px;color:#888;" id="preview-' . esc_attr( $key ) . '">Aucune vidéo</span>';
        } else {
            echo '<span style="width:80px;height:60px;display:flex;align-items:center;justify-content:center;background:#f0f0f1;border-radius:4px;font-size:11px;color:#888;" id="preview-' . esc_attr( $key ) . '">Aucune image</span>';
        }
    }
    echo '<div style="display:flex;flex-direction:column;gap:6px;">';
    echo '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '">';
    echo '<button type="button" class="button awa-media-btn" data-field="' . esc_attr( $key ) . '" data-type="' . esc_attr( $media_type ) . '">Choisir ' . ( 'video' === $media_type ? 'une vidéo' : 'une image' ) . '</button>';
    if ( $val ) {
        echo '<button type="button" class="button awa-media-clear" data-field="' . esc_attr( $key ) . '">Supprimer</button>';
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function awa_award_default( $i, $field ) {
    $defaults = array(
        1 => array( 'title' => '2e Prix Meilleur produit Made in Sénégal', 'sub' => 'Jus Moringa · 2026' ),
        2 => array( 'title' => 'Meilleure Entrepreneure', 'sub' => '4× lauréate · 2022, 2024, 2026' ),
        3 => array( 'title' => 'Supervision QHSE', 'sub' => '15 ans d\'expérience industrielle' ),
        4 => array( 'title' => '91% de satisfaction client', 'sub' => 'Avis vérifiés · 2024-2026' ),
    );
    return $defaults[ $i ][ $field ] ?? '';
}
