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

/**
 * Horaires d'ouverture du salon
 * Format: jour de la semaine (0=Dimanche, 1=Lundi, ..., 6=Samedi) => ['09:00', '19:00'] ou null si fermé
 */
function ba_v201_business_hours(): array
{
    return [
        0 => null, // Dimanche - fermé
        1 => ['09:00', '19:00'], // Lundi
        2 => ['09:00', '19:00'], // Mardi
        3 => ['09:00', '19:00'], // Mercredi
        4 => ['09:00', '20:00'], // Jeudi
        5 => ['09:00', '20:00'], // Vendredi
        6 => ['10:00', '18:00'], // Samedi
    ];
}

/**
 * Récupère le statut actuel du salon (ouvert/fermé) et les horaires du jour
 */
function ba_v201_current_status(): array
{
    $timezone = new DateTimeZone(wp_timezone_string() ?: 'Europe/Paris');
    $now = new DateTime('now', $timezone);
    $day = (int) $now->format('w');
    $current_time = $now->format('H:i');

    $hours = ba_v201_business_hours();
    $today = $hours[$day] ?? null;

    if (!$today) {
        return [
            'is_open' => false,
            'label' => __('Fermé aujourd\'hui', 'barber-architecte-v201'),
            'hours' => null,
        ];
    }

    [$open, $close] = $today;
    $is_open = $current_time >= $open && $current_time < $close;

    return [
        'is_open' => $is_open,
        'label' => $is_open
            ? sprintf(__('Ouvert aujourd\'hui • %s - %s', 'barber-architecte-v201'), $open, $close)
            : sprintf(__('Fermé • Aujourd\'hui %s - %s', 'barber-architecte-v201'), $open, $close),
        'hours' => $today,
    ];
}

function ba_v201_github_latest_release(): ?array
{
    $cache_key = 'ba_v201_github_latest_release';
    $cached_release = get_site_transient($cache_key);
    if (is_array($cached_release)) {
        return $cached_release;
    }

    $response = wp_remote_get(
        'https://api.github.com/repos/m-idriss/barber/releases/latest',
        [
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url('/'),
            ],
            'timeout' => 10,
        ]
    );

    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return null;
    }

    $release = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($release) || empty($release['tag_name'])) {
        return null;
    }

    set_site_transient($cache_key, $release, HOUR_IN_SECONDS);
    return $release;
}

function ba_v201_github_release_package_url(array $release): string
{
    if (!empty($release['assets']) && is_array($release['assets'])) {
        foreach ($release['assets'] as $asset) {
            $download_url = $asset['browser_download_url'] ?? '';
            if (is_string($download_url) && str_ends_with(strtolower($download_url), '.zip')) {
                return $download_url;
            }
        }
    }

    $zipball_url = $release['zipball_url'] ?? '';
    return is_string($zipball_url) ? $zipball_url : '';
}

function ba_v201_check_for_github_theme_update($transient)
{
    if (!is_object($transient) || empty($transient->checked) || !is_array($transient->checked)) {
        return $transient;
    }

    $theme = wp_get_theme();
    $stylesheet = $theme->get_stylesheet();
    $current_version = $theme->get('Version');
    $release = ba_v201_github_latest_release();
    if (!$release) {
        return $transient;
    }

    $latest_version = ltrim((string) $release['tag_name'], 'vV');
    if ('' === $latest_version || version_compare($latest_version, $current_version, '<=')) {
        return $transient;
    }

    $package_url = ba_v201_github_release_package_url($release);
    if ('' === $package_url) {
        return $transient;
    }

    $transient->response[$stylesheet] = [
        'theme' => $stylesheet,
        'new_version' => $latest_version,
        'url' => !empty($release['html_url']) ? $release['html_url'] : $theme->get('ThemeURI'),
        'package' => $package_url,
        'requires' => $theme->get('RequiresWP'),
        'requires_php' => $theme->get('RequiresPHP'),
    ];

    return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'ba_v201_check_for_github_theme_update');

function ba_v201_clear_github_release_cache($upgrader, array $options): void
{
    if (($options['action'] ?? '') !== 'update' || ($options['type'] ?? '') !== 'theme') {
        return;
    }

    $themes = $options['themes'] ?? [];
    if (!is_array($themes) || !in_array(get_stylesheet(), $themes, true)) {
        return;
    }

    delete_site_transient('ba_v201_github_latest_release');
}
add_action('upgrader_process_complete', 'ba_v201_clear_github_release_cache', 10, 2);
