<?php
/**
 * Functions — thème enfant AwA Bio Foods
 * Thème parent : Storefront
 *
 * @package awa-child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Force les templates WooCommerce classiques (cart.php, form-checkout.php)
 * au lieu des Gutenberg Blocks qui ignorent nos overrides de templates.
 * Sans ce filtre, nos woocommerce/cart/cart.php et checkout/form-checkout.php
 * ne sont jamais appelés.
 */
add_filter( 'the_content', function ( $content ) {
	if ( is_cart() ) {
		return do_shortcode( '[woocommerce_cart]' );
	}
	if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) && ! is_wc_endpoint_url( 'order-pay' ) ) {
		return do_shortcode( '[woocommerce_checkout]' );
	}
	if ( is_wc_endpoint_url( 'order-received' ) ) {
		return do_shortcode( '[woocommerce_order_tracking]' );
	}
	return $content;
}, 99 );

/**
 * URL complète d'un asset Lovable.
 */
function awa_lovable_asset_url( $asset_id, $filename ) {
    return 'https://juice-bliss-store.lovable.app/__l5e/assets-v1/' . $asset_id . '/' . $filename;
}

/**
 * URL d'un asset local du thème enfant (dossier /assets/).
 */
function awa_theme_asset( $filename ) {
    return get_stylesheet_directory_uri() . '/assets/' . $filename;
}

/**
 * Format/attribut d'un produit (affiché dans les cartes catalogue).
 * Cherche l'attribut WooCommerce "Format", puis "format", sinon 250ml.
 */
function awa_get_product_format( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return '250ml';
	}

	$format = $product->get_attribute( 'Format' );
	if ( $format ) {
		return $format;
	}

	$format = $product->get_attribute( 'format' );
	if ( $format ) {
		return $format;
	}

	return '250ml';
}

/**
 * Palette couleur par slug produit (design Lovable).
 */
function awa_get_product_palette( $slug = '' ) {
    $palettes = array(
        'fruits-rouges'  => array( 'accent' => '#c8102e', 'tint' => '#ffd6dc' ),
        'agrumes'        => array( 'accent' => '#f4b400', 'tint' => '#fff5cc' ),
        'gingembre'      => array( 'accent' => '#c8851a', 'tint' => '#f4e1bc' ),
        'mangue'         => array( 'accent' => '#e07a1a', 'tint' => '#f8d9b6' ),
        'soump'          => array( 'accent' => '#6b3a18', 'tint' => '#f1c9a6' ),
        'fruits-blancs'  => array( 'accent' => '#c89a3a', 'tint' => '#f5e8c4' ),
        'bissap-blanc'   => array( 'accent' => '#caa84a', 'tint' => '#f6ecc8' ),
        'melon-pasteque' => array( 'accent' => '#d63b4f', 'tint' => '#fad4d8' ),
        'moringa'        => array( 'accent' => '#3f7a32', 'tint' => '#dfeed5' ),
        'sidem'          => array( 'accent' => '#8b3a1a', 'tint' => '#eccfba' ),
        'tamarin'        => array( 'accent' => '#a85a1c', 'tint' => '#f1d4b3' ),
        'the-bio'        => array( 'accent' => '#9a6a2a', 'tint' => '#ede0c5' ),
    );

    return isset( $palettes[ $slug ] ) ? $palettes[ $slug ] : array( 'accent' => '#2f6b47', 'tint' => '#f7f4ed' );
}

/**
 * Image Lovable de secours par slug.
 */
