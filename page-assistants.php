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
$_sln        = SLN_Plugin::getInstance();
$booking_url = get_permalink($_sln->getSettings()->getPayPageId());
?>

<section class="ba-assistants-page">
    <div class="ba-assistants-page__hero">
        <div class="section-inner">
            <div class="ba-assistants-page__eyebrow"><?php esc_html_e('Notre équipe', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-assistants-page__title"><?php the_title(); ?></h1>
            <p class="ba-assistants-page__subtitle"><?php esc_html_e('Des experts passionnés à votre service. Chaque coupe, chaque soin est une signature.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <section class="section section--soft">
        <div class="section-inner">
            <div class="team-grid">
                <?php foreach ($attendants as $attendant) : ?>
                    <article class="team-card">
                        <?php echo get_the_post_thumbnail($attendant, 'large', ['loading' => 'lazy', 'decoding' => 'async']); ?>
                        <div class="team-card__body">
                            <h3><?php echo esc_html(get_the_title($attendant)); ?></h3>
                            <?php if ($attendant->post_excerpt) : ?>
                                <p><?php echo esc_html($attendant->post_excerpt); ?></p>
                            <?php else : ?>
                                <p><?php esc_html_e('Disponible à la réservation.', 'barber-architecte-v201'); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url(add_query_arg(['sln_book_attendant' => $attendant->ID], $booking_url)); ?>" class="team-card__cta">
                                <?php esc_html_e('Réserver', 'barber-architecte-v201'); ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <button class="services-toggle" data-toggles=".ba-assistants-page .team-grid" aria-expanded="false">
                <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </section>

    <div class="ba-assistants-page__cta section-inner">
        <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="btn">
            <?php esc_html_e('Prendre rendez-vous', 'barber-architecte-v201'); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php get_footer(); ?>
