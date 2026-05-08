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
            <div class="hero-team" aria-label="<?php esc_attr_e('Choisir un barber', 'barber-architecte-v201'); ?>">
                <?php foreach ($attendants as $attendant) :
                    $excerpt = $attendant->post_excerpt ?: '';
                ?>
                    <a class="hero-barber" href="<?php echo esc_url(add_query_arg(['sln_book_attendant' => $attendant->ID], $booking_url)); ?>">
                        <?php echo get_the_post_thumbnail($attendant, 'thumbnail', ['loading' => 'eager', 'decoding' => 'async']); ?>
                        <span>
                            <?php echo esc_html(get_the_title($attendant)); ?>
                            <?php if ($excerpt) : ?>
                                <small><?php echo esc_html($excerpt); ?></small>
                            <?php endif; ?>
                        </span>
                        <strong><?php esc_html_e('Choisir', 'barber-architecte-v201'); ?></strong>
                    </a>
                <?php endforeach; ?>
            </div>
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