function awa_lovable_product_image( $slug ) {
    $map = array(
        'fruits-rouges' => awa_lovable_asset_url( '15779170-b868-4dcd-82d2-2b3545185a59', 'fruits-rouges.jpg' ),
        'agrumes'       => awa_lovable_asset_url( '7c7b4016-78da-4662-a546-569dc0d8433f', 'agrumes.jpg' ),
        'gingembre'     => awa_lovable_asset_url( 'a1df18e8-f8c3-4776-8581-40c5cdab3f1e', 'gingembre.jpg' ),
        'mangue'        => awa_lovable_asset_url( '17dbcf42-82b2-408b-87d0-a87de4cab747', 'mangue.jpg' ),
        'soump'         => awa_lovable_asset_url( '713838ee-da4d-4f38-a333-e021b8a087fe', 'soump.jpg' ),
        'fruits-blancs' => awa_lovable_asset_url( 'aa812694-19cb-4cee-8509-0a1868ed260b', 'fruits-blancs.jpg' ),
    );

    return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/**
 * Contenu Lovable par défaut (tagline, description, bienfaits, ingrédients).
 */
function awa_get_product_content_defaults( $slug = '' ) {
    $data = array(
        'fruits-rouges' => array(
            'tagline'     => 'Mélange naturel de bissap et fraises',
            'description' => 'Une explosion de saveurs rouges : la profondeur du bissap rencontre la douceur des fraises pour un jus tonifiant et richement parfumé.',
            'benefits'    => array( 'Tonifiant', 'Régule la tension artérielle', 'Bonne santé cardiaque', 'Anti cholestérol', 'Idéal régime' ),
            'ingredients' => 'Bissap (hibiscus), fraises, eau, sucre de canne non raffiné.',
        ),
        'agrumes' => array(
            'tagline'     => 'Mélange naturel de citrons et de clémentines',
            'description' => 'Un cocktail vitaminé acidulé, pressé à froid pour préserver toute la fraîcheur des agrumes. Le boost parfait du matin.',
            'benefits'    => array( 'Anti-cholestérol', 'Anti-cancer', 'Anti-inflammatoire', 'Antioxydant', 'Énergisant', 'Os plus solides' ),
            'ingredients' => 'Citron, clémentine, eau de source, soupçon de sucre de canne.',
        ),
        'gingembre' => array(
            'tagline'     => 'Mélange naturel de gingembre et d\'ananas',
            'description' => 'Le piquant du gingembre frais adouci par le sucre naturel de l\'ananas. Un jus signature, vivant et stimulant.',
            'benefits'    => array( 'Aphrodisiaque', 'Dégraisse', 'Anti fièvre', 'Détox', 'Aide à la digestion' ),
            'ingredients' => 'Gingembre frais, ananas, jus de citron, eau.',
        ),
        'mangue' => array(
            'tagline'     => 'Mélange naturel de mangues mûres',
            'description' => 'Mangues sénégalaises cueillies à maturité, mixées pour offrir une texture veloutée et un parfum solaire.',
            'benefits'    => array( 'Riche en provitamine A', 'Vitamine C', 'Anti fatigue', 'Prévention cancer', 'Protection cœur', 'Bonne digestion' ),
            'ingredients' => 'Mangue, eau, jus de citron.',
        ),
        'soump' => array(
            'tagline'     => 'Fruits du Soump, dattiers du désert',
            'description' => 'Un jus ancestral à base de Soump, fruit du désert au goût caramélisé unique, riche en fibres et minéraux.',
            'benefits'    => array( 'Anti hypertension', 'Anti troubles digestifs & respiratoires', 'Anti ballonnement', 'Anti constipation' ),
            'ingredients' => 'Fruits de Soump, eau, sucre de canne.',
        ),
        'fruits-blancs' => array(
            'tagline'     => 'Mélange de bouyes (pain de singe) et corossol',
            'description' => 'Le baobab et le corossol s\'unissent dans un jus crémeux, doux et minéralisant, fidèle aux traditions ouest-africaines.',
            'benefits'    => array( 'Anti diarrhée', 'Vitamines B', 'Riche en calcium', 'Sels minéraux', 'Oligoéléments' ),
            'ingredients' => 'Bouye (baobab), corossol, eau, sucre de canne.',
        ),
        'bissap-blanc' => array(
            'tagline'     => 'Hibiscus blanc et ananas frais',
            'description' => 'Le bissap blanc, plus délicat que sa version rouge, marié à l\'ananas pour un jus floral, doré et désaltérant.',
            'benefits'    => array( 'Antioxydant', 'Digestif', 'Hydratant', 'Anti-inflammatoire', 'Rafraîchissant' ),
            'ingredients' => 'Hibiscus blanc, ananas, eau, sucre de canne.',
        ),
        'melon-pasteque' => array(
            'tagline'     => 'Le duo le plus rafraîchissant de l\'été',
            'description' => 'Pastèque juteuse et melon sucré : un jus tout en douceur et en eau, parfait pour les journées chaudes.',
            'benefits'    => array( 'Hydratant', 'Riche en eau', 'Lycopène', 'Vitamine A', 'Diurétique' ),
            'ingredients' => 'Pastèque, melon, jus de citron, eau.',
        ),
        'moringa' => array(
            'tagline'     => 'L\'arbre miracle, en bouteille',
            'description' => 'Le moringa, super-aliment ouest-africain, dans un jus vert vibrant aux mille bienfaits.',
            'benefits'    => array( 'Super-aliment', 'Riche en fer', 'Anti-anémie', 'Énergisant', 'Anti-fatigue', 'Vitamines & minéraux' ),
            'ingredients' => 'Feuilles de moringa, citron, gingembre, eau, sucre de canne.',
        ),
        'sidem' => array(
            'tagline'     => 'Fruit sauvage du Sahel',
            'description' => 'Le Sidem, fruit sauvage aux notes caramélisées et boisées. Une recette ancestrale transmise de génération en génération.',
            'benefits'    => array( 'Anti-anémie', 'Digestif', 'Riche en vitamine C', 'Tonique', 'Reminéralisant' ),
            'ingredients' => 'Fruits de Sidem, eau, sucre de canne.',
        ),
        'tamarin' => array(
            'tagline'     => 'L\'acidulé qui réveille',
            'description' => 'Le tamarin, gousse exotique au goût acidulé et caramélisé. Un jus vivant, parfait pour accompagner un repas épicé.',
            'benefits'    => array( 'Digestif', 'Laxatif doux', 'Antioxydant', 'Vitamine B', 'Magnésium' ),
            'ingredients' => 'Pulpe de tamarin, eau, sucre de canne, jus de citron.',
        ),
        'the-bio' => array(
            'tagline'     => 'Infusion glacée à la menthe fraîche',
            'description' => 'Notre thé bio infusé à froid, rehaussé de menthe fraîche du Sénégal. Une alternative légère et zen aux sodas.',
            'benefits'    => array( 'Antioxydant', 'Faible en sucre', 'Digestif', 'Apaisant', 'Hydratant' ),
            'ingredients' => 'Thé bio, menthe fraîche, eau, soupçon de sucre de canne.',
        ),
    );

    return isset( $data[ $slug ] ) ? $data[ $slug ] : array(
        'tagline'     => '',
        'description' => '',
        'benefits'    => array(),
        'ingredients' => '',
    );
}

/**
 * Catégories WooCommerce à afficher dans le filtre du catalogue.
 * Récupérées dynamiquement depuis le panel WooCommerce (product_cat).
 */
function awa_get_filter_categories() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return array();
	}

	return array_filter(
		$terms,
		function ( $term ) {
			if ( ! $term instanceof WP_Term ) {
				return false;
			}
			// Exclure la catégorie par défaut de WooCommerce (anglais et français).
			$excluded_slugs = array( 'uncategorized', 'non-classe' );
			return ! in_array( $term->slug, $excluded_slugs, true );
		}
	);
}

