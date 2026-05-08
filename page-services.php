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
        <?php echo do_shortcode('[salon_booking_services styled=true columns="3"]'); ?>
    </div>
</div>

<?php get_footer(); ?>
