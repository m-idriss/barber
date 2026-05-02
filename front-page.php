<?php
if (is_page() && get_post_meta(get_queried_object_id(), '_elementor_edit_mode', true)) {
    get_header();
    while (have_posts()) {
        the_post();
        the_content();
    }
    get_footer();
    return;
}

get_header();

$hero = ba_v201_upload_url('2026/05/barber-hero-v2-flipped.png');
$services = ba_v201_salon_posts('sln_service');
$attendants = ba_v201_salon_posts('sln_attendant');
?>

<section class="hero" style="--ba-hero-image: url('<?php echo esc_url($hero); ?>');">
    <div class="hero__content">
        <div class="hero__copy">
            <span class="eyebrow"><?php esc_html_e("Bienvenue chez Barbershop L'Architecte", 'barber-architecte-v201'); ?></span>
            <h1><?php bloginfo('name'); ?></h1>
            <p><?php esc_html_e("Votre lieu dédié à l’homme de tout âge. Nos coiffeurs/barbiers sont à votre disposition pour répondre à vos besoins, prodiguer les meilleurs conseils et vous procurer des soins adaptés afin de sublimer votre apparence et faciliter votre coiffage au quotidien.", 'barber-architecte-v201'); ?></p>
            <div class="hero-team" aria-label="<?php esc_attr_e('Choisir un barber', 'barber-architecte-v201'); ?>">
                <?php foreach (array_slice($attendants, 0, 4) as $attendant) : ?>
                    <a class="hero-barber" href="#reservation">
                        <?php echo get_the_post_thumbnail($attendant, 'thumbnail'); ?>
                        <span><?php echo esc_html(get_the_title($attendant)); ?></span>
                        <strong><?php esc_html_e('Choisir', 'barber-architecte-v201'); ?></strong>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="hero__actions">
                <a class="btn" href="#reservation"><?php esc_html_e('Reserver maintenant', 'barber-architecte-v201'); ?></a>
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
            <h2><?php esc_html_e('Formules claires, resultat net.', 'barber-architecte-v201'); ?></h2>
            <p><?php esc_html_e('Les prestations Salon Booking sont reprises automatiquement depuis le plugin.', 'barber-architecte-v201'); ?></p>
        </div>
        <div class="service-grid">
            <?php foreach ($services as $service) : ?>
                <?php
                $price = get_post_meta($service->ID, '_sln_service_price', true);
                $duration = get_post_meta($service->ID, '_sln_service_duration', true);
                ?>
                <article class="service-card">
                    <div>
                        <h3><?php echo esc_html(get_the_title($service)); ?></h3>
                        <?php if ($service->post_excerpt) : ?>
                            <p><?php echo esc_html($service->post_excerpt); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="service-card__meta">
                        <span><?php echo esc_html($duration ?: ''); ?></span>
                        <span><?php echo $price !== '' ? esc_html($price . ' EUR') : ''; ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="equipe" class="section section--soft">
    <div class="section-inner">
        <div class="section-head">
            <h2><?php esc_html_e('Choisis ton barber.', 'barber-architecte-v201'); ?></h2>
            <p><?php esc_html_e('Chaque assistant vient directement des donnees Salon Booking Pro.', 'barber-architecte-v201'); ?></p>
        </div>
        <div class="team-grid">
            <?php foreach ($attendants as $attendant) : ?>
                <article class="team-card">
                    <?php echo get_the_post_thumbnail($attendant, 'large'); ?>
                    <div class="team-card__body">
                        <h3><?php echo esc_html(get_the_title($attendant)); ?></h3>
                        <p><?php esc_html_e('Disponible a la reservation.', 'barber-architecte-v201'); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
