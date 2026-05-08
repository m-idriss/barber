<?php
/**
 * Template for the Services page (slug: services).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="ba-services-page">
    <div class="ba-services-page__hero">
        <div class="section-inner">
            <div class="ba-services-page__eyebrow"><?php esc_html_e('Nos prestations', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-services-page__title"><?php the_title(); ?></h1>
            <p class="ba-services-page__subtitle"><?php esc_html_e('Sélectionnez une ou plusieurs prestations pour commencer votre réservation.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <div class="ba-services-page__content section-inner">
        <?php echo do_shortcode('[salon_booking_services styled=true columns="3" skip_service_selection=true]'); ?>
        <button class="services-toggle" data-toggles=".ba-services-page__content" aria-expanded="false">
            <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>
</div>

<?php get_footer(); ?>
