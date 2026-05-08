<?php
/**
 * Template for the Booking page (slug: booking).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$hero = ba_v201_upload_url('2026/05/barber-hero-v2-flipped.png');
?>

<div class="ba-booking-page">
    <div class="ba-booking-page__hero" style="background-image: url('<?php echo esc_url($hero); ?>')">
        <div class="section-inner">
            <div class="ba-booking-page__eyebrow"><?php esc_html_e('Réservation', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-booking-page__title"><?php the_title(); ?></h1>
            <p class="ba-booking-page__subtitle"><?php esc_html_e('Choisissez votre prestation, votre barber et le créneau qui vous convient.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <div class="ba-booking-page__content section-inner">
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
