<?php
get_header();

$hero        = ba_v201_upload_url('2026/05/barber-hero-v2-flipped.png');
$services    = ba_v201_salon_posts('sln_service');
$attendants  = ba_v201_salon_posts('sln_attendant');
$_sln        = SLN_Plugin::getInstance();
$booking_url = get_permalink($_sln->getSettings()->getPayPageId());
?>

<div class="ba-accueil-page ba-accueil-page--theme" id="accueil">
<section class="hero">
    <div class="ba-hero-bg" style="background-image: url('<?php echo esc_url($hero); ?>');"></div>
    <div class="hero__content">
        <div class="hero__copy">
            <span class="eyebrow"><?php esc_html_e("Barbershop L'Architecte — Marseille", 'barber-architecte-v201'); ?></span>
            <h1><?php bloginfo('name'); ?></h1>
            <p><?php esc_html_e("Votre lieu dédié à l'homme de tout âge. Expertise, précision et raffinement — par des coiffeurs-barbiers passionnés.", 'barber-architecte-v201'); ?></p>
            <div class="hero-team" aria-label="<?php esc_attr_e('Choisir un barber', 'barber-architecte-v201'); ?>">
                <?php foreach (array_slice($attendants, 0, 4) as $attendant) :
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
            <div class="hero__actions">
                <a class="btn" href="#reservation"><?php esc_html_e('Réserver maintenant', 'barber-architecte-v201'); ?></a>
                <a class="btn btn--ghost" href="#services"><?php esc_html_e('Voir les services', 'barber-architecte-v201'); ?></a>
            </div>
        </div>
        <div id="reservation" class="hero-booking">
            <?php
            if (shortcode_exists('salon')) {
                echo do_shortcode('[salon]');
            } else {
                echo '<a class="btn" href="' . esc_url(admin_url('admin.php?page=salon')) . '">' . esc_html__('Ouvrir Salon Booking', 'barber-architecte-v201') . '</a>';
            }
            ?>
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
        <button class="services-toggle" id="servicesToggle" data-toggles="#services .service-grid" aria-expanded="false">
            <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>
</section>


</div>

<?php get_footer(); ?>