/**
 * Données des gammes (Lovable).
 */
function awa_get_gammes() {
    return array(
        array(
            'id'          => 'njaboot',
            'name'        => 'Njaboot',
            'tagline'     => 'Le bon goût, accessible',
            'description' => 'La gamme pensée pour toute la famille : jus frais à prix doux, sans compromis sur la qualité.',
            'accent'      => '#2e7d32',
            'tint'        => '#e6f1e0',
            'badge'       => 'Accessible',
            'image'       => awa_theme_asset( 'gamme-njaboot.jpeg' ),
        ),
        array(
            'id'          => 'teraanga',
            'name'        => 'Teraanga',
            'tagline'     => "L'hospitalité moderne",
            'description' => 'Notre gamme signature : un emballage moderne et soigné, parfait pour vos moments du quotidien et vos invités.',
            'accent'      => '#b8112b',
            'tint'        => '#f9dad5',
            'badge'       => 'Signature',
            'image'       => awa_theme_asset( 'gamme-teraanga.jpeg' ),
        ),
        array(
            'id'          => 'buur',
            'name'        => 'Buur',
            'tagline'     => "L'excellence en verre",
            'description' => 'Notre gamme premium en bouteille de verre : un écrin noble pour les occasions qui le méritent.',
            'accent'      => '#6b3a18',
            'tint'        => '#efe4d4',
            'badge'       => 'Premium',
            'image'       => awa_theme_asset( 'gamme-buur.jpeg' ),
        ),
    );
}

