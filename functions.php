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
    wp_enqueue_style('ba-v201-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
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
    ?>
    <style>
        :root {
            --ba-bg:         #0e0f0f;
            --ba-panel:      #171918;
            --ba-panel-soft: #20231f;
            --ba-text:       #f4f0e8;
            --ba-muted:      #beb6a8;
            --ba-line:       rgba(244,240,232,0.16);
            --ba-gold:       #c8a45d;
            --ba-rust:       #8d3f30;
            --ba-ink:        #15120b;
        }

        body.login {
            background: var(--ba-bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Logo area */
        #login h1 a {
            background-image: none !important;
            background-color: transparent;
            width: auto;
            height: auto;
            font-size: 2rem;
            color: var(--ba-gold);
            text-indent: 0;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        #login h1 a::before {
            content: '✂';
            font-size: 2.5rem;
            display: block;
        }

        #login h1 a::after {
            content: '<?php echo esc_js(get_bloginfo("name")); ?>';
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--ba-text);
        }

        /* Card */
        #loginform,
        #lostpasswordform {
            background: var(--ba-panel) !important;
            border: 1px solid var(--ba-line) !important;
            border-radius: 14px !important;
            box-shadow: 0 8px 40px rgba(0,0,0,0.5) !important;
            padding: 2rem !important;
        }

        /* Labels */
        #loginform label,
        #lostpasswordform label {
            color: var(--ba-muted) !important;
            font-size: 0.78rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
        }

        /* Inputs */
        #loginform input[type="text"],
        #loginform input[type="password"],
        #lostpasswordform input[type="text"] {
            background: var(--ba-panel-soft) !important;
            border: 1px solid var(--ba-line) !important;
            border-radius: 8px !important;
            color: var(--ba-text) !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem !important;
            box-shadow: none !important;
            outline: none !important;
        }

        #loginform input[type="text"]:focus,
        #loginform input[type="password"]:focus,
        #lostpasswordform input[type="text"]:focus {
            border-color: var(--ba-gold) !important;
            box-shadow: 0 0 0 1px var(--ba-gold) !important;
        }

        /* Submit button */
        #loginform .button-primary,
        #lostpasswordform .button-primary,
        input#wp-submit {
            background: var(--ba-gold) !important;
            border: none !important;
            border-radius: 8px !important;
            color: var(--ba-ink) !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.04em !important;
            padding: 0.85rem 1.5rem !important;
            box-shadow: none !important;
            text-shadow: none !important;
            width: 100% !important;
            height: auto !important;
            transition: background 0.2s !important;
        }

        #loginform .button-primary:hover,
        input#wp-submit:hover {
            background: #d4b06a !important;
        }

        /* Remember me */
        .forgetmenot label {
            color: var(--ba-muted) !important;
            font-size: 0.85rem !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
        }

        /* Links below the form */
        #nav a, #backtoblog a {
            color: var(--ba-muted) !important;
            font-size: 0.85rem !important;
            text-decoration: none !important;
        }

        #nav a:hover, #backtoblog a:hover {
            color: var(--ba-gold) !important;
        }

        #nav, #backtoblog {
            text-align: center;
        }

        /* Error / info messages */
        #login_error,
        .message {
            background: rgba(141,63,48,0.15) !important;
            border-left: 4px solid var(--ba-rust) !important;
            border-radius: 8px !important;
            color: #e08070 !important;
            box-shadow: none !important;
        }

        .message {
            background: rgba(200,164,93,0.1) !important;
            border-left-color: var(--ba-gold) !important;
            color: var(--ba-muted) !important;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'ba_v201_login_styles');

/**
 * Make the login logo link go to the site homepage instead of wordpress.org.
 */
add_filter('login_headerurl', fn() => home_url('/'));
add_filter('login_headertext', fn() => get_bloginfo('name'));

/**
 * Late booking widget polish for Elementor/live pages.
 * Printed after enqueued styles so it can override Elementor generated CSS.
 */
