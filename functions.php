<?php

if (!defined('ABSPATH')) {
    exit;
}

function ba_v201_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height' => 120,
        'width' => 120,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'primary' => __('Menu principal', 'barber-architecte-v201'),
    ]);
}
add_action('after_setup_theme', 'ba_v201_setup');

function ba_v201_assets(): void
{
    wp_enqueue_style('ba-v201-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'ba_v201_assets');

function ba_v201_upload_url(string $file): string
{
    $uploads = wp_get_upload_dir();
    return trailingslashit($uploads['baseurl']) . ltrim($file, '/');
}

function ba_v201_attachment_by_file(string $file): int
{
    $query = new WP_Query([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => '_wp_attached_file',
                'value' => ltrim($file, '/'),
            ],
        ],
    ]);

    return $query->posts ? (int) $query->posts[0] : 0;
}

function ba_v201_salon_posts(string $post_type): array
{
    return get_posts([
        'post_type' => $post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ]);
}
