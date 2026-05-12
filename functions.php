<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('BA_V201_GITHUB_REPOSITORY')) {
    define('BA_V201_GITHUB_REPOSITORY', 'm-idriss/barber');
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
    $version = wp_get_theme()->get('Version');
    $dir     = get_template_directory_uri();

    wp_enqueue_style('ba-v201-style', get_stylesheet_uri(), [], $version);
    wp_enqueue_script('ba-v201-header', $dir . '/assets/js/header.js', [], $version, true);
    wp_enqueue_script('ba-v201-booking-widget', $dir . '/assets/js/booking-widget.js', ['ba-v201-header'], $version, true);
    wp_enqueue_script('ba-v201-booking-attendant', $dir . '/assets/js/booking-attendant.js', [], $version, true);
    wp_localize_script('ba-v201-booking-attendant', 'baAttendant', [
        'cookiePath' => COOKIEPATH ?: '/',
    ]);
}
add_action('wp_enqueue_scripts', 'ba_v201_assets');

function ba_v201_github_repository(): string
{
    $repository = (string) apply_filters('ba_v201_github_repository', BA_V201_GITHUB_REPOSITORY);
    return '' !== $repository ? $repository : BA_V201_GITHUB_REPOSITORY;
}

function ba_v201_github_repository_url(): string
{
    return 'https://github.com/' . ba_v201_github_repository();
}

function ba_v201_github_latest_release_api_url(): string
{
    return 'https://api.github.com/repos/' . ba_v201_github_repository() . '/releases/latest';
}

function ba_v201_github_release_cache_ttl(): int
{
    $ttl = (int) apply_filters('ba_v201_github_release_cache_ttl', 12 * HOUR_IN_SECONDS);
    return $ttl > 0 ? $ttl : 12 * HOUR_IN_SECONDS;
}

function ba_v201_upload_url(string $file): string
{
    $uploads = wp_get_upload_dir();
    return trailingslashit($uploads['baseurl']) . ltrim($file, '/');
}

function ba_v201_hero_image_url(): string
{
    return ba_v201_upload_url('2026/05/barber-hero-v2-flipped.png');
}

function ba_v201_contact_defaults(): array
{
    return [
        'phone' => '+33123456789',
        'phone_display' => '+33 1 23 45 67 89',
        'email' => 'contact@barberlarchitecte.com',
        'address_line1' => '123 Rue de la Coupe',
        'address_line2' => '75000 Paris',
        'maps_url' => 'https://maps.google.com',
        'maps_label' => 'Paris',
        'social_facebook' => 'https://facebook.com',
        'social_instagram' => 'https://instagram.com',
        'social_tiktok' => 'https://tiktok.com',
    ];
}

function ba_v201_contact_setting(string $key): string
{
    $defaults = ba_v201_contact_defaults();
    $theme_mod_key = 'ba_' . $key;

    return (string) get_theme_mod($theme_mod_key, $defaults[$key] ?? '');
}

function ba_v201_contact_settings(): array
{
    $settings = [];
    foreach (array_keys(ba_v201_contact_defaults()) as $key) {
        $settings[$key] = ba_v201_contact_setting($key);
    }

    return $settings;
}

function ba_v201_hero_background_style(?string $image_url = null): string
{
    $image_url = $image_url ?: ba_v201_hero_image_url();

    return sprintf('background-image: url(%s)', esc_url($image_url));
}

function ba_v201_account_url(): string
{
    return is_user_logged_in()
        ? home_url('/booking-my-account/')
        : home_url('/login/');
}

function ba_v201_account_label(): string
{
    return is_user_logged_in()
        ? __('Mon compte', 'barber-architecte-v201')
        : __('Connexion', 'barber-architecte-v201');
}

function ba_v201_render_primary_menu(): void
{
    wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'fallback_cb' => false,
        'items_wrap' => '%3$s',
        'depth' => 1,
    ]);
}

function ba_v201_logo_id(): int
{
    return (int) (get_theme_mod('custom_logo') ?: ba_v201_attachment_by_file('2026/05/LOGO-DEFINITIF.jpg'));
}

function ba_v201_render_logo(array $attributes = []): void
{
    $logo_id = ba_v201_logo_id();
    if (!$logo_id) {
        return;
    }

    $attributes = array_merge(['alt' => get_bloginfo('name')], $attributes);
    echo wp_get_attachment_image($logo_id, 'full', false, $attributes);
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
    if (!post_type_exists($post_type)) {
        return [];
    }

    return get_posts([
        'post_type' => $post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ]);
}

