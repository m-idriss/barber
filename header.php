<?php
if (!defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top Bar Premium -->
<?php $ba_status = ba_v201_current_status(); ?>
<div class="topbar">
    <div class="topbar__inner">
        <div class="topbar__left">
            <span class="topbar__status <?php echo $ba_status['is_open'] ? 'is-open' : 'is-closed'; ?>">
                <span class="topbar__dot"></span>
                <?php echo esc_html($ba_status['label']); ?>
            </span>
        </div>
        <div class="topbar__right">
            <?php
            $ba_phone         = get_theme_mod('ba_phone', '+33123456789');
            $ba_phone_display = get_theme_mod('ba_phone_display', '+33 1 23 45 67 89');
            $ba_maps_url      = get_theme_mod('ba_maps_url', 'https://maps.google.com');
            $ba_maps_label    = get_theme_mod('ba_maps_label', 'Paris');
            ?>
            <a href="tel:<?php echo esc_attr($ba_phone); ?>" class="topbar__link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <?php echo esc_html($ba_phone_display); ?>
            </a>
            <span class="topbar__separator">·</span>
            <a href="<?php echo esc_url($ba_maps_url); ?>" target="_blank" rel="noopener noreferrer" class="topbar__link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?php echo esc_html($ba_maps_label); ?>
            </a>
        </div>
    </div>
</div>

<header class="site-header" id="siteHeader">
    <div class="site-header__inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php
            $logo_id = get_theme_mod('custom_logo') ?: ba_v201_attachment_by_file('2026/05/LOGO-DEFINITIF.jpg');
            if ($logo_id) {
                echo wp_get_attachment_image($logo_id, 'full', false, ['alt' => get_bloginfo('name')]);
            }
            ?>
            <div class="brand__text">
                <span class="brand__name"><?php bloginfo('name'); ?></span>
                <span class="brand__tagline"><?php esc_html_e('Barbering Excellence', 'barber-architecte-v201'); ?></span>
            </div>
        </a>

        <nav class="site-nav" aria-label="<?php esc_attr_e('Navigation principale', 'barber-architecte-v201'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
            ]);
            ?>
        </nav>

        <div class="site-header__actions">
            <a class="btn-header" href="#reservation">
                <span><?php esc_html_e('Réserver', 'barber-architecte-v201'); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>

            <button class="search-trigger" id="searchTrigger" aria-label="<?php esc_attr_e('Rechercher', 'barber-architecte-v201'); ?>" aria-expanded="false" aria-controls="searchOverlay">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>

            <?php if (is_user_logged_in()): ?>
            <a href="<?php echo esc_url(home_url('/booking-my-account/')); ?>" class="header-login" aria-label="<?php esc_attr_e('Mon compte', 'barber-architecte-v201'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            <?php else: ?>
            <a href="<?php echo esc_url(home_url('/login/')); ?>" class="header-login" aria-label="<?php esc_attr_e('Connexion', 'barber-architecte-v201'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            <?php endif; ?>

            <button class="nav-burger" id="navBurger" aria-label="<?php esc_attr_e('Ouvrir le menu', 'barber-architecte-v201'); ?>" aria-expanded="false" aria-controls="mobileNav">
                <span class="nav-burger__line"></span>
                <span class="nav-burger__line"></span>
                <span class="nav-burger__line"></span>
            </button>
        </div>
    </div>
</header>

<!-- Search Overlay -->
<div class="search-overlay" id="searchOverlay" hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Recherche', 'barber-architecte-v201'); ?>">
    <div class="search-overlay__backdrop" id="searchBackdrop"></div>
    <div class="search-overlay__panel">
        <div class="search-overlay__header">
            <span class="search-overlay__label"><?php esc_html_e('Recherche', 'barber-architecte-v201'); ?></span>
            <button class="search-overlay__close" id="searchClose" aria-label="<?php esc_attr_e('Fermer la recherche', 'barber-architecte-v201'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form class="search-overlay__form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="search-overlay__field">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="search" name="s" id="searchInput" placeholder="<?php esc_attr_e('Rechercher…', 'barber-architecte-v201'); ?>" autocomplete="off" />
                <button type="submit" aria-label="<?php esc_attr_e('Lancer la recherche', 'barber-architecte-v201'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mobile-nav" id="mobileNav" aria-hidden="true">
    <div class="mobile-nav__backdrop" data-nav-close></div>
    <aside class="mobile-nav__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Menu mobile', 'barber-architecte-v201'); ?>">
        <div class="mobile-nav__header">
            <span class="mobile-nav__brand"><?php bloginfo('name'); ?></span>
            <button class="mobile-nav__close" data-nav-close aria-label="<?php esc_attr_e('Fermer le menu', 'barber-architecte-v201'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="mobile-nav__menu" aria-label="<?php esc_attr_e('Navigation mobile', 'barber-architecte-v201'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
            ]);
            ?>
        </nav>

        <div class="mobile-nav__footer">
            <a class="mobile-nav__cta" href="#reservation"><?php esc_html_e('Réserver maintenant', 'barber-architecte-v201'); ?></a>
            <?php if (is_user_logged_in()): ?>
            <a href="<?php echo esc_url(home_url('/booking-my-account/')); ?>" class="mobile-nav__login"><?php esc_html_e('Mon compte', 'barber-architecte-v201'); ?></a>
            <?php else: ?>
            <a href="<?php echo esc_url(home_url('/login/')); ?>" class="mobile-nav__login"><?php esc_html_e('Connexion', 'barber-architecte-v201'); ?></a>
            <?php endif; ?>
            <?php get_template_part('template-parts/social-links', null, ['wrapper_class' => 'mobile-nav__socials']); ?>
        </div>
    </aside>
</div>

<main>
