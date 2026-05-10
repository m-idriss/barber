<?php
/**
 * Template for the Services page (slug: services).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$services    = ba_v201_salon_posts('sln_service');
$booking_url = ba_v201_salon_booking_url();
?>

<div class="ba-services-page">
    <div class="ba-services-page__hero" style="<?php echo esc_attr(ba_v201_hero_background_style()); ?>">
        <div class="section-inner">
            <div class="ba-services-page__eyebrow"><?php esc_html_e('Nos prestations', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-services-page__title"><?php the_title(); ?></h1>
            <p class="ba-services-page__subtitle"><?php esc_html_e('Expertise, précision et soin — pour chaque homme, chaque style.', 'barber-architecte-v201'); ?></p>
            <div class="service-grid">
                <?php foreach ($services as $service) : ?>
                    <?php ba_v201_render_service_card($service, $booking_url); ?>
                <?php endforeach; ?>
            </div>
            <button class="services-toggle" data-toggles=".ba-services-page .service-grid" aria-expanded="false">
                <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>

    <section class="ba-page-cta">
        <div class="section-inner">
            <p class="ba-page-cta__eyebrow"><?php esc_html_e('Barbershop L\'Architecte', 'barber-architecte-v201'); ?></p>
            <h2><?php esc_html_e('Prêt à passer à la caisse ?', 'barber-architecte-v201'); ?></h2>
            <p><?php esc_html_e('Réservez votre créneau en ligne, choisissez votre barber et votre prestation.', 'barber-architecte-v201'); ?></p>
            <a class="btn" href="<?php echo esc_url($booking_url); ?>"><?php esc_html_e('Réserver maintenant', 'barber-architecte-v201'); ?></a>
        </div>
    </section>
</div>

<?php get_footer(); ?>