/* ── Styles ─────────────────────────────────────────────────── */
add_action(
    'wp_enqueue_scripts',
    function () {
        wp_enqueue_style(
            'storefront-parent',
            get_template_directory_uri() . '/style.css'
        );
        wp_enqueue_style(
            'awa-child',
            get_stylesheet_uri(),
            array( 'storefront-parent' ),
            filemtime( get_stylesheet_directory() . '/style.css' )
        );
        wp_enqueue_style(
            'awa-fonts',
            'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
            array(),
            null
        );
    },
    20
);

/* ── Thème & WooCommerce ────────────────────────────────────── */
add_action(
    'after_setup_theme',
    function () {
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'woocommerce' );
    }
);

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/* Masquer éléments Storefront parasites */
add_action(
    'wp_enqueue_scripts',
    function () {
        wp_dequeue_style( 'storefront-woocommerce-style' );
    },
    99
);

/* Override inline tardif — boutons & marges — chargé après les inline Storefront */
add_action(
    'wp_enqueue_scripts',
    function () {
        if ( ! ( is_cart() || is_checkout() || is_wc_endpoint_url( 'order-received' ) ) ) {
            return;
        }

        $primary    = 'oklch(0.45 0.16 145)';
        $primary_fg = 'oklch(0.985 0.012 95)';
        $bg         = 'oklch(0.985 0.012 95)';
        $fg         = 'oklch(0.22 0.04 145)';
        $border     = 'oklch(0.9 0.02 95)';
        $muted_fg   = 'oklch(0.45 0.03 145)';
        $card       = 'oklch(1 0 0)';

        $css = "
/* AwA Override — boutons Storefront */
body.woocommerce-cart .button,
body.woocommerce-cart a.button,
body.woocommerce-cart button:not(.awa-pdetail__qty-btn),
body.woocommerce-cart input[type=submit],
body.woocommerce-checkout .button,
body.woocommerce-checkout a.button,
body.woocommerce-checkout button:not(.awa-pdetail__qty-btn),
body.woocommerce-checkout input[type=submit],
body.woocommerce-order-received .button,
body.woocommerce-order-received a.button {
    background-color: {$primary} !important;
    background: {$primary} !important;
    color: {$primary_fg} !important;
    border: none !important;
    border-radius: 9999px !important;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
    font-size: 0.8125rem !important;
    font-weight: 600 !important;
    padding: 0.75rem 1.5rem !important;
    box-shadow: none !important;
    text-shadow: none !important;
    letter-spacing: 0 !important;
    line-height: 1 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
}
body.woocommerce-cart .button:hover,
body.woocommerce-cart a.button:hover,
body.woocommerce-cart button:not(.awa-pdetail__qty-btn):hover,
body.woocommerce-cart input[type=submit]:hover,
body.woocommerce-checkout .button:hover,
body.woocommerce-checkout a.button:hover,
body.woocommerce-checkout button:not(.awa-pdetail__qty-btn):hover,
body.woocommerce-checkout input[type=submit]:hover {
    background-color: {$primary} !important;
    background: {$primary} !important;
    color: {$primary_fg} !important;
    opacity: 0.88 !important;
}
/* Boutons secondaires */
body.woocommerce-cart .awa-btn--light,
body.woocommerce-checkout .awa-btn--light {
    background-color: {$bg} !important;
    background: {$bg} !important;
    color: {$fg} !important;
    border: 1px solid {$border} !important;
    opacity: 1 !important;
}
body.woocommerce-cart .awa-btn--light:hover,
body.woocommerce-checkout .awa-btn--light:hover {
    background-color: {$bg} !important;
    color: {$fg} !important;
    opacity: 0.88 !important;
}
/* Supprimer les boutons de qty de la règle générale */
body.woocommerce-cart .awa-pdetail__qty-btn {
    background: transparent !important;
    background-color: transparent !important;
    color: {$fg} !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 0 !important;
    width: 2.25rem !important; height: 2.25rem !important;
    font-size: 1rem !important; font-weight: 400 !important;
}
/* Marges horizontales container */
body.woocommerce-cart .awa-container,
body.woocommerce-checkout .awa-container,
body.woocommerce-order-received .awa-container {
    padding-left: 1.25rem !important;
    padding-right: 1.25rem !important;
}
@media (min-width: 768px) {
    body.woocommerce-cart .awa-container,
    body.woocommerce-checkout .awa-container,
    body.woocommerce-order-received .awa-container {
        padding-left: 2rem !important;
        padding-right: 2rem !important;
    }
}
/* Bouton Valider la commande */
body.woocommerce-cart .awa-cart__checkout-btn {
    display: flex !important;
    width: 100% !important;
    padding: 1.125rem 1.5rem !important;
    background-color: {$primary} !important;
    background: {$primary} !important;
    color: {$primary_fg} !important;
    border-radius: 9999px !important;
    font-size: 1rem !important;
    font-weight: 700 !important;
    justify-content: space-between !important;
    align-items: center !important;
    border: none !important;
    letter-spacing: -0.01em !important;
    text-decoration: none !important;
    box-shadow: 0 8px 24px -8px oklch(0.45 0.16 145 / 0.5) !important;
}
body.woocommerce-cart .wc-proceed-to-checkout .checkout-button:not(.awa-cart__checkout-btn) {
    display: none !important;
}
/* Cart totals titre Storefront masqué */
body.woocommerce-cart .cart_totals > h2 { display: none !important; }
body.woocommerce-cart .cart_totals { float: none !important; width: 100% !important; background: transparent !important; border: none !important; padding: 0 !important; }
/* Order total en couleur primaire */
body.woocommerce-cart .cart_totals .order-total th,
body.woocommerce-cart .cart_totals .order-total td {
    color: {$primary} !important;
    font-size: 1.25rem !important;
    font-weight: 600 !important;
}
";
        wp_add_inline_style( 'awa-child', $css );
    },
    200
);

