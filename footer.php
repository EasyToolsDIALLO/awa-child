<?php
/**
 * Footer — reproduction Lovable SiteFooter
 *
 * @package awa-child
 */

$awa_home     = home_url( '/' );
$awa_shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/#produits' );
$awa_logo_url = awa_lovable_asset_url( '1ac4f2f0-5bc5-49cc-8e92-a516c0212208', 'logo.png' );
?>

        </div><!-- .col-full -->
    </div><!-- #content -->

    <footer class="awa-footer" id="contact" role="contentinfo">
        <div class="awa-container awa-footer__grid">
            <div class="awa-footer__brand">
                <div class="awa-footer__logo-row">
                    <img src="<?php echo esc_url( $awa_logo_url ); ?>" alt="AwA Bio Foods" class="awa-footer__logo-img" width="36" height="36">
                    <span class="awa-footer__logo-text">AwA Bio Foods</span>
                </div>
                <p class="awa-footer__desc">
                    Des jus 100% naturels, pressés au Sénégal à partir de fruits et plantes certifiés bio.
                    Sans conservateurs, sans additifs, sans colorants. Disponibles en 250ml et 1L.
                </p>
                <p class="awa-footer__slogan">« Avec AwA Bio Foods, la Nature s'exprime. »</p>
                <div class="awa-footer__social">
                    <a href="https://instagram.com/awa_bio_foods" target="_blank" rel="noopener noreferrer" class="awa-footer__social-link" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <a href="https://www.facebook.com/AwABioFoods/" target="_blank" rel="noopener noreferrer" class="awa-footer__social-link" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/company/awabiofoods" target="_blank" rel="noopener noreferrer" class="awa-footer__social-link" aria-label="LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <a href="https://twitter.com/AwaBio" target="_blank" rel="noopener noreferrer" class="awa-footer__social-link" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                    <a href="https://www.tiktok.com/@awa.bio.foods" target="_blank" rel="noopener noreferrer" class="awa-footer__social-link" aria-label="TikTok">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
                    </a>
                </div>
            </div>

            <div class="awa-footer__col">
                <h4 class="awa-footer__heading">Boutique</h4>
                <ul class="awa-footer__links">
                    <li><a href="<?php echo esc_url( $awa_shop_url ); ?>">Catalogue complet</a></li>
                    <li><a href="<?php echo esc_url( $awa_home . '#gammes' ); ?>">Nos 3 gammes</a></li>
                    <li><a href="<?php echo esc_url( $awa_home . '#bienfaits' ); ?>">Bienfaits</a></li>
                    <li><a href="<?php echo esc_url( $awa_home . '#histoire' ); ?>">Notre histoire</a></li>
                </ul>
                <h4 class="awa-footer__heading" style="margin-top: 1rem;">Livraison</h4>
                <p class="awa-footer__desc" style="margin-top: 0;">
                    Dakar · Touba · Louga · Thiès · Saint-Louis<br>
                    International : France, Belgique
                </p>
            </div>

            <div class="awa-footer__col">
                <h4 class="awa-footer__heading">Contact</h4>
                <ul class="awa-footer__links">
                    <li>Dakar, Sénégal</li>
                    <li><a href="tel:+221783793197">+221 78 379 31 97</a></li>
                    <li><a href="https://wa.me/221783793197" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
                    <li><a href="mailto:contact@awabio.com">contact@awabio.com</a></li>
                </ul>
            </div>
        </div>

        <div class="awa-footer__bottom">
            <p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> AwA Bio Foods — www.awabio.com</p>
        </div>
    </footer>

    <a href="https://wa.me/221783793197" class="awa-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <span class="awa-whatsapp__ping" aria-hidden="true"></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.881 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
