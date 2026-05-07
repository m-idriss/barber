<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="ba-error-page">
    <div class="ba-error-page__inner">
        <div class="ba-error-page__code" aria-hidden="true">404</div>
        <div class="ba-error-page__scissors" aria-hidden="true">✂</div>
        <h1 class="ba-error-page__title"><?php esc_html_e('Page introuvable', 'barber-architecte-v201'); ?></h1>
        <p class="ba-error-page__desc"><?php esc_html_e('La page que vous cherchez n\'existe pas ou a été déplacée. Revenez à l\'accueil ou lancez une recherche.', 'barber-architecte-v201'); ?></p>

        <form class="ba-error-page__search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="ba-search-field">
                <input
                    type="search"
                    name="s"
                    placeholder="<?php esc_attr_e('Rechercher…', 'barber-architecte-v201'); ?>"
                    value="<?php echo esc_attr(get_search_query()); ?>"
                    aria-label="<?php esc_attr_e('Rechercher sur le site', 'barber-architecte-v201'); ?>"
                />
                <button type="submit" aria-label="<?php esc_attr_e('Lancer la recherche', 'barber-architecte-v201'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </button>
            </div>
        </form>

        <div class="ba-error-page__actions">
            <a class="btn" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Retour à l\'accueil', 'barber-architecte-v201'); ?></a>
            <a class="btn btn--ghost" href="#reservation"><?php esc_html_e('Réserver maintenant', 'barber-architecte-v201'); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
