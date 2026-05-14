<?php
get_header();

$services    = ba_v201_salon_posts('sln_service');
$attendants  = ba_v201_salon_posts('sln_attendant');
$booking_url = ba_v201_salon_booking_url();
?>

<div class="ba-accueil-page ba-accueil-page--theme" id="accueil">
<section class="hero" style="<?php echo esc_attr(ba_v201_hero_background_style()); ?>">
    <div class="hero__content">
        <div class="hero__copy">
            <span class="eyebrow"><?php esc_html_e("Barbershop L'Architecte — Marseille", 'barber-architecte-v201'); ?></span>
            <h1><?php bloginfo('name'); ?></h1>
            <p><?php esc_html_e("Votre lieu dédié à l'homme de tout âge. Expertise, précision et raffinement — par des coiffeurs-barbiers passionnés.", 'barber-architecte-v201'); ?></p>
            <?php ba_v201_render_attendant_picker($attendants, $booking_url, 4); ?>
        </div>
        <div id="reservation" class="hero-booking">
            <?php ba_v201_render_salon_shortcode(); ?>
        </div>
    </div>
</section>

<section id="services" class="section">
    <div class="section-inner">
        <div class="section-head">
            <h2><?php esc_html_e('Formules claires, résultat net.', 'barber-architecte-v201'); ?></h2>
            <p><?php esc_html_e('Toutes nos prestations, tarifs et disponibilités en temps réel.', 'barber-architecte-v201'); ?></p>
        </div>
        <div class="service-grid">
            <?php foreach ($services as $service) : ?>
                <?php ba_v201_render_service_card($service, $booking_url); ?>
            <?php endforeach; ?>
        </div>
        <button class="services-toggle" id="servicesToggle" data-toggles="#services .service-grid" aria-expanded="false">
            <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>
</section>


</div>

<?php get_footer(); ?>
