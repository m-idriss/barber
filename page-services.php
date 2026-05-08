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
$_sln        = SLN_Plugin::getInstance();
$booking_url = get_permalink($_sln->getSettings()->getPayPageId());
$hero        = ba_v201_upload_url('2026/05/barber-hero-v2-flipped.png');
?>

<div class="ba-services-page">
    <div class="ba-services-page__hero" style="background-image: url('<?php echo esc_url($hero); ?>')">
        <div class="section-inner">
            <div class="ba-services-page__eyebrow"><?php esc_html_e('Nos prestations', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-services-page__title"><?php the_title(); ?></h1>
            <p class="ba-services-page__subtitle"><?php esc_html_e('Sélectionnez une ou plusieurs prestations pour commencer votre réservation.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <section class="section">
        <div class="section-inner">
            <div class="service-grid">
                <?php foreach ($services as $service) :
                    $price    = get_post_meta($service->ID, '_sln_service_price', true);
                    $duration = get_post_meta($service->ID, '_sln_service_duration', true);
                    $book_service_url = add_query_arg([
                        'action'                 => 'salon-booking-services-book-now',
                        'service'                => $service->ID,
                        'skip_service_selection' => 1,
                        'secondary'              => 0,
                    ], $booking_url);
                ?>
                    <article class="service-card">
                        <?php if (has_post_thumbnail($service->ID)) : ?>
                            <div class="service-card__image">
                                <?php echo get_the_post_thumbnail($service->ID, 'medium', ['loading' => 'lazy', 'decoding' => 'async']); ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h3><?php echo esc_html(get_the_title($service)); ?></h3>
                            <?php if ($service->post_excerpt) : ?>
                                <p><?php echo esc_html($service->post_excerpt); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="service-card__meta">
                            <span><?php echo esc_html($duration ?: ''); ?></span>
                            <?php if ($price !== '') : ?>
                                <strong><?php echo esc_html($price . ' EUR'); ?></strong>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url($book_service_url); ?>" class="service-card__cta">
                            <?php esc_html_e('Réserver', 'barber-architecte-v201'); ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            <button class="services-toggle" data-toggles=".ba-services-page .service-grid" aria-expanded="false">
                <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </section>
</div>

<?php get_footer(); ?>
