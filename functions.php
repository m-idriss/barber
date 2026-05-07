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

function ba_v201_github_release_url(array $release): string
{
    $release_url = !empty($release['html_url']) ? (string) $release['html_url'] : '';
    if ('' === $release_url && !empty($release['tag_name'])) {
        $release_url = ba_v201_github_repository_url() . '/releases/tag/' . rawurlencode((string) $release['tag_name']);
    }

    return '' !== $release_url ? $release_url : ba_v201_github_repository_url();
}

function ba_v201_github_theme_update(): ?array
{
    $theme = wp_get_theme(get_stylesheet());
    $stylesheet = $theme->get_stylesheet();
    $current_version = (string) $theme->get('Version');
    $release = ba_v201_github_latest_release();
    if (!$release) {
        return null;
    }

    $latest_version = ba_v201_release_version_from_tag((string) $release['tag_name']);
    if ('' === $latest_version || version_compare($latest_version, $current_version, '<=')) {
        return null;
    }

    $package_url = ba_v201_github_release_package_url($release, $stylesheet);
    if ('' === $package_url) {
        return null;
    }

    return [
        'theme' => $theme,
        'stylesheet' => $stylesheet,
        'current_version' => $current_version,
        'latest_version' => $latest_version,
        'release' => $release,
        'release_url' => ba_v201_github_release_url($release),
        'package_url' => $package_url,
    ];
}

function ba_v201_theme_update_url(string $stylesheet): string
{
    return wp_nonce_url(
        self_admin_url('update.php?action=upgrade-theme&theme=' . rawurlencode($stylesheet)),
        'upgrade-theme_' . $stylesheet
    );
}

function ba_v201_github_release_changelog_html(array $release): string
{
    $body = trim((string) ($release['body'] ?? ''));
    if ('' === $body) {
        return '<p>' . esc_html__('Release notes are not available for this version yet.', 'barber-architecte-v201') . '</p>';
    }

    $lines = preg_split('/\R/', $body) ?: [];
    $html = '';
    $paragraph = [];
    $list_items = [];

    $flush_paragraph = static function () use (&$html, &$paragraph): void {
        if ([] === $paragraph) {
            return;
        }

        $text = implode(' ', array_map('trim', $paragraph));
        $text = str_replace('**', '', $text);
        $html .= '<p>' . wp_kses_post(make_clickable(esc_html($text))) . '</p>';
        $paragraph = [];
    };

    $flush_list = static function () use (&$html, &$list_items): void {
        if ([] === $list_items) {
            return;
        }

        $html .= '<ul>';
        foreach ($list_items as $item) {
            $item = str_replace('**', '', $item);
            $html .= '<li>' . wp_kses_post(make_clickable(esc_html($item))) . '</li>';
        }
        $html .= '</ul>';
        $list_items = [];
    };

    foreach ($lines as $line) {
        $line = trim($line);
        if ('' === $line) {
            $flush_paragraph();
            $flush_list();
            continue;
        }

        if (preg_match('/^[-*+]\s+(.+)$/', $line, $matches)) {
            $flush_paragraph();
            $list_items[] = $matches[1];
            continue;
        }

        $flush_list();
        $paragraph[] = preg_replace('/^#{1,6}\s*/', '', $line);
    }

    $flush_paragraph();
    $flush_list();

    return '' !== $html ? $html : '<p>' . esc_html__('Release notes are not available for this version yet.', 'barber-architecte-v201') . '</p>';
}

function ba_v201_theme_update_admin_url(array $query_args = []): string
{
    return add_query_arg($query_args, admin_url('themes.php?page=ba-v201-theme-updates'));
}

function ba_v201_theme_update_check_url(): string
{
    return wp_nonce_url(
        ba_v201_theme_update_admin_url(['ba_v201_check_theme_update' => '1']),
        'ba_v201_check_theme_update'
    );
}

function ba_v201_theme_update_notices_key(): string
{
    return 'ba_v201_theme_update_admin_notice_' . get_current_user_id();
}

function ba_v201_set_theme_update_notice(string $type, string $message): void
{
    set_transient(
        ba_v201_theme_update_notices_key(),
        [
            'type' => $type,
            'message' => $message,
        ],
        MINUTE_IN_SECONDS * 10
    );
}

function ba_v201_handle_manual_theme_update_check(): void
{
    if (!is_admin() || !current_user_can('update_themes')) {
        return;
    }

    if (!isset($_GET['ba_v201_check_theme_update'])) {
        return;
    }

    check_admin_referer('ba_v201_check_theme_update');

    delete_site_transient('ba_v201_github_latest_release');
    delete_site_transient('update_themes');

    if (!function_exists('wp_update_themes')) {
        require_once ABSPATH . WPINC . '/update.php';
    }

    wp_update_themes();

    $update = ba_v201_github_theme_update();
    $message = $update
        ? sprintf(
            /* translators: %s: new theme version. */
            __('Barber Theme %s is available and ready to install.', 'barber-architecte-v201'),
            $update['latest_version']
        )
        : __('Barber Theme is already up to date.', 'barber-architecte-v201');

    ba_v201_set_theme_update_notice('success', $message);

    wp_safe_redirect(ba_v201_theme_update_admin_url());
    exit;
}
add_action('admin_init', 'ba_v201_handle_manual_theme_update_check');