function ba_v201_salon_booking_url(): string
{
    if (!class_exists('SLN_Plugin')) {
        return home_url('/booking/');
    }

    try {
        $plugin = SLN_Plugin::getInstance();
        $page_id = $plugin->getSettings()->getPayPageId();
    } catch (Throwable $e) {
        return home_url('/booking/');
    }

    $url = $page_id ? get_permalink($page_id) : false;
    return $url ? $url : home_url('/booking/');
}

function ba_v201_render_salon_shortcode(): void
{
    if (shortcode_exists('salon')) {
        echo do_shortcode('[salon]');
        return;
    }

    printf(
        '<a class="btn" href="%s">%s</a>',
        esc_url(admin_url('admin.php?page=salon')),
        esc_html__('Ouvrir Salon Booking', 'barber-architecte-v201')
    );
}

function ba_v201_service_booking_url(WP_Post $service, string $booking_url = ''): string
{
    return add_query_arg(
        [
            'action'                 => 'salon-booking-services-book-now',
            'service'                => $service->ID,
            'skip_service_selection' => 1,
            'secondary'              => 0,
        ],
        '' !== $booking_url ? $booking_url : ba_v201_salon_booking_url()
    );
}

function ba_v201_attendant_booking_url(WP_Post $attendant, string $booking_url = ''): string
{
    return add_query_arg(
        ['sln_book_attendant' => $attendant->ID],
        '' !== $booking_url ? $booking_url : ba_v201_salon_booking_url()
    );
}

function ba_v201_render_attendant_picker(array $attendants, string $booking_url = '', int $limit = 0): void
{
    $items = $limit > 0 ? array_slice($attendants, 0, $limit) : $attendants;
    ?>
    <div class="hero-team" aria-label="<?php esc_attr_e('Choisir un barber', 'barber-architecte-v201'); ?>">
        <?php foreach ($items as $attendant) :
            if (!$attendant instanceof WP_Post) {
                continue;
            }

            $excerpt = $attendant->post_excerpt ?: '';
            ?>
            <a class="hero-barber" href="<?php echo esc_url(ba_v201_attendant_booking_url($attendant, $booking_url)); ?>">
                <?php echo get_the_post_thumbnail($attendant, 'thumbnail', ['loading' => 'eager', 'decoding' => 'async']); ?>
                <span>
                    <?php echo esc_html(get_the_title($attendant)); ?>
                    <?php if ($excerpt) : ?>
                        <small><?php echo esc_html($excerpt); ?></small>
                    <?php endif; ?>
                </span>
                <strong><?php esc_html_e('Choisir', 'barber-architecte-v201'); ?></strong>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
}

function ba_v201_render_service_card(WP_Post $service, string $booking_url = ''): void
{
    $price    = get_post_meta($service->ID, '_sln_service_price', true);
    $duration = get_post_meta($service->ID, '_sln_service_duration', true);
    ?>
    <article class="service-card">
        <?php if (has_post_thumbnail($service->ID)) : ?>
            <div class="service-card__image">
                <?php echo get_the_post_thumbnail($service->ID, 'medium', ['loading' => 'lazy', 'decoding' => 'async']); ?>
            </div>
        <?php endif; ?>
        <div>
            <h3><?php echo esc_html(get_the_title($service)); ?></h3>
            <?php if ($service->post_excerpt) : ?>
                <p><?php echo esc_html($service->post_excerpt); ?></p>
            <?php endif; ?>
        </div>
        <div class="service-card__meta">
            <span><?php echo esc_html($duration ?: ''); ?></span>
            <?php if ($price !== '') : ?>
                <strong><?php echo esc_html($price . ' EUR'); ?></strong>
            <?php endif; ?>
        </div>
        <a href="<?php echo esc_url(ba_v201_service_booking_url($service, $booking_url)); ?>" class="service-card__cta">
            <?php esc_html_e('Réserver', 'barber-architecte-v201'); ?>
        </a>
    </article>
    <?php
}

/**
 * Horaires d'ouverture du salon
 * Format: jour de la semaine (0=Dimanche, 1=Lundi, ..., 6=Samedi) => ['09:00', '19:00'] ou null si fermé
 */
