<?php
/**
 * Template for the Assistants/Coiffeurs page (slug: assistants).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="ba-assistants-page">
    <div class="ba-assistants-page__hero">
        <div class="section-inner">
            <div class="ba-assistants-page__eyebrow"><?php esc_html_e('Notre équipe', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-assistants-page__title"><?php the_title(); ?></h1>
            <p class="ba-assistants-page__subtitle"><?php esc_html_e('Des experts passionnés à votre service. Chaque coupe, chaque soin est une signature.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <div class="ba-assistants-page__grid section-inner">
        <?php echo do_shortcode('[salon_booking_assistant styled=true columns="3"]'); ?>
    </div>

    <div class="ba-assistants-page__cta section-inner">
        <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="btn">
            <?php esc_html_e('Prendre rendez-vous', 'barber-architecte-v201'); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php get_footer(); ?>