function ba_v201_check_for_github_theme_update(mixed $transient): mixed
{
    if (!is_object($transient) || empty($transient->checked) || !is_array($transient->checked)) {
        return $transient;
    }

    $update = ba_v201_github_theme_update();
    if (!$update) {
        return $transient;
    }

    $theme = $update['theme'];
    $transient->response[$update['stylesheet']] = [
        'theme' => $update['stylesheet'],
        'new_version' => $update['latest_version'],
        'url' => $update['release_url'],
        'package' => $update['package_url'],
        'requires' => $theme->get('RequiresWP'),
        'requires_php' => $theme->get('RequiresPHP'),
    ];

    return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'ba_v201_check_for_github_theme_update');

function ba_v201_admin_theme_updates_styles(): void
{
    if (!is_admin()) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    $screen_id = $screen ? $screen->id : '';
    if (!in_array($screen_id, ['dashboard', 'themes', 'update-core', 'appearance_page_ba-v201-theme-updates'], true)) {
        return;
    }

    echo '<style>
        .ba-v201-update-card{margin:16px 0 0;padding:20px 24px;border:1px solid #d0d7de;border-radius:12px;background:linear-gradient(135deg,#fff 0%,#f6f7f7 100%);box-shadow:0 10px 30px rgba(15,23,42,.06)}
        .ba-v201-update-card h2,.ba-v201-update-card h3{margin:0 0 10px}
        .ba-v201-update-card p{margin:0 0 12px}
        .ba-v201-update-meta{display:flex;flex-wrap:wrap;gap:12px;margin:0 0 16px}
        .ba-v201-update-meta span{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;background:#f0f6fc;color:#0a4b78;font-weight:600}
        .ba-v201-update-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}
        .ba-v201-update-card details{margin-top:16px}
        .ba-v201-update-card summary{cursor:pointer;font-weight:600}
        .ba-v201-update-card ul{list-style:disc;margin:12px 0 0 20px}
        .ba-v201-update-layout{max-width:960px}
        .ba-v201-update-layout .card{padding:24px}
    </style>';
}
add_action('admin_head', 'ba_v201_admin_theme_updates_styles');

function ba_v201_render_theme_update_admin_notice(): void
{
    if (!is_admin() || !current_user_can('update_themes')) {
        return;
    }

    $notice = get_transient(ba_v201_theme_update_notices_key());
    if (is_array($notice) && !empty($notice['message'])) {
        delete_transient(ba_v201_theme_update_notices_key());
        printf(
            '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
            esc_attr((string) ($notice['type'] ?? 'info')),
            esc_html((string) $notice['message'])
        );
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    $screen_id = $screen ? $screen->id : '';
    if (!in_array($screen_id, ['dashboard', 'themes', 'update-core'], true)) {
        return;
    }

    $update = ba_v201_github_theme_update();
    if (!$update) {
        return;
    }

    $theme_name = $update['theme']->get('Name');
    $release = $update['release'];
    ?>
    <div class="notice notice-info ba-v201-update-card">
        <h2>
            <?php
            printf(
                /* translators: 1: theme name, 2: latest version. */
                esc_html__('%1$s v%2$s is available', 'barber-architecte-v201'),
                esc_html($theme_name),
                esc_html($update['latest_version'])
            );
            ?>
        </h2>
        <div class="ba-v201-update-meta">
            <span>
                <?php
                printf(
                    /* translators: 1: current version, 2: latest version. */
                    esc_html__('Current %1$s → New %2$s', 'barber-architecte-v201'),
                    esc_html($update['current_version']),
                    esc_html($update['latest_version'])
                );
                ?>
            </span>
            <?php if (!empty($release['published_at'])) : ?>
                <span>
                    <?php
                    printf(
                        /* translators: %s: release publication date. */
                        esc_html__('Published %s', 'barber-architecte-v201'),
                        esc_html(wp_date(get_option('date_format'), strtotime((string) $release['published_at'])))
                    );
                    ?>
                </span>
            <?php endif; ?>
        </div>
        <p><?php echo esc_html__('A new GitHub release is ready for your WordPress theme. Review the highlights below or install it now.', 'barber-architecte-v201'); ?></p>
        <details open>
            <summary><?php echo esc_html__('What’s new in this release', 'barber-architecte-v201'); ?></summary>
            <div><?php echo wp_kses_post(ba_v201_github_release_changelog_html($release)); ?></div>
        </details>
        <div class="ba-v201-update-actions">
            <a class="button button-primary" href="<?php echo esc_url(ba_v201_theme_update_url($update['stylesheet'])); ?>"><?php echo esc_html__('Update now', 'barber-architecte-v201'); ?></a>
            <a class="button" href="<?php echo esc_url(ba_v201_theme_update_check_url()); ?>"><?php echo esc_html__('Check for updates', 'barber-architecte-v201'); ?></a>
            <a class="button button-link" href="<?php echo esc_url(ba_v201_theme_update_admin_url()); ?>"><?php echo esc_html__('Open theme updater', 'barber-architecte-v201'); ?></a>
            <a class="button button-link" href="<?php echo esc_url($update['release_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('View GitHub release', 'barber-architecte-v201'); ?></a>
        </div>
    </div>
    <?php
}
add_action('admin_notices', 'ba_v201_render_theme_update_admin_notice');

function ba_v201_register_theme_updates_page(): void
{
    add_theme_page(
        __('Theme updates', 'barber-architecte-v201'),
        __('Theme updates', 'barber-architecte-v201'),
        'update_themes',
        'ba-v201-theme-updates',
        'ba_v201_render_theme_updates_page'
    );
}
add_action('admin_menu', 'ba_v201_register_theme_updates_page');

function ba_v201_render_theme_updates_page(): void
{
    if (!current_user_can('update_themes')) {
        wp_die(esc_html__('You are not allowed to manage theme updates.', 'barber-architecte-v201'));
    }

    $theme = wp_get_theme(get_stylesheet());
    $update = ba_v201_github_theme_update();
    $release = $update['release'] ?? null;
    ?>
    <div class="wrap ba-v201-update-layout">
        <h1><?php echo esc_html__('Barber Theme updates', 'barber-architecte-v201'); ?></h1>
        <div class="card ba-v201-update-card">
            <h2><?php echo esc_html($theme->get('Name')); ?></h2>
            <div class="ba-v201-update-meta">
                <span>
                    <?php
                    printf(
                        /* translators: %s: installed theme version. */
                        esc_html__('Installed version %s', 'barber-architecte-v201'),
                        esc_html((string) $theme->get('Version'))
                    );
                    ?>
                </span>
                <?php if ($update) : ?>
                    <span>
                        <?php
                        printf(
                            /* translators: %s: available theme version. */
                            esc_html__('Update available %s', 'barber-architecte-v201'),
                            esc_html($update['latest_version'])
                        );
                        ?>
                    </span>
                <?php else : ?>
                    <span><?php echo esc_html__('Theme is up to date', 'barber-architecte-v201'); ?></span>
                <?php endif; ?>
            </div>

            <?php if ($update) : ?>
                <p><?php echo esc_html__('A newer GitHub release has been detected for this theme.', 'barber-architecte-v201'); ?></p>
                <div class="ba-v201-update-actions">
                    <a class="button button-primary" href="<?php echo esc_url(ba_v201_theme_update_url($update['stylesheet'])); ?>"><?php echo esc_html__('Update now', 'barber-architecte-v201'); ?></a>
                    <a class="button" href="<?php echo esc_url(ba_v201_theme_update_check_url()); ?>"><?php echo esc_html__('Check for updates', 'barber-architecte-v201'); ?></a>
                    <a class="button button-link" href="<?php echo esc_url($update['release_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('View GitHub release', 'barber-architecte-v201'); ?></a>
                </div>
                <details open>
                    <summary><?php echo esc_html__('Release notes', 'barber-architecte-v201'); ?></summary>
                    <div><?php echo wp_kses_post(ba_v201_github_release_changelog_html($release)); ?></div>
                </details>
            <?php else : ?>
                <p><?php echo esc_html__('No newer GitHub release is available right now, but you can still run a fresh check at any time.', 'barber-architecte-v201'); ?></p>
                <div class="ba-v201-update-actions">
                    <a class="button button-primary" href="<?php echo esc_url(ba_v201_theme_update_check_url()); ?>"><?php echo esc_html__('Check for updates', 'barber-architecte-v201'); ?></a>
                    <a class="button button-link" href="<?php echo esc_url(ba_v201_github_repository_url() . '/releases'); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('Browse releases', 'barber-architecte-v201'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

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
    ba_v201_set_theme_update_notice(
        'success',
        sprintf(
            /* translators: %s: updated theme version. */
            __('Barber Theme successfully updated to v%s.', 'barber-architecte-v201'),
            wp_get_theme(get_stylesheet())->get('Version')
        )
    );
}
add_action('upgrader_process_complete', 'ba_v201_clear_github_release_cache', 10, 2);
