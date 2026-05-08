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
$hero        = ba_v201_upload_url('2026/05/barber-hero-v2-flipped.png');
?>

<section class="ba-assistants-page">
    <div class="ba-assistants-page__hero" style="background-image: url('<?php echo esc_url($hero); ?>')">
        <div class="section-inner">
            <div class="ba-assistants-page__eyebrow"><?php esc_html_e('Notre équipe', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-assistants-page__title"><?php the_title(); ?></h1>
            <p class="ba-assistants-page__subtitle"><?php esc_html_e('Des experts passionnés à votre service. Chaque coupe, chaque soin est une signature.', 'barber-architecte-v201'); ?></p>
            <div class="ba-page-hero__actions">
                <a class="btn" href="#equipe"><?php esc_html_e('Voir l\'équipe', 'barber-architecte-v201'); ?></a>
                <a class="btn btn--ghost" href="<?php echo esc_url($booking_url); ?>"><?php esc_html_e('Réserver', 'barber-architecte-v201'); ?></a>
            </div>
        </div>
    </div>

    <section id="equipe" class="section section--soft">
        <div class="section-inner">
            <div class="section-head">
                <h2><?php esc_html_e('Choisis ton barber.', 'barber-architecte-v201'); ?></h2>
                <p><?php esc_html_e('Une équipe de passionnés à votre service, chaque jour de la semaine.', 'barber-architecte-v201'); ?></p>
            </div>
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
