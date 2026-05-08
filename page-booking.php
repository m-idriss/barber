<?php
/**
 * Template for the Booking page (slug: booking).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="ba-booking-page">
    <div class="ba-booking-page__hero">
        <div class="section-inner">
            <div class="ba-booking-page__eyebrow"><?php esc_html_e('Réservation', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-booking-page__title"><?php the_title(); ?></h1>
            <p class="ba-booking-page__subtitle"><?php esc_html_e('Choisissez votre prestation, votre barber et le créneau qui vous convient.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <div class="ba-booking-page__content section-inner">
        <aside class="ba-booking-page__side" aria-label="<?php esc_attr_e('Étapes de réservation', 'barber-architecte-v201'); ?>">
            <div class="ba-booking-page__step">
                <span>01</span>
                <strong><?php esc_html_e('Prestation', 'barber-architecte-v201'); ?></strong>
            </div>
            <div class="ba-booking-page__step">
                <span>02</span>
                <strong><?php esc_html_e('Barber', 'barber-architecte-v201'); ?></strong>
            </div>
            <div class="ba-booking-page__step">
                <span>03</span>
                <strong><?php esc_html_e('Créneau', 'barber-architecte-v201'); ?></strong>
            </div>
        </aside>

        <main class="ba-booking-page__widget" id="reservation">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php
                $content = trim((string) get_the_content());

                if ('' !== $content) {
                    the_content();
                } elseif (shortcode_exists('salon')) {
                    echo do_shortcode('[salon]');
                }
                ?>
            <?php endwhile; endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