function ba_v201_live_booking_overrides(): void
{
    ?>
    <style id="ba-v201-live-booking-overrides">
        body #sln-salon,
        body .sln-bootstrap,
        body .ba-live-booking-widget,
        body .ba-live-booking-widget > .elementor-widget-container {
            border-radius: var(--ba-radius-xl) !important;
        }

        body .ba-live-booking-widget > .elementor-widget-container,
        body .ba-live-booking-shell {
            position: relative !important;
            overflow: hidden !important;
            border: 2px solid var(--ba-gold-30) !important;
            border-radius: var(--ba-radius-xl) !important;
            background: linear-gradient(180deg, var(--ba-white-08), transparent 44%), var(--ba-panel) !important;
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.42), 0 0 0 1px rgba(200, 164, 93, 0.08) inset !important;
        }

        body .ba-live-booking-widget > .elementor-widget-container::before,
        body .ba-live-booking-shell::before {
            content: "" !important;
            display: block !important;
            height: 4px !important;
            background: linear-gradient(90deg, var(--ba-gold), var(--ba-rust), var(--ba-green)) !important;
        }

        body #sln-salon #sln-salon__content,
        body .sln-bootstrap #sln-salon__content,
        body .sln-salon--m__content {
            background: transparent !important;
        }

        body #sln-salon .sln-salon-title,
        body #sln-salon .salon-step-title {
            text-align: left !important;
        }

        body #sln-salon .sln-salon-title {
            color: var(--ba-white) !important;
            font-size: clamp(21px, 2.4vw, 30px) !important;
            letter-spacing: 0 !important;
        }

        body #sln-salon .salon-step-title {
            color: var(--ba-muted) !important;
            font-size: 14px !important;
        }

        body #sln-salon .sln-progbar {
            overflow: hidden !important;
            background: var(--ba-white-08) !important;
        }

        body #sln-salon .sln-progbar span,
        body #sln-salon .sln-progbar__bar {
            background: var(--ba-gold) !important;
        }

        body #sln-salon .sln-list__item,
        body #sln-salon .sln-panel {
            border-color: var(--ba-white-13) !important;
            background: var(--ba-white-06) !important;
        }

        body #sln-salon .sln-list__item:hover {
            border-color: var(--ba-gold-65) !important;
            background: var(--ba-gold-10) !important;
        }

        body #sln-salon .sln-list__item__name {
            color: var(--ba-white) !important;
        }

        body #sln-salon .sln-box__bottombar,
        body #sln-salon .sln-cart-footer,
        body #sln-salon .sln-form-actions,
        body #sln-salon .sln-step-actions,
        body #sln-salon .sln-summary-bar {
            border-top-color: var(--ba-white-13) !important;
            background: rgba(14, 15, 15, 0.78) !important;
        }

        body #sln-salon .sln-btn--emphasis,
        body #sln-salon .sln-btn--emphasis button,
        body #sln-salon button#sln-step-submit {
            border-radius: var(--ba-radius-lg) !important;
            box-shadow: 0 10px 24px rgba(200, 164, 93, 0.22) !important;
        }

        @media (max-width: 767px) {
            body .ba-live-booking-widget > .elementor-widget-container {
                overflow: visible !important;
                border: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
            }

            body .ba-live-booking-widget > .elementor-widget-container::before {
                display: none !important;
            }

            body #sln-salon #sln-salon__content,
            body .sln-salon--m__content {
                overflow: hidden !important;
                border: 2px solid var(--ba-gold-30) !important;
                box-shadow: var(--ba-shadow-sm) !important;
            }

            body.home.page-id-223 .ba-live-booking-widget > .elementor-widget-container {
                overflow: hidden !important;
                border: 1px solid var(--ba-gold-20) !important;
                border-radius: var(--ba-radius-md) !important;
                background: var(--ba-panel) !important;
                box-shadow: var(--ba-shadow-sm) !important;
            }

            body.home.page-id-223 #sln-salon,
            body.home.page-id-223 #sln-salon #sln-salon__content,
            body.home.page-id-223 .sln-salon--m__content {
                position: static !important;
                left: auto !important;
                width: 100% !important;
                max-width: 100% !important;
                margin-right: 0 !important;
                margin-left: 0 !important;
                overflow: visible !important;
                border: 0 !important;
                border-radius: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
            }

            body.home.page-id-223 #sln-salon #sln-salon__content,
            body.home.page-id-223 .sln-salon--m__content {
                padding: 10px !important;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'ba_v201_live_booking_overrides', 999);

function ba_v201_mark_live_booking_widget(): void
{
    ?>
    <script>
    (function () {
        function markBookingWidget() {
            var salon = document.getElementById('sln-salon') || document.querySelector('.sln-bootstrap');
            if (!salon) return false;

            salon.classList.add('ba-live-booking-shell');

            var widget = salon.closest('.elementor-widget') || salon.closest('.elementor-element') || salon.parentElement;
            if (widget) {
                widget.classList.add('ba-live-booking-widget');
            }

            return true;
        }

        function init() {
            if (markBookingWidget()) return;
            var attempts = 0;
            var timer = setInterval(function () {
                attempts++;
                if (markBookingWidget() || attempts > 40) {
                    clearInterval(timer);
                }
            }, 250);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
        window.addEventListener('load', markBookingWidget);
    })();
    </script>
    <?php
}
add_action('wp_footer', 'ba_v201_mark_live_booking_widget', 5);

/**
 * Auto-create required pages on theme activation.
 * Skips creation if a page with the same slug already exists.
 */
function ba_v201_create_starter_pages(): void
{
    $pages = [
        [
            'title'    => 'Connexion',
            'slug'     => 'login',
            'template' => 'page-login.php',
            'content'  => '',
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
        $existing = get_page_by_path($page['slug']);
        if ($existing) {
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

/**
 * Pre-select attendant when clicking "Book now" from the assistants page.
 * Clears booking session, stores preferred attendant in a cookie,
 * then redirects to the booking form at the services step.
 */
add_action('wp_loaded', function () {
    if (empty($_GET['sln_book_attendant']) || !is_numeric($_GET['sln_book_attendant'])) {
        return;
    }
    $attendant_id = intval($_GET['sln_book_attendant']);
    if (!$attendant_id) {
        return;
    }

    $plugin = SLN_Plugin::getInstance();
    $bb     = $plugin->getBookingBuilder();
    $bb->clear();
    $bb->save();

    // Store for JS auto-selection on the attendant step (1 hour, httpOnly off so JS can clear it)
    setcookie('sln_pref_att', $attendant_id, time() + 3600, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '', is_ssl(), false);

    $booking_url = add_query_arg(
        ['sln_step_page' => 'services'],
        get_permalink($plugin->getSettings()->getPayPageId())
    );
    wp_safe_redirect($booking_url);
    exit;
});

/**
 * When the attendant step is rendered, auto-select the preferred attendant
 * and submit the form so the user doesn't have to click manually.
 * Reads the cookie in JS so it works whether set by PHP redirect or by JS.
 */
add_action('wp_footer', function () {
    $cookie_path = COOKIEPATH ?: '/';
    ?>
    <script>
    (function () {
        var cookiePath = '<?php echo esc_js($cookie_path); ?>';

        function getPref() {
            var m = document.cookie.match(/(?:^|; )sln_pref_att=(\d+)/);
            return m ? m[1] : null;
        }

        function clearPref() {
            document.cookie = 'sln_pref_att=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=' + cookiePath;
        }

        function tryAutoSelect() {
            var attId = getPref();
            if (!attId) return false;
            var form = document.getElementById('salon-step-attendant');
            if (!form) return false;
            var radio = form.querySelector('input[name="sln[attendant]"][value="' + attId + '"]');
            if (!radio) return false;

            // Click label (triggers plugin's own selection styling)
            var lbl = document.querySelector('label[for="' + radio.id + '"]');
            if (lbl) lbl.click(); else radio.click();

            clearPref();

            // Auto-submit after the plugin has processed the click
            setTimeout(function () {
                var btn = document.getElementById('sln-step-submit');
                if (btn) btn.click();
            }, 400);

            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (!tryAutoSelect()) {
                // Booking form loads steps via AJAX — watch for DOM changes
                var observer = new MutationObserver(function () {
                    if (tryAutoSelect()) observer.disconnect();
                });
                observer.observe(document.body, { childList: true, subtree: true });
                setTimeout(function () { observer.disconnect(); }, 30000);
            }
        });
    })();
    </script>
    <?php
});
