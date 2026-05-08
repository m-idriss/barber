<?php
if (!defined('ABSPATH')) {
    exit;
}
$placeholder = $args['placeholder'] ?? __('Rechercher…', 'barber-architecte-v201');
?>
<div class="ba-search-field">
    <input
        type="search"
        name="s"
        placeholder="<?php echo esc_attr($placeholder); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        aria-label="<?php esc_attr_e('Rechercher sur le site', 'barber-architecte-v201'); ?>"
    />
    <button type="submit" aria-label="<?php esc_attr_e('Lancer la recherche', 'barber-architecte-v201'); ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
    </button>
</div>
