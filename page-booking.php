<?php
/**
 * Template for the Booking page (slug: booking).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$ba_status  = ba_v201_current_status();
$ba_contact = ba_v201_contact_settings();
?>

<div class="ba-booking-page">
    <div class="ba-booking-page__hero" style="<?php echo esc_attr(ba_v201_hero_background_style()); ?>">
        <div class="ba-booking-page__hero-inner">
            <div class="ba-booking-page__copy">
                <div class="ba-booking-page__eyebrow"><?php esc_html_e('Réservation', 'barber-architecte-v201'); ?></div>
                <h1 class="ba-booking-page__title"><?php the_title(); ?></h1>
                <p class="ba-booking-page__subtitle"><?php esc_html_e('Choisissez votre prestation, votre barber et le créneau qui vous convient.', 'barber-architecte-v201'); ?></p>

                <aside class="ba-booking-page__guide" aria-label="<?php esc_attr_e('Aide reservation', 'barber-architecte-v201'); ?>">
                    <ol class="ba-booking-page__steps">
                        <li>
                            <span>01</span>
                            <strong><?php esc_html_e('Formule', 'barber-architecte-v201'); ?></strong>
                            <small><?php esc_html_e('Choisissez la prestation.', 'barber-architecte-v201'); ?></small>
                        </li>
                        <li>
                            <span>02</span>
                            <strong><?php esc_html_e('Barber', 'barber-architecte-v201'); ?></strong>
                            <small><?php esc_html_e('Sélectionnez votre coiffeur.', 'barber-architecte-v201'); ?></small>
                        </li>
                        <li>
                            <span>03</span>
                            <strong><?php esc_html_e('Créneau', 'barber-architecte-v201'); ?></strong>
                            <small><?php esc_html_e('Validez votre horaire.', 'barber-architecte-v201'); ?></small>
                        </li>
                    </ol>

                    <div class="ba-booking-page__contact">
                        <span class="ba-booking-page__status <?php echo $ba_status['is_open'] ? 'is-open' : 'is-closed'; ?>">
                            <?php echo esc_html($ba_status['label']); ?>
                        </span>
                        <a href="tel:<?php echo esc_attr($ba_contact['phone']); ?>"><?php echo esc_html($ba_contact['phone_display']); ?></a>
                    </div>
                </aside>
            </div>

            <main class="ba-booking-page__widget hero-booking" id="reservation">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php
                    $content = trim((string) get_the_content());

                    if ('' !== $content) {
                        the_content();
                    } else {
                        ba_v201_render_salon_shortcode();
                    }
                    ?>
                <?php endwhile; endif; ?>
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>
