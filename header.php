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
<header class="site-header">
    <div class="site-header__inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php
            $logo_id = get_theme_mod('custom_logo') ?: ba_v201_attachment_by_file('2026/05/LOGO-DEFINITIF.jpg');
            if ($logo_id) {
                echo wp_get_attachment_image($logo_id, 'full', false, ['alt' => get_bloginfo('name')]);
            }
            ?>
            <span><?php bloginfo('name'); ?></span>
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
            <a class="btn" href="#reservation"><?php esc_html_e('Reserver', 'barber-architecte-v201'); ?></a>
        </nav>

        <button class="nav-burger" id="navBurger" aria-label="<?php esc_attr_e('Ouvrir le menu', 'barber-architecte-v201'); ?>" aria-expanded="false" aria-controls="mobileNav">
            <span class="nav-burger__line"></span>
            <span class="nav-burger__line"></span>
            <span class="nav-burger__line"></span>
        </button>
    </div>
</header>

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
            <div class="mobile-nav__socials">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.117.6c-.69.263-1.296.577-1.933 1.214-.637.637-.951 1.243-1.214 1.933-.267.788-.468 1.659-.527 3.937C.004 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 2.278.261 3.149.527 3.937.263.69.577 1.296 1.214 1.933.637.637 1.243.951 1.933 1.214.788.267 1.659.468 3.937.527C8.333 23.996 8.74 24 12 24s3.667-.015 4.947-.072c2.278-.06 3.149-.261 3.937-.527.69-.263 1.296-.577 1.933-1.214.637-.637.951-1.243 1.214-1.933.267-.788.468-1.659.527-3.937.058-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-2.278-.261-3.149-.527-3.937-.263-.69-.577-1.296-1.214-1.933C21.319 1.27 20.713.957 20.023.694c-.788-.267-1.659-.468-3.937-.527C15.667.004 15.26 0 12 0zm0 2.16c3.203 0 3.585.009 4.849.070 1.171.054 1.805.244 2.227.408.56.217.96.477 1.382.896.419.42.679.822.896 1.381.164.422.354 1.057.408 2.227.061 1.264.07 1.646.07 4.849s-.009 3.585-.07 4.849c-.054 1.171-.244 1.805-.408 2.227-.217.56-.477.96-.896 1.382-.42.419-.822.679-1.381.896-.422.164-1.057.354-2.227.408-1.264.061-1.646.07-4.849.07s-3.585-.009-4.849-.07c-1.171-.054-1.805-.244-2.227-.408-.56-.217-.96-.477-1.382-.896-.419-.42-.679-.822-.896-1.381-.164-.422-.354-1.057-.408-2.227-.061-1.264-.07-1.646-.07-4.849s.009-3.585.07-4.849c.054-1.171.244-1.805.408-2.227.217-.56.477-.96.896-1.382.42-.419.822-.679 1.381-.896.422-.164 1.057-.354 2.227-.408 1.264-.061 1.646-.07 4.849-.07z"/></svg>
                </a>
                <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.498 3.75c1.73 0 3.248.755 4.276 1.934.706-.135 1.413-.302 2.113-.495v3.975c-.594.092-1.176.27-1.733.524 1.025 1.043 1.655 2.481 1.655 4.084 0 3.314-2.686 6-6 6a5.993 5.993 0 01-4.873-2.43c-1.247.996-2.84 1.597-4.582 1.597-3.865 0-7-3.135-7-7 0-3.762 2.955-6.834 6.628-6.99.116-1.255.632-2.388 1.387-3.305C6.256 1.055 5.27.744 4.25.744 2.188.744.5 2.432.5 4.494v14.012c0 2.063 1.688 3.75 3.75 3.75.949 0 1.817-.357 2.475-.939 1.09 1.038 2.59 1.689 4.275 1.689 3.314 0 6-2.686 6-6 0-.656-.106-1.286-.3-1.885.68-.598 1.51-.92 2.297-.92.829 0 1.593.267 2.196.706v-5.06c-.48.104-.988.16-1.498.16z"/></svg>
                </a>
            </div>
        </div>
    </aside>
</div>

<script>
(function() {
    const burger = document.getElementById('navBurger');
    const nav = document.getElementById('mobileNav');
    if (!burger || !nav) return;

    const closeButtons = nav.querySelectorAll('[data-nav-close]');
    const menuLinks = nav.querySelectorAll('a');

    function openNav() {
        nav.classList.add('is-open');
        burger.classList.add('is-active');
        burger.setAttribute('aria-expanded', 'true');
        nav.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeNav() {
        nav.classList.remove('is-open');
        burger.classList.remove('is-active');
        burger.setAttribute('aria-expanded', 'false');
        nav.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    burger.addEventListener('click', function() {
        nav.classList.contains('is-open') ? closeNav() : openNav();
    });

    closeButtons.forEach(btn => btn.addEventListener('click', closeNav));
    menuLinks.forEach(link => link.addEventListener('click', closeNav));

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) {
            closeNav();
        }
    });
})();
</script>

<main>