/* Supprimer le formulaire de recherche Storefront (bouton "Rechercher") */
add_action( 'init', function () {
    remove_action( 'storefront_header', 'storefront_product_search', 40 );
} );

add_filter( 'storefront_menu_toggle_text', '__return_empty_string' );

/* Désactiver la sidebar sur les pages WooCommerce */
add_filter(
    'is_active_sidebar',
    function ( $is_active, $index ) {
        if ( 'sidebar-1' !== $index ) {
            return $is_active;
        }
        if ( is_cart() || is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
            return false;
        }
        return $is_active;
    },
    10,
    2
);

/* ── Override final absolu — wp_head 9999 ───────────────────── */
/* Chargé EN DERNIER dans le <head>, après les inline CSS de Storefront  */
add_action( 'wp_head', function () {
    if ( ! ( is_cart() || is_checkout() || is_wc_endpoint_url( 'order-received' ) ) ) {
        return;
    }
    ?>
    <style id="awa-override-final">
    /*
     * AwA Child — override final (wp_head 9999)
     * Valeurs exactes des tokens : --awa-foreground = #38543f (sombre)
     *                              --awa-primary    = #2f6b47 (vert bouton)
     *                              --awa-background = #f8f5ee (crème)
     *                              --awa-card       = #ffffff
     *                              --awa-border     = #e5e2d9
     *                              --awa-muted-fg   = #6b8c78
     */

    /* ── Base ── */
    body.woocommerce-cart,
    body.woocommerce-checkout,
    body.woocommerce-order-received {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        background-color: #f8f5ee !important;
        color: #38543f !important;
        -webkit-font-smoothing: antialiased !important;
    }

    /* ── Titres ── */
    body.woocommerce-cart h1,
    body.woocommerce-cart h2,
    body.woocommerce-cart h3,
    body.woocommerce-cart h4,
    body.woocommerce-checkout h1,
    body.woocommerce-checkout h2,
    body.woocommerce-checkout h3,
    body.woocommerce-checkout h4 {
        font-family: 'Fraunces', Georgia, serif !important;
        color: #38543f !important;
        letter-spacing: -0.02em !important;
    }

    /* ── Paragraphes & labels — toujours sombres ── */
    body.woocommerce-cart p,
    body.woocommerce-cart label,
    body.woocommerce-cart td,
    body.woocommerce-cart th,
    body.woocommerce-cart span,
    body.woocommerce-checkout p,
    body.woocommerce-checkout label,
    body.woocommerce-checkout td,
    body.woocommerce-checkout th,
    body.woocommerce-checkout span {
        color: #38543f !important;
    }

    /* ── Liens — couleur sombre héritée, PAS verte ── */
    body.woocommerce-cart a,
    body.woocommerce-checkout a,
    body.woocommerce-order-received a {
        color: #38543f !important;
        text-decoration: none !important;
    }

    /* ── Prix — couleur primaire (vert) ── */
    body.woocommerce-cart .woocommerce-Price-amount,
    body.woocommerce-cart .cart-subtotal .amount,
    body.woocommerce-cart .order-total .amount,
    body.woocommerce-checkout .woocommerce-Price-amount {
        color: #2f6b47 !important;
        font-weight: 600 !important;
    }

    /* ── Lien supprimer produit ── */
    body.woocommerce-cart a.remove,
    body.woocommerce-cart .awa-cart__remove {
        color: #6b8c78 !important;
        opacity: 0.7 !important;
    }

    /* ── Inputs & selects ── */
    body.woocommerce-cart input[type="text"],
    body.woocommerce-cart input[type="email"],
    body.woocommerce-cart input[type="tel"],
    body.woocommerce-cart input[type="number"],
    body.woocommerce-cart select,
    body.woocommerce-cart textarea,
    body.woocommerce-checkout input[type="text"],
    body.woocommerce-checkout input[type="email"],
    body.woocommerce-checkout input[type="tel"],
    body.woocommerce-checkout input[type="number"],
    body.woocommerce-checkout select,
    body.woocommerce-checkout textarea {
        color: #38543f !important;
        background: #ffffff !important;
        border: 1px solid #e5e2d9 !important;
        border-radius: 0.75rem !important;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        font-size: 0.875rem !important;
        padding: 0.625rem 0.875rem !important;
        box-shadow: none !important;
        outline: none !important;
    }
    body.woocommerce-cart input:focus,
    body.woocommerce-checkout input:focus,
    body.woocommerce-checkout select:focus,
    body.woocommerce-checkout textarea:focus {
        border-color: #2f6b47 !important;
        box-shadow: 0 0 0 3px rgba(47,107,71,0.12) !important;
    }

    /* ── Tous les boutons Storefront → pill vert AwA ── */
    body.woocommerce-cart .button,
    body.woocommerce-cart a.button,
    body.woocommerce-cart button.button,
    body.woocommerce-cart input.button,
    body.woocommerce-cart input[type="submit"],
    body.woocommerce-cart button[type="submit"]:not(.awa-pdetail__qty-btn),
    body.woocommerce-checkout .button,
    body.woocommerce-checkout a.button,
    body.woocommerce-checkout button.button,
    body.woocommerce-checkout input.button,
    body.woocommerce-checkout input[type="submit"],
    body.woocommerce-checkout button[type="submit"]:not(.awa-pdetail__qty-btn),
    body.woocommerce-page .button,
    body.woocommerce-page a.button {
        background-color: #2f6b47 !important;
        background: #2f6b47 !important;
        color: #f8f5ee !important;
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
        text-decoration: none !important;
        line-height: 1 !important;
    }
    body.woocommerce-cart .button:hover,
    body.woocommerce-cart a.button:hover,
    body.woocommerce-cart input[type="submit"]:hover,
    body.woocommerce-checkout .button:hover,
    body.woocommerce-checkout a.button:hover,
    body.woocommerce-checkout input[type="submit"]:hover {
        background-color: #245437 !important;
        background: #245437 !important;
        color: #f8f5ee !important;
    }

    /* ── Bouton secondaire (Appliquer coupon) ── */
    body.woocommerce-cart .awa-btn--light {
        background-color: #ffffff !important;
        background: #ffffff !important;
        color: #38543f !important;
        border: 1px solid #e5e2d9 !important;
    }
    body.woocommerce-cart .awa-btn--light:hover {
        background-color: #f8f5ee !important;
        background: #f8f5ee !important;
    }

    /* ── Supprimer les boutons qty natifs WooCommerce (div vert) ── */
    body.woocommerce-cart .quantity .plus,
    body.woocommerce-cart .quantity .minus,
    body.woocommerce-cart .quantity button.button,
    body.woocommerce-cart .quantity input[type="button"],
    body.woocommerce-cart div.quantity > .button {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
        visibility: hidden !important;
    }
    /* Neutraliser tout fond/border sur le wrapper .quantity */
    body.woocommerce-cart .quantity,
    body.woocommerce-checkout .quantity {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: contents !important;
    }

    /* ── Boutons qty — 2rem = 0.75rem + 20px ── */
    body.woocommerce-cart .awa-pdetail__qty-btn,
    body.woocommerce-checkout .awa-pdetail__qty-btn {
        background-color: rgba(56,84,63,0.08) !important;
        background: rgba(56,84,63,0.08) !important;
        color: #38543f !important;
        border: none !important;
        border-radius: 50% !important;
        padding: 0 !important;
        width: 2rem !important;
        height: 2rem !important;
        font-size: 1rem !important;
        font-weight: 400 !important;
        box-shadow: none !important;
        text-shadow: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        flex-shrink: 0 !important;
    }
    body.woocommerce-cart .awa-pdetail__qty,
    body.woocommerce-checkout .awa-pdetail__qty {
        border: none !important;
        background: transparent !important;
    }

    /* ── Bouton Valider la commande ── */
    body.woocommerce-cart .awa-cart__checkout-btn {
        display: grid !important;
        grid-template-columns: auto 1fr auto !important;
        align-items: center !important;
        gap: 0.875rem !important;
        width: 100% !important;
        padding: 1rem 1.25rem !important;
        background: linear-gradient(135deg, #1d6640, #2f8055) !important;
        background-color: #1d6640 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 1rem !important;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        text-decoration: none !important;
        cursor: pointer !important;
        box-shadow: 0 4px 20px rgba(29,102,64,0.32) !important;
        box-sizing: border-box !important;
        transition: transform 0.18s ease, box-shadow 0.18s ease !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn:hover {
        transform: translateY(-2px) !important;
        color: #ffffff !important;
        text-decoration: none !important;
        box-shadow: 0 8px 28px rgba(29,102,64,0.42) !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn__left,
    body.woocommerce-cart .awa-cart__checkout-btn__arrow {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: rgba(255,255,255,0.2) !important;
        flex-shrink: 0 !important;
        color: #ffffff !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn__left {
        width: 2.25rem !important;
        height: 2.25rem !important;
        border-radius: 0.625rem !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn__arrow {
        width: 2rem !important;
        height: 2rem !important;
        border-radius: 50% !important;
        transition: transform 0.2s ease !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn:hover .awa-cart__checkout-btn__arrow {
        transform: translateX(3px) !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn__label,
    body.woocommerce-cart .awa-cart__checkout-btn span.awa-cart__checkout-btn__label {
        display: block !important;
        font-size: 0.9375rem !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        line-height: 1.2 !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn__total,
    body.woocommerce-cart .awa-cart__checkout-btn span.awa-cart__checkout-btn__total {
        display: block !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: rgba(255,255,255,0.78) !important;
        line-height: 1 !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn span,
    body.woocommerce-cart .awa-cart__checkout-btn .awa-cart__checkout-btn__total .woocommerce-Price-amount {
        color: #ffffff !important;
    }
    body.woocommerce-cart .awa-cart__checkout-btn .awa-cart__checkout-btn__total,
    body.woocommerce-cart .awa-cart__checkout-btn .awa-cart__checkout-btn__total span {
        color: rgba(255,255,255,0.78) !important;
    }
    body.woocommerce-cart .awa-cart__checkout-secure {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.3rem !important;
        margin: 0.5rem 0 0 !important;
        font-size: 0.6875rem !important;
        font-weight: 500 !important;
        color: #6b8c78 !important;
    }

    /* ── Checkout — bouton Passer la commande ── */
    body.woocommerce-checkout #place_order {
        width: 100% !important;
        padding: 1rem 1.5rem !important;
        background: linear-gradient(135deg, #1d6640, #2f8055) !important;
        background-color: #1d6640 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 1rem !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        display: block !important;
        box-shadow: 0 4px 20px rgba(29,102,64,0.32) !important;
        letter-spacing: -0.01em !important;
        cursor: pointer !important;
    }

    /* ── Masquer boutons parasites ── */
    body.woocommerce-cart button[name="update_cart"],
    body.woocommerce-cart .awa-cart__update,
    body.woocommerce-cart button[name="s"],
    body.woocommerce-cart .search-submit,
    body.woocommerce-cart .widget_search,
    body.woocommerce-cart .search-form { display: none !important; }
    body.woocommerce-cart .wc-proceed-to-checkout .checkout-button:not(.awa-cart__checkout-btn) { display: none !important; }

    /* ── Marges conteneur — padding global 10px (hors header) ── */
    body.woocommerce-cart .awa-container:not(.awa-header__inner),
    body.woocommerce-checkout .awa-container:not(.awa-header__inner),
    body.woocommerce-order-received .awa-container:not(.awa-header__inner) {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    /* ── Checkout — pleine largeur ── */
    body.woocommerce-checkout .awa-checkout,
    body.woocommerce-checkout .awa-checkout .awa-container,
    body.woocommerce-checkout .woocommerce-checkout {
        max-width: 100% !important;
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    /* ── Header — padding correct sur toutes les pages WooCommerce ── */
    body.woocommerce-cart .awa-header .awa-container,
    body.woocommerce-cart .awa-header__inner,
    body.woocommerce-checkout .awa-header .awa-container,
    body.woocommerce-checkout .awa-header__inner,
    body.woocommerce-order-received .awa-header .awa-container,
    body.woocommerce-order-received .awa-header__inner {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }
    </style>
    <?php
}, 9999 );

/* ── Fallback wp_footer — styles critiques absolus ──────────── */
/* Injecté dans <body>, APRÈS tout le reste, impossible à écraser */
add_action( 'wp_footer', function () {
    if ( ! ( is_cart() || is_checkout() ) ) {
        return;
    }
    ?>
    <style id="awa-footer-critical">
    .awa-cart__checkout-btn,
    a.awa-cart__checkout-btn {
        background: linear-gradient(135deg,#1d6640,#2f8055) !important;
        background-color: #1d6640 !important;
        color: #fff !important;
        display: grid !important;
        grid-template-columns: auto 1fr auto !important;
        border-radius: 1rem !important;
        border: none !important;
        padding: 1rem 1.25rem !important;
        width: 100% !important;
        text-decoration: none !important;
        box-shadow: 0 4px 20px rgba(29,102,64,.32) !important;
        box-sizing: border-box !important;
        gap: .875rem !important;
        align-items: center !important;
    }
    .awa-cart__checkout-btn *,
    a.awa-cart__checkout-btn * { color: #fff !important; }
    .awa-cart__checkout-btn__label { font-size: .9375rem !important; font-weight: 700 !important; }
    .awa-cart__checkout-btn__total,
    .awa-cart__checkout-btn__total * { color: rgba(255,255,255,.78) !important; }
    .awa-cart__checkout-btn__left,
    .awa-cart__checkout-btn__arrow {
        display: flex !important; align-items: center !important; justify-content: center !important;
        background: rgba(255,255,255,.2) !important; flex-shrink: 0 !important;
    }
    .awa-cart__checkout-btn__left { width: 2.25rem !important; height: 2.25rem !important; border-radius: .625rem !important; }
    .awa-cart__checkout-btn__arrow { width: 2rem !important; height: 2rem !important; border-radius: 50% !important; }
    .awa-cart__checkout-secure { display: flex !important; align-items: center !important; justify-content: center !important; gap: .3rem !important; margin: .5rem 0 0 !important; font-size: .6875rem !important; color: #6b8c78 !important; }
    </style>
    <?php
}, 1 );

/* ── Page d'accueil par défaut ──────────────────────────────── */
add_action(
    'after_switch_theme',
    function () {
        if ( ! get_option( 'page_on_front' ) ) {
            $home = get_page_by_title( 'Accueil' );
            if ( ! $home ) {
                $id = wp_insert_post(
                    array(
                        'post_title'  => 'Accueil',
                        'post_status' => 'publish',
                        'post_type'   => 'page',
                    )
                );
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $id );
            }
        }
    }
);
