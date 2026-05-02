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
            <a href="#services"><?php esc_html_e('Services', 'barber-architecte-v201'); ?></a>
            <a class="btn" href="#reservation"><?php esc_html_e('Reserver', 'barber-architecte-v201'); ?></a>
        </nav>
    </div>
</header>
<main>
