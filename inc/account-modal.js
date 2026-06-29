/**
 * account-modal.js — Modal connexion / inscription AwA Bio Foods
 *
 * Comportement :
 *  - Intercepte le clic sur #awa-account-btn (icône utilisateur dans le header)
 *  - Ouvre la modal #awa-account-modal si l'utilisateur n'est pas connecté
 *  - Gère deux onglets : Se connecter / Créer un compte
 *  - Fermeture : bouton ×, clic sur l'overlay, touche Escape
 *  - Fallback no-JS : l'attribut href pointe déjà vers la page Mon compte
 *
 * @package awa-child
 */
(function () {
    'use strict';

    /* ── Sélecteurs ──────────────────────────────────────────── */
    var modal    = document.getElementById('awa-account-modal');
    var btn      = document.getElementById('awa-account-btn');
    var overlay  = document.getElementById('awa-modal-overlay');
    var closeBtn = document.getElementById('awa-modal-close');
    var tabLogin    = document.getElementById('awa-tab-login');
    var tabRegister = document.getElementById('awa-tab-register');

    /* Aucun modal dans le DOM → utilisateur connecté ou WC absent */
    if (!modal || !btn) return;

    /* ── Détection des sections WooCommerce dans la modal ────── */
    /* WC's form-login.php enveloppe login dans .u-column1 et register dans .u-column2 */
    var loginForm    = modal.querySelector('.woocommerce-form-login');
    var registerForm = modal.querySelector('.woocommerce-form-register');

    /* Remonte jusqu'au wrapper colonne ou utilise le parent direct */
    var loginWrap    = loginForm    ? (loginForm.closest('.u-column1')    || loginForm.closest('.col-1')    || loginForm.parentElement) : null;
    var registerWrap = registerForm ? (registerForm.closest('.u-column2') || registerForm.closest('.col-2') || registerForm.parentElement) : null;

    /* Masque l'onglet "Créer un compte" si WC a désactivé l'inscription */
    if (!registerForm && tabRegister) {
        tabRegister.style.display = 'none';
    }

    /* ── Gestion des onglets ─────────────────────────────────── */
    function showPanel(panel) {
        var isLogin = panel === 'login';

        /* Affiche / masque les sections WC */
        if (loginWrap)    loginWrap.style.display    = isLogin ? '' : 'none';
        if (registerWrap) registerWrap.style.display = isLogin ? 'none' : '';

        /* Met à jour les états ARIA des onglets */
        if (tabLogin) {
            tabLogin.classList.toggle('is-active', isLogin);
            tabLogin.setAttribute('aria-selected', isLogin ? 'true' : 'false');
        }
        if (tabRegister) {
            tabRegister.classList.toggle('is-active', !isLogin);
            tabRegister.setAttribute('aria-selected', isLogin ? 'false' : 'true');
        }
    }

    /* ── Ouverture / fermeture ───────────────────────────────── */
    function openModal() {
        showPanel('login');
        modal.hidden = false;
        document.body.classList.add('awa-modal-open');
        /* Donne le focus à la boîte pour l'accessibilité */
        var box = modal.querySelector('.awa-account-modal__box');
        if (box) box.focus();
    }

    function closeModal() {
        modal.hidden = true;
        document.body.classList.remove('awa-modal-open');
        /* Rend le focus au bouton déclencheur */
        if (btn) btn.focus();
    }

    /* ── Événements ──────────────────────────────────────────── */

    /* Clic sur l'icône compte : ouvre la modal (remplace la navigation par défaut) */
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        openModal();
    });

    /* Fermeture via overlay */
    if (overlay) {
        overlay.addEventListener('click', closeModal);
    }

    /* Fermeture via bouton × */
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    /* Fermeture via la touche Escape */
    document.addEventListener('keydown', function (e) {
        if ((e.key === 'Escape' || e.keyCode === 27) && !modal.hidden) {
            closeModal();
        }
    });

    /* Clic onglet "Se connecter" */
    if (tabLogin) {
        tabLogin.addEventListener('click', function () { showPanel('login'); });
    }

    /* Clic onglet "Créer un compte" */
    if (tabRegister) {
        tabRegister.addEventListener('click', function () { showPanel('register'); });
    }

    /* ── Initialisation : affiche le panneau connexion par défaut ── */
    showPanel('login');

})();