function ba_v201_business_hours(): array
{
    return [
        0 => null,               // Dimanche - fermé
        1 => null,               // Lundi - fermé
        2 => ['09:00', '18:30'], // Mardi
        3 => ['09:00', '18:30'], // Mercredi
        4 => ['09:00', '18:30'], // Jeudi
        5 => ['09:00', '18:30'], // Vendredi
        6 => ['09:00', '18:30'], // Samedi
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

    $theme_slug = sanitize_key(get_stylesheet());
    $response = wp_remote_get(
        ba_v201_github_latest_release_api_url(),
        [
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'User-Agent' => $theme_slug . '-updater WordPress/' . get_bloginfo('version'),
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

    set_site_transient($cache_key, $release, ba_v201_github_release_cache_ttl());
    return $release;
}

function ba_v201_github_release_package_url(array $release, string $theme_slug): string
{
    $fallback_zip_url = '';
    if (!empty($release['assets']) && is_array($release['assets'])) {
        foreach ($release['assets'] as $asset) {
            $asset_name = strtolower((string) ($asset['name'] ?? ''));
            $download_url = $asset['browser_download_url'] ?? null;
            if (!is_string($download_url) || '' === $download_url) {
                continue;
            }

            if (str_ends_with(strtolower($download_url), '.zip')) {
                if ('' === $fallback_zip_url) {
                    $fallback_zip_url = $download_url;
                }
                if (str_contains($asset_name, strtolower($theme_slug))) {
                    return $download_url;
                }
            }
        }
    }

    if ('' !== $fallback_zip_url) {
        return $fallback_zip_url;
    }

    $zipball_url = (string) ($release['zipball_url'] ?? '');
    return '' !== $zipball_url ? $zipball_url : '';
}

function ba_v201_release_version_from_tag(string $tag): string
{
    if (preg_match('/^[vV]?(\d+(?:\.\d+)*)/', trim($tag), $matches)) {
        return $matches[1];
    }

    return '';
}

function ba_v201_check_for_github_theme_update(mixed $transient): mixed
{
    if (!is_object($transient) || empty($transient->checked) || !is_array($transient->checked)) {
        return $transient;
    }

    $theme = wp_get_theme(get_stylesheet());
    $stylesheet = $theme->get_stylesheet();
    $current_version = $theme->get('Version');
    $release = ba_v201_github_latest_release();
    if (!$release) {
        return $transient;
    }

    $latest_version = ba_v201_release_version_from_tag((string) $release['tag_name']);
    if ('' === $latest_version || version_compare($latest_version, $current_version, '<=')) {
        return $transient;
    }

    $package_url = ba_v201_github_release_package_url($release, $stylesheet);
    if ('' === $package_url) {
        return $transient;
    }
    $release_url = !empty($release['html_url']) ? (string) $release['html_url'] : '';
    if ('' === $release_url && !empty($release['tag_name'])) {
        $release_url = ba_v201_github_repository_url() . '/releases/tag/' . rawurlencode((string) $release['tag_name']);
    }

    $transient->response[$stylesheet] = [
        'theme' => $stylesheet,
        'new_version' => $latest_version,
        'url' => '' !== $release_url ? $release_url : ba_v201_github_repository_url(),
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

/**
 * After logout, redirect to the login page template with ?loggedout=true
 * so the user sees a branded confirmation instead of wp-login.php.
 */
function ba_v201_logout_redirect(): void
{
    $login_page = get_page_by_path('login');
    if (!$login_page) {
        // Fallback: find any page using the login template
        $pages = get_posts([
            'post_type'  => 'page',
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'page-login.php',
            'numberposts' => 1,
            'fields'     => 'ids',
        ]);
        if ($pages) {
            $login_page = get_post($pages[0]);
        }
    }

    if ($login_page) {
        wp_redirect(add_query_arg('loggedout', 'true', get_permalink($login_page->ID)));
        exit;
    }
}
add_action('wp_logout', 'ba_v201_logout_redirect');

/**
 * Style wp-login.php to match the barbershop theme.
 */
function ba_v201_login_styles(): void
{
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('ba-v201-login', get_template_directory_uri() . '/assets/css/login.css', [], $version);
    wp_add_inline_style('ba-v201-login', sprintf(
        '#login h1 a::after { content: "%s"; display: block; font-size: 1.1rem; font-weight: 700; letter-spacing: 0.05em; color: var(--ba-text); }',
        esc_attr(get_bloginfo('name'))
    ));
}
add_action('login_enqueue_scripts', 'ba_v201_login_styles');

/**
 * Make the login logo link go to the site homepage instead of wordpress.org.
 */
add_filter('login_headerurl', fn() => home_url('/'));
add_filter('login_headertext', fn() => get_bloginfo('name'));



/**
 * Auto-create required pages on theme activation.
 * Skips creation if a page with the same slug already exists.
 */
function ba_v201_create_starter_pages(): void
{
    $pages = [
        [
            'title'    => 'Accueil',
            'slug'     => 'accueil',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Services',
            'slug'     => 'services',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Réservation',
            'slug'     => 'booking',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Assistants',
            'slug'     => 'assistants',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Connexion',
            'slug'     => 'login',
            'template' => 'page-login.php',
            'content'  => '',
        ],
        [
            'title'    => 'Mon compte réservation',
            'slug'     => 'booking-my-account',
            'template' => 'default',
            'content'  => '[salon_booking_my_account]',
        ],
        [
            'title'    => 'Actualités',
            'slug'     => 'actualites',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'À propos',
            'slug'     => 'a-propos',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Contact',
            'slug'     => 'contact',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Mentions légales',
            'slug'     => 'mentions-legales',
            'template' => 'default',
            'content'  => '',
        ],
        [
            'title'    => 'Politique de confidentialité',
            'slug'     => 'politique-confidentialite',
            'template' => 'default',
            'content'  => '',
        ],
    ];

    foreach ($pages as $page) {
        $existing = get_posts([
            'name'        => $page['slug'],
            'post_type'   => 'page',
            'post_status' => ['publish', 'future', 'draft', 'pending', 'private'],
            'numberposts' => 1,
            'fields'      => 'ids',
        ]);
        if (!empty($existing)) {
            continue;
        }

        $id = wp_insert_post([
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_content' => $page['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);

        if ($id && !is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', $page['template']);
        }
    }
}
add_action('after_switch_theme', 'ba_v201_create_starter_pages');
add_action('init', 'ba_v201_create_starter_pages');

/**
 * Keep the primary navigation aligned with the starter pages.
 */
function ba_v201_sync_primary_menu(): void
{
    $menu_version = '20260510-2';
    if (get_option('ba_v201_primary_menu_version') === $menu_version) {
        return;
    }

    $menu = wp_get_nav_menu_object('primary');
    if (!$menu) {
        $menu_id = wp_create_nav_menu('primary');
    } else {
        $menu_id = (int) $menu->term_id;
    }

    if (!$menu_id || is_wp_error($menu_id)) {
        return;
    }

    $locations = (array) get_theme_mod('nav_menu_locations', []);
    if (($locations['primary'] ?? 0) !== $menu_id) {
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    $existing_items = wp_get_nav_menu_items($menu_id, ['post_status' => 'any']);
    if ($existing_items) {
        foreach ($existing_items as $item) {
            wp_delete_post((int) $item->ID, true);
        }
    }

    $items = [
        ['label' => 'Accueil', 'url' => home_url('/')],
        ['label' => 'Services', 'slug' => 'services'],
        ['label' => 'Coiffeurs', 'slug' => 'assistants'],
        ['label' => 'Réservation', 'slug' => 'booking'],
        ['label' => 'Contact', 'slug' => 'contact'],
    ];

    foreach ($items as $position => $item) {
        if (!empty($item['url'])) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title'    => $item['label'],
                'menu-item-url'      => $item['url'],
                'menu-item-type'     => 'custom',
                'menu-item-status'   => 'publish',
                'menu-item-position' => $position + 1,
            ]);
            continue;
        }

        $pages = get_posts([
            'name'        => $item['slug'],
            'post_type'   => 'page',
            'post_status' => 'publish',
            'numberposts' => 1,
            'fields'      => 'ids',
        ]);

        if (empty($pages)) {
            continue;
        }

        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'     => $item['label'],
            'menu-item-object-id' => (int) $pages[0],
            'menu-item-object'    => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish',
            'menu-item-position'  => $position + 1,
        ]);
    }

    update_option('ba_v201_primary_menu_version', $menu_version, false);
}
add_action('after_switch_theme', 'ba_v201_sync_primary_menu', 20);
add_action('init', 'ba_v201_sync_primary_menu', 20);

/**
 * Register Customizer settings for contact info and social URLs.
 */
function ba_v201_customizer(WP_Customize_Manager $wp_customize): void
{
    $wp_customize->add_section('ba_contact', [
        'title'    => __('Contact & Réseaux sociaux', 'barber-architecte-v201'),
        'priority' => 30,
    ]);

    $defaults = ba_v201_contact_defaults();
    $fields = [
        'ba_phone'            => [__('Téléphone (lien tel:)', 'barber-architecte-v201'), $defaults['phone'], 'sanitize_text_field'],
        'ba_phone_display'    => [__('Téléphone (affiché)', 'barber-architecte-v201'), $defaults['phone_display'], 'sanitize_text_field'],
        'ba_email'            => [__('Email', 'barber-architecte-v201'), $defaults['email'], 'sanitize_email'],
        'ba_address_line1'    => [__('Adresse (ligne 1)', 'barber-architecte-v201'), $defaults['address_line1'], 'sanitize_text_field'],
        'ba_address_line2'    => [__('Adresse (ligne 2)', 'barber-architecte-v201'), $defaults['address_line2'], 'sanitize_text_field'],
        'ba_maps_url'         => [__('Lien Google Maps', 'barber-architecte-v201'), $defaults['maps_url'], 'esc_url_raw'],
        'ba_maps_label'       => [__('Ville (topbar)', 'barber-architecte-v201'), $defaults['maps_label'], 'sanitize_text_field'],
        'ba_social_facebook'  => [__('URL Facebook', 'barber-architecte-v201'), $defaults['social_facebook'], 'esc_url_raw'],
        'ba_social_instagram' => [__('URL Instagram', 'barber-architecte-v201'), $defaults['social_instagram'], 'esc_url_raw'],
        'ba_social_tiktok'    => [__('URL TikTok', 'barber-architecte-v201'), $defaults['social_tiktok'], 'esc_url_raw'],
    ];

    foreach ($fields as $id => [$label, $default, $sanitize]) {
        $wp_customize->add_setting($id, [
            'default'           => $default,
            'sanitize_callback' => $sanitize,
        ]);
        $wp_customize->add_control($id, [
            'label'   => $label,
            'section' => 'ba_contact',
            'type'    => 'text',
        ]);
    }
}
add_action('customize_register', 'ba_v201_customizer');

/**
 * Render business hours for the footer, grouped by identical schedules.
 */
function ba_v201_render_footer_hours(): string
{
    $hours    = ba_v201_business_hours();
    $day_names = [
        0 => __('Dimanche', 'barber-architecte-v201'),
        1 => __('Lundi', 'barber-architecte-v201'),
        2 => __('Mardi', 'barber-architecte-v201'),
        3 => __('Mercredi', 'barber-architecte-v201'),
        4 => __('Jeudi', 'barber-architecte-v201'),
        5 => __('Vendredi', 'barber-architecte-v201'),
        6 => __('Samedi', 'barber-architecte-v201'),
    ];

    // Mon→Sun order so Sunday appears last
    $ordered = [1, 2, 3, 4, 5, 6, 0];
    $groups  = [];
    $prev_key = null;

    foreach ($ordered as $day) {
        $schedule = $hours[$day] ?? null;
        $key      = $schedule ? implode('-', $schedule) : 'closed';
        if ($key !== $prev_key) {
            $groups[] = ['key' => $key, 'schedule' => $schedule, 'days' => [$day]];
        } else {
            $groups[array_key_last($groups)]['days'][] = $day;
        }
        $prev_key = $key;
    }

    $html = '<div class="footer-hours-premium">';
    foreach ($groups as $group) {
        $label     = implode(' • ', array_map(fn($d) => $day_names[$d], $group['days']));
        $is_closed = $group['key'] === 'closed';
        $html     .= '<div class="hour-row' . ($is_closed ? ' closed' : '') . '">';
        $html     .= '<span class="hour-label">' . esc_html($label) . '</span>';
        if ($is_closed) {
            $html .= '<span class="hour-value">' . esc_html__('Repos', 'barber-architecte-v201') . '</span>';
        } else {
            [$open, $close] = $group['schedule'];
            $html .= '<span class="hour-value">' . esc_html($open) . ' <span class="hour-dash">–</span> ' . esc_html($close) . '</span>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
}

/**
 * Pre-select attendant when clicking "Book now" from the assistants page.
 * Clears booking session, stores preferred attendant in a cookie,
 * then redirects to the booking form at the services step.
 */
function ba_v201_attendant_redirect(): void
{
    if (empty($_GET['sln_book_attendant']) || !is_numeric($_GET['sln_book_attendant'])) {
        return;
    }
    $attendant_id = intval($_GET['sln_book_attendant']);
    if (!$attendant_id) {
        return;
    }

    if (!class_exists('SLN_Plugin')) {
        wp_safe_redirect(home_url('/booking/'));
        exit;
    }

    $plugin = SLN_Plugin::getInstance();
    $bb     = $plugin->getBookingBuilder();
    $bb->clear();
    $bb->save();

    setcookie('sln_pref_att', $attendant_id, time() + 3600, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '', is_ssl(), false);

    $booking_url = add_query_arg(
        ['sln_step_page' => 'services'],
        get_permalink($plugin->getSettings()->getPayPageId())
    );
    wp_safe_redirect($booking_url);
    exit;
}
add_action('wp_loaded', 'ba_v201_attendant_redirect');
