<?php
/**
 * Template for the Assistants/Coiffeurs page (slug: assistants).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$attendants  = ba_v201_salon_posts('sln_attendant');
$booking_url = ba_v201_salon_booking_url();
?>

<section class="ba-assistants-page">
    <div class="ba-assistants-page__hero" style="<?php echo esc_attr(ba_v201_hero_background_style()); ?>">
        <div class="section-inner">
            <div class="ba-assistants-page__eyebrow"><?php esc_html_e('Notre équipe', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-assistants-page__title"><?php the_title(); ?></h1>
            <p class="ba-assistants-page__subtitle"><?php esc_html_e('Des experts passionnés à votre service. Chaque coupe, chaque soin est une signature.', 'barber-architecte-v201'); ?></p>
            <?php ba_v201_render_attendant_picker($attendants, $booking_url); ?>
            <div class="ba-page-hero__actions">
                <a class="btn btn--ghost" href="<?php echo esc_url($booking_url); ?>"><?php esc_html_e('Réserver', 'barber-architecte-v201'); ?></a>
            </div>
        </div>
    </div>

    <section class="ba-page-cta">
        <div class="section-inner">
            <p class="ba-page-cta__eyebrow"><?php esc_html_e('Barbershop L\'Architecte', 'barber-architecte-v201'); ?></p>
            <h2><?php esc_html_e('Ton barber t\'attend.', 'barber-architecte-v201'); ?></h2>
            <p><?php esc_html_e('Réservez en ligne, choisissez votre expert et votre créneau en quelques secondes.', 'barber-architecte-v201'); ?></p>
            <a class="btn" href="<?php echo esc_url($booking_url); ?>"><?php esc_html_e('Prendre rendez-vous', 'barber-architecte-v201'); ?></a>
        </div>
    </section>
</section>

<?php get_footer(); ?>
